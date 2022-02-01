<?php
/**
 * Password recovery
 *
 * Plugin to reset an account password
 *
 * @version 1.0
 * @original_author Alexander Alferov
 *
 * @url https://github.com/AlfnRU/roundcube-password_recovery
 */

class password_recovery extends rcube_plugin {

    public $task = 'login|logout|settings|mail';
    public $rc;
    public $db;
    public $user;
    public $use_password;
    public $pwd_conf;
    private $pwd;
    private $fields;
    private $send;

    function init() {
        $this->rc = rcmail::get_instance();
        $this->load_config();

        if (!$this->rc->config->get('pr_use_confirm_code') && !$this->rc->config->get('pr_use_question'))
            return;

        $this->init_ui();
        $this->add_texts('localization/');

        if ($this->rc->task == 'login' || $this->rc->task == 'logout') {
            $this->add_hook('render_page', [$this, 'add_labels_to_login_page']);
            $this->add_hook('startup', [$this, 'startup']);
            $this->register_action('plugin.get_confirm_code_count', [$this, 'get_confirm_code_count']);
        } else if ($this->rc->task == 'mail') {
            $this->add_hook('render_page', [$this, 'add_labels_to_mail_page']);
            $this->add_hook('messages_list', [$this, 'check_identities']);
        } else if ($this->rc->task == 'settings') {
            $this->add_hook('identity_form', [$this, 'identity_form']);
            $this->add_hook('identity_update', [$this, 'identity_update']);
        }

        $this->include_script('password_recovery.js');
    }

    /*******************
    * STARTUP
    *******************/

    function init_ui() {
        if (!$this->fields) $this->fields = $this->rc->config->get('pr_fields');
        if (!$this->db) $this->get_dbh();
        if (!$this->user) $this->get_user_props();

        $this->use_password = ($this->rc->config->get('pr_use_password_plugin') && $this->rc->plugins->load_plugin('password', true));

        $new_fields = [
            'token'          => ['type' => 'VARCHAR(255)', 'default' => '\'\''],
            'token_validity' => ['type' => 'DATETIME'    , 'default' => '\'2000-01-01 00:00:00\'']
        ];

        foreach($this->fields as $field => $field_name){
            $new_fields[$field_name] = ['type' => ($field == 'phone' ? 'VARCHAR(30)' : 'VARCHAR(255)'), 'default' => '\'\''];
        }

        foreach($new_fields as $field_name => $field_props){
            $query = "SELECT " . $field_name . " FROM " . $this->rc->config->get('pr_users_table');
            $result = $this->db->query($query);
            if (!$result) {
                $query = "ALTER TABLE " . $this->rc->config->get('pr_users_table') . " ADD " . $field_name . " " . $field_props['type'] . " DEFAULT " . $field_props['default'];
                $result = $this->db->query($query);
            }
        }

        require_once $this->home . '/lib/send.php';
        $this->send = new password_recovery_send($this);

        require_once $this->home . '/lib/password.php';
        $this->pwd = new password_recovery_pwd($this);
    }

    function startup($p) {
        if ($this->rc->action != 'plugin.password_recovery' || !isset($_SESSION['temp']))
            return $p;

        switch ($this->get_action()) {
            case 'init':
                $this->recovery_password_form();
                break;

            case 'renew':
                $this->renew_confirm_code();
                break;

            case 'new':
                $this->new_password_form();
                break;

            case 'reset':
                $this->reset_password();
                break;

            case 'save':
                $this->save_password();
                break;

            case 'cancel':
                $this->rc->kill_session();
                $this->rc->output->command('redirect', './');
                break;
        }
        return $p;
    }

    function add_labels_to_login_page($p) {
        if ($p['template'] == 'login') {
            $this->rc->output->add_label('password_recovery.forgot_password');
        }
        return $p;
    }

    function add_labels_to_mail_page($p) {
        $this->rc->output->add_label('password_recovery.no_identities');
        $this->rc->output->add_script('rcmail.message_time = 10000;');
        return $p;
    }

    /*******************
    * PASSWORD
    *******************/

    // Creating form for reset password
    private function recovery_password_form() {
        $this->rc->output->add_label(
            'password_recovery.recovery_password',
            'password_recovery.no_username'
        );

        $this->rc->output->set_pagetitle($this->gettext('recovery_password'));
        $this->rc->output->add_gui_object('recoverypasswordform', 'recovery-password-form');
        $this->rc->output->send('password_recovery.recovery_password_form');
    }

