<<<<<<< HEAD
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
=======
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
>>>>>>> 4b7d7f5ab0689830a94602ff9dd5b692a524dda3
</nav>