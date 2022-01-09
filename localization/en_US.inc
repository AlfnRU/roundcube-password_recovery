<?php

$labels['forgot_password']          = 'Forgot password?';
$labels['recovery_password']        = 'Recovery password';
$labels['phone']                    = 'Phone';
$labels['altemail']                 = 'Alternate E-mail';
$labels['newpassword']              = 'New password';
$labels['newpassword_confirm']      = 'Confirmation';
$labels['code']                     = 'Confirmation code for recovery password';
$labels['question']                 = 'Secret question';
$labels['answer']                   = 'Answer to a secret question';
$labels['answer_confirm']           = 'Confirmation';
$labels['recovery']                 = 'Recovery';
$labels['save']                     = 'Save';
$labels['cancel']                   = 'Cancel';
$labels['email_subject']            = 'Restore mail access';
$labels['email_subject_admin']      = 'Password recovery request';
$labels['click_here']               = 'Click here';
$labels['no_identities']            = 'Complete your password recovery information.';
$labels['no_username']              = 'Enter your user name (e-mail address)';
$labels['renew_code']               = 'Send a new confirmation code';
$labels['count_send_code_maximum']  = 'Maximum number of confirmation code submissions exceeded';
$labels['no_code']                  = 'Enter confirmation code';
$labels['no_answer']                = 'Enter a secret answer';
$labels['no_password']              = 'Enter new password';
$labels['no_password_confirm']      = 'Enter password confirmation';
$labels['password_inconsistency']   = 'Password and confirmation do not match';
$labels['password_too_short']       = 'The password must be at least %d characters long.';

$messages['disabled']               = 'The system is currently being maintained and password recovery is not possible. Everything should return to normal soon. We apologize for the inconvenience.';
$messages['banned']                 = 'Access is temporarily blocked (too many failed login attempts). Please try again later or contact your administrator.';
$messages['no_identities']          = 'The settings required for password recovery are not configured. %s and configure!';
$messages['user_not_found']         = 'User not found';
$messages['check_account_notice']   = 'The password recovery code has already been sent to you. Check your email and phone';
$messages['check_account']          = 'Password recovery code has been sent to you ';
$messages['check_email']            = 'at an additional email address ';
$messages['check_sms']              = 'on the phone';
$messages['and']                    = 'and ';
$messages['sent_to_admin']          = 'No password recovery information was found, so your request was sent to the administrator.';
$messages['send_failed']            = 'Error sending code to restore password. Try again later.';
$messages['write_failed']           = 'Error writing data. Contact your administrator.';
$messages['code_expired']           = 'Confirmation code has expired';
$messages['code_failed']            = 'Invalid confirmation code';
$messages['answer_failed']          = 'Incorrect answer to secret question';
$messages['password_invalid']       = 'Invalid password';
$messages['password_check_failed']  = 'Error. The password is too simple!';
$messages['password_forbidden']     = 'The password contains invalid characters';
$messages['password_changed']       = 'Password changed successfully!';
$messages['password_not_changed']   = 'An error occurred while changing your password. Please try again later or contact your administrator.';
$messages['crypt_error']            = 'I cannot save my new password. Missing cryptographic function.';
$messages['connect_error']          = 'I cannot save my new password. Connection error.';
$messages['password_in_history']    = 'This password has already been used.';
$messages['password_const_viol']    = 'Password restriction violation. The password may be too weak.';
$messages['phone_invalid']          = 'Wrong phone number! Number must be %d digits';
$messages['altemail_invalid']       = 'The alternate email address is incorrect!';
$messages['altemail_match_primary'] = 'The secondary email address must not be the same as the primary email address!';

?>