    // Creating form for new password
    private function new_password_form() {
        $this->rc->output->add_label(
            'password_recovery.newpassword',
            'password_recovery.newpassword_confirm',
            'password_recovery.question',
            'password_recovery.answer',
            'password_recovery.code',
            'password_recovery.recovery_password',
            'password_recovery.renew_code',
            'password_recovery.count_send_code_maximum',
            'password_recovery.no_code',
            'password_recovery.no_answer',
            'password_recovery.no_password',
            'password_recovery.no_password_confirm',
            'password_recovery.password_inconsistency',
            'password_recovery.password_too_short'
        );

        $this->rc->output->set_pagetitle($this->gettext('recovery_password'));
        $this->rc->output->add_gui_object('newpasswordform', 'new-password-form');

        $password_minimum_length = ($this->use_password ? $this->rc->config->get('password_minimum_length',8) : $this->rc->config->get('pr_password_minimum_length',8));

        $this->rc->output->set_env('pr_username', $this->user['username']);
        $this->rc->output->set_env('pr_question', $this->user['question']);
        $this->rc->output->set_env('pr_use_question', (bool) ($this->rc->config->get('pr_use_question') && $this->user['have_answer']));
        $this->rc->output->set_env('pr_use_confirm_code', (bool) ($this->rc->config->get('pr_use_confirm_code') && $this->user['have_code']));
        $this->rc->output->set_env('pr_password_minimum_length', (int) $password_minimum_length);

        $this->rc->output->send('password_recovery.new_password_form');
    }

    // Renew and send confirmation code to user (to alternative email and phone) 
    private function renew_confirm_code() {
        if ($this->get_confirm_code_count() < $this->rc->config->get('pr_confirm_code_count_max')) {
            $result = $this->send->send_confirm_code_to_user();
            if ($result['send']) {
                $this->update_confirm_code_count(1);
                $message = $result['message'];
                $type = 'confirmation';
            } else {
                $message = $this->gettext('send_failed') . "\n" . $result['message'];
                $type = 'error';
            }
        } else {
            $message = $this->gettext('count_send_code_maximum');
            $type = 'error';
        }
        $this->rc->output->command('display_message', $message, $type);
        $this->rc->output->send('plugin');
    }

    // Creating and send confirmation code to user (to alternative email and phone) or send message to administrator
    private function reset_password() {
        // kill remember_me cookies
        setcookie ('rememberme_user', '', time()-3600);
        setcookie ('rememberme_pass', '', time()-3600);

        $allow_answer = ($this->rc->config->get('pr_use_question') && $this->user['have_answer']);
        $allow_code = ($this->rc->config->get('pr_use_confirm_code') && $this->user['have_code']);

        if (!$this->user['username']) {
            $message = $this->gettext('user_not_found');
            $type = 'error';
        } else if (!$allow_answer && !$allow_code) {
            $this->send->send_alert_to_admin($this->user['username']);
            $message = $this->gettext('sent_to_admin');
            $type = 'error';
        } else if ($allow_code) {
            $result['send'] = false;
            if ($this->user['token_validity'] && !$this->user['token_expired']) {
                $this->update_confirm_code_count(1);
                $result['send'] = true;
                $message = $this->gettext('check_account_notice');
                $type = 'notice';
            } else {
                $result = $this->send->send_confirm_code_to_user();
                if ($result['send']) {
                    $this->update_confirm_code_count(1);
                    $message = $result['message'];
                    $type = 'confirmation';
                } else {
                    $message = $this->gettext('send_failed') . "\n" . $result['message'];
                    $type = 'error';
                }
            }
        }

        $this->logging("Password recovery request for '" . $this->user['username'] . "' (IP: " . rcube_utils::remote_addr() . ")");
        if ($message) {
            $this->rc->output->command('display_message', $message, $type);
        }

        if ($type == 'error') {
            $this->recovery_password_form();
        } else {
            $this->new_password_form();
        }
    }

