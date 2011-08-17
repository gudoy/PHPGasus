{block name='html'}
{block name='doctype'}{include file='default/blocks/head/doctype.tpl'}{/block}
{block name='head'}<head>
	<title>{$view.title|default:$smarty.const._APP_TITLE}</title>
	
	{block name='metas'}{include file='default/blocks/head/metas/metas.tpl'}
	
	{/block}
	{block name='icons'}{include file='default/blocks/head/icons.tpl'}
	{/block}
	
	{block name='css'}{include file='default/blocks/css/css.tpl'}{/block}
	
	{block name='html5shiv'}{include file='default/blocks/js/html5.tpl'}{/block}
	{block name='googleAnalytics'}{include file='default/blocks/js/google/analytics/init.tpl'}{/block}
	{block name='detectJs'}{include file='default/blocks/js/detectJs.tpl'}{/block}
	{* block name='googleChromeFrameLoad'}{include file='default/blocks/js/google/chromeFrame/load.tpl'}{/block *}
	
</head>{/block}
{block name='body'}<body>{block name='bodyContent'}
{* block name='onBodyStartFlush'}{include file='default/blocks/misc/flush/onBodyStart.tpl'}{/block *}
{block name='googleChromeFrameInit'}{include file='default/blocks/js/google/chromeFrame/init.tpl'}{/block}
	{block name='layout'}<div id="layout">{block name='layoutContent'}
		{block name='header'}{/block}
		{block name='pageContent'}{/block}
		{block name='footer'}{/block}
	{/block}</div>{/block}{block name='js'}{include file='default/blocks/js/js.tpl'}{/block}
{/block}
</body>{/block}
</html>
{/block}