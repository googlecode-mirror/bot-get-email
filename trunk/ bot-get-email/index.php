<?php
/**
* Auto Get Email
* Yplitgroup
* 20/2/2012
*/
require_once('database.class.php');
require_once('simple_html_dom.php');
require_once('config.php');
require_once('bot.php');

// ### DATABASE ###
$C->db = new sql_db( $C->database );

