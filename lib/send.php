<?php

class password_recovery_send {

    private $rc;
    private $pr;
    private $user;

    function __construct($pr_plugin) {
        $this->pr = $pr_plugin;
        $this->rc = $pr_plugin->rc;
        $this->user = $pr_plugin->user;
    }

    // Send SMS over Clickatell API
    function send_sms_clickatell($to, $message) {
        $clickatell_api_id   = 'CHANGEME';
        $clickatell_user     = 'CHANGEME';
        $clickatell_password = 'CHANGEME';
        $clickatell_sender   = 'CHANGEME';

        $url = 'https://api.clickatell.com/http/sendmsg?api_id=%s&user=%s&password=%s&to=%s&from=%s&text=%s';
        $url = sprintf($url, $clickatell_api_id, $clickatell_user, $clickatell_password, $to, $clickatell_sender, urlencode($message));
        $result = file_get_contents($url);
        return $result !== false;
    }



    // Send SMS
    function send_sms($to, $message) {
        $ret = false;
        $to = escapeshellarg($to);
        $message = escapeshellarg($message);
        $sms_send_function = $this->rc->config->get('pr_sms_send_function');
        if ($sms_send_function) {
            if (is_file($sms_send_function)) {
                $ret = (int) exec("bash $sms_send_function $to $message");
            } else if (is_callable($sms_send_function)) {
                $ret = $sms_send_function($to, $message);
            }
        }
        return $ret !== false || $ret > 0;
    }

    // Send E-Mail
    function send_email($to, $from, $subject, $body) {
        $ctb = md5(rand() . microtime());
        $subject = "=?UTF-8?B?".base64_encode($subject)."?=";

        $headers  = "Return-Path: $from\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"=_$ctb\"\r\n";
        $headers .= "Date: " . date('r', time()) . "\r\n";
        $headers .= "From: $from\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "Subject: $subject\r\n";
        $headers .= "Reply-To: $from\r\n";

        $txt_body  = "--=_$ctb\r\n";
        $txt_body .= "\r\n";
        $txt_body .= "Content-Transfer-Encoding: 7bit\r\n";
        $txt_body .= "Content-Type: text/plain; charset=" . $this->rc->config->get('default_charset', RCUBE_CHARSET) . "\r\n";

        $h2t = new rcube_html2text($body, false, true, 0);
        $txt = rcube_mime::wordwrap($h2t->get_text(), $this->rc->config->get('line_length', 75), "\r\n");
        $txt = wordwrap($txt, 998, "\r\n", true);
        $txt_body .= "$txt\r\n";
        $txt_body .= "--=_$ctb";
        $txt_body .= "\r\n";

        $msg_body = "Content-Type: multipart/alternative; boundary=\"=_$ctb\"\r\n\r\n";
        $msg_body .= $txt_body;
        $msg_body .= "Content-Transfer-Encoding: quoted-printable\r\n";
        $msg_body .= "Content-Type: text/html; charset=" . $this->rc->config->get('default_charset', RCUBE_CHARSET) . "\r\n\r\n";
        $msg_body .= str_replace("=","=3D",$body);
        $msg_body .= "\r\n\r\n";
        $msg_body .= "--=_$ctb--";
        $msg_body .= "\r\n\r\n";

        // send message
        if (!is_object($this->rc->smtp)) {
            $this->rc->smtp_init(true);
        }

        if($this->rc->config->get('smtp_pass') == "%p") {
            $this->rc->config->set('smtp_server', $this->rc->config->get('pr_default_smtp_server'));
            $this->rc->config->set('smtp_user', $this->rc->config->get('pr_default_smtp_user'));
            $this->rc->config->set('smtp_pass', $this->rc->config->get('pr_default_smtp_pass'));
        }

        $this->rc->smtp->connect();
        if($this->rc->smtp->send_mail($from, $to, $headers, $msg_body)) {
            return true;
        } else {
            rcube::write_log('errors', 'response:' . print_r($this->rc->smtp->get_response(),true));
            rcube::write_log('errors', 'errors:' . print_r($this->rc->smtp->get_error(),true));
            return false;
        }
    }

    // Send message to administrator
    function send_alert_to_admin($user_requesting_new_password) {
        $file = $this->get_localization_dir($this->rc->user->language) . "/alert_for_admin_to_reset_pw.html";
        $body = strtr(file_get_contents($file), array('[USER]' => $user_requesting_new_password));
        $subject = $this->pr->gettext('email_subject_admin');
        return $this->send_email(
            $this->rc->config->get('pr_admin_email'),
            $this->get_email_from($user_requesting_new_password),
            $subject,
            $body
        );
    }

    // Send code to user
    function send_confirm_code_to_user() {
        $send_email = false;
        $send_sms = false;
        $confirm_code = $this->generate_confirm_code();

        if ($confirm_code && $this->pr->set_user_props(['token'=>$confirm_code])) {
            // send EMail
            if ($this->user['have_altemail']) {
                $file = $this->get_localization_dir($this->rc->user->language) . "/reset_pw_body.html";
                $link = "http://{$_SERVER['SERVER_NAME']}/?_task=login&_action=plugin.password_recovery&_username=". $this->user['username'];
                $body = strtr(file_get_contents($file), ['[LINK]' => $link, '[CODE]' => $confirm_code]);
                $subject = $this->pr->gettext('email_subject');

                $from = $this->rc->config->get('pr_replyto_email');
                if(!$from){
                    $from = $this->get_email_from($this->rc->config->get('pr_admin_email'));
                }

                $send_email = $this->send_email(
                    $this->user['altemail'],
                    $from,
                    $subject,
                    $body
                );
            }

            // send SMS
            if ($this->user['have_phone']) {
                $send_sms = $this->send_sms(
                    $this->user['phone'],
                    $this->pr->gettext('code') . ": " . $confirm_code
                );
            }

            // log & message
            if ($send_email || $send_sms) {
                $log = "Send password recovery code [". $confirm_code . "] for '" . $this->user['username'] . "'";
                $message = $this->pr->gettext('check_account');
                if ($send_email) {
                    $log .= " to alt email: '" . $this->user['altemail'] . "'";
                    $message .= $this->pr->gettext('check_email');
                }
                if ($send_sms) {
                    if ($send_email) {
                        $log .= " and";
                        $message .= $this->pr->gettext('and');
                    }
                    $log .= " to phone: '" . $this->user['phone'] . "'";
                    $message .= $this->pr->gettext('check_sms');
                }
                $this->pr->logging($log);
            } else {
                $this->pr->set_user_props(['token'=>'', 'token_validity'=>'']);
            }
        } else {
            $message = $this->pr->gettext('write_failed');
        }

        return [
            'send' => ($send_email || $send_sms),
            'message' => $message
        ];
    }

    // Generate and return a random code
    function generate_confirm_code() {
        $code_length = (int) $this->rc->config->get('pr_confirm_code_length', 6);
        $code = "";
        $possible = "0123456789";
        while (strlen($code) < $code_length) {
            $random = random_int(0, strlen($possible)-1);
            $char = substr($possible, $random, 1);
            $code .= $char;
            $possible = str_replace($char,"",$possible); //removing the used character from the possible
        }
        return $code;
    }

    function get_email_from($email) {
        $parts = explode('@',$email);
        return 'no-reply@'.$parts[1];
    }

    function get_localization_dir($language) {
        $file = dirname(__FILE__) . "/../localization/" . $language;
        if (!file_exists($file)) {
            $file = dirname(__FILE__) . "/../localization/en_US";
        }
        return $file;
    }
}

?>
