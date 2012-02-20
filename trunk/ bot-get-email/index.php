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

// ### INCLUDE ###
require_once('database.class.php');
require_once('simple_html_dom.php');
require_once('config.php');
require_once('bot.php');

// ### DATABASE ###
$C->db = new sql_db( $C->database );

