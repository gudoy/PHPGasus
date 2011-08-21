{extends file='default/layouts/page.tpl'}

{block name="header"}{/block}

{block name='googleAnalytics'}{/block}

{block name='aside'}
<aside class="col aside" id="sideCol" role="complementary">
	{block name='asideHeader'}{/block}
	{block name='asideContent'}
		<div class="colContent asideContent" id="asideContent">
		{block name='adminSearch'}{include file='default/blocks/admin/search/search.tpl'}{/block}
			{block name='adminMainNav'}{include file='default/blocks/admin/nav/mainNav.tpl'}{/block}
		</div>
	{/block}
	{block name='asideFooter'}{/block}
</aside>
{/block}

{block name='asideFooter'}{include file='default/blocks/aside/asideFooterAdmin.tpl'}{/block}
{block name='mainColFooter'}{include file='default/blocks/mainCol/mainColFooterAdmin.tpl'}{/block}