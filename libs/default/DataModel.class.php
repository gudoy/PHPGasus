<?php

( isset($_resources) && isset($_columns) ) || require(_PATH_CONFIG . 'dataModel.generated.php');

class DataModel extends Core
//class DataModel
{
	public static $resources 	= array();
	public static $groups 		= array();
	public static $columns 		= array();
	
	// TODO: $propName => array('default' => null|[true|false]|{$value}, 'comment' => null, 'deprecated' => true|false)
	static $resourceProperties 				= array(
		# Meta
		'type',
		'name', 'singular', 'plural', 
		'displayName', 'nameField',
		'defaultNameField', 					// Deprecated: use nameField instead 
		
		# Database
		'database', 'engine', 
		'table', 'alias',
		
		# Relations
		'extends', 								// default = null
		//'parent', 								// default = null
		//'parents',  							// default = array()
		//'siblings',  							// default = array()
		//'children', 							// default = array()
		//'related', 								// parent + siblings + children
		// use relations instead of parent/siblings/children??????
		// 'relations'  	=> array('oneToOne','oneToMany','manyToOne', 'manyToMany')
		
		# PHPGasus features related
		'order', 'importance', 					// TODO: implement
		'searchable', 'crudability', 'exposed',
		
		# Generated
		'_plurals', 							// array ([$plural => $singular])
		'_parent', 								// resource name (default = null)
		'_parents', 							// array of resource names (default = empty)
		'_siblings', 							// array of resource names (default = empty)
		'_children', 							// array of resource names (default = empty)
		'_related', 							// array of resource names (default = empty)
		'_exposedColumns', 						// array of column names (default = empty) 
		'_searchableColumns', 					// array of column names (default = empty)
	);
	
	static $realTypes = array(
		'string','text','enum','set',
		'int','tinyint', 'smallint', 'mediumint', 'bigint',
		'float',
		'boolean',
		'timestamp','date','datetime',
		'onetone', 'onetomany', 'manytoone', 'manytomany'
	);
	
	static $columnTypes = array(
        # mysql types:
        // 'serial', 'bit', 'tinyint', 'bool', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'double precision', 'decimal', 
        // 'date', 'datetime', 'timestamp', 'time', 'year', 
        // 'char', 'varchar', 
        // 'binary', 'varbinary', 
        // 'tinyblob', 'blob', 'mediumblob', 'longblob',
        // 'tinytext', 'text', 'mediumtext', 'longtext', 
        // 'enum', 'set'
        // 'bit'
		'types' 	=> array(
			# Texts
			'string', 'varchar', 
			'email', 'password', 'url', 'tel', 'color', 'meta', 'ip',
			'slug', 
			//'tag', 
			'text', 'html', 'code',
			
			# Numbers
			'int', 'integer', 'numeric', 'decimal',
			'tinyint', 'smallint', 'mediumint', 'bigint',
			'float', 'real', 'double',
			
			# Booleans
			'bool','boolean',
			
			# Dates & times
			'timestamp', 'datetime', 'date', 'time', 'year', 
			//'month', 'week', 'day', 'hour', 'minutes', 'seconds',
			
			# Arrays
			'enum', 'choice', 'set',
			
			# Relations
			'1-1', 'onetoone', 'one2one', '121', '1to1', '12one',
			'1-n', 'onetomany', 'one2many', '12n', '1ton', '1tomany', '12many',  
			'n-1', 'manytoone', 'many2one', 'n21', 'nto1', 'manyto1', 'many21',
			'n-n', 'manytomany', 'many2many', 'n2n', 'nton',
			
			# media
			'file', 'image', 'video', 'sound', 'file',
			
			# Misc
			//'pk', 'id', 'serial',
			'id', 'serial',
		),
		'realtypes' => array(
			# Texts
				// strings (length=255) 
				'string' 		=> 'varchar', 		// + length = 255
				'varchar' 		=> 'varchar', 		// alias of string
				'slug' 			=> 'varchar', 		// + length = 64
				//'tag' 			=> 'string', 		// alias of slug
				'email' 		=> 'varchar', 		// + validator pattern
				'password'		=> 'varchar', 		// + modifiers = sha1 + length(will depend of the algorithm)
				'url' 			=> 'varchar', 		// + max = 2083 + FILTER_VALIDATE_URL?
				'tel' 			=> 'varchar', 		// + length = 20???, + pattern ? 
				'color'			=> 'varchar', 		// + length = 32, + validator pattern (#hex, rgb(), rgba(), hsl(), ... ?)
				'meta' 			=> 'varchar',
				'ip' 			=> 'varchar', 		// + length = 40 + FILTER_VALIDATE_IP, ? 
				
				// texts (length=null)				
				'html' 			=> 'text',
				'code' 			=> 'text',
				'text' 			=> 'text',
				
				// enumerations
				'enum' 			=> 'enum',
				'choice' 		=> 'enum',
				'set' 			=> 'set',

			# Numbers
				// ints
				'int' 			=> 'int', 			// + min = -2147483648, + max = 2147483648
				'integer'		=> 'int', 			// + min = -2147483648, + max = 2147483648
				'num'			=> 'int', 			// + min = -2147483648, + max = 2147483648
				'number'		=> 'int', 			// + min = -2147483648, + max = 2147483648
				
				'tinyint' 		=> 'tinyint', 		// + length = 4, + min = -128, + max = 128 
				'smallint' 		=> 'smallint', 		// + length = 6, + min = -32768, + max = 32768
				'mediumint' 	=> 'mediumint', 	// + length = 9, + min = -8388608, + max = 8388608
				'bigint' 		=> 'bigint', 		// + length = 20, + min = -9223372036854775808, + max = 9223372036854775808
				
				// floats
				'decimal' 		=> 'decimal', 		// + length = length(10,0)????
				'float' 		=> 'float',
				'real' 			=> 'float',
				'double'		=> 'double',		
				
			# Booleans
				'bit' 			=> 'boolean',
				'bool' 			=> 'boolean',
				'boolean' 		=> 'boolean',
				
			# Dates & times
				'timestamp' 	=> 'timestamp',
				'date' 			=> 'date',
				'datetime' 		=> 'datetime',
				'time' 			=> '', 				// ?
				'year' 			=> '', 				// ?
				//'month' 		=> '', 				// ?
				//'week' 			=> '', 				// ?
				//'day' 			=> '', 				// ?
				//'hour' 			=> '', 				// ?
				//'minutes' 		=> '', 				// ?
				//'seconds' 		=> '', 				// ?
				
			# Relations
				// One to one relations (& aliases)
				/*
				'1-1' 			=> 'onetone', 
				'onetoone' 		=> 'onetone', 
				'one2one' 		=> 'onetone', 
				'121' 			=> 'onetone', 
				'1to1' 			=> 'onetone', 
				'12one' 		=> 'onetone',

				// One to many relations (& aliases)
				'1-n' 			=> 'onetomany', 
				'onetomany' 	=> 'onetomany', 
				'one2many' 		=> 'onetomany', 
				'12n' 			=> 'onetomany', 
				'1ton' 			=> 'onetomany', 
				'1tomany' 		=> 'onetomany', 
				'12many' 		=> 'onetomany',  
				
				// Many to one relations (& aliases)
				'n-1' 			=> 'manytoone', 
				'manytoone' 	=> 'manytoone', 
				'many2one' 		=> 'manytoone', 
				'n21' 			=> 'manytoone', 
				'nto1' 			=> 'manytoone', 
				'manyto1' 		=> 'manytoone', 
				'many21' 		=> 'manytoone',
				
				// Many to many relations (& aliases)
				'n-n' 			=> 'manytomany', 
				'manytomany' 	=> 'manytomany', 
				'many2many' 	=> 'manytomany', 
				'n2n' 			=> 'manytomany', 
				'nton' 			=> 'manytomany',
				 */
				
				// TODO: if relColumn type is int
				'onetoone' 		=> 'int', 				// + fk = 1 + handle relations props
				// TODO: if relColumn type is serial
				'onetoone' 		=> 'serial', 			// + fk = 1 + handle relations props
			
			# Misc				
				//'pk' 			=> 'int', 			// Pk + length = 11, pk = 1, ai = 1, unsigned = 1, null = 0, unique = 1, editable = 0
				'id' 			=> 'int', 			// Pk + length = 11, pk = 1, ai = 1, unsigned = 1, null = 0, unique = 1, editable = 0
				'serial' 		=> 'int', 			// Pk + length = 20, pk = 1, ai = 1, unsigned = 1, null = 0, unique = 1, editable = 0
		),
	);
	
	// TODO: $propName => array('default' => null|[true|false]|{$value}, 'comment' => null, 'deprecated' => true|false)
	static $columnProperties 				= array(
		# PHPGasus meta
		'type',
		'exposed',
		'importance',
	
		# SQL related
		'realtype',
		'length',
		'values',
		'possibleValues', 						// Deprecated: use values instead
		'unsigned', 
		'null', 'default',
		'fk',
		'pk', 'ai',  
		'unique', 'index', 						// TODO: implement
		
		# Relations
		'from', 								// TODO: use this to tell the column from which a slug will be created
		//'on 									// TODO: ???
		'relResource', 							// 
		'relField', 							// Deprecated: use relColumn instead
		'relColumn', 							//  
		'getFields','relGetFields', 			// Deprecated: use getColumns instead
		'relGetAs', 							// Deprecated: use associative array getColumns
		'getColumns', 							// TODO: 'col1,cold2,...' or array('col1','col2',...) or array('col1' => 'my_col_1', 'col2' => 'my_col_2')
		'pivotResource', 'pivotLeftField', 'pivotRightField',
		'fetchingStrategy',  					// null,'none,','join','select','subselect','batch'
		'lazy', 								// TODO: ???

		# Format/validation related
		'placeholder', 'required', 
		'min', 'max', 'step', 'patterns', 'pattern',
		'computed', 'computedValue', 'eval',	// Deprecated. 
		'modifiers', 							// TODO: implement trim|lower|upper|camel|capitalize|now|escape, ....
		'algo', 								// for password. TODO: implement md5,sha1
		'prefix', 								// TODO?????
		'suffix', 								// TODO?????
		
		// Files related
		'forceUpload', 
		'storeOn',  							// TODO????? (ftp|amazon_s3|amazon_ec2) 
		'acl', 									// TODO????? (S3_ACL_PRIVATE, S3_ACL_PUBLIC, S3_ACL_OPEN, S3_ACL_AUTH_READ. default = S3_ACL_PRIVATE)
		'destBaseURL', 							// TODO????? 
		'destRoot', 							// TODO?????
		'destName', 							// TODO?????
		'destFolder', 							// TODO?????

		
		// UI or admin purpose
		'uiWidget',
		'displayName', 'displayValue', 
		'list', 								// Deprecated: use importance instead
		'editable', 'forceUpdate',
		'comment',
		
		// Deprecated (for BC only)
		//'possibleValues', 'relField' , 'relGetFields' , 'relGetAs'
		// 'computed', 'eval', 'computedValue'
		// 'list' 
		
	);
	static $columnBoleanDefaultedProperties = array(
	
	);
	
	public function _construct()
	{
		parent::__construct();
	}
	
	// 
	public function build($params = array())
	{		
		$params 		= array_merge(array(
			//'what' => 'resources,colums,groups',
			//'what' => 'resources',
			'what' => 'resources,columns',
		), $params); 
		
		//$dir 			= 'config/dataModel/';
		$dir 			= 'config/';
		
		// Init final file content string
		$filecontent 	= '';
		
		// Create a zip archive, open it, create the proper folder and create the file
		$zipFile 		= tempnam('tmp', 'zip');
		$zip 			= new ZipArchive();
		$zip->open($zipFile, ZipArchive::OVERWRITE);
		$zip->addEmptyDir($dir);
		
		// 
		$what 	= Tools::toArray($params['what']);
		
		// 1nd pass: parse 'default' & 'yours' files and build a 1st pass dataModel.generated.php
		foreach( $what as $v){ $parseMtdh = 'parse' . ucfirst($v); self::$parseMtdh(); }
		

		// 2nd pass:
		// (required to handle props depending on others props that are not already computed at the time we try to handle them )
		foreach( $what as $v){ $parseMtdh = 'parse' . ucfirst($v); self::$parseMtdh(array('mode' => 'update', 'checkDatabase' => false)); }

//echo $fileContent;
//var_dump($fileContent);
//var_dump(self::$resources);
//var_dump(self::$columns);

//var_dump(__METHOD__);

		// We can now generate the final file
		foreach( $what as $v)
		{
			$genMthd 	= 'generate' . ucfirst($v);
			
			$fileContent .= self::$genMthd(array('inline' => false));
			$fileContent .= PHP_EOL . PHP_EOL;
		}

//var_dump($dir);
//var_dump($fileContent);
		
		// Create the file into the zip and close it
		$added = $zip->addFromString($dir . 'dataModel.generated.php', $fileContent);
		$zip->close();
		
//var_dump($added);
//var_dump(self::$resources);
//var_dump(self::$columns);
//die();

		
		// Push the file to the client & delete the zip
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize($zipFile));
		header('Content-Disposition: attachment; filename="[' . _APP_NAME . ']_' . 'dataModel.zip"');
		readfile($zipFile);
		unlink($zipFile);
	}
	
	public function buildResources() 	{ return $this->build(array('what' => 'resources')); }
	public function buildColumns() 		{ return $this->build(array('what' => 'columns')); }
	public function buildGroups() 		{ return $this->build(array('what' => 'groups')); }
	
	public function parse()
	{
		$this->parseResources();
		//$this->parseGroups();
		$this->parseColumns();
		
//var_dump(self::$resources);
//var_dump(self::$columns);
	}
	
	// Merge order: database, dataModel (generated), dataModel (manual)
	public function parseResources(array $params = array())
	{
		$p = array_merge(array(
			'from' 			=> array(
				_PATH_CONF . 'default/dataModel/resources.php',
				_PATH_CONF . 'yours/dataModel/resources.php',
			), 														// From where to look for resource
			'varname' 		=> '_resources', 						// Name of the variable containing resources 
			'checkDatabase' => true, 								// Do we want to look for database resources
			'mode' 			=> 'rewrite', 							// 'rewrite' (default) or 'update'
		), $params);
		
		// Init temp resources var 
		$tmpRes = $p['mode'] === 'update' ? self::$resources : array();
		
		// Loop over passed resources files
		foreach(Tools::toArray($p['from']) as $item)
		{
			// Do not continue is the file could not be loaded
			if ( !is_file($item) || !require($item) ){ continue; }
			
			// Merge its resources into a temp resources array
			$tmpRes = array_merge($tmpRes, ${$p['varname']});
		}
		
		// Get unregistered resources from database
		// TODO
		
		// Get registered resources from database
		if ( $p['checkDatabase'] )
		{
			switch(_DB_DRIVER)
			{
				case 'default':
				case 'pdo': 		$mName = 'pdoModel'; break;
				case 'mysqli': 		$mName = 'mysqliModel'; break;
				default: 			$mName = _DB_SYSTEM . 'Model'; break;
			}
			
			$Resources = new Model(array('_resource' => 'resources'));
			
			//$dbResources = CResources::getInstance()->find();
			//$dbResources = MResources::getInstance()->find();
			//$dbResources = MResources->find();
			//$dbResources = Resources::getInstance()->find();
			//$dbResources = $this->resources->find();
			//$dbResources = $this->resources->find();
			$dbResources = $Resources->find();
			
			//$dbResources 	= array();	
		}
		
		// Merge the database resources into the temp resources array
		$tmpRes = array_merge((array) $tmpRes, (array) $dbResources);
		
		// Init final resources
		if ( $p['mode'] === 'rewrite' )
		{
			self::$resources = array(
				'items' 		=> array(),
				'_aliases' 		=> array(),
				'_searchable' 	=> array(),
				'_exposed' 		=> array(),
			);	
		}
		
		// Loop over the resources
		foreach ( $tmpRes as $name => &$res )
		{
			$realName 	= Tools::slug(preg_replace('/[\+\s\_]/', '', $name));
			$searchable = !empty($res['searchable']) ? $res['searchable'] : 0;
			$exposed 	= !empty($res['exposed']) ? $res['exposed'] : 0;
			$alias 		= !empty($res['alias']) ? $res['alias'] : self::getDbTableAlias($realName);
			
			self::$resources['items'][$realName] = array(
				'name' 				=> $realName,
				'displayName' 		=> !empty($res['displayName']) ? $res['displayName'] : $name,
				'type' 				=> !empty($res['type']) ? $res['type'] : $this->guessResourceType($name),
				'singular' 			=> !empty($res['singular']) ? $res['singular'] : Tools::singular($name),
				'plural' 			=> !empty($res['plural']) ? $res['plural'] : $realName,
				'database' 			=> !empty($res['database']) ? $res['database'] : 'default',
				'table' 			=> !empty($res['table']) ? $res['table'] : self::getDbTableName($realName),
				'alias' 			=> $alias,
				'engine' 			=> !empty($res['engine']) ? strtolower($res['engine']) : 'innobd',
				'displayName' 		=> !empty($res['displayName']) ? $res['displayName'] : $name,
				'nameField' 		=> !empty($res['nameField']) ? $res['nameField'] : ( !empty($res['defaultNameField']) ? $res['defaultNameField'] : self::guessNameField($name) ),
				'extends' 			=> !empty($res['extends']) ? $res['extends'] : null,
				'searchable' 		=> $searchable,
				'exposed' 			=> $exposed,
				'crudability' 		=> !empty($res['crudability']) ? $res['crudability'] : 'CRUD',
				
				// Generated properties
				'_exposedColumns' 		=> array(), // will be populated by parseColumns() 
				'_searchableColumns' 	=> array(), // will be populated by parseColumns()
				'_parent' 				=> null, 	// will be populated by parseColumns()
				'_parents' 				=> array(), // will be populated by parseColumns()
				'_siblings' 			=> array(), // will be populated by parseColumns()
				'_children' 			=> array(), // will be populated by parseColumns()
				'_related' 				=> array(), // will be populated by parseColumns()
				
			);
			
			self::$resources['_aliases'][$alias] = $realName;
			
			// 
			if ( $searchable )	{ self::$resources['_searchable'][] = $realName; }
			if ( $exposed )		{ self::$resources['exposed'][] 	= $realName; }

			ksort($res);
		}

		// Sort resources by alphabetical order
		//asort(self::$resources);
	}
	
	public function parseGroups()
	{
		// TODO
	}
	
	
	// Merge order: database, dataModel (generated), dataModel (manual)
	public function parseColumns(array $params = array())
	{
		$p = array_merge(array(
			'from' 			=> array(
				_PATH_CONF . 'default/dataModel/columns.php',
				_PATH_CONF . 'yours/dataModel/columns.php',
			), 														// // From where to look for resource
			'varname' 		=> '_columns', 							// Name of the variable containing resources 
			'checkDatabase' => true, 								// Do we want to look for database resources
			'mode' 			=> 'rewrite', 							// 'rewrite' (default) or 'update'
		), $params);
		
		// Init temp columns var 
		$tmpColumns = $p['mode'] === 'update' ? self::$columns : array();
		
		// Loop over passed columns files
		foreach(Tools::toArray($p['from']) as $item)
		{
			// Do not continue is the file could not be loaded
			if ( !is_file($item) || !require($item) ){ continue; }
			
			// Merge its resources into a temp columns array
			$tmpColumns = array_merge($tmpColumns, ${$p['varname']});
		}
		
		// Get unregistered columns from database
		// TODO
		
		// Get registered columns from database
		if ( $p['checkDatabase'] )
		{
			// TODO
			$dbColumns = array();
		}
		
		// Merge the database columns into the temp columns array
		$tmpColumns = array_merge((array) $tmpColumns, (array) $dbColumns);
		
		// Init final resources
		// Init final resources
		if ( $p['mode'] === 'rewrite' )
		{
			self::$columns = array(
				//'items' 		=> array(),
				//'_exposed' 		=> array(),
				//'_searchable' 	=> array(),
			);
		}
		
		// Loop over the resources
		foreach ( array_keys((array) $tmpColumns) as $rName )
		{
//var_dump($rName);
			
			//$rProps = self::resource($rName);
			$rProps = self::$resources['items'][$rName];
				
			// Shortcut for current resource columns
			$rCols = &$tmpColumns[$rName];
			
			// Loop over the columns of the current resource
			$i 		= 0;
			$colsNb = count($rCols); 
			foreach ( array_keys((array) $rCols) as $cName )
			{
//var_dump($rName . ' -> ' . $cName);
				// Shortcut for defined column properties
				//$p 		= &$rCols[$cName];
				// First step: takes the defined properties as is (just remove unknown properties)
				$p = array_intersect_key((array) $rCols[$cName], (array) array_flip((array) self::$columnProperties));
				
				// Shortcut for final column properties
				//$cProps = &self::$columns['items'][$rName][$cName];
				$cProps = &self::$columns[$rName]['items'][$cName];
//var_dump($p);
//var_dump('-----------: ' . $cName);

				// Init final resource columns metas
				if ( $p['mode'] === 'rewrite' )
				{
					self::$columns[$rName] = array_merge(self::$columns[$rName], array('_exposed','_searchable'));
				}
				
				// Loop over known columns names 
				foreach(self::$columnProperties as $k)
				{						
					# PHPGasus meta
					// Handle 'type'
					if ( $k === 'type' )
					{
						$cProps['type'] = isset($p['type']) && in_array($p['type'], self::$columnTypes['types']) 
											? $p['type'] 
											: self::guessColumnType($cName);
											
						// Handle resource 'nameField' meta:
						// If not already defined, get the fil
						if ( empty($rProps['nameField']) 
							&& in_array($cProps['type'], array('string','varchar','email','url','slug','tag')) )
						{ $rProps['nameField'] = $cName; }
					}
					
					// Handle 'exposed'
					elseif ( $k === 'exposed' )
					{	
						$cProps['exposed'] = isset($p['exposed']) ? ( $p['exposed'] == true ) : null;
						
						// Do not continue if the property has been defined
						if ( !is_null($cProps['exposed']) ){ continue; }
						
						if ( $cProps['type'] === 'password' )	{ $cProps['exposed'] = false; }
						else 									{ $cProps['exposed'] = true; }
					}
					
					// Handle 'importance'
					elseif ( $k === 'importance' )
					{	
						$cProps['importance'] = isset($p['importance']) ? intVal($p['importance']) : 0;
						
						// Do not continue if the property has been defined
						if ( $cProps['importance'] ){ continue; }
						
						// If the colum is the name field of it's resource
						if 		( !empty($rProps['nameField']) && $rProps['nameField'] === $cName ){ $cProps['importance'] = 100; }
						elseif 	( $cName === 'id' ){ $cProps['importance'] = 90; }
					}
	
					# SQL related
					// Handle 'realtype'
					elseif ( $k === 'realtype' )
					{
						$cProps['realtype'] = isset($p['realtype']) && in_array($p['realtype'], self::$columnTypes['realtypes']) 
											? $p['realtype'] 
											: self::$columnTypes['realtypes'][$cProps['type']];
					}
					
					// Handle 'length'
					elseif ( $k === 'length' )
					{
						$cProps['length'] = isset($p['length']) ? intVal($p['length']) : null;
						
						// Do not continue if the property has been defined
						if ( !empty($cProps['length']) ) { continue; }
						
						// Otherwise, do some magic depending of defined type
						switch($cProps['type'])
						{
							// TODO: complete
							case 'tinyint':
							case 'smallint':
							case 'mediumint':
							case 'bigint':  
							case 'int': 		$cProps['length'] = 11; break;

							case 'tag':
							case 'slug': 		$cProps['length'] = 64; break;
							case 'ip': 			$cProps['length'] = 40; break;
							case 'tel': 		$cProps['length'] = 20; break;
							case 'color': 		$cProps['length'] = 32; break; 
							
							case 'email':
								
							case 'password': 	// TODO: will depends of algo
												$cProps['length'] = 40; break; // sha1
												
										
							case 'varchar':
							case 'string': 		$cProps['length'] = 255; break;
								
							default: 			$cProps['length'] = null; break;							
						}
					}
					
					// Handle 'possibleValues'
					elseif ( $k === 'possibleValues' )
					{
						//$cProps['values'] = isset($p['possibleValues']) ? Tools::toArray($p['possibleValues']) : null;
						if ( isset($p['possibleValues']) ){ $cProps['values'] = $p['possibleValues']; }
					}
					
					// Handle 'values'
					elseif ( $k === 'values' )
					{

						$cProps['values'] = isset($p['values']) ? $p['values'] : null;
						
						// TODO: if type accept several values, transform it into an array
						if ( !empty($cProps['values']) && in_array($cProps['type'], array('set','enum','choice')) )
						{
							$cProps['values'] = Tools::toArray($cProps['values']);
						}
					}
					
					elseif ( $k === 'unsigned' )
					{
						// Do not continue if the property has been defined
						$cProps['unsigned'] = isset($cProps['unsigned']) ? ($cProps['unsigned'] ? true : false) : null;
					}
					
					// Handle 'null'
					elseif ( $k === 'null' )
					{
						// Do not continue if the property has been defined
						if ( isset($p['null']) ){ $cProps['null'] = $cProps['null'] == true ? true : false; }
						
						// Otherwise, do some magic depending of defined type
						switch($cProps['type'])
						{
							// The following types MUST have a value and thus have to be NOT NULL
							case 'id':
							case 'pk':
							case 'serial':
							case 'bit':
							case 'bool':
							case 'boolean':
							case 'set':		
							case 'enum':
							case 'choice':
								$cProps['null'] = false; break;
							default: 
								$cProps['null'] = true; break;							
						}
					}
					
					// Handle 'default'
					elseif ( $k === 'default' )
					{
						// Do not continue if the property has been defined
						if ( isset($p['default']) ){ $cProps['default'] = $cProps['default'] == true ? true : false; }

						// TODO: do some magic
						$cProps['default'] = null;
					}
					
					// Handle 'fk'
					elseif ( $k === 'fk' )
					{
						$cProps['fk'] = isset($p['fk']) ? ( $p['fk'] == true ) : in_array($cProps['type'], array('onetoone'));
					}
					// Handle 'pk'
					elseif ( $k === 'pk' )
					{
						$cProps['pk'] = isset($p['pk']) ? ( $p['pk'] == true ) : in_array($cProps['type'], array('pk','id','serial'));
					}
					
					// Handle 'ai'
					elseif ( $k === 'ai' )
					{
						$cProps['ai'] = isset($p['ai']) ? ( $p['ai'] == true ) : in_array($cProps['type'], array('pk','id','serial'));
					}
					
					// Handle 'unique'
					elseif ( $k === 'unique' )
					{
						$cProps['unique'] = isset($p['unique']) ? ( $p['unique'] == true ) : in_array($cProps['type'], array('pk','id','serial'));
					}
					// Handle 'index'
					elseif ( $k === 'index' )
					{
						$cProps['index'] = isset($p['index']) 
							? ( $p['index'] == true ) 
							: ( in_array($cProps['type'], array('onetoone')) || $cProps['fk'] || $cProps['pk'] );
					}
					
					// Handle 'from'
					elseif ( $k === 'from' )
					{
						//$cProps['from'] = isset($p['from']) && self::isColumn($rName, $p['from']) ? $p['from'] : null;	
						$cProps['from'] = isset($p['from']) ? $p['from'] : null;
					}
					
					// Handle 'relResource',
					elseif ( $k === 'relResource' )
					{	
						$cProps['relResource'] = isset($p['relResource']) && self::isResource($p['relResource']) 
							? $p['relResource'] 
							: ( in_array($cProps['type'], array('onetoone','onetomany')) ? self::guessRelatedResource($cName) : null );
							
//var_dump($cProps['relResource']);
							
						// Populater proper resource relation related properties
						if ( $cProps['relResource'] )
						{
							//self::$resources['items'][$rName]['_related'][] = $cProps['relResource'];
							if ( !in_array($cProps['relResource'], $rProps['_related']) ){ array_push($rProps['_related'], $cProps['relResource']); }
							//self::$resources['items'][$rName]['_children'][] = $cProps['relResource'];
							if ( !in_array($cProps['relResource'], $rProps['_children']) ){ array_push($rProps['_children'], $cProps['relResource']); }
						}
					}
					// Handle 'relField',
					elseif ( $k === 'relField' )
					{
						if ( isset($p['relField']) ){ $cProps['relColumn'] = $p['relField']; }
					}
					// Handle 'relColumn',
					elseif ( $k === 'relColumn' )
					{				
						$cProps['relColumn'] = isset($p['relColumn']) && !empty($cProps['relResource']) 
												&& self::isColumn($cProps['relResource'], $p['relColumn']) 
													? $p['relColumn'] 
													: self::$resources['items'][$cProps['relResource']]['nameField'];
					} 							
					// Handle 'getFields','relGetFields', 			
					// Handle 'relGetAs', 							
					// Handle 'getColumns',
					// Handle 'pivotResource',
					// Handle 'pivotLeftField',
					// Handle 'pivotRightField',
					// Handle 'fetchingStrategy',
					// Handle 'lazy',
				}
					
				$i++;
			}

			// Handle resource 'nameField' meta:
			// If not already defined, default it to 'id'
			if ( !$rProps['nameField'] ) { $rProps['nameField'] = 'id'; }

//var_dump($rName);
//var_dump(self::$columns['items'][$rName]);
		}
	}
	
	// string: resource name
	// string: csv resource names
	// array: resource names
	// return: array (resource name => columns) is several resource names passed
	// return: array of columns of the passed resource name
	public function parseDBColumns($resources)
	{
		// Force the resource names to be an array
		$resources = Tools::toArray($resources);
		
		// Init returned data array
		$dbCols = array();

		// Loop over the resource names		
		foreach(array_keys($resources) as $key)
		{
			// Set a shortcut to current resource data & resource name
			$resource 	= &self::$resources[$key];
			$rName 		= &$resource['name'];
			
			// Get resource DB columns data using proper query  
			$query 		= "DESCRIBE " . $resource['table'] . ";"; 
			//$dbColumns 	= CResourcescolumns::getInstance()->index(array('manualQuery' => $query));
$dbColumns = array();
			
			// Do no continue if the table does not exists in DB	
			//if ( empty($dbColumns) ){ die($rName); }
			if ( empty($dbColumns) ){ continue; }
			
			// Loop over the found columns
			foreach ( array_keys($dbColumns) as $dbColumn )
			{
				// Shortcut for column properties
				$p = &$dbColumns[$dbColumn];
				
				$realtype 	= strpos($p['Type'], '(') !== false ? substr($p['Type'], 0, strpos($p['Type'], '(')) : $p['Type'];
				$values 	= $realtype === 'enum' 
								? explode(',', str_replace(array('enum(',')',"'"),'', $p['Type']) ) 
								: null;
				
				$dbCols[$rName][$p['Field']] = array(
					'name' 				=> $p['Field'],
					'realtype' 			=> $realtype,
					'length' 			=> strpos($p['Type'], '(') !== false ? (int) substr($p['Type'], strpos($p['Type'], '(') + 1, -1) : null,
					'null' 				=> $p['Null'] === 'YES' ? true : false,
					'pk' 				=> $p['Key'] === 'PRI' ? true : false,
					'values' 			=> $values,
				);
			}
		}
		
		return count($resources) === 1 ? $dbCols[$rNames[0]] : $dbCols;
	}
	
	
	public function generate()
	{
		$this->generateResources();
		$this->generateGroups();
		$this->generateColumns();
	}
	
	public function generateResources($params = array())
	{
		$o 				= array_merge(array(
			'inline' 	=> false,
			'lb' 		=> "\n",
			'tab' 		=> "\t",
		), $params);
		
		$code 			= '<?php' . $o['lb'] . $o['lb'] . '$_resources = array(' . $o['lb'];
		$longer 		= null;
		
		// Build an array or resource names only (for perf issues)
		$resNames 		= array_keys(self::$resources['items']);
		
		// Get the longest resource name and use it to get position used to verticaly align the resource code (indentation)
		$longRes 		= Tools::longestValue($resNames);
		$resVertPos 	= strlen($longRes) + ( 4 - (strlen($longRes) % 4) );
		
		
		// Get the longest resource propery name
		//$longProp 		= Tools::longestValue($rPropNames);
		$longProp 		= Tools::longestValue(self::$resourceProperties);
		$propVertPos	= strlen($longProp) + ( 4 - (strlen($longProp) % 4) );
		
		$code 			.= "'items' => array(" . $o['lb'];
		
		// Loop over the resources
		foreach ( $resNames as $rName )
		{
			// Shortcut for resource props
			$p 		= &self::$resources['items'][$rName];
			
			$tabs 	= '';
			
			if ( $o['inline'] )
			{
				// Calculate the number of tabs required for proper vertical alignement of the current resource
				//$tabsCnt = floor(($resVertPos - strlen($rName) / 4));
				$resTabs = ($resVertPos - (strlen($rName)+3))/4;
				//$resTabs = ( $resTabs < 1 ) ? 1 : ( ( $resTabs - ceil($resTabs) < 0.5 ) ? ceil($resTabs) : floor($resTabs) );
				for($i=0; $i<$resTabs; $i++){ $tabs .= $o['tab']; }				
			}
			
			$code .= $o['tab'] . "'" . $p['name'] . "' " . $tabs . "=> array(" . ( $o['inline'] ? '' : $o['lb'] );
			
			// Loop over properties
			foreach (self::$resourceProperties as $propName)
			{
				$tabs = '';
				
				if ( !$o['inline'] )
				{
					// Calculate the number of tabs required for proper vertical alignement of the current property
					//$tabsCnt = floor(($resVertPos - strlen($rName) / 4));
					$propTabs = ($propVertPos - (strlen($propName)+3))/4;
					//$propTabs = ( $propTabs < 1 ) ? 1 : ( ( $propTabs - ceil($propTabs) < 0.5 ) ? ceil($propTabs) : floor($propTabs) );
					for($i=0; $i<$propTabs; $i++){ $tabs .= $o['tab']; }					
				}
				
				$code .=  ($o['inline'] ? '' : $o['tab'] ) . "'" . $propName . "' " . $tabs . "=> ";
				
				$boolProps 	= array('searchable','exposed');
				$nullProps 	= array('extends','database', '_parent');
				$arrayProps = array('_parents','_siblings','_children','_related','_exposedColumns','_searchableColumns');
				
				// Boolean props
				if ( in_array($propName, $boolProps) ) 		{ $code .= $p[$propName] ? 'true' : 'false'; }
				// Defined string of default to null props
				elseif ( in_array($propName, $nullProps) ) 	{ $code .= !empty($p[$propName]) ? "'" . $p[$propName] . "'" : 'null'; }
				//
				elseif ( in_array($propName, $arrayProps) ) { $code .= "array('" . (join("', '", (array) $p[$propName]) ) . "')"; }
				// Default: string props
				else 										{ $code .= "'" . $p[$propName] . "'"; }
				
				$code .=  "," . ( $o['inline'] ? '' : $o['lb'] );
			}
			
			$code .= ")," . $o['lb'];
		}

		$code .= ")," . $o['lb'];
		
		// TODO: handle meta values
		foreach(self::$resources as $propName => $propValue)
		{
			// Skip 'items'
			if ( $propName === 'items' ){ continue; }
			
			$tabs 	= '';
			
			//$code .= "'" . $propName . "' " . $tabs . "=> " . ( $o['inline'] ? '' : $o['lb'] );
			$code .= "'" . $propName . "' " . $tabs . "=> ";
			
			// TODO: do we need to handle others than array values?
			$code .= "array(";
			$i = 0;
			foreach ($propValue as $k => $v)
			{
				$isAssoc = !is_numeric($k);
				
				$code .= ( $o['inline'] || (!$isAssoc && count($propValue) < 4) ) 
							? ( $i !== 0 ? " " : '') 
							: $o['lb'] . $o['tab'];
				$code .= !$isAssoc ? "'" . $v . "'" : "'" . $k . "' => '" . $v . "'";
				$code .= ',';
				$i++;
			}
			$code .= "),";
			
			$code .= $o['lb'];
		}
		
		$code .= ');' . $o['lb'] . '?>';
		
		return $code;
	}
	
	public function generateGroups()
	{
		$lb 			= "\n";
		$tab 			= "\t";
		$code 		= '<?php' . $lb . $lb . '$_groups = array(' . $lb;

		// TODO

		$code .= ');' . $lb . '?>';
		
		return $code;
	}
	
	public function generateColumns()
	{
		$lb 			= "\n";
		$tab 			= "\t";
		$code 			= '<?php' . $lb . $lb . '$_columns = array(' . $lb;
		
		// Build an array or resource names only (for perf issues)
		$resNames 		= array_keys(self::$columns);
		
		// Get the longest resource name and use it to get position used to verticaly align the resource code (indentation)
		$longRes 		= Tools::longestValue($resNames);
		$resVertPos 	= strlen($longRes) + ( 4 - (strlen($longRes) % 4) );
				
		// Loop over the resources columns
		foreach ( $resNames as $rName )
		{
			// Shortcut for resource name & resource cols
			//$rName 		= &self::$resources[$rKey]['name'];
			$rCols 		= &self::$columns[$rName]['items'];
			
			// Do not continue if the resource has no columns
			//if ( !$rCols ){ continue; }
			
			// Get the resource columns names
			$rColNames 	= array_keys((array) $rCols);
			
//var_dump($rKey);
//var_dump($rCols);

//var_dump($rColNames);
//Core::dump('here1');			
//Core::dump(self::$columns);
//$this->log(self::$columns);
//die();
//continue;
			
			// Get the longer column name
			$longCol 		= empty($rColNames) ? '' : Tools::longestValue($rColNames);
			$colVertPos		= strlen($longCol) + ( 4 - (strlen($longCol) % 4) );
			
			$code .= "'" . $rName . "' => array(" . $lb;
			$code .= $tab . "'items' => array(" . $lb;
			
			/*
			// Loop over the resource columns
			foreach ( $rColNames as $cName )
			{
				// Shortcut for column properties
				$cProps = &$rCols[$cName];
				
				$colTabs = ($colVertPos - (strlen($cName)+3))/4;
				$tabs = '';
				for($i=0; $i<$colTabs; $i++){ $tabs .= $tab; }
				
				$code .= $tab . "'" . $cName . "' " . $tabs . "=> array(" . ( $o['inline'] ? '' : $o['lb'] );
				
				// Loop over the columns properties
				foreach (array_keys((array) $cProps) as $pName)
				{
					$pValue = &$cProps[$pName];
					
					$code .= "'" . $pName . "' => "; 
					
					if ( is_null($pValue) )
					{
						$code .= "null";
					}
					else if ( is_bool($pValue) )
					{
						$code .= $pValue ? "true" : "false"; 
					}
					else if ( is_numeric($pValue) )
					{
						$code .= $pValue;
					}
					else
					{
						$code .= "'" . $pValue . "'";
					}
					
					$code .= ", ";
				}
				
				$code .= ")," . $lb;
			}*/
			
			$cProps = array_keys(self::$columns[$rName]);
			
var_dump($cProps);
			
			/*
			foreach($cProps as $propName)
			{
				// Skip 'items'
				if ( $propName === 'items' ){ continue; }
				
				$propValue = self::$columns[$rName][$propName];
				
				$tabs 	= '';
				
				//$code .= "'" . $propName . "' " . $tabs . "=> " . ( $o['inline'] ? '' : $o['lb'] );
				$code .= "'" . $propName . "' " . $tabs . "=> ";
				
				// TODO: do we need to handle others than array values?
				$code .= "array(";
				$i = 0;
				foreach ($propValue as $k => $v)
				{
					$isAssoc = !is_numeric($k);
					
					$code .= ( $o['inline'] || (!$isAssoc && count($propValue) < 4) ) 
								? ( $i !== 0 ? " " : '') 
								: $o['lb'] . $o['tab'];
					$code .= !$isAssoc ? "'" . $v . "'" : "'" . $k . "' => '" . $v . "'";
					$code .= ',';
					$i++;
				}
				$code .= "),";
				
				$code .= $o['lb'];
			}*/
			
			$code .= $tab . ")," . $lb;
			$code .= ")," . $lb;
		}
		
		$code .= ');' . $lb . '?>';
		
var_dump($code);
die();
		
		return $code;		
	}
	
	
	// Checks that a resource exists
	static function isResource($string)
	{
		global $_resources;

//var_dump(__METHOD__);		
//var_dump($string);
//var_dump($_resources);
//die();
		
		//return !empty(self::$resources[$string]);
		//return !empty($_resources[(string) $string]);
		return !empty($_resources['items'][(string) $string]);
	}
	
	// Search for a mispelled resource
	static function searchResource($name)
	{
		// Do not continue any longer if the $name as is is an existing resource
		if 	( self::isResource($name) ){ return $name; }
		
//var_dump(__METHOD__ . ' -> ' . $name);
		// Otherwise, try to get its singular & plural forms
		$sing = Tools::singular($name);
		$plur = Tools::plural($name);
		
//var_dump('sing' . ' : ' . $sing);
//var_dump('plur' . ' : ' . $plur);
		
		// Check if any of them is a resource
		if 		( self::isResource($sing) ){ return $sing; }
		elseif 	( self::isResource($plur) ){ return $plur; }
		
		// TODO
		// Compare string with resource name and return if matching is XX%?
		// using levenshtein()
		
		// If not found at this point, return false
		return false;
	}
	
	static function columns($resource)
	{
//var_dump(__METHOD__);
//$this->log(__METHOD__);
		global $_columns;
		
//var_dump('resource: ' . $resource);
//$this->log('resource: ' . $resource);
//var_dump($_columns);
//die();
		
		return isset($_columns[$resource]['items']) ? $_columns[$resource]['items'] : false;
	}
	
	static function resource($string)
	{
		global $_resources;
		
		//return self::isResource($string) ? $_resources[$string] : false;
		//return self::isResource($string) ? $_resources['items'][$string] : false;
		return isset($_resources['items'][$string]) ? $_resources['items'][$string] : false;
	}
	
	static function resources()
	{
		global $_resources;
		
		return $_resources;
	}
	
	/*
	static function resourceProp($resource, $prop)
	{
		global $_resources;
		
		return isset($_resources['items'][$resource][$prop]) ? $_resources['items'][$resource][$prop] : null;		
	}*/
	
	
	// Checks that a column existing in a given resource
	static function isColumn($resource, $column)
	{
		global $_columns;
		
//var_dump(__METHOD__);
//var_dump($resource);
//var_dump($column);
//var_dump($_columns);
		
//var_dump(__METHOD__ . " -> $resource.$column");
		
		//return !empty($_columns[$resource]['items'][$colName]);
		return isset($_columns[$resource]['items'][$column]);
	}
	
	// Returns the singular of a resource
	static function singular($resource)
	{
		//return self::isResource($resource) ? self::$resources[$resource]['singular'] : false;
		return self::isResource($resource) ? $_resources[$resource]['singular'] : false;
	}
	
	
	static function guessResourceType($resource)
	{
		// Default type
		$type = 'native';
		
		// Split the name on the '_'
		$parts 		= explode('_', $resource);
		
		// Check if contains name of 2 resources
		// if (  ){ $type = 'relation'; }
		
		return $type;
	}
	
	
	static function getDbTableName($resource)
	{
		$tableName = null;
		
		// If the resource is not found
		if ( !self::isResource($resource) ) { return $tableName; }
			
		// Assume default table name is the resource name
		$tableName = $resource;
		
		// For relation resources, create names like '{$resource1}_{$resource2}' 
		if ( self::$resources[$resource]['type'] === 'relation' )
		{
			// TODO
		}
		
		return $tableName;
	}
	
	
	static function getDbTableAlias($resource)
	{
		$alias = null;
		
		// If the resource is not found
		if ( !self::isResource($resource) ) { return $tableAlias; }
		
		// Shortcut to resource properties
		//$rProps = &$_resources['items'][$resource];
		$rProps = self::resource($resource);
		
		// For relation resources, create names like '{$resource1}_{$resource2}' 
		//if ( self::$resources[$resource]['type'] === 'relation' )
		
		// admin logs 		=> admlog 		(compouned word ==> concat both aliases)
		// bans 			=> ban 			(> singular 4 chars ==> |singular)
		// groups 			=> grp|gp 		(default? ==> |singular|consonants)
		// users 			=> usr 			(default? ==> |singular|consonants)
		// sessions 		=> sess 		((|singular|consonants).match(/^([bcdfghjklnpqrstvwwxyz]{1}\1)/) ==> |substr(0,4))
		// resources 		=> ress
		// users groups 	=> usrgp 		(compouned word ==> concat both aliases)
		// tasks 			=> task 		(default? ==> |singular|consonants)
		
		// products 		=> prod|prdc
		// medias 			=> med|media
		// applications 	=> app|applctn
		// platforms 		=> plat|pltfrm
		
		// Possible rules
		// - 2 first consonants are equals
		
		// Possible values
		// - contatenation of the aliases of all words
		// - singular without vowels
		$sing 	= $rProps['singular'];
		$dName 	= $rProps['displayName'];

		// The resource is relation table
		// or 
		// it's name is a compound word
		if ( $rProps['type'] === 'relation' || preg_match('/\s+/', $dName) )
		{
			// Foreach part
			foreach(explode(' ', Tools::singular($dName)) as $part)
			{
				if ( preg_match('/[aeiou]/', $part[0]) )
				{
					$alias .= substr($part[0] . Tools::consonants($part), 0, 3);
				}
				else
				{
					$alias .= substr(Tools::consonants($part), 0, 3);	
				}
			}
		}
		// Singular is < 4 chars
		// or
		// Vowels free string starts by 2 identic chars
		elseif ( strlen($sing) <= 4 || preg_match('/([bcdfghjklmnpqrstvwxyz]){1}\1/', Tools::consonants($sing)) )
		 {
		 	$alias = substr($sing, 0, 4);
		 }
		// Otherwise, fallback simply removing the vowels & trimming to 5 cars
		else 											{ $alias = substr(Tools::consonants($sing), 0, 5); }
		
//var_dump($alias);
//die();
		
		// TODO: check to the alias is not already in use
		
		return $alias;
	}
	
	
	static function guessNameField($resource)
	{
		$nameField = null;
		
		// TODO
		// Get the first unique index char field
		// Get the first defined char field
		
		return $nameField;
	}
	
	
	static function guessAlias($resource)
	{
		// TODO: use resource resource table
		//$table = self::$resources[$resource]['table'] ? self::$resources[$resource]['table'] : self::getDbTableName($resource); 
		//$table = self::$resources[$resource]['table'] ? self::$resources[$resource]['table'] : self::guessDbTableName($resource);
		$table = $resource;
		
		// Split the resource name on _ chars
		$parts = explode('_', $table);
		
		// Force parts to be an array
		$parts = is_array($parts) ? $parts : $table;
		
		// ex: 			user_medias => um (if not already in use)
		// otherwise: 	user_medias => usr_md
		
		// 1st possibility: get the first char of every part of the name
		$poss1 = '';
		foreach ( $parts as $v ){ $poss1 .= $v{0}; }
		
		// 2nd possibility: get only the vowels of every part of the name
		$poss2 = '';
		foreach ( $parts as $v ){ $poss2 .= Tools::consonants($v); }
		
		// 3rd possibility: use the full resource name
		$poss3 = $resource;
		
		return !empty(self::$resources['_aliases'][$poss1]) 
				? $poss1 
				: ( !empty(self::$resources['_aliases'][$poss2]) ? $poss2 : $poss3 );
	}
	
	
	// Try to gess column type using it's name
	static function guessColumnType($colName)
	{
//Core::log(__METHOD__ . ' -> ' . $colName);

		// Init type
		$type 		= null;
		
		# TODO: Check the column name as is against common naming patterns
		
		// Check if the column name as is is an existing type
		if ( isset(self::$columnTypes['realtypes'][$colName]) ){ return $colName; }
		
		// Split the name on the '_'
		$parts 		= explode('_', $colName); 
		
		// Loop over each parth of the word
		foreach ( (array) $parts as $part )
		{
//var_dump($part);
			
			$sing 			= Tools::singular($part);
			$plur 			= Tools::plural($part);
			
			// Check if is an existing resource
			//$isResource 	= self::isResource($sing) || self::isResource($plur);
			$relResource 	= self::searchResource($part);
			$isResource 	= !!$relResource;
			
			// If next part is an existing colum of this resource
			$next = next($parts);
//var_dump('next: ' . $next);
			$isColumn 	= $isResource && self::isColumn($relResource, $next);
//var_dump('relResource:' . $relResource);
//var_dump('isResource: ' . (int) $isResource);
//var_dump('isColumn: ' . (int) $isColumn);
			
			if 		( $isResource && $isColumn )	{ $type = 'onetoone'; break; }
			else if ( $isResource && !$isColumn )	{ $type = 'manytoone'; break; }

			// If resource && resource not current one, assume it's a relation
			
			// Otherwise, compare agains common used naming patterns 
			// 'name$' 				=> 'string' + length 64
			// '_name' 				=> 'string' + length 64
			// 'title$' 			=> 'string'
			// '_title' 			=> 'string'
			// 'color' 				=> 'color'
			
			// slug 				=> 'slug'
			
			// '_url' 				=> 'url'
			// 'url_' 				=> 'url'			
			// _uri 				=> 'url'
			
			// 'phone' 				=> 'tel'
			// 'mobile' 			=> 'tel'
			
			// is_ 					=> 'boolean'
			// has_ 				=> 'boolean'
			
			// 'mail_'
			// '_mail' 				=> 'email'
			// '_id' 				=> 'onetoone'
			//
			
			// '_nb' 				=> 'integer'
			// '_number' 			=> 'integer'
			// '_count' 			=> 'integer'
			// '_age' 				=> 'integer' + length = 3
			
			// 'id' 				=> 'pk'
			
			// '_date' 				=> 'date'
			// '_at'
			// 'creation_date' 		=> 'timestamp'
			// 'created_' 			=> 'timestamp'
			// 'update_date' 		=> 'timestamp'
			// 'updated_at' 		=> 'timestamp'
			
			// 'text' 				=> 'text'
			// 'summary' 			=> 'text',
			// 'description' 		=> 'text',
			// 'desc' 				=> 'text',
			
			// ip 					=> 'ip'
			
			// TODO
			// length, _len, 
			// time, _time, year, _year, month, _month, day, _day, hour, _hour, minutes, _minutes, seconds, _seconds
			// amout, price => floats
		}

		// Return found type if any or default it to string
		return $type !== null ? $type : 'varchar';
	}

	public static function guessRelatedResource($colName)
	{
//var_dump(__METHOD__ . ' -> ' . $colName);
		// Default return value to false (nothing found)
		$ret 			= false;
		
		// Check if the column as is is an existing resource
		$relResource 	= self::searchResource($colName);
		
		// If yes, just returns
		if ( !empty($relResource) ) { return $relResource; }
		
		// Otherwise, split on '_' chars
		$parts = explode('_', $colName);
		
		// Loop over the parts
		$concat = '';
		foreach ($parts as $part)
		{
			// Check if the concatenation of the previous parts with the current one is a resource
			$ret = self::searchResource($concat . $part);
			
			// If yes, just returns
			if ( !empty($ret) ) { break; } 
			
			// Check if the current part is an existing resource
			$ret = self::searchResource($part);
			
			// If yes, just returns
			if ( !empty($ret) ) { break; }
			
			// If a resource as not already been found 
			$concat .= $part;
		}
		
//var_dump($ret);
		
		return $ret;	
	}
	
	static function getColumn($resource, $column)
	{
//var_dump(__METHOD__);
//$this->dump(__METHOD__);
		
		return $_columns[$resource][$column];
	}

	static function getColumnType($resource, $column)
	{
//var_dump(__METHOD__);
//$this->dump(__METHOD__);
		
		return $_columns[$resource][$column]['type'];
	}
	
	static function getColumnRealType($resource, $column)
	{
//var_dump(__METHOD__);
//$this->dump(__METHOD__);
		
		return $_columns[$resource][$column]['realtype'];
	}
	
	
	// TODO
	static function validate($value, $params = array())
	{
		
	}

	// TODO
	static function sanitize($value, $params = array())
	{
		$p = array_merge(array(
			'type' => 'string'
		), $params);

		// ints
		if ( in_array($p['type'], array('int', 'integer', 'numeric', 'tinyint', 'smallint', 'mediumint', 'bigint')) )
		{
			$value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
			//$value = intval($value);
		}
		// floats
		elseif ( in_array($p['type'], array('float', 'real', 'double')) )
		{
			$value = floatval($value);
		}
		// uri
		// url
		// mail
		// ... 
		// phone number
		else if ( $p['type'] === 'tel' )
		{
			$value = preg_replace('/\D/', '', $value);
		}
		// TODO: all other types
		else
		{
			$value = filter_var($value, FILTER_SANITIZE_STRING);
		}
		
		return $value;
	}
}

?>