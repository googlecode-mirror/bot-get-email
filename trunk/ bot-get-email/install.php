<?php
/**
* Auto Get Email
* Yplitgroup
* 20/2/2012
*/

require_once('database.class.php');
require_once('functions.php');
if( file_exits('config.php') )
{
	@unlink('config.php');
}
if( !$_POST['submit'] )
{
	yplitgroup_view_form_install(array('dbhost'=>'','dbname'=>'','dbuname'=>'','dbpass'=>''), '');
}
else
{
	$_POST['dbhost'] = !empty($_POST['dbhost'])?$_POST['dbhost']:'';
	$_POST['dbname'] = !empty($_POST['dbname'])?$_POST['dbname']:'';
	$_POST['dbuname'] = !empty($_POST['dbuname'])?$_POST['dbuname']:'';
	$_POST['dbpass'] = !empty($_POST['dbpass'])?$_POST['dbpass']:'';
	
	if( $_POST['dbhost'] == '' )
	{
		yplitgroup_view_form_install($_POST, 'dbhost Empty!!!');
	}
	elseif( $_POST['dbname'] == '' )
	{
		yplitgroup_view_form_install($_POST, 'dbname Empty!!!');
	}
	elseif( $_POST['dbuname'] == '' )
	{
		yplitgroup_view_form_install($_POST, 'dbuname Empty!!!');
	}

}