<?php

$_columns = array(
'resources' => array(
	'id' 					=> array('type' => 'pk'),
	'table'                 => array('type' => 'varchar', 'length' => 32),
	'name' 					=> array('type' => 'slug', 'from' => 'table', 'length' => 32),
	'singular' 				=> array('type' => 'varchar', 'length' => 32),
	'type' 					=> array('type' => 'enum', 'values' => array('native','relation','filter'), 'default' => 'native',),
	'alias'                 => array('type' => 'varchar', 'length' => 8),
	'extends' 				=> array('type' => 'varchar', 'length' => 8),
	'displayName' 			=> array('type' => 'varchar', 'length' => 32),
	'searchable' 			=> array('type' => 'bool', 'default' => false),
	'nameField' 			=> array('type' => 'varchar', 'length' => 32),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
),
'users' => array(
	'id' 					=> array('type' => 'pk'),
	'email' 				=> array('type' => 'email'),
	'password' 				=> array('type' => 'password', 'hash' => 'sha1', 'length' => 64, 'exposed' => 0),
	'first_name' 			=> array('type' => 'varchar', 'length' => 64),
	'last_name' 			=> array('type' => 'varchar', 'length' => 64),
	'groups' 				=> array('type' => 'onetomany'),
	'activated' 			=> array('type' => 'bool', 'default' => 0, 'exposed' => 0),
	'activation_key' 		=> array('type' => 'varchar', 'length' => 32, 'exposed' => 0),
	'password_reset_key' 	=> array('type' => 'varchar', 'length' => 32, 'exposed' => 0),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
),
'sessions' => array(
	'id' 					=> array('type' => 'pk'),
	'name' 					=> array('type' => 'varchar', 'length' => 32),
	'user_id' 				=> array('type' => 'onetoone'),
	'expiration_time'		=> array('type' => 'timestamp'),
	'ip' 					=> array('type' => 'ip'),
	'last_url' 				=> array('type' => 'url'),
	'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
),
);

?>
