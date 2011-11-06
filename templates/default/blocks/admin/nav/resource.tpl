<<<<<<< HEAD
<li class="resource">
	<a class="action view" href="{$smarty.const._URL_ADMIN}{$rName}/">
		{if $data.total.resources[$rName]}<span class="count">$data.total.resources[$rName]</span>{/if}
		<span class="name">{$_resources[$rName]['displayName']|default:$rName}</span>
	</a>		
=======
<li class="resource" id="adminNav{$rName|ucfirst}Item">
	<a class="action view" href="{$smarty.const._URL_ADMIN}{$rName}/">
		{if $data.total.resources[$rName]}<span class="count">$data.total.resources[$rName]</span>{/if}
		<span class="value name">{$_resources[$rName]['displayName']|default:$rName}</span>
	</a>		
>>>>>>> 4b7d7f5ab0689830a94602ff9dd5b692a524dda3
</li>