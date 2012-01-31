<?php

class SQLModel extends Model
{
	public $queryString = '';
	
	public $uniqueOperators 	= array('>','<','>=','<=');             			// Operator whose value can only by unique
	public $oneAtATimeOperators = array('LIKE','NOT LIKE','=','!=','SOUNDS LIKE'); 	// Operators allowing multiple conditions but with only 1 at a time
	public $knownOperators 		= array( 											// Known condition operators
		'contains' 			=> 'LIKE',          // + %value% // TODO
		'like' 				=> 'LIKE',          // + %value% // TODO
		'doesnotcontains' 	=> 'NOT LIKE',      // Deprecated: typo mistake
		'doesnotcontain' 	=> 'NOT LIKE',      // + %value% // TODO
		'notlike' 			=> 'NOT LIKE',      // + %value% // TODO
		'startsby' 			=> 'LIKE',          // + value% // TODO
		'endsby' 			=> 'LIKE',          // + %value // TODO
		'doesnotstartsby' 	=> 'NOT LIKE',      // Deprecated: typo mistake
		'doesnotstartby' 	=> 'NOT LIKE',      // + value% // TODO
		'doesnotendsby' 	=> 'NOT LIKE',      // Deprecated: typo mistake
		'doesnotendby' 		=> 'NOT LIKE',      // + %value // TODO
		'not' 				=> '!=',
		'notin' 			=> 'NOT IN',
		'greater' 			=> '>',
		'>' 				=> '>',
		'lower' 			=> '<',
		'<' 				=> '<',
		'greaterorequal' 	=> '>=',
		'>=' 				=> '>=',
		'lowerorequal' 		=> '<=',
		'<=' 				=> '<=',
		'is' 				=> '=',
		'equal' 			=> '=',
		'=' 				=> '=',
		'in' 				=> 'IN',
		'isnot' 			=> '!=',
		'notequal' 			=> '!=',
		'!=' 				=> '!=',
		'notin' 			=> 'NOT IN',
		'between' 			=> 'BETWEEN',       // TODO
		'notbetween' 		=> 'NOT BETWEEN', 	//
		'soundslike' 		=> 'SOUNDS LIKE',
		'match' 			=> 'MATCH', 		// + AGAINST(). Only for MyISAM tables
		'search' 			=> 'MATCH', 		// + AGAINST(). Only for MyISAM tables
	);
	
	public function find()
	{
//var_dump($query);
$this->log(__METHOD__);
		$args = func_get_args();
		$this->handleOptions($args);

		$this->buildQuery();
		$this->query();
		
		return $this->data;
	}
	
	
	public function setEncoding()
	{
		// Tell mysql we are sending already utf8 encoded data
		$this->db->query("SET NAMES 'UTF8'");
	}
	
	public function query(array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// If the connection is not opened, open it
		if ( !$this->db ){ $this->connect(); }
		
		// Extends default params by passed ones
		$p = array_merge(array(
			'type' 		=> 'select',
			'prepared' 	=> false,
		), $params);
		
		// TODO: handle prepared queries
		
		$this->doQuery($p);
		
		//return $this->data;
	}
	
	public function doQuery(array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$p = &$params;

		// Log launched query
		// $this->log($query);
		// $this->logs['launched'][] = $query;
		
		$this->results = $this->db->query($this->queryString);
		
		$this->success = is_bool($this->results) && !$this->results ? false : true;
		
		$this->handleResults();
	}
	
	
	public function handleResults(array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Do not continue if the request did not returned results
		if ( !$this->success ){ return; }
		
		$p = &$params; 
    	
		$this->numRows();
		$this->numFields();
		
//var_dump($this->numRows);
$this->log('numrows: ' . $this->numRows);
//var_dump($this->numFields);
$this->log('numfields: ' . $this->numFields);
		
		if ( $p['type'] === 'insert' )
		{
			$this->getinsertedId();
			$this->affectedRows();
		}
		elseif ( $p['type'] === 'udpate' )
		{
			$this->affectedRows();
		}
		
		$this->fetchResults();
	}
	
	public function affectedRows()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		$this->affectedRows = $this->success ? $this->db->affected_rows : null; 
	}
	
	public function numRows()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$this->numRows = is_object($this->results) ? $this->results->num_rows : null;
	}
	
	public function numFields()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		$this->numFields = is_object($this->results) ? $this->results->field_count : null;
	}
	
	public function insertedId()
	{
		$this->insertedId = $this->success ? $this->db->insert_id : null;
	}
	
	public function fetchResults()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
