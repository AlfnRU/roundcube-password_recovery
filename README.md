#### Password Recovery Plugin for Roundcube

Plugin that adds functionality so that a user can 
create a new password if the original is lost.

To restore the password, the user is asked a secret question, 
and/or a confirmation code is sent to an additional email address 
and SMS to the phone.

It is recommended that you use the "SMSTools" package to send SMS.

When checking and saving a new password, 
the password is encrypted using the MD5-Crypt method. 
The password is written directly to the Postfix database (mailbox table).

The Password plugin can also be used when configured accordingly.
