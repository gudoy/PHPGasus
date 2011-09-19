{$rName 	= $request->resource}
{$rProps 	= $_resources[$rName]}
{$rModel 	= $_columns[$rName]}

{foreach $data[$request.resource] as $item}
<article id="{$request.resource}{$item.id}">
	<input type="checkbox" value="" />
	<header>
		<h3 class="title">
			<span class="id">{$item.id}</span>
			<span class="name">{$item[$rProps.nameField]}</span>
		</h3>
	</header>
	<div class="content">
		{foreach $rModel as $col => $props}
		<span class="value" data-exact="{$item[$col]}">{$item[$col]}</span>
		{/foreach}
	</div>
</article>
{/foreach}
