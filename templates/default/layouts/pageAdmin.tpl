<<<<<<< HEAD
{extends file='default/layouts/page.tpl'}

{block name="header"}{/block}

{block name='googleAnalytics'}{/block}

{block name='aside'}
<aside class="col aside" id="sideCol" role="complementary">
	{block name='asideHeader'}
	<header>
		<h1 class="title">{$smarty.const._APP_TITLE}</h1>
	</header>
	{/block}
	{block name='asideContent'}
		<div class="colContent asideContent" id="asideContent">
		{block name='adminSearch'}{include file='default/blocks/admin/search/search.tpl'}{/block}
			{block name='adminMainNav'}{include file='default/blocks/admin/nav/mainNav.tpl'}{/block}
		</div>
	{/block}
	{block name='asideFooter'}
	{include file='default/blocks/aside/asideFooterAdmin.tpl'}
	{/block}
</aside>
{/block}

{block name='mainColHeader'}
<header>
	{include file='default/blocks/admin/nav/breadcrumbs.tpl'}
</header>
{/block}


=======
{extends file='default/layouts/page.tpl'}

{block name="header"}{/block}

{block name='googleAnalytics'}{/block}

{block name='aside'}
<aside class="col aside" id="sideCol" role="complementary">
	{block name='asideHeader'}
	<header>
		<h1 class="title">{$smarty.const._APP_TITLE}</h1>
	</header>
	{/block}
	{block name='asideContent'}
		<div class="colContent asideContent" id="asideContent">
		{block name='adminSearch'}{include file='default/blocks/admin/search/search.tpl'}{/block}
			{block name='adminMainNav'}{include file='default/blocks/admin/nav/mainNav.tpl'}{/block}
		</div>
	{/block}
	{block name='asideFooter'}
	{include file='default/blocks/aside/asideFooterAdmin.tpl'}
	{/block}
</aside>
{/block}

{block name='mainColHeader'}
<header>
	{include file='default/blocks/admin/nav/breadcrumbs.tpl'}
</header>
{/block}

>>>>>>> 4b7d7f5ab0689830a94602ff9dd5b692a524dda3
{block name='mainColFooter'}{include file='default/blocks/mainCol/mainColFooterAdmin.tpl'}{/block}