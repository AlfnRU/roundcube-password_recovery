<?php

/*  Addition for PostfixAdmin (for plugin roundcube password recovery)
    add this to the end of the file 'config.inc.php' or 'config.local.inc.php'

    if (file_exists(dirname(__FILE__) . '/roundcube_password_recovery.php')) {
        require_once(dirname(__FILE__) . '/roundcube_password_recovery.php');
    }
*/

$CONF['language_hook'] = 'a_language_hook';
function a_language_hook($PALANG, $language) {
    $PALANG['pQuestion'] = 'Secret question';
    $PALANG['pAnswer'] = 'Answer';
    return $PALANG;
}


$CONF['mailbox_struct_hook'] = 'a_struct_mailbox_modify';
function a_struct_mailbox_modify($struct) {
    $struct['phone']       = pacol(1, 1, 1, 'text', 'pCreate_mailbox_phone', 'pCreate_mailbox_phone_desc');
    $struct['email_other'] = pacol(1, 1, 1, 'text', 'pCreate_mailbox_email', 'pCreate_mailbox_email_desc');
    $struct['question']    = pacol(1, 1, 0, 'text', 'pQuestion', '');
    $struct['answer']      = pacol(1, 1, 0, 'text', 'pAnswer', '');
    return $struct;
}

?>
