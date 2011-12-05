<nav class="main adminNav adminMainNav" id="adminMainNav">
	{if $_groups.items}
	<ul class="resourceGroups">
		{foreach $_groups.items as $rGpName => $rGpProps}
		<li class="resourceGroup" id="adminNav{$rGpName|ucfirst}Group">
			<a class="action" href="#"><span class="value name">{$rGpName}</span></a>
			{include file='default/blocks/admin/nav/resources.tpl' resourcesNames=$_groups.items[$rGpName]['resources']}			
		</li>
		{/foreach}
	</ul>
	{else}
		{include file='default/blocks/admin/nav/resources.tpl' resourcesNames=array_keys($_resources.items)}	
	{/if}
</nav>