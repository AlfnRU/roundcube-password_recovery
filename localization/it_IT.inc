<?php

$labels['forgot_password']          = 'Password dimenticata?';
$labels['recovery_password']        = 'Recupera la password';
$labels['phone']                    = 'Telefono';
$labels['altemail']                 = 'Indirizzo e-mail alternativo';
$labels['newpassword']              = 'Nuova password';
$labels['newpassword_confirm']      = 'Conferma';
$labels['code']                     = 'Codice di conferma';
$labels['question']                 = 'Domanda segreta';
$labels['answer']                   = 'Risposta alla domanda segreta';
$labels['answer_confirm']           = 'Conferma';
$labels['recovery']                 = 'Recupera';
$labels['save']                     = 'Salva';
$labels['cancel']                   = 'Annulla';
$labels['email_subject']            = 'Restore mail access';
$labels['email_subject_admin']      = 'Richiesta recupero password';
$labels['click_here']               = 'Clicca qui';
$labels['no_identities']            = 'Compila le informazioni di recupero della password.';
$labels['no_username']              = 'Inserisci il tuo nome utente (indirizzo e-mail)';
$labels['renew_code']               = 'Invia un nuovo codice di conferma';
$labels['count_send_code_maximum']  = 'Hai superato il massimo numero di richieste del codice di conferma';
$labels['no_code']                  = 'Inserisci il codice di conferma';
$labels['no_answer']                = 'Inserisci la domanda segreta';
$labels['no_password']              = 'Inserisci la nuova password';
$labels['no_password_confirm']      = 'Conferma la password';
$labels['password_inconsistency']   = 'Le password non corrispondono';
$labels['password_too_short']       = 'La password deve essere lunga almeno %d caratteri.';

$messages['disabled']               = 'The system is currently being maintained and password recovery is not possible. Everything should return to normal soon. We apologize for the inconvenience.';
$messages['banned']                 = 'Accesso temporaneamente bloccato (troppi tentativi falliti). Prova più tardi o contatta l'amministratore del sistema'; /*Access is temporarily blocked (too many failed login attempts). Please try again later or contact your administrator.'; */
$messages['no_identities']          = 'I dati richiesti per il recupero della password non sono disponibili. %s e configurali!';
$messages['user_not_found']         = 'Utente non trovato';
$messages['check_account_notice']   = 'Il codice di recupero password è già stato inviato. Controlla la casela di posta e il tuo cellulare'; /* The password recovery code has already been sent to you. Check your email and phone'; */
$messages['check_account']          = 'Il codice di recupero password ti è stato inviato ';
$messages['check_email']            = 'all'indirizzo email alternativo ';
$messages['check_sms']              = 'sul cellulare';
$messages['and']                    = 'e ';
$messages['sent_to_admin']          = 'Non ci sono le informazioni per il recupero della password. La tua richiesta è stata inviata all'amministratore.';  /*No password recovery information was found, so your request was sent to the administrator.'; */
$messages['send_failed']            = 'Errore nella spedizione del codice di recupero della passsword. Riprova più tardi.';
$messages['write_failed']           = 'Errore nella scrittura dei dati. Contatta l'amministratore del sistema.';
$messages['code_expired']           = 'Il codice di conferma è scaduto';
$messages['code_failed']            = 'Il codice di conferma non è valido';
$messages['answer_failed']          = 'Risposta errata alla domanda segreta';
$messages['password_invalid']       = 'Password non valida';
$messages['password_check_failed']  = 'Errore. La passowrd è troppo semplice!';
$messages['password_forbidden']     = 'La password contiene caratteri non validi';
$messages['password_changed']       = 'La password è stata cambiata!';
$messages['password_not_changed']   = 'An error occurred while changing your password. Please try again later or contact your administrator.';
$messages['crypt_error']            = 'Non posso salvare la nuova la password. Manca la funzione crittografica.';
$messages['connect_error']          = 'Non posso salvare la nuova la password. Errore di connessione.';
$messages['password_in_history']    = 'Questa password è già stata usata.';
$messages['password_const_viol']    = 'Violazione delle regole sulla password. La password è troppo semplice.';
$messages['phone_invalid']          = 'Numero telefonico non valido! Deve essere lungo almeno %d cifre';
$messages['altemail_invalid']       = 'L'indirizzo a-mail alternativo non è valido!';
$messages['altemail_match_primary'] = 'L'indirizzo a-mail alternativo non può essere uguale all'indirizzo e-mail principale!';

?>
