<li class="resource" id="adminNav{$rName|ucfirst}Item">
	<a class="action view" href="{$smarty.const._URL_ADMIN}{$rName}/">
		{if $data.total.resources[$rName]}<span class="count">$data.total.resources[$rName]</span>{/if}
		<span class="value name">{$_resources[$rName]['displayName']|default:$rName}</span>
	</a>		
</li>