<?php

$_resources = array(
'adminlogs' 		=> array('singular' => 'adminlog', 'table' => 'admin_logs', 'alias' => 'admlog', 'defaultNameField' => 'slug', 'displayName' => 'admin logs'),
'bans' 				=> array('singular' => 'ban', 'table' => 'bans', 'alias' => 'b', 'defaultNameField' => 'ip', 'displayName' => 'bans'), 
'groups' 			=> array('singular' => 'group', 'alias' => 'gp', 'crudability' => 'CRUD', 'defaultNameField' => 'admin_title'),
'groupsauths' 		=> array('singular' => 'groupsauth', 'table' => 'groups_auths',  'alias' => 'gpauth', 'crudability' => 'CRUD', 'defaultNameField' => 'group_id'),
'resources' 		=> array('singular' => 'resource', 'alias' => 'res', 'crudability' => 'CRUD', 'defaultNameField' => 'name'),
'resourcescolumns' 	=> array('singular' => 'resourcecolumn', 'alias' => 'rescol', 'table' => 'resources_columns', 'crudability' => 'CRUD', 'defaultNameField' => 'columns', 'displayName' => 'columns'),
'sessions' 			=> array('singular' => 'session', 'alias' => 'sess', 'crudability' => 'R', 'defaultNameField' => 'id'),
'tasks' 			=> array('singular' => 'task', 'alias' => 'tsk', 'crudability' => 'CRUD', 'defaultNameField' => 'slug'),
'users' 			=> array('singular' => 'user', 'alias' => 'u', 'crudability' => 'CRUD', 'defaultNameField' => 'email', 'searchable' => 1),
'usersgroups' 		=> array('singular' => 'usersgroup', 'table' => 'users_groups', 'alias' => 'ugp', 'crudability' => 'CRUD'),
);

?>