//var_dump($this);
//die();

		// TODO: use requested cols/rows number to decide which method to use to fetch results???
		# Conditions are in order of use frequency
		
		// X row, X cols
		if ( $this->numRows > 1 ) 									{ $this->fetchRows(); }
		// 1 row, 1 col
		elseif ( $this->numRows === 1 && $this->numFields === 1 )	{ $this->fetchCol(); }
		// 1 row, X cols
		elseif ( $this->numRows === 1 && $this->numFields > 1 ) 	{ $this->fetchRow(); }
		// X row, 1 col
		elseif ( $this->numRows > 1 && $this->numFields > 1 )		{ $this->fetchCols(); }
		
//var_dump($this->data);
$this->log($this->data);
	}
	
	public function getResources()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$this->data['resources'] = $this->query('SHOW TABLES');
	}
	
	
	public function buildQuery(array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		$p = array_merge(array(
			'type' => 'select',
		), $params);
		
		switch ($p['type'])
		{
			case 'insert': 	$this->buildInsert(); break;
			case 'update':	$this->buildUpdate(); break;
			case 'delete': 	$this->buildDelete(); break;
			
			// TODO
			//case 'upsert': 	$this->buildUpsert(); break;
			
			case 'select': 
			default: 		$this->buildSelect(); break;
		}
		
$this->log('builtQuery: ' . $this->queryString);
	}
	

	public function buildSelect(array $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$qp 	= &$this->queryPlan; 
		
		// Build final query  
		$this->queryString = 
			"SELECT "
				. $this->buildColumnsList()
				. $this->buildFrom()
				. $this->buildLeftJoins()
				. $this->buildRightJoins()
				. $this->buildCrossJoins()
				. $this->buildWhere()
				. $this->buildGroupBy()
				. $this->buildOrderBy()
				. $this->buildLimit()
				. $this->buildOffset()
		;
		
		//return $qp;
	}
	
	public function buildInsert()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$qp 	= &$this->queryPlan; 
		
		// Build final query
		$this->queryString = 
			"INSERT INTO "
				. $this->buildFrom()
				. $this->buildColumnsList()
				. $this->buildLeftJoins()
				. $this->buildRightJoins()
				. $this->buildCrossJoins()
				. $this->buildWhere()
				. $this->buildGroupBy()
				. $this->buildOrderBy()
				. $this->buildLimit()
				. $this->buildOffset()
		;
		
		return $qp;
	}
	
	public function buildUpdate()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$qp 	= &$this->queryPlan; 
		
		// Build final query  
		$this->queryString = 
			"UPDATE "
				. $this->buildFrom()
				. $this->buildColumnsList()
				. $this->buildLeftJoins()
				. $this->buildRightJoins()
				. $this->buildCrossJoins()
				. $this->buildWhere()
				. $this->buildGroupBy()
				. $this->buildOrderBy()
				. $this->buildLimit()
				. $this->buildOffset()
		;
	}
	
	
	public function buildDelete()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Define shortcuts
		$this->queryPlan = array(
			'tables' 	=> array(),
			'columns' 	=> array(),
		);
		$qp 	= &$this->queryPlan; 
		
		// Build final query  
		$this->queryString = 
			"DELETE "
				. $this->buildFrom()
				. $this->buildColumnsList()
				. $this->buildLeftJoins()
				. $this->buildRightJoins()
				. $this->buildCrossJoins()
				. $this->buildWhere()
				. $this->buildGroupBy()
				. $this->buildOrderBy()
				. $this->buildLimit()
				. $this->buildOffset()
		;
		
		return $_q;
	}
	

	public function buildColumnsList()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o 	= &$this->options;
		$_r = &$this->_resource;
		
		// TODO
		
		return $this->escape($_r['alias']) . '.* ';	
	}
	
	public function buildFrom()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o 	= &$this->options;
		$_r = &$this->_resource;
		
		// TODO
		
		return 'FROM ' 
			. $this->escape($_r['table']) . ' AS ' . $this->escape($_r['alias']) . ' ';	
	}
	
	public function buildLeftJoins()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildRightJoins()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildCrossJoins()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		// Shortcut to options
		$o = &$this->options;
		
		// TODO
		
		return '';	
	}
	
	public function buildWhere()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

