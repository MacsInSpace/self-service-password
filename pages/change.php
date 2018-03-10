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

# This page is called to change password

#==============================================================================
# POST parameters
#==============================================================================
# Initiate vars
$result = "";
$login = "";
$confirmpassword = "";
$newpassword = "";
$oldpassword = "";
$ldap = "";
$userdn = "";
if (!isset($pwd_forbidden_chars)) { $pwd_forbidden_chars=""; }
$mail = "";
$toggleextra = "";
$hiddenarealogin = "";
$hiddenareapassword = "";

if (!empty($_POST["toggleextra"]) and $_POST["toggleextra"]) { $toggleextra = $_POST["toggleextra"]; }
if (isset($_POST["hiddenarealogin"]) and $_POST["hiddenarealogin"]) { $hiddenarealogin = $_POST["hiddenarealogin"]; }
if (isset($_POST["hiddenareapassword"]) and $_POST["hiddenareapassword"]) { $hiddenareapassword = $_POST["hiddenareapassword"]; }
if (!empty($_POST["toggleextra"]) and !isset($_POST["hiddenarealogin"])) { $result = "hiddenarealoginrequired"; }
if (!empty($_POST["toggleextra"]) and !isset($_POST["hiddenareapassword"])) { $result = "hiddenareapasswordrequired"; }

if (isset($_POST["confirmpassword"]) and $_POST["confirmpassword"]) { $confirmpassword = $_POST["confirmpassword"]; }
 else { $result = "confirmpasswordrequired"; }
if (isset($_POST["newpassword"]) and $_POST["newpassword"]) { $newpassword = $_POST["newpassword"]; }
 else { $result = "newpasswordrequired"; }
if (isset($_POST["oldpassword"]) and $_POST["oldpassword"]) { $oldpassword = $_POST["oldpassword"]; }
 else { $result = "oldpasswordrequired"; }
if (isset($_REQUEST["login"]) and $_REQUEST["login"]) { $login = $_REQUEST["login"]; }
 else { $result = "loginrequired"; }
if (! isset($_REQUEST["login"]) and ! isset($_POST["confirmpassword"]) and ! isset($_POST["newpassword"]) and ! isset($_POST["oldpassword"]))
 { $result = "emptychangeform"; }

# Check the entered username for characters that our installation doesn't support
if ( $result === "" ) {
    $result = check_username_validity($login,$login_forbidden_chars);
}
if ( $result === "" ) {
    $result = check_username_validity($hiddenarealogin,$login_forbidden_chars);
}
# Match new and confirm password
if ( $newpassword != $confirmpassword ) { $result="nomatch"; }

#==============================================================================
# Check reCAPTCHA
#==============================================================================
if ( $result === "" && $use_recaptcha ) {
    $result = check_recaptcha($recaptcha_privatekey, $recaptcha_request_method, $_POST['g-recaptcha-response'], $login);
}

#==============================================================================
# Check old password
#==============================================================================
if ( $result === "" ) {

    # Connect to LDAP
    $ldap = ldap_connect($ldap_url);
    ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
    if ( $ldap_starttls && !ldap_start_tls($ldap) ) {
        $result = "ldaperror";
        error_log("LDAP - Unable to use StartTLS");
    } else {

    # Bind
    if ( isset($ldap_binddn) && isset($ldap_bindpw) ) {
        $bind = ldap_bind($ldap, $ldap_binddn, $ldap_bindpw);
    } else {
        $bind = ldap_bind($ldap);
    }

    $errno = ldap_errno($ldap);
    if ( $errno ) {
        $result = "ldaperror";
        error_log("LDAP - Bind error $errno  (".ldap_error($ldap).")");
    } else {

    # Search for user
    $ldap_filter = str_replace("{login}", $login, $ldap_filter);
    $search = ldap_search($ldap, $ldap_base, $ldap_filter);

    $errno = ldap_errno($ldap);
    if ( $errno ) {
        $result = "ldaperror";
        error_log("LDAP - Search error $errno  (".ldap_error($ldap).")");
    } else {

    # Get user DN
    $entry = ldap_first_entry($ldap, $search);
    $userdn = ldap_get_dn($ldap, $entry);

    if( !$userdn ) {
        $result = "badcredentials";
        error_log("LDAP - User $login not found");
    } else {

    # Get user email for notification
    if ( $notify_on_change ) {
        $mailValues = ldap_get_values($ldap, $entry, $mail_attribute);
        if ( $mailValues["count"] > 0 ) {
            $mail = $mailValues[0];
        }
    }

    # Check objectClass to allow samba and shadow updates
    $ocValues = ldap_get_values($ldap, $entry, 'objectClass');
    if ( !in_array( 'sambaSamAccount', $ocValues ) and !in_array( 'sambaSAMAccount', $ocValues ) ) {
        $samba_mode = false;
    }
    if ( !in_array( 'shadowAccount', $ocValues ) ) {
        $shadow_options['update_shadowLastChange'] = false;
        $shadow_options['update_shadowExpire'] = false;
    }

    # Bind with old password
    $bind = ldap_bind($ldap, $userdn, $oldpassword);
    $errno = ldap_errno($ldap);
    if ( ($errno == 49) && $ad_mode ) {
        if ( ldap_get_option($ldap, 0x0032, $extended_error) ) {
            error_log("LDAP - Bind user extended_error $extended_error  (".ldap_error($ldap).")");
            $extended_error = explode(', ', $extended_error);
            if ( strpos($extended_error[2], '773') or strpos($extended_error[0], 'NT_STATUS_PASSWORD_MUST_CHANGE') ) {
                error_log("LDAP - Bind user password needs to be changed");
                $errno = 0;
            }
            if ( ( strpos($extended_error[2], '532') or strpos($extended_error[0], 'NT_STATUS_ACCOUNT_EXPIRED') ) and $ad_options['change_expired_password'] ) {
                error_log("LDAP - Bind user password is expired");
                $errno = 0;
            }
            unset($extended_error);
        }
    }
    if ( $errno ) {
        $result = "badcredentials";
        error_log("LDAP - Bind user error $errno  (".ldap_error($ldap).")");
    } else {

    # Rebind as Manager if needed
    if ( $who_change_password == "manager" ) {
        $bind = ldap_bind($ldap, $ldap_binddn, $ldap_bindpw);
    }

    }}}}}

}

