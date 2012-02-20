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