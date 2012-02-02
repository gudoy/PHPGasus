{$rName 	= $request->resource}
{$rProps 	= $_resources.items[$rName]}
{$rModel 	= $_columns[$rName].items}

{foreach array_keys($data[$rName]) as $key}
{$item = $data[$rName][$key]}
<article class="resource" id="{$rName}{$item.id}">
	<div class="meta">
		<input type="checkbox" name="selectedResources" value="{$item.id}" />	
	</div>
	<nav class="actions">
		{include file='default/blocks/common/buttons/action.tpl' class="view" href="{$smarty.const._URL}admin/{$rName}/{$item.id}" label='view'}
	</nav>
	<header>
		<h3 class="title">
			<span class="id">{$item.id}</span>
			<span class="name">{$item[$rProps.nameField]}</span>
		</h3>
	</header>
	<div class="content">
		{foreach $rModel as $col => $props}
		<span class="key">{$col}</span>
		<span class="value" data-exact="{$item[$col]}">{$item[$col]}</span>
		{/foreach}
	</div>
</article>
{/foreach}
