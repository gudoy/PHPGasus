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
	{block name='layout'}<div id="layout">
		{block name='layoutContent'}
		{block name='notifier'}{include file='default/blocks/common/notifier/notifier.tpl'}{/block}
		{block name='header'}{include file='default/blocks/header/header.tpl'}{/block}
		{block name='page'}<div id="body">{block name='pageContent'}
				{block name='breadcrumbs'}{/block}
		    	{block name='aside'}{/block}
		    	{block name='mainCol'}
		    	<div class="col main" id="mainCol" role="main">
		    		{block name='mainColHeader'}{/block}
		    		<div class="colContent mainColContent" id="mainColContent">
		    			{block name='mainContent'}{/block}
		    		</div>
		    		{block name='mainColFooter'}{/block}
		    	</div>
		    	{/block}
    		{/block}</div>{/block}
    	{block name='footer'}{include file='default/blocks/footer/footer.tpl'}{/block}
		
	{/block}</div>{/block}
	{block name='js'}{include file='default/blocks/js/js.tpl'}{/block}
{/block}
</body>{/block}
</html>
{/block}