{if $resourcesNames}
<ul class="resources">
	{foreach $resourcesNames as $rName}
	{include file='default/blocks/admin/nav/resource.tpl'}
	{/foreach}
</ul>
{/if}
