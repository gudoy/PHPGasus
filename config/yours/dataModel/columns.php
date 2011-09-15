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
);

?>
