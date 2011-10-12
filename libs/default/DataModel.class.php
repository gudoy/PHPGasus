<?php

( isset($_resources) && isset($_columns) ) || require(_PATH_CONFIG . 'dataModel.generated.php');

//class DataModel extends Core
class DataModel
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
		//'defaultNameField', 					// Deprecated: use nameField instead 
		
		# Database
		'database', 'table', 'alias',
		
		# Relations
		'extends', 								// default = null
		'parent', 								// default = null
		'parents',  							// default = array()
		'siblings',  							// default = array()
		'children', 							// default = array()
		'related', 								// parent + siblings + children
		// use relations instead of parent/siblings/children??????
		// 'relations'  	=> array('oneToOne','oneToMany','manyToOne', 'manyToMany')
		
		# PHPGasus features related
		'order', 'importance', 					// TODO: implement
		'searchable', 'crudability', 'exposed',
		
		# Generated
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
			'tag', 
			'text', 'html', 'code',
			
			# Numbers
			'int', 'integer', 'numeric',
			'tinyint', 'smallint', 'mediumint', 'bigint',
			'float', 'real', 'double',
			
			# Booleans
			'bool','boolean',
			
			# Dates & times
			'timestamp', 'datetime', 'date', 'time', 'year', 
			//'month', 'week', 'day', 'hour', 'minutes', 'seconds', 
			
			# Relations
			'1-1', 'onetoone', 'one2one', '121', '1to1', '12one',
			'1-n', 'onetomany', 'one2many', '12n', '1ton', '1tomany', '12many',  
			'n-1', 'manytoone', 'many2one', 'n21', 'nto1', 'manyto1', 'many21',
			'n-n', 'manytomany', 'many2many', 'n2n', 'nton',
			
			# media
			'file', 'image', 'video', 'sound', 'file',
			
			# Misc
			'pk', 'id', 'serial',
			'enum', 'choice', 'set',
		),
		'realtypes' => array(
			# Texts
				// strings (length=255) 
				'string' 		=> 'string', 		// + length = 255
				'varchar' 		=> 'string', 		// alias of string
				'slug' 			=> 'string', 		// + length = 64
				//'tag' 			=> 'string', 		// alias of slug
				'email' 		=> 'string', 		// + validator pattern
				'password'		=> 'string', 		// + modifiers = sha1 + length(will depend of the algorithm)
				'url' 			=> 'string', 		// + max = 2083 + FILTER_VALIDATE_URL?
				'tel' 			=> 'string', 		// + length = 20???, + pattern ? 
				'color'			=> 'string', 		// + length = 32, + validator pattern (#hex, rgb(), rgba(), hsl(), ... ?)
				'meta' 			=> 'string',
				'ip' 			=> 'string', 		// + length = 40 + FILTER_VALIDATE_IP, ? 
				
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
				
				'tinyint' 		=> 'tinyint', 		// + min = -128, + max = 128 
				'smallint' 		=> 'smallint', 		// + min = -32768, + max = 32768
				'mediumint' 	=> 'mediumint', 	// + min = -8388608, + max = 8388608
				'bigint' 		=> 'bigint', 		// + min = -9223372036854775808, + max = 9223372036854775808
				
				// floats
				'decimal' 		=> 'float',
				'float' 		=> 'float',
				'real' 			=> 'float',
				'double'		=> 'float',		
				
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
				
				'onetoone' 		=> 'int', 			// + fk = 1 + handle relations props
			
			# Misc				
				'pk' 			=> 'int', 			// Pk + length = 11, pk = 1, editable = 0 
				'id' 			=> 'int', 			// Pk + length = 11, pk = 1, editable = 0
				'serial' 		=> 'int', 			// Pk + length = 11, pk = 1, editable = 0
		),
	);
	
	// TODO: $propName => array('default' => null|[true|false]|{$value}, 'comment' => null, 'deprecated' => true|false)
	static $columnProperties 				= array(
		# PHPGasus meta
		'type', 'from',
		'exposed',
		'importance',
	
		# SQL related
		'realtype',
		'length',
		'values',
		'possibleValues', 						// Deprecated: use values instead
		'null', 'default',
		'fk',
		'pk', 'ai',  
		'unsigned', 'unique', 'index', 			// TODO: implement
		
		# Relations
		//'on 									// TODO: ???
		'relResource', 							// TODO: ??? replace by on, to, or from????
		'relField', 							// Deprecated: use relColumn instead
		'relColumn', 							// TODO: implement 
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
		
		// Files related
		'forceUpload', 
		'storeOn',  							// TODO????? (ftp|amazon_s3|amazon_ec2) 
		'acl', 									// TODO????? (S3_ACL_PRIVATE, S3_ACL_PUBLIC, S3_ACL_OPEN, S3_ACL_AUTH_READ. default = S3_ACL_PRIVATE)
		'destRoot', 'destName', 'destFolder', 	// TODO?????
		
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
		$params = array_merge(array(
			'what' => 'resources,colums,groups',
		), $params); 
		
		$dir = 'config/dataModel/';
		
		// Create a zip archive, open it, create the proper folder and create the file
		$zipFile 	= tempnam('tmp', 'zip');
		$zip 		= new ZipArchive();
		$zip->open($zipFile, ZipArchive::OVERWRITE);
		$zip->addEmptyDir($dir);
		
		$files = Tools::toArray($params['what']);
		
		foreach( $files as $name)
		{
			$mthd = 'generate' . ucfirst($name);
			$zip->addFromString($dir . $name . '.php', self::$mthd());
		}
		$zip->addFromString($dir . 'resources.php', self::generateResources());
		$zip->addFromString($dir . 'columns.php', self::generateColumns());
		$zip->addFromString($dir . 'groups.php', self::generateGroups());
		
		$zip->close();
		
		// Stream the file to the client
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize($zipFile));
		header('Content-Disposition: attachment; filename="[' . _APP_NAME . ']_' . 'dataModel.zip"');
		readfile($zipFile);
		unlink($zipFile);
		
		//$this->generate();
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
			),
			'varname' 		=> '_resources',
			'checkDatabase' => true,
		), $params);
		
		$tmpRes = array();
		
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
		self::$resources = array(
			'items' 		=> array(),
			'_aliases' 		=> array(),
			'_searchable' 	=> array(),
			'_exposed' 		=> array(),
		);
		
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
				'displayName' 		=> !empty($res['displayName']) ? $res['displayName'] : $name,
				'nameField' 		=> !empty($res['nameField']) ? $res['nameField'] : ( !empty($res['defaultNameField']) ? $res['defaultNameField'] : self::guessNameField($name) ),
				'extends' 			=> !empty($res['extends']) ? $res['extends'] : null,
				'searchable' 		=> $searchable,
				'exposed' 			=> $exposed,
				'crudability' 		=> !empty($res['crudability']) ? $res['crudability'] : 'CRUD',
				
				// Generated properties
				'_exposedColumns' 		=> array(), // will be populated by parseColumns() 
				'_searchableColumns' 	=> array(), // will be populated by parseColumns()
			);
			
			self::$resources['_aliases'][$alias] = $realName;
			
			// 
			if ( $searchable )	{ self::$resources['_searchable'][] = $realName; }
			if ( $exposed )		{ self::$resources['exposed'][] 	= $realName; }

			ksort($res);
		}

		// Sort resources by alphabetical order
		asort(self::$resources);
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
			),
			'varname' 		=> '_columns',
			'checkDatabase' => true,
		), $params);
		
		$tmpColumns = array();
		
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
		self::$columns = array(
			'items' 		=> array(),
			'_exposed' 		=> array(),
			'_searchable' 	=> array(),
		);
		
		// Loop over the resources
		foreach ( array_keys((array) $tmpColumns) as $rName )
		{			
			// Shortcut for current resource columns
			$rCols = &$tmpColumns[$rName];
			
			// Loop over the columns of the current resource
			$i 		= 0;
			$colsNb = count($rCols); 
			foreach ( array_keys((array) $rCols) as $cName )
			{
				// Shortcut for defined column properties
				//$p 		= &$rCols[$cName];
				// First step: takes the defined properties as is (just remove unknown properties)
				$p = array_intersect_key((array) $rCols[$cName], (array) array_flip((array) self::$columnProperties));
				
				// Shortcut for final column properties
				$cProps = &self::$columns['items'][$rName][$cName];
//var_dump($p);
//var_dump('-----------: ' . $cName);
				
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
					}
					
					// Handle 'from'
					elseif ( $k === 'from' )
					{
						//$cProps['from'] = isset($p['from']) && self::isColumn($rName, $p['from']) ? $p['from'] : null;	
						$cProps['from'] = isset($p['from']) ? $p['from'] : null;
					}
					
					// Handle 'exposed'
					elseif ( $k === 'exposed' )
					{	
						$cProps['exposed'] = isset($p['exposed']) && $p['exposed'] ? true : false;
					}
					
					// Handle 'importance'
					elseif ( $k === 'importance' )
					{	
						$cProps['importance'] = isset($p['importance']) ? intVal($p['importance']) : 0;
					}
	
					# SQL related
					// Handle 'realtype'
					elseif ( $k === 'realtype' )
					{
						$cProps['realtype'] = isset($p['realtype']) && in_array($p['realtype'], self::$columnTypes['realtypes']) 
											? $p['realtype'] 
											: self::$columnTypes['reatype'][$cProps['type']];
					}
					
					// Handle 'length'
					elseif ( $k === 'length' )
					{
						$cProps['length'] = isset($p['length']) ? intVal($p['length']) : null;
						
						// Do not continue if the length has been defined
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
							default: 			$cProps['length'] = 11; break;							
						}
					}
					
					// Handle 'possibleValues'
					elseif ( $k === 'possibleValues' )
					{
						$cProps['values'] = isset($p['possibleValues']) ? $p['possibleValues'] : null;
					}
					
					// Handle 'values'
					elseif ( $k === 'values' )
					{
						$cProps['values'] = isset($p['values']) ? $p['values'] : null;
						
						// TODO: if type accept several values, transform into an array
						if ( in_array($cProps['type'], array('set','enum','choice')) ){ $cProps['values'] = Tools::toArray($cProps['values']); }
					}
					
					// Handle 'null'
					elseif ( $k === 'null' )
					{
					}
					
					// Handle 'default'
					elseif ( $k === 'default' )
					{
					}
				}
				
				/*
				// Force booleans
				$cProps['pk'] = $cProps['pk'] ? true : false;
				$cProps['ai'] = $cProps['ai'] ? true : false;
				
				### Now, we can start to do some magic
				
				// Handle Numeric types
				if ( $p['type'] === 'varchar' )
				{
					
				}
				// Handle Numbers types
				elseif ( $p['type'] === 'int' )
				{
					// has AI && is first
					if ( isset($p['AI']) && $i === 0 )
					{
						$p['type'] = 'serial';
					}	
				}*/
				
				/*				
				# Type
				$type 			= isset($p['type']) && in_array(strtolower($p['type']), $known['types']) ? strtolower($p['type']) : 'string';
				if 		( $cName === 'id' ) 	{ $type = 'pk'; $ai = 1; $pk = 1; }
				elseif 	( $cName === 'slug' ) 	{ $type = 'slug'; }
				
				# Length
				$length 		= isset($p['length']) && ( is_numeric($p['length']) || is_null($p['length']) )? $p['length'] : 'null';
				if ( $realtype === 'string' )
				{
					if 		( $type === 'slug' ) 	{ $length = 64; }
					elseif 	( $type === 'color' ) 	{ $length = 32; }
					else 							{ $length = 255; }
				}
				elseif ( $realtype === 'integer' )
				{
					$length = 11;
				}
				if ( $pk ) { $length = 11; }
				*/
					
				$i++;
			}

