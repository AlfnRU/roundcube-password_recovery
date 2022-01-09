<?php

/*******************************************
*
* Function for save with Password plugin
*
*******************************************/

define('PASSWORD_SUCCESS', 0);
define('PASSWORD_CRYPT_ERROR', 1);
define('PASSWORD_ERROR', 2);
define('PASSWORD_CONNECT_ERROR', 3);
define('PASSWORD_IN_HISTORY', 4);
define('PASSWORD_CONSTRAINT_VIOLATION', 5);
define('PASSWORD_COMPARE_CURRENT', 6);
define('PASSWORD_COMPARE_NEW', 7);


class password_recovery_pwd {

    private $rc;
    private $pr;
    private $drivers = [];

    function __construct($pr_plugin) {
        $this->pr = $pr_plugin;
        $this->rc = $pr_plugin->rc;
    }

    function _check_strength($passwd)
    {
        $min_score = ($this->pr->use_password ? $this->rc->config->get('password_minimum_score') : $this->rc->config->get('pr_password_minimum_score'));

        if (!$min_score) {
            return;
        }

        if ($this->pr->use_password && ($driver = $this->_load_driver('strength')) && method_exists($driver, 'check_strength')) {
            list($score, $reason) = $driver->check_strength($passwd);
        } else {
            $score = (!preg_match("/[0-9]/", $passwd) || !preg_match("/[^A-Za-z0-9]/", $passwd)) ? 1 : 5;
        }

        if ($score < $min_score) {
            return $this->pr->gettext('password_check_failed') . (!empty($reason) ? " $reason" : '');
        }
    }

    function _save($passwd, $username)
    {
        if ($res = $this->_check_strength($passwd)) {
            return $res;
        }

        if (!($driver = $this->_load_driver())) {
            return $this->pr->gettext('write_failed');
        }

        $result  = $driver->save('', $passwd, $username);
        $message = '';

        if (is_array($result)) {
            $message = $result['message'];
            $result  = $result['code'];
        }

        switch ($result) {
            case PASSWORD_SUCCESS:
                return PASSWORD_SUCCESS;
            case PASSWORD_CRYPT_ERROR:
                $reason = $this->pr->gettext('crypt_error');
                break;
            case PASSWORD_CONNECT_ERROR:
                $reason = $this->pr->gettext('connect_error');
                break;
            case PASSWORD_IN_HISTORY:
                $reason = $this->pr->gettext('password_in_history');
                break;
            case PASSWORD_CONSTRAINT_VIOLATION:
                $reason = $this->pr->gettext('password_const_viol');
                break;
            case PASSWORD_ERROR:
            default:
                $reason = $this->pr->gettext('write_failed');
        }

        if ($message) {
            $reason .= ' ' . $message;
        }

        return $reason;
    }

    function _load_driver($type = 'password')
    {
        if (!($type && $driver = $this->rc->config->get('password_' . $type . '_driver'))) {
            $driver = $this->rc->config->get('password_driver', 'sql');
        }

        if (empty($this->drivers[$type])) {
            $class  = "rcube_{$driver}_password";
            $file = __DIR__ . "/../../password/drivers/$driver.php";

            if (!file_exists($file)) {
                rcube::raise_error([
                        'code' => 600, 'file' => __FILE__, 'line' => __LINE__,
                        'message' => "Password plugin: Driver file does not exist ($file)"
                    ], true, false
                );
                return false;
            }

            include_once $file;

            if (!class_exists($class, false) || (!method_exists($class, 'save') && !method_exists($class, 'check_strength'))) {
                rcube::raise_error([
                        'code' => 600, 'file' => __FILE__, 'line' => __LINE__,
                        'message' => "Password plugin: Broken driver $driver"
                    ], true, false
                );
                return false;
            }

            $this->drivers[$type] = new $class;
        }

        return $this->drivers[$type];
    }

}

?>
