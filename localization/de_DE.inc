<?php

$labels['forgot_password']          = 'Passwort vergessen?';
$labels['recovery_password']        = 'Passwort zurücksetzen';
$labels['phone']                    = 'Telefonnummer';
$labels['altemail']                 = 'Alternative E-Mail';
$labels['newpassword']              = 'Neues Passwort';
$labels['newpassword_confirm']      = 'Bestätigen';
$labels['code']                     = 'Bestätigungscode';
$labels['question']                 = 'Geheime Frage';
$labels['answer']                   = 'Geheime Antwort';
$labels['answer_confirm']           = 'Bestätigung';
$labels['recovery']                 = 'Zurücksetzen';
$labels['save']                     = 'Speichern';
$labels['cancel']                   = 'Abbrechen';
$labels['email_subject']            = 'Wiederherstellung der E-Mail-Zugangsdaten';
$labels['email_subject_admin']      = 'Anfrage zur wiederherstellung von E-Mail-Zugangsdaten';
$labels['click_here']               = 'Hier klicken';
$labels['no_identities']            = 'Vervollständigen Sie Ihre Identitätseinstellungen.';
$labels['no_username']              = 'Geben Sie Ihren Benutzernamen (E-Mail-Adresse) ein.';
$labels['renew_code']               = 'Einen neuen Bestätigungscodes versenden.';
$labels['count_send_code_maximum']  = 'Sie haben die maximale Anzahl an Bestätigungscodes erhalten.';
$labels['no_code']                  = 'Geben Sie den Bestätigungscode an.';
$labels['no_answer']                = 'Geben Sie eine Geheime Antwort an.';
$labels['no_password']              = 'Geben Sie ein neues Passwort ein.';
$labels['no_password_confirm']      = 'Geben Sie das neue Passwort erneut ein.';
$labels['password_inconsistency']   = 'Das angegebene Passwort ist nicht identisch.';
$labels['password_too_short']       = 'Das Passwort muss mindestens %d Stellig aufweisen.';

$messages['disabled']               = 'Aufgrund von Wartungsarbeiten ist das Zurücksetzen von Passwörtern zur Zeit nicht möglich. Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut oder kontaktieren Sie Ihren Administrator.';
$messages['banned']                 = 'Aufgrund von zu vielen Zugriffsversuchen, wurde der Zugang temporär gesperrt. Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut oder kontaktieren Sie Ihren Administrator.';
$messages['no_identities']          = 'Damit Ihr Passwort im Falle eines verlustes zurückgestezt werden kann, müssen Sie zunächst Ihre Identität festlegen. %s um eine Identität anzulegen.';
$messages['user_not_found']         = 'Benutzer wurde nicht gefunden.';
$messages['check_account_notice']   = 'Der Bestätigungscode wurde bereits versandt. Überprüfen Sie Ihre alternative E-Mail oder Ihr Telefon.';
$messages['check_account']          = 'Der Bestätigungscode wurde an Sie versandt ';
$messages['check_email']            = 'an Ihre alternative E-Mail ';
$messages['check_sms']              = 'auf dem Telefon';
$messages['and']                    = 'und ';
$messages['sent_to_admin']          = 'Das Passwort kann nicht zurückgesetzt werden, da keine Identität hinterlegt wurde. Ihre Anfrage wurde an einen Administrator weitergeleitet.';
$messages['send_failed']            = 'Fehler beim versenden des Bestätigungscodes. Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut.';
$messages['write_failed']           = 'Schreibfehler! Bitte kontaktieren Sie Ihren Administrator.';
$messages['code_expired']           = 'Der Bestätigungscode ist abgelaufen.';
$messages['code_failed']            = 'Der Bestätigungscode ist ungültig.';
$messages['answer_failed']          = 'Die geheime Antwort passt nicht zu der geheimen Frage.';
$messages['password_invalid']       = 'Das Passwort ist ungültig.';
$messages['password_check_failed']  = 'Das Passwort erfüllt nicht die Sicherheitsanfoderungen!';
$messages['password_forbidden']     = 'Das Passwort enthält ungültige Zeichen';
$messages['password_changed']       = 'Das Passwort wurde erfolgreich zurückgesetzt.';
$messages['password_not_changed']   = 'Ein Fehler ist aufgetreten. Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut oder kontaktieren Sie Ihren Administrator.';
$messages['crypt_error']            = 'Das neue Passwort konnte nicht gespeichert werden, da die kryptografische Funktion fehlt.';
$messages['connect_error']          = 'Das neue Passwort konnte nicht gespeichert werden, da die Verbindung fehlgeschlagen ist.';
$messages['password_in_history']    = 'Dieses Passwort wurde bereits verwendet.';
$messages['password_const_viol']    = 'Password restriction violation. The password may be too weak.';
$messages['phone_invalid']          = 'Falsche Telefonnummer! Eine Telefonnummer muss midestens %d Zahlen enthalten.';
$messages['altemail_invalid']       = 'Die alternative E-Mail ist falsch!';
$messages['altemail_match_primary'] = 'Die alternative E-Mail darf nicht identisch zu der primären E-Mail sein.';

?>
