<?php

$labels['forgot_password']          = 'Mot de passe perdu ?';
$labels['recovery_password']        = 'Récuperer son mot de passe';
$labels['phone']                    = 'Téléphone';
$labels['altemail']                 = 'Adresse mail secondaire';
$labels['newpassword']              = 'Nouveau mot de passe';
$labels['newpassword_confirm']      = 'Confirmation';
$labels['code']                     = 'Code de confirmation';
$labels['question']                 = 'Question secrète';
$labels['answer']                   = 'Réponse à la question secrète';
$labels['answer_confirm']           = 'Confirmation';
$labels['recovery']                 = 'Retrouver';
$labels['save']                     = 'Enregistrer';
$labels['cancel']                   = 'Annuler';
$labels['email_subject']            = "Récuperer l'accès aux emails";
$labels['email_subject_admin']      = 'Demande de récuperation de mot de passe';
$labels['click_here']               = 'Cliquer ici';
$labels['no_identities']            = "Compléter les informations pour la récupération des mots de passes.";
$labels['no_username']              = "Saisir votre nom d'utilisateur (addresse email)";
$labels['renew_code']               = 'Envoyer un nouveau code de confirmation';
$labels['count_send_code_maximum']  = 'Nombre maximal de confirmations dépassé';
$labels['no_code']                  = 'Saisir votre code de confirmation';
$labels['no_answer']                = 'Saisir votre réponse secrète';
$labels['no_password']              = 'Saisir un nouveau mot de passe';
$labels['no_password_confirm']      = 'Confirmer le mot de passe';
$labels['password_inconsistency']   = 'Le mot de passe et sa confirmation ne correspondent pas';
$labels['password_too_short']       = 'Le mot de passe doit comporter au moins %d caractères';

$messages['disabled']               = "Le système est actuellement en maintenance. La récuperation de mots de passes n'est sans doute pas possible. Tout reviendra à la normale. Nous nous excusons pour les désagréments";
$messages['banned']                 = "Accès temporairement désactivé (trop de tentatives infructueuses). Merci d'essayer plus tard ou de contacter votre administrateur.";
$messages['no_identities']          = 'Les paramètres nécessaires à la récuperation de mots de passes ne sont pas configurés. %s et configurer!';
$messages['user_not_found']         = 'Utilisateur inexistant';
$messages['check_account_notice']   = 'Le code de récuperation vous a déjà été envoyé. Merci de vérifier vos messages';
$messages['check_account']          = 'Code de récuperation envoyé ';
$messages['check_email']            = 'à votre adresse secondaire ';
$messages['check_sms']              = 'sur votre téléphone';
$messages['and']                    = 'et ';
$messages['sent_to_admin']          = "Aucune information de récuperation trouvée, votre demande a été transféré à l'administrateur.";
$messages['send_failed']            = "Erreur d'envoi du code de restauration. Veuillez essayer plus tard.";
$messages['write_failed']           = "Erreur d'écriture des données. Contacter votre administrateur.";
$messages['code_expired']           = 'Votre code de confirmation est expiré';
$messages['code_failed']            = 'Code de confirmation invalide';
$messages['answer_failed']          = 'Réponse invalide';
$messages['password_invalid']       = 'Mot de passe invalide';
$messages['password_check_failed']  = 'Erreur. Mot de passe trop simple!';
$messages['password_forbidden']     = 'Le mot de passe contient des caractères invalides';
$messages['password_changed']       = 'Changement de mot de passe réussit !';
$messages['password_not_changed']   = "Une erreur est survenue durant le changement de mot de passe. Merci d'éssayer plus tard ou de contacter votre administrateur.";
$messages['crypt_error']            = 'Je ne peux pas enregistrer le nouveau mot de passe. Fonction de chiffrement absente.';
$messages['connect_error']          = 'Je ne peux pas enregistrer le nouveau mot de passe. Erreur de connexion';
$messages['password_in_history']    = 'Ce mot de passe à déjà été utilisé.';
$messages['password_const_viol']    = 'Le mot de passe est trop faible.';
$messages['phone_invalid']          = 'Mauvais numéro de téléphone! Il doit comporter %d chiffres';
$messages['altemail_invalid']       = "L'email secondaire est incorrect";
$messages['altemail_match_primary'] = "L'email secondaire ne peut pas être similaire au principal";

?>
