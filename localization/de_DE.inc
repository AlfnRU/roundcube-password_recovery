<?php

$labels['forgot_password']          = 'Passwort vergessen?';
$labels['recovery_password']        = 'Passwort zurücksetzen';
$labels['phone']                    = 'Telefonnummer';
$labels['altemail']                 = 'Alternative E-Mail-Adresse';
$labels['newpassword']              = 'Neues Passwort';
$labels['newpassword_confirm']      = 'Bestätigen';
$labels['code']                     = 'Bestätigungscode';
$labels['question']                 = 'Geheime Frage';
$labels['answer']                   = 'Geheime Antwort';
$labels['answer_confirm']           = 'Bestätigung';
$labels['recovery']                 = 'Zurücksetzen';
$labels['save']                     = 'Speichern';
$labels['cancel']                   = 'Abbrechen';
$labels['email_subject']            = 'Restore mail access';
$labels['email_subject_admin']      = 'Password recovery request';
$labels['click_here']               = 'Hier klicken';
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

$messages['disabled']               = 'Aufgrund von Wartungsarbeiten ist das Zurücksetzen von Passwörtern zur Zeit nicht möglich. Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut oder kontaktieren Sie Ihren Administrator.';
$messages['banned']                 = 'Aufgrund von zu vielen Zugriffsversuchen, wurde der Zugang temporär gesperrt. Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut oder kontaktieren Sie Ihren Administrator.';

$messages['no_identities']          = 'The settings required for password recovery are not configured. %s and configure!';
$messages['user_not_found']         = 'Benutzer wurde nicht gefunden.';
$messages['check_account_notice']   = 'The password recovery code has already been sent to you. Check your email and phone';
$messages['check_account']          = 'Password recovery code has been sent to you ';
$messages['check_email']            = 'at an additional email address ';
$messages['check_sms']              = 'on the phone';
$messages['and']                    = 'und ';
$messages['sent_to_admin']          = 'No password recovery information was found, so your request was sent to the administrator.';
$messages['send_failed']            = 'Error sending code to restore password. Try again later.';
$messages['write_failed']           = 'Schreibfehler! Bitte kontaktieren Sie Ihren Administrator.';
$messages['code_expired']           = 'Der Bestätigungscode ist abgelaufen.';
$messages['code_failed']            = 'Invalid confirmation code';
$messages['answer_failed']          = 'Die geheime Antwort passt nicht zu der geheimen Frage.';
$messages['password_invalid']       = 'Invalid password';
$messages['password_check_failed']  = 'Das Passwort erfüllt nicht die Sicherheitsanfoderungen!';
$messages['password_forbidden']     = 'The password contains invalid characters';
$messages['password_changed']       = 'Password changed successfully!';
$messages['password_not_changed']   = 'An error occurred while changing your password. Please try again later or contact your administrator.';
$messages['crypt_error']            = 'Das neue Passwort konnte nicht gespeichert werden, da die kryptografische Funktion fehlt.';
$messages['connect_error']          = 'Das neue Passwort konnte nicht gespeichert werden, da die Verbindung fehlgeschlagen ist.';
$messages['password_in_history']    = 'Dieses Passwort wurde bereits verwendet.';
$messages['password_const_viol']    = 'Password restriction violation. The password may be too weak.';
$messages['phone_invalid']          = 'Wrong phone number! Number must be %d digits';
$messages['altemail_invalid']       = 'Die alternative E-Mail-Adresse ist falsch!';
$messages['altemail_match_primary'] = 'Die alternative E-Mail-Adresse darf nicht identisch zu Ihrer primären E-Mail-Adresse sein.';

?>
