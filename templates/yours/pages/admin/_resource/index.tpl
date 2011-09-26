{extends file='yours/layouts/pageAdmin.tpl'}

{block name='mainContent'}
<section class="resources resourcesBlock" id="{$request->resource}Block">
	<div class="content">
	{include file='default/blocks/admin/_resource/index.tpl'}	
	</div> 
</section>
{/block}