#==============================================================================
# Check password strength
#==============================================================================
if ( $result === "true" ) {
    $result = check_password_strength( $newpassword, $oldpassword, $pwd_policy_config, $login );
}
#==============================================================================
# Change eduMail password
#==============================================================================
if ( !empty($toggleextra)) {

	if ( $result === "" ) {
			#echo "changing det pw";
			$result = change_det_pw($hiddenarealogin, $hiddenareapassword, $newpassword, $confirmpassword);
			if ( $result == "Success" ) {
				$result = "";
			}	else {
			$result = "DET error: $result";
			}
	}
}
#==============================================================================
# Change password
#==============================================================================
if ( $result === "" ) {
    $result = change_password($ldap, $userdn, $newpassword, $ad_mode, $ad_options, $samba_mode, $samba_options, $shadow_options, $hash, $hash_options, $who_change_password, $oldpassword);
    if ( $result === "passwordchanged" && isset($posthook) ) {
        $command = escapeshellcmd($posthook).' '.escapeshellarg($login).' '.escapeshellarg($newpassword).' '.escapeshellarg($oldpassword);
        exec($command);
   	  }
	}
#==============================================================================
# HTML
#==============================================================================
?>

<div class="result alert alert-<?php echo get_criticity($result) ?>">
<p><i class="fa fa-fw <?php echo get_fa_class($result) ?>" aria-hidden="true"></i> <?php echo $messages[$result]; ?></p>
</div>