    // Save new password to DB
    private function save_password() {
        $params = rcube_utils::request2param(rcube_utils::INPUT_POST);
        $this->debug("Save new password: " . print_r($params, true));

        if ($this->rc->config->get('pr_use_question') && $this->user['have_answer'] && $this->user['answer'] != $params['answer']) {
            $message = $this->gettext('answer_failed');
            $type = 'error';
        } else if ($this->rc->config->get('pr_use_confirm_code') && $this->user['token_expired']) {
            $message = $this->gettext('code_expired');
            $type = 'error';
        } else if ($this->rc->config->get('pr_use_confirm_code') && $this->user['token'] != $params['code']) {
            $message = $this->gettext('code_failed');
            $type = 'error';
        } else {
            // props to save
            $save = ['token'=>'', 'token_validity'=>''];

            // check allowed characters according to the configured 'password_charset' option
            // by converting the password entered by the user to this charset and back to UTF-8
            $rc_charset = strtoupper($this->rc->output->get_charset());
            $orig_pwd = $params['newpassword'];
            $chk_pwd = rcube_charset::convert($orig_pwd, $rc_charset, 'UTF-8');
            $chk_pwd = rcube_charset::convert($chk_pwd, 'UTF-8', $rc_charset);

            // We're doing this for consistence with Roundcube core
            $newpassword = rcube_charset::convert($params['newpassword'], $rc_charset, 'UTF-8');

            if ($chk_pwd != $orig_pwd || preg_match('/[\x00-\x1F\x7F]/', $newpassword)) {
                $message = $this->gettext('password_forbidden');
                $type = 'error';
            } else if (!$this->use_password && ($chk_strength = $this->pwd->_check_strength($newpassword))) {
                $message = $chk_strength;
                $type = 'error';
            } else {
                if ($this->use_password) {
                    $result = $this->pwd->_save($newpassword, $this->user['username']);
                    if ($result != 0) {
                        $message = $this->gettext('write_failed') . ": " . $result;
                        $type = 'error';
                        $this->debug($message);
                    }
                } else {
                    $save['password'] = crypt($newpassword, '$1$' . rcube_utils::random_bytes(9));
                }

                if ($type != 'error' && $this->set_user_props($save)) {
                    $this->logging("Save new password for '" . $this->user['username'] . "' (IP: " . rcube_utils::remote_addr() . ")");
                    $message = $this->gettext('password_changed');
                    $type = 'confirmation';
                } else if (!$this->use_password) {
                    $message = $this->gettext('password_not_changed');
                    $type = 'error';
                }
            }
        }

        $this->rc->output->command('display_message', $message, $type);

        if ($type != 'error') {
            $this->rc->kill_session();
            $this->rc->output->command('redirect', './', 2);
//            $this->rc->output->send('login');
        }
    }

    /*******************
    * IDENTITIES
    *******************/

    // Verifying the user identities needed to recover the password
    function check_identities() {
        if (!isset($_SESSION['show_notice_identities']) && !$this->user['have_altemail'] && !$this->user['have_phone']) {
            $link = "<a href='./?_task=settings&_action=identities'>". $this->gettext('click_here') ."</a>";
            $this->rc->output->command('display_message', sprintf($this->gettext('no_identities'), $link), 'notice');
            $_SESSION['show_notice_identities'] = true;
        }
    }

    // Handler for 'identity_form' hook (executed on identities form create)
    function identity_form($p) {
        if (isset($p['form']['addressing']) && !empty($p['record']['identity_id'])) {
            $new_fields = [];
            foreach ($p['form']['addressing']['content'] as $col => $colprop) {
                $new_fields[$col] = $colprop;
                if ($col == 'email') {
                    // add ext fields after 'email'
                    foreach ($this->fields as $field => $field_name){
                        $new_fields[$field] = ['type' => 'text', 'size' => 40, 'label' => $this->gettext($field)];
                    }
                }
            }

            if (!$this->rc->config->get('pr_use_question')) {
                unset($new_fields['question']);
                unset($new_fields['answer']);
            }

            $p['form']['addressing']['content'] = $new_fields;

            if($this->user['username']){
                foreach ($this->fields as $field => $field_name){
                    $p['record'][$field] = $this->user[$field];
                }
            }
        }
        return $p;
    }

    // Handler for identity_update hook (executed on identities form submit)
    function identity_update($p) {
        $save = [];
        foreach ($this->fields as $field => $field_name) {
            $save[$field] = rcube_utils::get_input_value("_".$field,rcube_utils::INPUT_POST);
        }

        foreach ($save as $par => $val) {
            if ((!$this->user['username'] && empty($val)) || ($this->user['username'] && $val == $this->user[$par])) {
                unset($save[$par]);
            }
        }

        if ($save['altemail']) {
            $save['altemail'] = rcube_utils::idn_to_ascii($save['altemail']);
            if (empty($save['altemail'])) {
                $this->rc->output->command('display_message', $this->gettext('altemail_cleared'), 'confirmation');
            } else if($save['altemail'] == $p['record']['email']) {
                unset($save['altemail']);
                $p['abort'] = true;
                $p['message'] = $this->gettext('altemail_match_primary');
                return $p;
            } else if(!rcube_utils::check_email($save['altemail'])) {
                unset($save['altemail']);
                $p['abort'] = true;
                $p['message'] = $this->gettext('altemail_invalid');
                return $p;
            }
        }

        if (count($save)) {
            $this->debug("Save user identities: " . print_r($save, true));
            $this->set_user_props($save);
        }

        return $p;
    }

