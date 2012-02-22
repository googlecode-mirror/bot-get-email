<?php
/**
* Auto Get Email
* Yplitgroup
* 20/2/2012
*/

// Cront job
function yplitgroup_crontjob()
{
	global $C;
	if( !yplitgroup_check_time( ) ) return false;
	
	// Start work
	$q = "SELECT `id`,`url` FROM `yplitgroup_global_url` WHERE `active` = 1 LIMIT " . $C->constant->link_get_limit;
	$C->db->sql_query( $q );
	$result = array();
	while( $re = $C->db->sql_fetch_assoc() )
	{
		$t1 = time();
		$result['Id'] = $re['id'];
		$result['Url'] = $re['url'];
		yplitgroup_get_email( $re['url'] ); 
		yplitgroup_bot_get_url( $re['url'] ); 
		yplitgroup_disable_url( $re['id'] );
		$result['Time'] = time()-$t1;
		yplitgroup_update_time( );
	}
	echo "<hr>";
		echo "[ $result[Id] ] Url: $result[Url] ($result[Time])";
}

// BOT: Disable url
function yplitgroup_disable_url( $id )
{
	global $C;
	$id = (int) $id;
	$q = "UPDATE `yplitgroup_global_url` SET `active`=0 WHERE `id` = $id ";
	return $C->db->sql_query( $q );
}

// BOT: Auto get url
function yplitgroup_bot_get_url( $url )
{
	global $C; 
	if( empty( $url ) ) return false;
	// Get all url
	$html = file_get_html( $url ); 
	$this_host = parse_url( $url );
	$this_host = $this_host['scheme'] . '://' . $this_host['host'] . '/';
	foreach( $html->find('a') as $a )
	{
		if( !empty( $a->href ) and !preg_match('/^#/', $a->href) and !preg_match('/^javascript:/', $a->href) )
		{
			if( !preg_match( '/^http:\/\//is', $a->href ) )
			{
				$a->href = $this_host . pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME ) . $a->href;
			}
			$q = "SELECT 1 FROM `yplitgroup_global_url` WHERE `url` = " . $C->db->dbescape_string( $a->href );
			$C->db->sql_query( $q );
			if( $C->db->sql_numrows() == 0 ) // Check 1
			{
				$real_url = parse_url( $a->href );
				$real_url = $real_url['scheme'] . '://' . $real_url['host'] . ( isset( $real_url['path'] ) ? $real_url['path'] : '' ) . ( isset( $real_url['query'] ) ? '?'.$real_url['query'] : '' );
				$q = "SELECT 1 FROM `yplitgroup_global_url` WHERE `url` = " . $C->db->dbescape_string( $real_url );
				$C->db->sql_query( $q );
				if( $C->db->sql_numrows() == 0 ) // Check 2
				{
					echo $q = "INSERT INTO `yplitgroup_global_url`(`url`, `active`) VALUE( " . $C->db->dbescape_string( $a->href ) . ", 1 ) ";
					$C->db->sql_query( $q );
				}
			}
		}
	}
}

// BOT: Auto get email
function yplitgroup_get_email( $url )
{
	global $C;
	if( empty( $url ) ) return false;
	$html = file_get_html( $url );
	$text = $html->plaintext;
	$email_preg = '/(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))/'; // Power by Nukeviet :)
	preg_match_all( $email_preg, $text, $email );
	$email = $email[0];
	foreach( $email as $_email )
	{ //die($_email);
		$q = "SELECT 1 FROM `yplitgroup_email` WHERE `email` = " . $C->db->dbescape_string( $_email );
		$C->db->sql_query( $q );
		if( $C->db->sql_numrows() == 0 ) // Chua ton tai url nay
		{
			$q = "INSERT INTO `yplitgroup_email`(`email`) VALUE( " . $C->db->dbescape_string( $_email ) . ") ";
			$C->db->sql_query( $q );
		}
	}
}

// Check time (cront job)
function yplitgroup_check_time()
{
	global $C;
	$f = $C->constant->last_run_file;
	$fp = fopen($f, 'r');
	$lastrun = (int)@fread( $fp, filesize( $f ) );
	if( ( time() - $lastrun ) < $C->constant->time_autorun ) return false;
	return true;
}


// Update auto run time
function yplitgroup_update_time()
{
	global $C;
	$f = $C->constant->last_run_file;
	$fp = fopen($f, "w");
	fwrite( $fp, time() );
	fclose( $fp );
	return true;
}

// View main page
function yplitgroup_view_main()
{
	echo "<h3>Auto BOT</h3><br />";
	echo "
<ul>
	<li>
		<a href='index.php?action=help'>index.php?action=help</a>: View help page
	</li>
	<li>
		<a href='index.php?action=list'>index.php?action=list</a>: View list email
		<ul>
			<li>
				<a href='index.php?action=list'>index.php?action=list&viewtype=file</a>: Download file txt list email 
			</li>
			<li>
				<a href='index.php?action=list'>index.php?action=list&viewtype=textarea</a>: View textarea
			</li>
			<li>
				<a href='index.php?action=list'>index.php?action=list&viewtype=emailsent</a>: View textarea like email Senter (to copy)
			</li>
		</ul>
	</li>
</ul>
";
}

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
	$f = empty( $file ) ?  ( $C->constant->list_file_default ) : $file;
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