<nav class="main adminNav adminMainNav" id="adminMainNav">
	{if $_groups}
	<ul class="resourceGroups">
		{foreach array_keys($_groups) as $rGpName}
		<li class="resourceGroup">
			<a class="action" href="#"><span class="name">{$rGpName}</span></a>
			{include file='default/blocks/admin/nav/resources.tpl' resourcesNames=$_groups[$rGpName]['resources']}			
		</li>
		{/foreach}
	</ul>
	{else}
		{include file='default/blocks/admin/nav/resources.tpl' resourcesNames=$_resources}	
	{/if}
</nav>