<li class="resource" id="adminNav{$rName|ucfirst}Item">
	<a class="view" href="{$smarty.const._URL_ADMIN}{$rName}/">
		{if $data.total.resources[$rName]}<span class="count">$data.total.resources[$rName]</span>{/if}
		<span class="value name">{$_resources.items[$rName]['displayName']|default:$rName}</span>
	</a>
</li>