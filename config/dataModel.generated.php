<?php

global $_groups, $_resources, $_columns;

$_groups = array(
'items' => array(
    'users'         => array('resources' => array('users', 'usersgroups', 'groups', 'groupsauths', 'sessions')),
    'config'        => array('resources' => array('adminlogs', 'bans', 'resources', 'resourcescolumns', 'tasks',)),
));

$_resources = array(
'items' => array(
	'resources' 		=> array(
		'name' => 'resources', 'type' => 'native', 'singular' => 'resource', 'plural' => 'resources', 
		'displayName' => 'resources', 'database' => 'default', 'table' => 'resources', 'alias' => 'res', 
		'nameField' => 'name', 'extends' => null, 'searchable' => true, 'exposed' => false, 'crudability' => 'CRUD',
	),
	'adminlogs' 		=> array('name' => 'adminlogs', 'type' => 'native', 'singular' => 'adminlog', 'plural' => 'adminlogs', 'displayName' => 'admin logs', 'database' => 'default', 'table' => 'admin_logs', 'alias' => 'admlog', 'defaultNameField' => 'admin_title', 'nameField' => 'slug', 'extends' => null, 'searchable' => false, 'exposed' => false, 'crudability' => 'CRUD',),
	'bans' 				=> array('name' => 'bans', 'type' => 'native', 'singular' => 'ban', 'plural' => 'bans', 'displayName' => 'bans', 'database' => 'default', 'table' => 'bans', 'alias' => 'ban', 'defaultNameField' => 'ip', 'nameField' => '', 'extends' => null, 'searchable' => false, 'exposed' => false, 'crudability' => 'CRUD',),
	'groups' 			=> array('name' => 'groups', 'type' => 'native', 'singular' => 'group', 'plural' => 'groups', 'displayName' => 'groups', 'database' => 'default', 'table' => 'groups', 'alias' => 'gp', 'defaultNameField' => 'admin_title', 'nameField' => '', 'extends' => null, 'searchable' => true, 'exposed' => false, 'crudability' => 'CRUD',),
	'groupsauths' 		=> array('name' => 'groupsauths', 'type' => 'relation', 'singular' => 'groupsauth', 'plural' => 'groupsauths', 'displayName' => 'groups auths', 'database' => 'default', 'table' => 'groups_auths', 'alias' => 'gpauth', 'defaultNameField' => '', 'nameField' => '', 'extends' => null, 'searchable' => false, 'exposed' => false, 'crudability' => 'CRUD',),
	'resourcescolumns' 	=> array('name' => 'resourcescolumns', 'type' => 'native', 'singular' => 'resourcescolumn', 'plural' => 'resourcescolumns', 'displayName' => 'resources columns', 'database' => 'default', 'table' => 'resources_columns', 'alias' => 'rc', 'defaultNameField' => '', 'nameField' => '', 'extends' => null, 'searchable' => false, 'exposed' => false, 'crudability' => 'CRUD',),
	'sessions' 			=> array('name' => 'sessions', 'type' => 'native', 'singular' => 'session', 'plural' => 'sessions', 'displayName' => 'sessions', 'database' => 'default', 'table' => 'sessions', 'alias' => 'sess', 'defaultNameField' => 'name', 'nameField' => '', 'extends' => null, 'searchable' => false, 'exposed' => false, 'crudability' => 'CRUD',),
	'tasks' 			=> array('name' => 'tasks', 'type' => 'native', 'singular' => 'task', 'plural' => 'tasks', 'displayName' => 'tasks', 'database' => 'default', 'table' => 'tasks', 'alias' => 'tsk', 'defaultNameField' => 'admin_title', 'nameField' => '', 'extends' => null, 'searchable' => false, 'exposed' => false, 'crudability' => 'CRUD',),
	'users' 			=> array('name' => 'users', 'type' => 'native', 'singular' => 'user', 'plural' => 'users', 'displayName' => 'users', 'database' => 'default', 'table' => 'users', 'alias' => 'u', 'defaultNameField' => 'email', 'nameField' => '', 'extends' => null, 'searchable' => true, 'exposed' => false, 'crudability' => 'CRUD',),
	'usersgroups' 		=> array('name' => 'usersgroups', 'type' => 'relation', 'singular' => 'usersgroup', 'plural' => 'usersgroups', 'displayName' => 'user groups', 'database' => 'default', 'table' => 'users_groups', 'alias' => 'ugp', 'defaultNameField' => '', 'nameField' => '', 'extends' => null, 'searchable' => false, 'exposed' => false, 'crudability' => 'CRUD',),
),
'_aliases' 		=> array(),
'_searchable' 	=> array('users'),
'_exposed' 		=> array(),
);

$_columns = array(
'resources' => array(
	'items' => array(
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
	'_exposed' 		=> array(),
	'_searchable' 	=> array(),
),
'users' => array(
	'items' => array(
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
	'_exposed' 		=> array(),
	'_searchable' 	=> array(),
),
'sessions' => array(
	'items' => array(
		'id' 					=> array('type' => 'pk'),
		'name' 					=> array('type' => 'varchar', 'length' => 32),
		'user_id' 				=> array('type' => 'onetoone'),
		'expiration_time'		=> array('type' => 'timestamp'),
		'ip' 					=> array('type' => 'ip'),
		'last_url' 				=> array('type' => 'url'),
		'creation_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
		'update_date'			=> array('type' => 'timestamp', 'editable' => 0, 'default' => 'now'),
	),
	'_exposed' 		=> array(),
	'_searchable' 	=> array(),
),
);

?>