    // Return array - user props (username must be user@domain.ltd)
    function get_user_props($username = null, $with_alias = true) {
        if (!$username) {
            $username = ($this->rc->get_user_name() ? $this->rc->get_user_name() : rcube_utils::get_input_value("_username", rcube_utils::INPUT_GPC));
        }

        $ret = [];
        $user = trim(urldecode($username));
        if ($user) {
            // get user row
            $query = "SELECT u.user_id, u.username, i.email" .
                    " FROM users u" .
                    " INNER JOIN identities i ON i.user_id = u.user_id" .
                    " WHERE username=?";

            $result = $this->rc->db->query($query, $user);

            if ($result && ($arr = $this->rc->db->fetch_assoc($result))) {
                $fields = [];
                foreach ($this->fields as $field => $field_name) {
                    $fields[] = $field_name . " as " . $field;
                }
                $query = "SELECT " . implode(",",$fields) . ", token, token_validity, token='' OR token_validity < NOW() as token_expired" .
                        " FROM " . $this->rc->config->get('pr_users_table') .
                        " WHERE username=?";

                $result = $this->db->query($query, $arr['email']);
                $ret = array_merge($arr, $this->db->fetch_assoc($result));
            } else {
                // for alias (with users_alias plugin)
                if ($with_alias && $this->rc->plugins->load_plugin('users_alias', true)) {
                    $users_alias = new users_alias($this->api);
                    $result = $users_alias->alias2user(['user' => $user]);
                    if ($result['user']) {
                        $ret = $this->get_user_props($result['user'], false);
                    }
                }
            }

            $_SESSION['username'] = $ret['username']; //for password plugin

            $ret['have_altemail'] = ($ret['altemail'] && !empty($ret['altemail']));
            $ret['have_phone'] = ($ret['phone'] && !empty($ret['phone']));
            $ret['have_code'] = ($ret['have_altemail'] || $ret['have_phone']);
            $ret['have_answer'] = ($ret['question'] && !empty($ret['question']) && $ret['answer'] && !empty($ret['answer']));
        }

        $this->user = $ret;
        return $ret;
    }

    // Save user props to DB
    function set_user_props($props) {
        $fields = [];
        foreach ($this->fields as $field => $field_name) {
            if (isset($props[$field])) {
                $fields[] = $field_name . " = '" . $props[$field] . "'";
            }
        }

        if (isset($props['token'])) {
            if (empty($props['token'])) {
                $code_validity_time = 0;
            } else {
                $code_validity_time = (int) $this->rc->config->get('pr_confirm_code_validity_time', 30);
            }
            $fields[] = "token = '" . $props['token'] . "', token_validity = NOW() + INTERVAL " . $code_validity_time . " MINUTE";
        }

        if ($props['password']) {
            $fields[] = "password = '" . $props['password'] . "'";
        }

        if (count($fields)) {
            $query = "UPDATE " . $this->rc->config->get('pr_users_table') . " SET " . implode(",",$fields) . " WHERE username=?";
            $this->db->query($query, $this->user['username']);
            $this->get_user_props(); //update user props
            $this->debug("Update user '" . $this->user['username'] . "' props: " . print_r($fields, true));
            return $this->db->affected_rows() == 1;
        }
        return false;
    }

    function update_confirm_code_count($plus = 0) {
        $count = $this->get_confirm_code_count() + $plus;
        $_SESSION['pr_confirm_code_count'] = $count;
    }

    function get_confirm_code_count() {
        if (!isset($_SESSION['pr_confirm_code_count'])) {
            $_SESSION['pr_confirm_code_count'] = 0;
        }
        return $_SESSION['pr_confirm_code_count'];
    }

    /*******************
    * service functions
    *******************/

    function get_dbh() {
        if (!$this->db) {
            if ($dsn = $this->rc->config->get('pr_db_dsn')) {
                $this->db = rcube_db::factory($dsn);
                $this->db->set_debug((bool)$this->rc->config->get('sql_debug'));
            }
            else {
                $this->db = $this->rc->get_dbh();
            }
        }
        return $this->db;
    }

    function get_action() {
        $action = rcube_utils::get_input_value('_a', rcube_utils::INPUT_GPC);
        if (!$action || empty($action)) {
            $action = 'init';
        }
        return $action;
    }

    function logging($text) {
        if ($this->rc->config->get('pr_password_log') == true) {
            rcube::write_log('password', $text);
        }
    }

    function debug($text) {
        if ($this->rc->config->get('pr_debug') == true) {
            $msg = (is_array($text) ? print_r($text, true) : $text);
            rcube::write_log('console', $msg);
        }
    }
}

?>
