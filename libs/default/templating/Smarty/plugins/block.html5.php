<?php
function smarty_block_html5($params, $content)
{
	// http://www.felgall.com/html5v.htm
	
	$html5 = array(
		'section' 		=> array('alt' => 'div', 'class' => 'section'),
		'header' 		=> array('alt' => 'div', 'class' => 'header'),
		'footer' 		=> array('alt' => 'div', 'class' => 'footer'),
		'article' 		=> array('alt' => 'div', 'class' => 'article'),
		'aside' 		=> array('alt' => 'div', 'class' => 'aside'),
		'hgroup' 		=> array('alt' => 'div', 'class' => 'hgroup'),
		'figure' 		=> array('alt' => 'div', 'class' => 'figure'),
		'figcaption' 	=> array('alt' => 'div', 'class' => 'figcaption'),
		'dialog' 		=> array('alt' => 'div', 'class' => 'dialog'),
		'details' 		=> array('alt' => 'div', 'class' => 'details'),
		'datalist' 		=> array('alt' => 'div', 'class' => 'datalist'),
		'datagrid' 		=> array('alt' => 'div', 'class' => 'datagrid'),
		
		'menu' 			=> array('alt' => 'ul', 'class' => 'menu'),
		'nav' 			=> array('alt' => 'ul', 'class' => 'nav'),
		
		//'canvas' 		=> array('alt' => '', 'class' => 'canvas'),
		//'audio' 		=> array('alt' => '', 'class' => 'audio'),
		//'video' 		=> array('alt' => '', 'class' => 'video'),
		//'source' 		=> array('alt' => 'param', 'class' => 'source'),
		//'embed' 		=> array('alt' => '', 'class' => 'embed'),
		//'eventsource' => array('alt' => '', 'class' => 'eventsource'),
		//'command' 	=> array('alt' => 'span', 'class' => 'command'),
		
		'summary' 		=> array('alt' => 'p', 'class' => 'summary'),

		'mark' 			=> array('alt' => 'span', 'class' => 'mark'),
		'time' 			=> array('alt' => 'span', 'class' => 'time'),
		'meter' 		=> array('alt' => 'span', 'class' => 'meter'),
		'progress'		=> array('alt' => 'span', 'class' => 'progress'),
		'output' 		=> array('alt' => 'span', 'class' => 'output'),
		'keygen' 		=> array('alt' => 'span', 'class' => 'keygen'),
		'ruby' 			=> array('alt' => 'span', 'class' => 'ruby'),
		'rp' 			=> array('alt' => 'span', 'class' => 'rp'),
		'rt' 			=> array('alt' => 'span', 'class' => 'rt'),
		'wbr' 			=> array('alt' => 'span', 'class' => 'wbr'),
	);
	
	
	if ( empty($params['tag']) )			{ throw new SmartyException("Missing required name param"); }
	if ( !isset($html5[$params['tag']]) )	{ throw new SmartyException("Unknown html5 tag"); }
	
	// Extends default params with passed ones, creating a shortcut by the way
	$p 			= array_merge(array( 
		'html5' 		=> true,
		'fallbackTag' 	=> 'div',
		'class' 		=> '',
		'id' 			=> '',
	), (array) $params);
	$tag 		= $p['html5'] ? $p['tag'] : $p['fallbackTag'];
	$classes 	= $html5[$p['tag']]['class'] . ' ' . trim((string) $p['class']);
	
	return '<' . $tag . ' class="' . $classes . '"' . ( !empty($p['id']) ? ' id="' . $p['id'] . '"' : '' ) . '>' . $content . '</' . $tag . '>';
} 

?>