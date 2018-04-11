<?php
#==============================================================================
# LTB Self Service Password
#
# Copyright (C) 2009 Clement OUDOT
# Copyright (C) 2009 LTB-project.org
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# GPL License: http://www.gnu.org/licenses/gpl.txt
#
#==============================================================================

#==============================================================================
# English
#==============================================================================
$messages['phpupgraderequired'] = "PHP upgrade required";
$messages['nophpldap'] = "You should install PHP LDAP to use this tool";
$messages['nophpmhash'] = "You should install PHP mhash to use Samba mode";
$messages['nophpcurl'] = "You should install PHP curl module to change eduMail passwords.";
$messages['nokeyphrase'] = "Token encryption requires a random string in keyphrase setting";
$messages['ldaperror'] = "Cannot access Local Network directory";
$messages['loginrequired'] = "Your Local Network login is required";
$messages['oldpasswordrequired'] = "Your old Local Network password is required";
$messages['newpasswordrequired'] = "Your new password is required";
$messages['confirmpasswordrequired'] = "Please confirm your new password";
$messages['passwordchanged'] = "Your password was changed";
$messages['sshkeychanged'] = "Your SSH Key was changed";
$messages['nomatch'] = "Passwords mismatch: Your new passwords do not match.";
$messages['badcredentials'] = "Local Network login or password incorrect";
$messages['passworderror'] = "Password was refused by the Local Network directory";
$messages['sshkeyerror'] = "SSH Key was refused by the LDAP directory";
$messages['title'] = "Self service password";
$messages['login'] = "Login";
$messages['oldpassword'] = "Old password";
$messages['newpassword'] = "New password";
$messages['confirmpassword'] = "Confirm";
$messages['submit'] = "Send";
$messages['getuser'] = "Get user";
$messages['tooshort'] = "Your password is too short";
$messages['toobig'] = "Your password is too long";
$messages['minlower'] = "Your password does not have enough lowercase characters";
$messages['minupper'] = "Your password does not have enough uppercase characters";
$messages['mindigit'] = "Your password does not have enough digits";
$messages['minspecial'] = "Your password does not have enough special characters";
$messages['sameasold'] = "Your new password is identical to your old password";
$messages['policy'] = "Your password must conform to the following constraints:";
$messages['policyminlength'] = "Minimum length:";
$messages['policymaxlength'] = "Maximum length:";
$messages['policyminlower'] = "Minimum number of lowercase characters:";
$messages['policyminupper'] = "Minimum number of uppercase characters:";
$messages['policymindigit'] = "Minimum number of digits:";
$messages['policyminspecial'] = "Minimum number of special characters:";
$messages['forbiddenchars'] = "You password contains forbidden characters";
$messages['policyforbiddenchars'] = "Forbidden characters:";
$messages['policynoreuse'] = "Your new password may not be the same as your old password";
$messages['questions']['birthday'] = "When is your birthday?";
$messages['questions']['color'] = "What is your favorite color?";
$messages['password'] = "Password";
$messages['question'] = "Question";
$messages['answer'] = "Answer";
$messages['setquestionshelp'] = "Initialize or change your password reset question/answer. You will then be able to reset your password <a href=\"?action=resetbyquestions\">here</a>.";
$messages['answerrequired'] = "No answer given";
$messages['questionrequired'] = "No question selected";
$messages['passwordrequired'] = "Your password is required";
$messages['sshkeyrequired'] = "SSH Key is required";
$messages['answermoderror'] = "Your answer has not been registered";
$messages['answerchanged'] = "Your answer has been registered";
$messages['answernomatch'] = "Your answer is incorrect";
$messages['resetbyquestionshelp'] = "Choose a question and answer it to reset your password. This requires that you have already <a href=\"?action=setquestions\">register an answer</a>.";
$messages['changehelp'] = "Enter your old password and choose a new one.";
$messages['changehelpreset'] = "Forgot your password?";
$messages['changehelpquestions'] = "<a href=\"?action=resetbyquestions\">Reset your password by answering questions</a>";
$messages['changehelptoken'] = "<a href=\"?action=sendtoken\">Email a password reset link</a>";
$messages['changehelpsms'] = "<a href=\"?action=sendsms\">Reset your password with a SMS</a>";
$messages['changehelpsshkey'] = "<a href=\"?action=changesshkey\">Change your SSH Key</a>";
$messages['changesshkeyhelp'] = "Enter your password and new SSH key.";
$messages['resetmessage'] = "Hello {login},\n\nClick here to reset your password:\n{url}\n\nIf you didn't request a password reset, please ignore this email.";
$messages['resetsubject'] = "Reset your password";
$messages['sendtokenhelp'] = "Enter your user name and your email address to reset your password. When you receive the email, click the link inside to complete the password reset.";
$messages['sendtokenhelpnomail'] = "Enter your user name to reset your password. An email will be sent to the address associated with the supplied user name. When you receive this email, click the link inside to complete the password reset.";
$messages['mail'] = "Mail";
$messages['mailrequired'] = "Your email address is required";
$messages['mailnomatch'] = "The email address does not match the submitted user name";
$messages['tokensent'] = "A confirmation email has been sent";
$messages['tokennotsent'] = "Error when sending confirmation email";
$messages['tokenrequired'] = "Token is required";
$messages['tokennotvalid'] = "Token is not valid";
$messages['resetbytokenhelp'] = "The link sent by email allows you to reset your password. To request a new link via email, <a href=\"?action=sendtoken\">click here</a>.";
$messages['resetbysmshelp'] = "The token sent by sms allows you to reset your password. To get a new token, <a href=\"?action=sendsms\">click here</a>.";
$messages['changemessage'] = "Hello {login},\n\nYour password has been changed.\n\nIf you didn't request a password reset, please contact your administrator immediately.";
$messages['changesubject'] = "Your password has been changed";
$messages['changesshkeymessage'] = "Hello {login},\n\nYour SSH Key has been changed.\n\nIf you didn't initiate this change, please contact your administrator immediately.";
$messages['changesshkeysubject'] = "Your SSH Key has been changed";
$messages['badcaptcha'] = "The reCAPTCHA was not entered correctly. Try again.";
$messages['notcomplex'] = "Your password does not have enough different classes of characters";
$messages['policycomplex'] = "Minimum number of different classes of characters:";
$messages['sms'] = "SMS number";
$messages['smsresetmessage'] = "Your password reset token is:";
$messages['sendsmshelp'] = "Enter your login to get password reset token. Then type token in sent SMS.";
$messages['smssent'] = "A confirmation code has been send by SMS";
$messages['smsnotsent'] = "Error when sending SMS";
$messages['smsnonumber'] = "Can't find mobile number";
$messages['userfullname'] = "User full name";
$messages['username'] = "Username";
$messages['smscrypttokensrequired'] = "You can't use reset by SMS without crypt_tokens setting";
$messages['smsuserfound'] = "Check that user information are correct and press Send to get SMS token";
$messages['smstoken'] = "SMS token";
$messages['sshkey'] = "SSH Key";
$messages['nophpmbstring'] = "You should install PHP mbstring";
$messages['menuquestions'] = "Question";
$messages['menutoken'] = "Email";
$messages['menusms'] = "SMS";
$messages['menusshkey'] = "SSH Key";
$messages['nophpxml'] = "You should install PHP XML to use this tool";
$messages['tokenattempts'] = "Invalid token, try again";
$messages['emptychangeform'] = "Change your password";
$messages['emptysshkeychangeform'] = "Change your SSH Key";
$messages['emptysendtokenform'] = "Email a password reset link";
$messages['emptyresetbyquestionsform'] = "Reset your password";
$messages['emptysetquestionsform'] = "Set your password reset questions";
$messages['emptysendsmsform'] = "Get a reset code";
$messages['sameaslogin'] = "Your new password is identical to your login";
$messages['policydifflogin'] = "Your new password may not be the same as your login";
#DET errors
$messages['edumailservererror'] = "DET error: Cannot reach eduMail server. Try again later.";
$messages['hiddenarealoginrequired'] = "DET error: Must specify your eduMail UserID.";
$messages['hiddenareapasswordrequired'] = "DET error: Must specify a new eduMail password.";
$messages['Success'] = "DET: eduMail change Password Success";
$messages['DET error: Your eduMail UserID and eduMail Password are incorrect.'] = "DET error: Your eduMail UserID and eduMail Password are incorrect.";
$messages['DET error: Must specify your eduMail UserID.'] = "DET error: Must specify your eduMail UserID.";
$messages['DET error: Must specify a new password.'] = "DET error: Must specify a new eduMail password.";
$messages['DET error: The passwords you typed do not match.'] = "DET error: The new passwords you typed do not match.";
$messages['DET error: This password is not valid. Passwords must contain a minimum of seven characters and maximum of 32 characters.'] = "DET error: This password is not valid. eduMail passwords must contain a minimum of seven characters and maximum of 32 characters.";
$messages['DET error: New Password must be different from your last eight passwords.<br />And your password cannot be changed more than once in 24 hours.'] = "DET error: New eduMail Password must be different from your last eight passwords.<br>And your password cannot be changed more than once in 24 hours.";
$messages['DET error: New Password must contain at least one character from at least three of the following sets:<br /> lowercase (a-z), uppercase (A-Z), numerals (0-9) or special characters (~`#$*@^)'] = "DET error: New Password must contain at least one character from at least three of the following sets: lowercase (a-z), uppercase (A-Z), numerals (0-9) or special characters (~`#$*@^)";
$messages['DET error: Your eduMail account is currently locked. This may be because of too many bad password attempts.<br />Please visit <a href=\"ResetPassword.aspx\">Reset my forgotten password.</a>'] = "DET error: Your eduMail account is currently locked. This may be because of too many bad password attempts.<br />Please visit <a href=\"https://www.eduweb.vic.gov.au/password/ResetPassword.aspx\">Reset my forgotten password.</a>";
