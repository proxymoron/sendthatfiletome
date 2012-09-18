<?
show_header('Recover Password','validate');
echo $Validate->GenerateJS('recoverform');
?>
<script src="<?=STATIC_SERVER?>functions/jquery.js" type="text/javascript"></script>
<script src="<?=STATIC_SERVER?>functions/password_validate.js" type="text/javascript"></script>
<form name="recoverform" id="recoverform" method="post" action="" onsubmit="return formVal();">
	<input type="hidden" name="key" value="<?=display_str($_REQUEST['key'])?>" />
	<div style="width:500px;">
		<span class="titletext">Reset your password - Final Step</span><br /><br />
<?
if(empty($Reset)) {
	if(!empty($Err)) {
?>
		<strong class="important_text"><?=display_str($Err)?></strong><br /><br />
<?	} ?> A strong password is between 8 and 40 characters long, contains at least 1 lowercase and uppercase letter, and contains at least a number or symbol.<br /><br />
		<table class="layout" cellpadding="2" cellspacing="1" border="0" align="center" width="100%">
			<tr valign="top">
				<td align="right" style="width:100px;">Password&nbsp;</td>
				<td align="left"><input type="password" name="password" id="new_pass_1" class="inputtext" /> <b id="pass_strength"/></td>
			</tr>
			<tr valign="top">
				<td align="right">Confirm Password&nbsp;</td>
				<td align="left"><input type="password" name="verifypassword" id="new_pass_2" class="inputtext" /> <b id="pass_match"/></td>
			</tr>
			<tr>
				<td colspan="2" align="right"><input type="submit" name="reset" value="Reset!" class="submit" /></td>
			</tr>
		</table>
<? } else { ?>
		Your password has been succesfully reset.<br />
		Please <a href="login.php">click here</a> to log in using your new password.
<? } ?>
	</div>
</form>
<?
show_footer();
?>
