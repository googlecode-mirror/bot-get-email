<?php
/**
* Auto Get Email
* Yplitgroup
* 20/2/2012
*/

// View list email as sent page
function yplitgroup_view_emailsent()
{
	global $C;
	$q = "SELECT `email` FROM `yplitgroup_email`";
	$C->db->sql_query( $q );
	echo "<h3>Auto BOT Result</h3><br />";
	echo "<textarea style='width:100%; height:98%; color:#15c; padding:10px;'>";
	while( $re = $C->db->sql_fetch_assoc() )
	{
		echo $re['email'] . ", ";
	}
	echo "</textarea>";
	return;
}

// View list email as textarea
function yplitgroup_view_textarea()
{
	global $C;
	$q = "SELECT `email` FROM `yplitgroup_email`";
	$C->db->sql_query( $q );
	echo "<h3>Auto BOT Result</h3><br />";
	echo "<textarea style='width:100%; height:98%; color:#15c; padding:10px;'>";
	while( $re = $C->db->sql_fetch_assoc() )
	{
		echo $re['email'] . "\n";
	}
	echo "</textarea>";
	return;
}

// View list email as file
function yplitgroup_view_file( $file, $autoDownload = true, $limit=0 )
{
	global $C;
	if( $limit !=0 )
	{
		$q = "SELECT `email` FROM `yplitgroup_email` LIMIT 0, $limit";
	}
	else
	{
		$q = "SELECT `email` FROM `yplitgroup_email`";
	}
	$C->db->sql_query( $q );
	$f = empty( $file ) ?  ( time() . '.txt' ) : $file;
	$fp = @fopen( $f, 'w' );
	while( $re = $C->db->sql_fetch_assoc() )
	{
		@fwrite( $fp, $re['email'] . "\n" );
	}
	@fclose( $fp );
	if( $autoDownload )
	{
		$fp = @fopen( $f, 'r' );
		header('Content-disposition: attachment; filename="'.$f.'"');
		@header("Content-type: application/octet-stream");
		@header("Content-length: " . @filesize($f)); 
		@fpassthru($fp);
		@fclose($fp);
	}
	else
	{
		return $f;
	}
}

// View help page
function yplitgroup_view_help()
{
	echo "
<html>
<head>
	<title> ### HELP BOT ### </title>
</head>
<body>
	<h1>
		AUTO BOT SYSTEM
	</h1>
	<br />
	<ul>
		<li>
			<a href='index.php?action=help'>index.php?action=help</a>: View help page
		</li>
		<li>
			<a href='index.php?action=list'>index.php?action=list</a>: View list 
		</li>
	</ul>
</body>
</html>
";
}

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