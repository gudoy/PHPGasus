<?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:04
         compiled from "/var/www/phpgasus/devv1/templates/yours/pages/home/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3717078444e55fbdcf078d5-76991347%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7b46645dbf8d32b7371d348d7b54826c345091c0' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/yours/pages/home/index.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    '6d8a3433e5427e26fec4519b578559c48d6aa1aa' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/yours/layouts/page.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    'dfab39ef6776d39e33bb08bdadf82b8a8ed368d0' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/layouts/page.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    'fb3c414414b0ec79e0816b79b3b9fd1ec815faa0' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/head/doctype.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    '1907229eb75830b119f697ca2cbb0b3863758df2' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/head/metas/metas.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    '77dbdbef13088b0235b068ec0bb1eb7a486fc728' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/head/icons.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    '55988d2cc91a7e8beb00b49075cc828fae9755ac' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/css/IE/ie.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    '4054ba2698ad384386dc92ded0c223685ff3edf4' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/css/css.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    'e09731fc83a8cebf0da2a4cf7bb29f925b1623af' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/js/html5.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    'a25603207d35101e0759b00bb09364798e8e5229' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/js/google/analytics/init.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    'd9761a1c90fc11885700cafafe38dcd67c5daa3d' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/js/detectJs.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    '9bf1be2e336d2624d450d71db65c8141ed9b310d' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/js/google/chromeFrame/init.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    '4f21a989bb5394ed0214514e89ec75e06fd18243' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/common/notifier/notifier.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    '26d17ead2cb11621b0cc9d4457d0f7b585f3fa13' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/header/header.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    '83ed2bfd14c1fb3ca6173da9198b231a35ff3cc1' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/home/index.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    'e3d2db0ebbabe67617264f1f71a14200253beaaf' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/footer/footer.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
    '424fc0116608b427f7e61848b9a89af6f13965c7' => 
    array (
      0 => '/var/www/phpgasus/devv1/templates/default/blocks/js/js.tpl',
      1 => 1314013634,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3717078444e55fbdcf078d5-76991347',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'view' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1-RC1',
  'unifunc' => 'content_4e55fbdd558ce',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4e55fbdd558ce')) {function content_4e55fbdd558ce($_smarty_tpl) {?>
<?php /*  Call merged included template "default/blocks/head/doctype.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/head/doctype.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd003de($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/head/doctype.tpl" */?>
<head>
	<title><?php echo (($tmp = @$_smarty_tpl->tpl_vars['view']->value['title'])===null||$tmp==='' ? @_APP_TITLE : $tmp);?>
</title>
	
	<?php /*  Call merged included template "default/blocks/head/metas/metas.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/head/metas/metas.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd15169($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/head/metas/metas.tpl" */?>
	
	
	<?php /*  Call merged included template "default/blocks/head/icons.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/head/icons.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd275de($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/head/icons.tpl" */?>
	
	
	<?php /*  Call merged included template "default/blocks/css/css.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/css/css.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd29f2f($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/css/css.tpl" */?>
	
	<?php /*  Call merged included template "default/blocks/js/html5.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/js/html5.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd33b77($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/js/html5.tpl" */?>
	<?php /*  Call merged included template "default/blocks/js/google/analytics/init.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/js/google/analytics/init.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd36013($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/js/google/analytics/init.tpl" */?>
	<?php /*  Call merged included template "default/blocks/js/detectJs.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/js/detectJs.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd3b6b0($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/js/detectJs.tpl" */?>
	
</head>
<body>
<?php /*  Call merged included template "default/blocks/js/google/chromeFrame/init.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/js/google/chromeFrame/init.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd3f0b0($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/js/google/chromeFrame/init.tpl" */?>
	<div id="layout">
		
		<?php /*  Call merged included template "default/blocks/common/notifier/notifier.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/common/notifier/notifier.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd43d83($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/common/notifier/notifier.tpl" */?>
		<?php /*  Call merged included template "default/blocks/header/header.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/header/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd44d09($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/header/header.tpl" */?>
		<div id="body">
		    	
		    	<div class="col main" id="mainCol" role="main">
		    		<div class="colContent mainColContent" id="mainColContent">
		    			
<?php /*  Call merged included template "default/blocks/home/index.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/home/index.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd49225($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/home/index.tpl" */?>

		    		</div>
		    	</div>
		    	
    		</div>
    	<?php /*  Call merged included template "default/blocks/footer/footer.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/footer/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd4bab0($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/footer/footer.tpl" */?>
		
	</div>
	<?php /*  Call merged included template "default/blocks/js/js.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/js/js.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd4d526($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/js/js.tpl" */?>

</body>
</html>
<?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:04
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/head/doctype.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd003de')) {function content_4e55fbdd003de($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/phpgasus/devv1/libs/default/templating/Smarty/plugins/modifier.truncate.php';
?><?php $_smarty_tpl->tpl_vars['doctype'] = new Smarty_variable((($tmp = @(($tmp = @$_smarty_tpl->tpl_vars['view']->value['doctype'])===null||$tmp==='' ? @_APP_DOCTYPE : $tmp))===null||$tmp==='' ? 'html5' : $tmp), null, 0);?><?php $_smarty_tpl->tpl_vars['dtd'] = new Smarty_variable('', null, 0);?><?php $_smarty_tpl->tpl_vars['attrs'] = new Smarty_variable('', null, 0);?><?php $_smarty_tpl->tpl_vars['curLang'] = new Smarty_variable(smarty_modifier_truncate((($tmp = @$_SESSION['lang'])===null||$tmp==='' ? @_APP_DEFAULT_LANGUAGE : $tmp),2,''), null, 0);?><?php $_smarty_tpl->tpl_vars['xmlns'] = new Smarty_variable(' xmlns="http://www.w3.org/1999/xhtml"', null, 0);?><?php $_smarty_tpl->tpl_vars['xmllang'] = new Smarty_variable((('xml:lang="').($_smarty_tpl->tpl_vars['curLang']->value)).('"'), null, 0);?><?php $_smarty_tpl->tpl_vars['langAttr'] = new Smarty_variable((('lang="').($_smarty_tpl->tpl_vars['curLang']->value)).('"'), null, 0);?><?php if ($_smarty_tpl->tpl_vars['doctype']->value==='html5'&&$_smarty_tpl->tpl_vars['request']->value->outputFormat!=='xhtml'){?><?php }elseif($_smarty_tpl->tpl_vars['doctype']->value==='xhtml-strict-1.1'){?><?php $_smarty_tpl->tpl_vars['dtd'] = new Smarty_variable(' PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"', null, 0);?><?php $_smarty_tpl->tpl_vars['attrs'] = new Smarty_variable(" ".($_smarty_tpl->tpl_vars['xmlns']->value)." ".($_smarty_tpl->tpl_vars['xmllang']->value), null, 0);?><?php }elseif($_smarty_tpl->tpl_vars['doctype']->value==='xhtml-strict'){?><?php $_smarty_tpl->tpl_vars['dtd'] = new Smarty_variable(' PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"', null, 0);?><?php $_smarty_tpl->tpl_vars['attrs'] = new Smarty_variable(" ".($_smarty_tpl->tpl_vars['xmlns']->value)." ".($_smarty_tpl->tpl_vars['langAttr']->value)." ".($_smarty_tpl->tpl_vars['xmllang']->value), null, 0);?><?php }else{ ?><?php $_smarty_tpl->tpl_vars['dtd'] = new Smarty_variable(' PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"', null, 0);?><?php $_smarty_tpl->tpl_vars['attrs'] = new Smarty_variable(" ".($_smarty_tpl->tpl_vars['xmlns']->value)." ".($_smarty_tpl->tpl_vars['langAttr']->value)." ".($_smarty_tpl->tpl_vars['xmllang']->value), null, 0);?><?php }?>
<!DOCTYPE html<?php echo $_smarty_tpl->tpl_vars['doctypeCompl']->value;?>
>
<html<?php if ($_smarty_tpl->tpl_vars['view']->value['smartname']){?> <?php echo $_smarty_tpl->tpl_vars['view']->value['smartname'];?>
<?php }?> id="<?php echo $_smarty_tpl->tpl_vars['view']->value['name'];?>
" class="no-js <?php echo $_smarty_tpl->tpl_vars['view']->value['classes'];?>
"<?php if (@_APP_USE_MANIFEST){?> manifest="<?php echo @_APP_MANIFEST_FILENAME;?>
"<?php }?><?php echo $_smarty_tpl->tpl_vars['attributes']->value;?>
<?php echo $_smarty_tpl->tpl_vars['htmlAttrbitues']->value;?>
>
<?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/head/metas/metas.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd15169')) {function content_4e55fbdd15169($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/phpgasus/devv1/libs/default/templating/Smarty/plugins/modifier.replace.php';
?><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="<?php echo smarty_modifier_replace((($tmp = @$_smarty_tpl->tpl_vars['view']->value['description'])===null||$tmp==='' ? @_APP_META_DECRIPTION : $tmp),'&','&amp;');?>
" />
	<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['view']->value['keywords'];?>
" />
	<meta name="robots" content="<?php if ($_smarty_tpl->tpl_vars['view']->value['robotsIndexable']){?>index,follow,<?php }else{ ?>noindex,nofollow,<?php }?><?php if (!$_smarty_tpl->tpl_vars['view']->value['robotsArchivable']){?>noarchive,<?php }?><?php if (!$_smarty_tpl->tpl_vars['view']->value['robotsImagesIndexable']){?>noimageindex,<?php }?>" />
<?php if ($_smarty_tpl->tpl_vars['view']->value['robotsIndexable']){?><meta name="revisit-after" content="7" /><?php }?>
	<?php if (!$_smarty_tpl->tpl_vars['view']->value['googleTranslatable']){?><meta name="google" content="notranslate" /><?php }?>
	
	<meta name="rating" content="General" />
	<meta name="distribution" content="Global" />
	<meta name="author" content="<?php echo @_APP_AUTHOR_NAME;?>
" />
	<meta name="reply-to" content="<?php echo @_APP_AUTHOR_MAIL;?>
" />
	<meta name="owner" content="<?php echo @_APP_OWNER_MAIL;?>
" />
	<?php if ($_smarty_tpl->tpl_vars['view']->value['refresh']){?><meta http-equiv="refresh" content="<?php echo $_smarty_tpl->tpl_vars['view']->value['refresh'];?>
" /><?php }?>
	
	<meta name="apple-mobile-web-app-capable" content="<?php if ($_smarty_tpl->tpl_vars['view']->value['iosWebappCapable']){?>yes<?php }else{ ?>no<?php }?>" />
	<meta name="viewport" content="width=<?php echo $_smarty_tpl->tpl_vars['view']->value['viewportWidth'];?>
, initial-scale=<?php echo $_smarty_tpl->tpl_vars['view']->value['viewportIniScale'];?>
, maximum-scale=<?php echo $_smarty_tpl->tpl_vars['view']->value['viewportMaxScale'];?>
, user-scalable=<?php if ($_smarty_tpl->tpl_vars['view']->value['viewportUserScalable']){?>yes<?php }else{ ?>no<?php }?>" />
	
	<?php if ($_smarty_tpl->tpl_vars['view']->value['allowPrerendering']&&$_smarty_tpl->tpl_vars['request']->value->url){?><link rel="prerender" href="<?php echo $_smarty_tpl->tpl_vars['request']->value->url;?>
"><?php }?><?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/head/icons.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd275de')) {function content_4e55fbdd275de($_smarty_tpl) {?><link href="<?php echo @_URL_PUBLIC;?>
favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<link href="<?php echo @_URL_PUBLIC;?>
apple-touch-icon.png" rel="apple-touch-icon" /><?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/css/css.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd29f2f')) {function content_4e55fbdd29f2f($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['version'] = new Smarty_variable((($tmp = @('v=').(@_CSS_VERSION))===null||$tmp==='' ? '' : $tmp), null, 0);?>
<!--[if IE]><![endif]-->
<?php  $_smarty_tpl->tpl_vars['css'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['view']->value['css']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['css']->key => $_smarty_tpl->tpl_vars['css']->value){
?>
	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['css']->value;?>
<?php if (strpos($_smarty_tpl->tpl_vars['css']->value,'?')!==false){?>&<?php }else{ ?>?<?php }?><?php echo $_smarty_tpl->tpl_vars['version']->value;?>
" media="screen" />
<?php }} ?>
<?php /*  Call merged included template "default/blocks/css/IE/ie.tpl" */
$_tpl_stack[] = $_smarty_tpl;
 $_smarty_tpl = $_smarty_tpl->setupInlineSubTemplate('default/blocks/css/IE/ie.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0, '3717078444e55fbdcf078d5-76991347');
content_4e55fbdd2f84e($_smarty_tpl);
$_smarty_tpl = array_pop($_tpl_stack); /*  End of included template "default/blocks/css/IE/ie.tpl" */?>
<?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/css/IE/ie.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd2f84e')) {function content_4e55fbdd2f84e($_smarty_tpl) {?><?php if (@_APP_USE_CSS_IE){?>
	<!--[if lte IE 8]>
	<link rel="stylesheet" href="<?php echo @_URL_STYLESHEETS_REL;?>
ie.css?<?php echo $_smarty_tpl->tpl_vars['version']->value;?>
" media="screen" />
	<![endif]-->
<?php }?><?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/js/html5.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd33b77')) {function content_4e55fbdd33b77($_smarty_tpl) {?><?php if (!@_USE_MODERNIZR){?>
<!--[if lte IE 8]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<?php }else{ ?>
<?php }?><?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/js/google/analytics/init.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd36013')) {function content_4e55fbdd36013($_smarty_tpl) {?><?php if (@_APP_USE_GOOGLE_ANALYTICS&&@_APP_GOOGLE_ANALYTICS_UA!=='UA-XXXXX-X'){?>
<script>
		var _gaq = _gaq || [];

		_gaq.push(['_setAccount', '<?php echo @_APP_GOOGLE_ANALYTICS_UA;?>
']);
		_gaq.push(['_trackPageview']);
		_gaq.push(['_setDomainName', ".<?php echo @_DOMAIN;?>
"]);
		
		(function()
		{
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
		})();
	</script>
<?php }else{ ?>

<?php }?><?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/js/detectJs.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd3b6b0')) {function content_4e55fbdd3b6b0($_smarty_tpl) {?><?php if (!@_USE_MODERNIZR){?>
<script>(function(H){ H.className = H.className.replace(/\bno-js\b/,'js') })(document.documentElement)</script>
<?php }?>
<?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/js/google/chromeFrame/init.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd3f0b0')) {function content_4e55fbdd3f0b0($_smarty_tpl) {?><?php if (@_APP_USE_CHROME_FRAME&&$_smarty_tpl->tpl_vars['request']->value->browser['alias']==='ie'){?>
<!--[if IE]>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
<style>
	.chromeFrameInstallDefaultStyle { width:100%; border: 5px solid blue; }
</style>
<div id="gglChrFrPlaceholer"></div>
<script>window.attachEvent("onload", function() { CFInstall.check({ mode: "overlay", node: "gglChrFrPlaceholer" }); });</script>
<![endif]-->
<?php }?><?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/common/notifier/notifier.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd43d83')) {function content_4e55fbdd43d83($_smarty_tpl) {?><?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/header/header.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd44d09')) {function content_4e55fbdd44d09($_smarty_tpl) {?><?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/home/index.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd49225')) {function content_4e55fbdd49225($_smarty_tpl) {?>Block home page content<?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/footer/footer.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd4bab0')) {function content_4e55fbdd4bab0($_smarty_tpl) {?><?php }} ?><?php /* Smarty version Smarty 3.1-RC1, created on 2011-08-25 07:38:05
         compiled from "/var/www/phpgasus/devv1/templates/default/blocks/js/js.tpl" */ ?>
<?php if ($_valid && !is_callable('content_4e55fbdd4d526')) {function content_4e55fbdd4d526($_smarty_tpl) {?><?php $_smarty_tpl->tpl_vars['version'] = new Smarty_variable((($tmp = @('v=').(@_JS_VERSION))===null||$tmp==='' ? '' : $tmp), null, 0);?>

<?php  $_smarty_tpl->tpl_vars['js'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['view']->value['js']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['js']->key => $_smarty_tpl->tpl_vars['js']->value){
?>
	<script src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
<?php if (strpos($_smarty_tpl->tpl_vars['css']->value,'?')!==false){?>&<?php }else{ ?>?<?php }?><?php echo $_smarty_tpl->tpl_vars['version']->value;?>
">
<?php }} ?>
<?php if ($_smarty_tpl->tpl_vars['request']->value->_magic['jsCalls']){?>
	<script>
		$(document).ready(function(){ <?php echo $_smarty_tpl->tpl_vars['request']->value->_magic['jsCalls'];?>

		});
	</script>
<?php }?><?php }} ?>