var_dump($rName);
var_dump(self::$columns['items'][$rName]);
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
	
	public function generateResources()
	{		
		$lb 			= "\n";
		$tab 			= "\t";
		$code 			= '<?php' . $lb . $lb . '$_resources = array(' . $lb;
		$longer 		= null;
		
		/*
		$rPropNames 	= array(
			// Semantic props
			'type', 'singular', 'plural', 'displayName',
			// Database binding props
			'database', 'table', 'alias',
			// Relation props
			'defaultNameField', 'nameField', 'extends',
			// Misc props
			'searchable', 'exposed', 'crudability'
		);*/
		
		// Build an array or resource names only (for perf issues)
		$resNames 		= array_keys(self::$resources);
		
		// Get the longest resource name and use it to get position used to verticaly align the resource code (indentation)
		$longRes 		= Tools::longestValue($resNames);
		$resVertPos 	= strlen($longRes) + ( 4 - (strlen($longRes) % 4) );
		
		// Get the longest resource propery name
		//$longProp 		= Tools::longestValue($rPropNames);
		$longProp 		= Tools::longestValue(self::$resourceProperties);
		$propVertPos	= strlen($longProp) + ( 4 - (strlen($longProp) % 4) );
		
		// Loop over the resources
		foreach ( array_keys((array) self::$resources) as $rName )
		{
			// Shortcut for resource props
			$p 	= &self::$resources[$rName];
			
			// Calculate the number of tabs required for proper vertical alignement of the current resource
			//$tabsCnt = floor(($resVertPos - strlen($rName) / 4));
			$resTabs = ($resVertPos - (strlen($rName)+3))/4;
			//$resTabs = ( $resTabs < 1 ) ? 1 : ( ( $resTabs - ceil($resTabs) < 0.5 ) ? ceil($resTabs) : floor($resTabs) );
			$tabs = '';
			//for($i=0; $i<$resTabs; $i++){ $tabs .= $tab; }
			
			$code .= "'" . $p['name'] . "' " . $tabs . "=> array(" . $lb;
			
			foreach (self::$resourceProperties as $propName)
			{
				// TODO: Calculate the number of tabs required for proper vertical alignement of the current property
				//$tabsCnt = floor(($resVertPos - strlen($rName) / 4));
				$propTabs = ($propVertPos - (strlen($propName)+3))/4;
				//$propTabs = ( $propTabs < 1 ) ? 1 : ( ( $propTabs - ceil($propTabs) < 0.5 ) ? ceil($propTabs) : floor($propTabs) );
				$tabs = '';
				for($i=0; $i<$propTabs; $i++){ $tabs .= $tab; }
				
				$code .=  $tab . "'" . $propName . "' " . $tabs . "=> ";
				
				// Boolean props
				if ( in_array($propName, array('searchable','exposed')) ) 	{ $code .= $p[$propName] ? 'true' : 'false'; }
				// Defined string of default to null props
				elseif ( in_array($propName, array('extends','database')) ) { $code .= !empty($p[$propName]) ? "'" . $p[$propName] . "'" : 'null'; }
				// Default: string props
				else 														{ $code .= "'" . $p[$propName] . "'"; }
				
				$code .=  "," . $lb;
			}
			
			$code .= ")," . $lb;
		}
		
		$code .= ');' . $lb . '?>';
		
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
				
		// Loop over the resources columns
		foreach ( array_keys((array) self::$resources) as $rKey )
		{
			// Shortcut for resource name & resource cols
			$rName 		= &self::$resources[$rKey]['name'];
			$rCols 		= &self::$columns[$rName];
			
			// Get the resource columns names
			$rColNames 	= array_keys((array) $rCols);
			
			// Get the longer column name
			$longCol 		= Tools::longestValue($rColNames);
			$colVertPos		= strlen($longCol) + ( 4 - (strlen($longCol) % 4) );
			
			$code .= "'" . $rName . "' => array(" . $lb;
			
			// Loop over the resource columns
			foreach ( $rColNames as $cName )
			{
				// Shortcut for column properties
				$cProps = &$rCols[$cName];
				
				$colTabs = ($colVertPos - (strlen($cName)+3))/4;
				$tabs = '';
				for($i=0; $i<$colTabs; $i++){ $tabs .= $tab; }
				
				$code .= $tab . "'" . $cName . "' " . $tabs . "=> array(";
				
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
			}
			
			$code .= ")," . $lb;
		}
		
		$code .= ');' . $lb . '?>';
		
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
		// TODO
		// Compare string with resource name and return if matching is XX%?
		
		return $false;
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
	
	
	// Checks that a column existing in a given resource
	static function isColumn($resource, $string)
	{
		global $_columns;
		
		//return !empty(self::$columns[$resource][$string]);
		//return !empty($_columns[$resource][$string]);
		return !empty($_columns['items'][$resource][$string]);
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
		// Split the name on the '_'
		$parts 		= explode('_', $colName);
		
		foreach ( (array) $parts as $part )
		{
			$sing = Tools::singular($part);
			$plur = Tools::plural($part);
			
			// Check if is an existing resource
			$isResource = self::isResource($sing) || self::isResource($plur);
			
			// If resource && resource not current one, assume it's a relation
			
			// [default] 			=> 'string',
			
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
	}

	static function getColumnType($resource, $column)
	{
var_dump(__METHOD__);
		
		return $_columns[$resource][$column]['type'];
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