<?php if ( $result !== "passwordchanged" ) { ?>

<?php
if ( $show_help ) {
    echo "<div class=\"help alert alert-warning\"><p>";
    echo "<i class=\"fa fa-fw fa-info-circle\"></i> ";
    echo $messages["changehelp"];
    echo "</p>";
    if (isset($messages['changehelpextramessage'])) {
        echo "<p>" . $messages['changehelpextramessage'] . "</p>";
    }
    if ( !$show_menu and ( $use_questions or $use_tokens or $use_sms or $change_sshkey ) ) {
        echo "<p>".  $messages["changehelpreset"] . "</p>";
        echo "<ul>";
        if ( $use_questions ) {
            echo "<li>" . $messages["changehelpquestions"] ."</li>";
        }
        if ( $use_tokens ) {
            echo "<li>" . $messages["changehelptoken"] ."</li>";
        }
        if ( $use_sms ) {
            echo "<li>" . $messages["changehelpsms"] ."</li>";
        }
        if ( $change_sshkey ) {
            echo "<li>" . $messages["changehelpsshkey"] . "</li>";
        }
        echo "</ul>";
    }
    echo "</div>\n";
}
?>

<?php
if ($pwd_show_policy_pos === 'above') {
    show_policy($messages, $pwd_policy_config, $result);
}
?>

<div class="alert alert-info">
<form action="#" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="login" class="col-sm-4 control-label"><?php echo "Sync eduMail Password<BR><font size='1'>(eduMail Users Only)</font>"; ?></label>
        <div class="col-sm-8">
            <div class="input-group">
			<?php echo "<BR>"; ?>
            <!-- Here's the tick box -->
            <input type="checkbox" name="toggleextra" id="toggleextra">
            <label for="toggleextra"></label>
            <!-- end tick box -->     
           </div>
        </div>
    </div>
    <div class="form-group">
        <label for="login" class="col-sm-4 control-label"><?php echo "Local Nework Username"; ?></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                <input type="text" name="login" autocomplete="username" id="login" value="<?php echo htmlentities($login) ?>" class="form-control" placeholder="<?php echo "012345678 or ABC0001"; ?>" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="oldpassword" class="col-sm-4 control-label"><?php echo "Local Nework Password"; ?></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                <input type="password" name="oldpassword" autocomplete="current-password" id="oldpassword" class="form-control" placeholder="<?php echo $messages["oldpassword"]; ?>" />
            </div>
        </div>
    </div>
    <?php echo "<BR>"; ?>
    <!-- Added an id for the hidden field row here and set it to not display -->
    <div class="form-group2" id="hidden-area" style="display: none;">
    <div class="form-group">
        <label for="hiddenarealogin" class="col-sm-4 control-label"><?php echo "eduMail Username"; ?></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                <input type="text" name="hiddenarealogin" autocomplete="username" id="hiddenarealogin" class="form-control" placeholder="<?php echo "012345678"; ?>" />
            </div>
        </div>
     </div>
    <div class="form-group">
        <label for="hiddenareapassword" class="col-sm-4 control-label"><?php echo "eduMail Password"; ?></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                <input type="password" name="hiddenareapassword" autocomplete="current-password" id="hiddenareapassword" class="form-control" placeholder="<?php echo "Old eduMail Password"; ?>" />
            </div>
        </div>
     </div>
                 <?php echo "<BR>"; ?>

     </div>
    <!-- end new hidden field -->
    
    <div class="form-group">
        <label for="newpassword" class="col-sm-4 control-label"><?php echo $messages["newpassword"]; ?></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                <input type="password" name="newpassword" id="newpassword" autocomplete="new-password" class="form-control" placeholder="<?php echo $messages["newpassword"]; ?>" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="confirmpassword" class="col-sm-4 control-label"><?php echo $messages["confirmpassword"]; ?></label>
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                <input type="password" name="confirmpassword" id="confirmpassword" autocomplete="new-password" class="form-control" placeholder="<?php echo $messages["confirmpassword"]; ?>" />
            </div>
        </div>
    </div>
<?php if ($use_recaptcha) { ?>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_publickey; ?>" data-theme="<?php echo $recaptcha_theme; ?>" data-type="<?php echo $recaptcha_type; ?>" data-size="<?php echo $recaptcha_size; ?>"></div>
            <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>"></script>
        </div>
    </div>
<?php } ?>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-fw fa-check-square-o"></i> <?php echo $messages['submit']; ?>
            </button>       
        </div>
    </div>
</form>
</div>
<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        // Menu links popovers
        $('[data-toggle="menu-popover"]').popover({
            trigger: 'hover',
            placement: 'bottom',
            container: 'body' // Allows the popover to be larger than the menu button
        });
    });
    
    // Set an on click event handler for the tick box to toggle the field on and off
    $('#toggleextra').on('click', function() { 
   		$('#hidden-area').slideToggle() 
    });
</script>
       <!-- mirror usernames fields. Mirror passwords fields -->
 <script type="text/javascript">
 $("#login").bind('input', function () {
   var stt = $(this).val();
   $("#hiddenarealogin").val(stt);
});
 $("#oldpassword").bind('input', function () {
   var stt = $(this).val();
   $("#hiddenareapassword").val(stt);
});
</script>
<script>
	    // let the browser natively reset default
			$(window).bind("pageshow", function() {
    		var form = $('form'); 
    		form[0].reset();
			});
			
</script>
<?php
if ($pwd_show_policy_pos === 'below') {
    show_policy($messages, $pwd_policy_config, $result);
}
?>

<?php } else {

    # Notify password change
    if ($mail and $notify_on_change) {
        $data = array( "login" => $login, "mail" => $mail, "password" => $newpassword);
        if ( !send_mail($mailer, $mail, $mail_from, $mail_from_name, $messages["changesubject"], $messages["changemessage"].$mail_signature, $data) ) {
            error_log("Error while sending change email to $mail (user $login)");
        }
    }

    if (isset($messages['passwordchangedextramessage'])) {
        echo "<div class=\"result alert alert-" . get_criticity($result) . "\">";
        echo "<p><i class=\"fa fa-fw " . get_fa_class($result) . "\" aria-hidden=\"true\"></i> " . $messages['passwordchangedextramessage'] . "</p>";
        echo "</div>\n";
    }

}
?>
