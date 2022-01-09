
if (window.rcmail) {
    rcmail.addEventListener('init', function(evt) {
        var loginform = $('#login-form');
        if (loginform) {
            loginform.append('<a class="home" id="password_forgot" href="javascript:forgot_password();">' + rcmail.gettext('forgot_password','password_recovery') + '</a>');
        }

        var newpasswordform = $('#new-password-form');
        if (newpasswordform && rcmail.env.pr_use_confirm_code) {
            newpasswordform.append('<a class="home" id="renew_confirm_code" href="javascript:renew_confirm_code();">' + rcmail.gettext('renew_code','password_recovery') + '</a>');
        }

        rcmail.register_command('plugin.password_recovery.cancel', function() {
            rcmail.http_request('plugin.password_recovery', { '_a':'cancel' });
        }, true);

        rcmail.register_command('plugin.password_recovery.reset', function() {
            var input_username = rcube_find_object('_username');
            if (input_username && input_username.value == '') {
                rcmail.alert_dialog(rcmail.get_label('no_username', 'password_recovery'), function() {
                    input_username.focus();
                    return true;
                });
            }
            else {
                rcmail.gui_objects.recoverypasswordform.submit();
            }
        }, true);

        rcmail.register_command('plugin.password_recovery.save', function() {
            var input_code = rcube_find_object('_code'),
                input_answer = rcube_find_object('_answer'),
                input_newpassword = rcube_find_object('_newpassword'),
                input_newpassword_confirm = rcube_find_object('_newpassword_confirm');

            if (rcmail.env.pr_use_confirm_code && input_code && input_code.value == '') {
                rcmail.alert_dialog(rcmail.get_label('no_code', 'password_recovery'), function() {
                    input_code.focus();
                    return true;
                });
            }
            else if (rcmail.env.pr_use_question && input_answer && input_answer.value == '') {
                rcmail.alert_dialog(rcmail.get_label('no_answer', 'password_recovery'), function() {
                    input_answer.focus();
                    return true;
                });
            }
            else if (input_newpassword && input_newpassword.value == '') {
                rcmail.alert_dialog(rcmail.get_label('no_password', 'password_recovery'), function() {
                    input_newpassword.focus();
                    return true;
                });
            }
            else if (input_newpassword_confirm && input_newpassword_confirm.value == '') {
                rcmail.alert_dialog(rcmail.get_label('no_password_confirm', 'password_recovery'), function() {
                    input_newpassword_confirm.focus();
                    return true;
                });
            }
            else if (input_newpassword && input_newpassword_confirm && input_newpassword.value != input_newpassword_confirm.value) {
                rcmail.alert_dialog(rcmail.get_label('password_inconsistency', 'password_recovery'), function() {
                    input_newpassword.focus();
                    return true;
                });
            }
            else if (input_newpassword && input_newpassword.value.length < rcmail.env.pr_password_minimum_length) {
                rcmail.alert_dialog(rcmail.get_label('password_too_short', 'password_recovery').replace('%d', minimum_length), function() {
                    input_newpassword.focus();
                    return true;
                });
            }
            else {
                rcmail.gui_objects.newpasswordform.submit();
            }
        }, true);

        $('input:not(:hidden)').first().focus();
    });
}

function forgot_password() {
    var url = "./?_task=login&_action=plugin.password_recovery";
/*    var input_user = rcube_find_object('_user');
    if (input_user && input_user.value != '') {
        url = url + "&_u=" + input_user.value;
    }*/
    document.location.href = url;
}

function renew_confirm_code() {
    rcmail.http_request('plugin.password_recovery', { '_a':'renew', '_username':rcmail.env.pr_username });
}

