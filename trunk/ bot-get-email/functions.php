<?php
/**
* Auto Get Email
* Yplitgroup
* 20/2/2012
*/

// Check install
function check_install()
{
	global $C;
	if( !isset( $C->db ) ) return false;
	return true;
}