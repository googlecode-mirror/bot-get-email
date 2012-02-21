<?php
/**
* Auto Get Email
* Yplitgroup
* 20/2/2012
*/

// Check install
function yplitgroup_check_install()
{
	global $C;
	if( !isset( $C->db ) ) return false;
	return true;
}

// Redirect install
function yplitgroup_redirect_install()
{
	@header('Location: ./install.php');
	die();
}

// ### INSTALL ###
// View form for install
function yplitgroup_view_form_install($POST, $error)
{
	$mt[0] = htmlspecialchars('->');
	$mt[1] = htmlspecialchars('[\'');
	$mt[2] = htmlspecialchars('\']');
	$error = isset($error)?$error:'';
	echo <<<Y
<html>
	<head>
		<title>AUTO GET EMAIL - INSTALL - Power by Yplitgroup</title>
	</head>
	<style>
		.error{font-weight:bold; color: red;}
	</style>
	<body>
		<h1>Install</h1>
		<form action="" method="POST">
			<table border=0>
				<tr>
					<td>
						<span class="error">{$error}</span>
					</td>
				</tr>
				<tr>
					<td width="40%">
						<b>\$C{$mt[0]}database{$mt[1]}dbhost{$mt[2]}</b>
					</td>
					<td>
						<input type='text' maxlength=100 name='dbhost' value="{$POST[dbhost]}">
					</td>
				<tr>
				<tr>
					<td width="40%">
						<b>\$C{$mt[0]}database{$mt[1]}dbname{$mt[2]}</b>
					</td>
					<td>
						<input type='text' maxlength=100 name='dbname' value="{$POST[dbname]}">
					</td>
				<tr>
				<tr>
					<td width="40%">
						<b>\$C{$mt[0]}database{$mt[1]}dbuname{$mt[2]}</b>
					</td>
					<td>
						<input type='text' maxlength=100 name='dbuname' value="{$POST[dbuname]}">
					</td>
				<tr>
				<tr>
					<td width="40%">
						<b>\$C{$mt[0]}database{$mt[1]}dbpass{$mt[2]}</b>
					</td>
					<td>
						<input type='text' maxlength=100 name='dbpass' value="{$POST[dbpass]}">
					</td>
				<tr>
				<tr>
					<td>
						
					</td>
					<td>
						<input type='submit' name='submit'>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>
Y;

}