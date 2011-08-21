<li class="resource">
	<a class="action view" href="{$smarty.const._URL_ADMIN}{$rName}/">
		{if $data.total.resources[$rName]}<span class="count">$data.total.resources[$rName]</span>{/if}
		<span class="name">{$_resources[$rName]['displayName']|default:$rName}</span>
	</a>		
</li>