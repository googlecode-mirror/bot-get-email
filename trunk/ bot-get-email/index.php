<?php
/**
* Auto Get Email
* Yplitgroup
* 20/2/2012
*/
/**
* Auto Get Email Tool
* This Tools run in PHP. This will get all email
* from a site input. They also get all URL from 
* this site, and save it to database to get 
* later. That will increase email result too.
* Email will print in result page. You can set 
* crontjob for this tool.
* 
* If you want donate for me. Please email 
* duyet2000@gmail.com
* Website: http://lemon9x.com
*/
// ### C Config ###
$C = new stdClass;
$C->database = array();
$C->action = '';
$C->is_crontjob = false;

session_start();

// ### ROOT DIR ###
define( 'DIR', pathinfo( str_replace( DIRECTORY_SEPARATOR, '/', __file__ ), PATHINFO_DIRNAME ) );

// ### INCLUDE ###
require_once( DIR . '/include/database.class.php');
require_once( DIR . '/include/functions.php');
require_once( DIR . '/include/simple_html_dom.php');
require_once( DIR . '/config.php');
require_once( DIR . '/bot.php');

// ### DATABASE ###
$C->db = new sql_db( $C->database );

// ### CHECK INSTALL ###
if( !yplitgroup_check_install() )
{
	yplitgroup_redirect_install();
}

// ### CHECK SQL ###
if( count( $C->db->error ) )
{
	die( "SQL ERROR!!!<hr>" . $C->db->error['user_message']);
}

// ### GET QUERY THOUGHT GET METHOD ###
$C->action = ( !empty( $_GET['action'] ) ) ? $_GET['action'] : 'none';

// ### CHECK AUTO RUN ###
if( isset( $_GET['auto'] ) )
{
	$C->is_crontjob = true;
}

// ### DO REQUEST ###
	// ### HELP CONSOLE ###
		if( $C->action == 'help' OR $C->action == 'h' )
		{
			yplitgroup_view_help();
			die();
		}
	
	// ### VIEW LIST EMAIL ###
		if( $C->action == 'list' OR $C->action == 'l' )
		{
			if( isset( $_GET['viewtype'] ) and !empty( $_GET['viewtype'] ) and $_GET['viewtype'] == 'file' )
			{
				yplitgroup_view_file('email_List.txt', true, 0); 
				die();
			}
			elseif( isset( $_GET['viewtype'] ) and !empty( $_GET['viewtype'] ) and $_GET['viewtype'] == 'textarea' )
			{
				yplitgroup_view_textarea(); 
				die();
			}
			elseif( isset( $_GET['viewtype'] ) and !empty( $_GET['viewtype'] ) and $_GET['viewtype'] == 'emailsent' )
			{
				yplitgroup_view_emailsent(); 
				die();
			}
			else
			{
				yplitgroup_view_main();
			}
		}

// ### IS CRONT JOB ###
if( $C->is_crontjob )
{
	if( $C->constant->refresh_count != 0 )
	{
		if( !isset( $_SESSION['refresh_count'] ) )
		{
			$_SESSION['refresh_count'] = 1;
		}
		else
		{
			$_SESSION['refresh_count']++;
		}
	}
	ini_set('max_execution_time', 0);
	yplitgroup_crontjob();
}
