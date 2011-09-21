<nav class="main adminNav adminMainNav" id="adminMainNav">
	{if $_groups}
	<ul class="resourceGroups">
		{foreach array_keys($_groups) as $rGpName}
		<li class="resourceGroup" id="adminNav{$rGpName|ucfirst}Group">
			<a class="action" href="#"><span class="value name">{$rGpName}</span></a>
			{include file='default/blocks/admin/nav/resources.tpl' resourcesNames=$_groups[$rGpName]['resources']}			
		</li>
		{/foreach}
	</ul>
	{else}
		{include file='default/blocks/admin/nav/resources.tpl' resourcesNames=$_resources}	
	{/if}
</nav>