<?php

$_columns = array(
'adminlogs' => array(
	'id' 					=> array(),
    'slug'           		=> array('type' => 'varchar', 'length' => 64),
    'action' 				=> array('type' => 'enum', 'values' => array('create','update','delete','import')),
    'resource_name'			=> array('type' => 'varchar', 'length' => 32),
    'resource_id'			=> array('type' => 'varchar'),
	'user_id' 				=> array(),
	'revert_query' 			=> array('type' => 'text'),
	'creation_date'			=> array('type' => 'timestamp'),
	'update_date'			=> array('type' => 'timestamp'),
),
'bans' => array(
	'id' 					=> array(),
    'ip'					=> array('length' => 40),
    'reason' 				=> array('length' => 32),
    'end_date'         		=> array('type' => 'timestamp'),
	'creation_date'			=> array('type' => 'timestamp'),
	'update_date'			=> array('type' => 'timestamp'),
),
'groups' => array(
	'id' 					=> array(),
	'name' 					=> array('length' => 32),
	'slug' 					=> array('from' => 'name'),
	'creation_date'			=> array('type' => 'timestamp'),
	'update_date'			=> array('type' => 'timestamp'),
),
'groupsauths' => array(
	'id' 					=> array(),
	'group_id' 				=> array(),
	'resource_id' 			=> array(),
	'allow_display' 		=> array('type' => 'bool', 'default' => true),
	'allow_create' 			=> array('type' => 'bool', 'default' => false),
	'allow_retrieve' 		=> array('type' => 'bool', 'default' => false),
	'allow_update' 			=> array('type' => 'bool', 'default' => false),
	'allow_delete' 			=> array('type' => 'bool', 'default' => false),
	'creation_date'			=> array('type' => 'timestamp'),
	'update_date'			=> array('type' => 'timestamp'),
),
'resources' => array(
	'id' 					=> array(),
	'table'                 => array('length' => 32),
	'name' 					=> array('type' => 'slug', 'from' => 'table'),
	'singular' 				=> array('length' => 32),
	'type' 					=> array('type' => 'enum', 'values' => array('native','relation','filter'), 'default' => 'native'),
	'alias'                 => array('length' => 8),
	'extends' 				=> array('length' => 8),
	'displayName' 			=> array('length' => 32),
	'searchable' 			=> array('type' => 'bool', 'default' => false),
	'nameField' 			=> array('length' => 32),
	'creation_date'			=> array('type' => 'timestamp'),
	'update_date'			=> array('type' => 'timestamp'),
),
'resourcescolumns' => array(
	'id' 					=> array(),
	'resource_id' 			=> array(),
	'name' 					=> array('length' => 32),
	'type' 					=> array('type' => 'enum', 'values' => DataModel::$columnTypes['types']),
	'realtype' 				=> array('type' => 'enum', 'values' => DataModel::$realTypes),
	//'length' 				=> array('type' => 'bigint', 'list' => 1),
	'length' 				=> array('type' => 'int', 'default' => 0),
	'pk' 					=> array('type' => 'bool', 'default' => false),
	'ai' 					=> array('type' => 'bool', 'default' => false),
	'fk' 					=> array('type' => 'bool', 'default' => false),
	'default' 				=> array(),
	'null' 					=> array('type' => 'bool', 'default' => false),
	'creation_date'			=> array('type' => 'timestamp'),
	'update_date'			=> array('type' => 'timestamp'),
),
'users' => array(
	'id' 					=> array(),
	'email' 				=> array(),
	'password' 				=> array('type' => 'password', 'exposed' => 0),
	'first_name' 			=> array('length' => 64),
	'last_name' 			=> array('length' => 64),
	'groups' 				=> array(),
	'activated' 			=> array('type' => 'bool', 'default' => false, 'exposed' => false),
	'activation_key' 		=> array('length' => 32, 'exposed' => false),
	'password_reset_key' 	=> array('length' => 32, 'exposed' => false),
	'creation_date'			=> array('type' => 'timestamp'),
	'update_date'			=> array('type' => 'timestamp'),
),
'sessions' => array(
	'id' 					=> array(),
	'name' 					=> array('length' => 32),
	'user_id' 				=> array(),
	'expiration_time'		=> array('type' => 'timestamp'),
	'ip' 					=> array(),
	'last_url' 				=> array(),
	'creation_date'			=> array('type' => 'timestamp'),
	'update_date'			=> array('type' => 'timestamp'),
),
);

?>