$this->log($this->options['conditions']);

		// Shortcut to options
		$o = &$this->options;

		// Initialize conditions request outptut
		$output = '';
				
		// Do not continue if there's no conditions to handle 
		if ( empty($o['conditions']) ) { return $output; }
		
		// Counter for valid conditions
		$i = 0;
		
		// Loop over the passed conditions
		foreach ($o['conditions'] as $key => $item)
		{
			# Determine pattern
			// Get condition depending of the used pattern:
			// basic key-value (KV): 						$column = $values
			// or indexed array with index array (II): 		$index 	= array($column,[$operator,],$values)
			// or assoc array with index array (AI): 		$column = array($column,[$operator,],$values)
			// or assoc array with assoc array (AA): 		$column = array('colum' => $columns, ['operator' => $operator, ] 'values' => $values)
			// or indexed array with assoc array (IA): 		$index 	= array('colum' => $columns, ['operator' => $operator, ] 'values' => $values)
			
			$kIsNum 		= is_numeric($key);
			$isArray 		= is_array($item);
			$count 			= $isArray ? count($item) : null;
			
$this->dump('kIsNum: ' . $kIsNum);
$this->dump('isArray: ' . $isArray);
$this->dump('count: ' . $count);
$this->dump('isset column: ' . isset($item['colum']));
$this->dump('isset 0: ' . isset($item[0]));
			
			if 		( $kIsNum && $isArray && $count >= 2 && isset($item['column']) ) 	{ $pattern = 'IA'; }
			elseif 	( $kIsNum && $isArray && $count >= 2 && isset($item[0]) )			{ $pattern = 'II'; }
			elseif 	( !$kIsNum && !$isArray )											{ $pattern = 'KV'; }
			elseif 	( !$kIsNum && $isArray && $count >= 2 && isset($item[0]) ) 			{ $pattern = 'AI'; }
			elseif 	( !$kIsNum && $isArray && $count >= 2 && isset($item['column']) ) 	{ $pattern = 'AA'; }
			else 																		{ $pattern = false; }
			
$this->dump('pattern: ' . ($pattern ? $pattern : 'false'));

			$column 		= $this->getConditionColumn($key, $item, $pattern); // Get condition column depending of the used pattern
			$usedOperator 	= $this->getConditionOperator($item, $pattern); 	// Get requested operator depending of the used pattern
			$tmpValues 		= $this->getConditionValues($item, $pattern); 		// Get condition value(s) depending of the used pattern
			
			// Force values into an array
			$values 		= Tools::toArray($tmpValues);
			$vCount 		= count($values);
			
			// Get SQL operator accordingly to used operator
			$op 			= isset($this->knownOperators[$usedOperator]) ? $this->knownOperators[$usedOperator] : false;
			
			// Case when operator is IN/NOT IN and value is single, use =/!= instead
			if ( in_array($op, array('IN','NOT IN')) && $vCount === 1 )
			{
				$op = $op === 'NOT IN' ? '!=' : '=';
			}
			// Case when the operator is '=' and the passed value is null, we have to use IS/IS NOT operator instead
			elseif ( in_array($op, array('=','!=','IN','NOT IN')) && is_null($tmpValues) )
			{
				$op = in_array($op, array('!=','NOT IN')) ? 'IS NOT' : 'IS';
			}
			// Case when the operator is '=' or '!=' and passed values are multiple
			elseif ( in_array($usedOperator, array('=','!=')) && $vCount >= 2 )
			{
				$op = $op === '!=' ? 'NOT IN' : 'IN'; 
			}
			
			// Special case if the operator is '=' or "!=" and passed values are multiple
			$usedOperator  = in_array($usedOperator, array('=','!=')) && $multiValues ? ( $usedOperator === '!=' ? 'NOT IN' : 'IN' ) : $usedOperator;
			
$this->dump('column: ' . $column);
$this->dump('usedOperator: ' . $usedOperator);
$this->dump('values: ' . $values);
$this->dump('op: ' . $op);
			
			// Do not continue if the pattern is invalid
			// The condition will be ignored
			// throwing a warning by the way
			if ( !$pattern || !$column || !$values )
			{
				$this->warnings[_ERR_INVALID_CONDITION_FORMAT] = array('key' => $key, 'value' => $item); 
				continue;
			}
			
			// Set condition linker keyword
			$condKeyword = !empty($item['linker']) && $i !== 0 && in_array(strtoupper($item['linker']), array('AND','OR'))  
							? strtoupper($item['linker']) 
							: ( $i === 0 ? 'WHERE' : 'AND' );
			
			// TODO:
			// handle operators properly since some like MATCH 
			// may have a different pattern than "$column $operator $value" 
			// TODO:
			// handle the triplet column/operator/value in 1 time
			//$output .= $this->buildConditionColumn($column) . $op . $this->buildConditionValues($values, array('column' => $column));
			$output .= $condKeyword . ' ' 
						. $this->buildKeyValueCouple($column, $values, array('operator' => $op, 'context' => 'condition'));
			
			$i++;
			
$this->dump('output: ' . $output);
		}
		
		return $output;		
	}


	public function buildKeyValueCouple($column, $values, $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$p = array_merge(array(
			'operator' 	=> null,
			'resource' 	=> $this->_resource->name,
			'prefix' 	=> '',
			'suffix' 	=> '',
			'context' 	=> null
		), $params);
		$output = '';
		
		# Handle value(s)
		// Get column props
		$colProps 	= DataModel::getColumn($p['resource'], $p['column']);
		$type 		= $colProps['type'];
		$realtype 	= $colProps['realtype'];

$this->dump($column);	
$this->dump($values);
		
		// TODO
		// Validate formats?????
		
		// Prepare formated value
		$fVal = '';
		
		// Force value(s) into an array
		$values = Tools::toArray($values);
		foreach ($values as $val)
		{
			/*
			// Transform it into an the proper format
			if 		( in_array($type, array('enum','choice','set')) )	{ $val .= "'" . $this->escapeString(join(',',$values)) . "'"; }
			elseif 	( $realtype === 'timestamp' ) 	{ $val = "FROM_UNIXTIME('" . $this->escapeString($values) . "')"; }
			elseif 	( $realtype === 'boolean' ) 	{ $val = in_array($values, array(1,true,'1','true','t'), true) ? 1 : 0; }
			elseif 	( $realtype === 'float' ) 		{ $val = (float) $values; }
			elseif 	( $realtype === 'int' ) 		{ $val = (int) $values; }
			else 									{ $val = "'" . $this->escapeString($this->escapeString(join(',',$values))) . "'"; } 
			 */
			$fVal .= $val;
		}	

		// Get resource alias
		$alias = $this->_resource->alias;
		
		// TODO
		// context condition: $column $operator $value
		// context UPDATE: 
		// context default: $column = $value
		if ( $p['context'] === 'condition' )
		{
			// TODO:
			// Get proper column name with using proper alias (or not)
			$output .= $alias . '.' . $this->escapeColName($column) . ' ' . $p['operator'] . ' ' . $p['prefix'] . $fVal . $p['prefix'];
		}
		else
		{
			// Get proper column name with using proper alias (or not)
			$output .= $alias . '.' . $this->escapeColName($column) . ' = ' . $fVal; 
		}
		
		return $output;
	}

	public function getConditionOperator($condition, $pattern)
	{
		if 		( in_array($pattern, array('II','AI')) )		{ return count($condition) === 2 ? '=' : $condition[1]; }
		elseif 	( $pattern === 'KV' )							{ return '='; }
		elseif 	( in_array($pattern, array('AA','IA')) )		{ return ( !isset($condition['operator']) ? '=' : $condition['operator'] ); }
		else 	{ return false; }
	}

	public function getConditionColumn($key, $condition, $pattern)
	{
		if 		( in_array($pattern, array('KV','AI', 'AA')) )	{ return $key; }
		elseif 	( $pattern === 'II' )							{ return $condition[1]; }
		elseif 	( $pattern === 'IA' )							{ return $condition['column']; }
		else 													{ return false; }
	}

	public function getConditionValues($condition, $pattern)
	{
		//if 		( in_array($pattern, array('II','AI')) )		{ return $condition[1]; }
		if 		( in_array($pattern, array('II','AI')) )		{ return count($condition) === 2 ? $condition[1] : $condition[2]; }
		elseif 	( $pattern === 'KV' )							{ return $condition; }
		elseif 	( in_array($pattern, array('AA','IA')) )		{ return $condition['values']; }
		else 													{ return false; }
	}
	
	/*
	public function buildConditionColumn($column)
	{
		return $this->escape($column);	
	}*/
	
	/*
	public function buildConditionValues($values, $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

$this->log($values);
		
		$output = '';
		
		if ( is_array($values) )
		{
			$j = 0; 
			foreach ( $values as $val )
			{
				$output .= ($j !== 0 ? ',' : '') . $this->handleSqlInputTypes($val, $params);
				$j++;
			}
		}
		else
		{
			$output .= $this->handleSqlInputTypes($values, $params);
		}
		
		return $output;
	}*/
	
	public function buildGroupBy()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		// TODO
		
		return '';
	}
	
	public function buildOrderBy()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		// TODO
		
		return '';
	}
	
	public function buildLimit()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		!empty($o['limit']) && $o['limit'] != -1 ? "LIMIT " . $o['limit'] . " " : " ";
	}
	
	public function buildOffset()
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);
		
		$o = &$this->options;
		
		return !empty($o['offset']) ? "OFFSET " . $o['offset'] . " " : " ";
	}
	
	
	public function escapeColName($column)
	{
		return '`' . $column . '`';
	}
	
	public function escape($string)
	{
		return '`' . (string) $string . '`';
	}
	
	public function escapeString($string)
	{
		//$string = !empty($string) ? (string) $string : '';
		
		//return '`' . $string . '`';
		
		return '`' . (string) $string . '`';
	}
	
	
	public function handleSqlInputTypes($value, $params = array())
	{
//var_dump(__METHOD__);
$this->log(__METHOD__);

$this->dump('val: ' . $val);
		
		$p            = array_merge(array(
			'resource'   => $this->_resource->name,
			'column'     => null,
			'operator'   => null,
		), $params);
		$v = $value;
		
$this->dump($p);
		
		// Get column type in datamodel
		$realtype = DataModel::getColumnRealType($p['resource'],$p['column']);
		
		// TODO
		$pref = ''; 	// prefix
		$suf = ''; 		// suffix
		
		/*
		$pref    = !empty($p['operator']) && in_array($p['operator'], array('contains','like','doesnotcontains','notlike','endsby','doesnotendsby','doesnotendby')) ? '%' : '';
		$suf    = !empty($p['operator']) && in_array($p['operator'], array('contains','like','doesnotcontains','notlike','startsby','doesnotstartsby','doesnotstartby')) ? '%' : '';
		 */
		
		// TODO
		// Handle this depending of the column type (in dataModel) 
		/*
		if 		( $type === 'timestamp' && !is_null($val) ) 		{ $val = "FROM_UNIXTIME('" . $this->escapeString($val) . "')"; }
		else if ( $type === 'bool'  ) 								{ $val = in_array($val, array(1,true,'1','true','t'), true) ? 1 : 0; }
		else if ( is_int($val) ) 									{ $val = (int) $val; }
		else if ( is_float($val) ) 									{ $val = (float) $val; }
		else if ( is_bool($val) ) 									{ $val = (int) $val; }
		else if ( is_null($val) || strtolower($val === 'null') ) 	{ $val = 'NULL'; }
		else if ( is_string($val) ) 								{ $val = "'" . $valPrefix . $this->escapeString($val) . $valSuffix . "'"; }
		else 														{ $val = "'" . $valPrefix . $this->escapeString($val) . $valSuffix . "'"; }
		*/
		
		if 		( $realtype === 'string' ) 		{ $val = "'" . $pref . $this->escapeString($v) . $suff . "'"; }
		elseif 	( $realtype === 'int' ) 		{ $val = (int) $v; }
		elseif 	( $realtype === 'float' ) 		{ $val = (float) $v; }
		elseif 	( $realtype === 'boolean' ) 	{ $val = in_array($v, array(1,true,'1','true','t'), true) ? 1 : 0; }
		elseif 	( $realtype === 'timestamp' ) 	{ $val = "FROM_UNIXTIME('" . $this->escapeString($v) . "')"; }
		else 									{ $val = "'" . $pref . $this->escapeString($v) . $suff . "'"; }
		
		return $val;
	}
}

?>