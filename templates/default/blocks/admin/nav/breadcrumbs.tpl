{$breadcrumbs = $request->breadcrumbs}
<nav class="breadcrumbs" id="breadcrumbs">
	<ol>
		<li class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="home" href="{$smarty.const._URL}" itemprop="url"><span class="value" itemprop="title">{t}home{/t}</span></a>
		</li>
		{$relUp	= ''}
		{$path 	= ''}
		{foreach $breadcrumbs as $breadcrumb}
		{$relUp = $relUp|cat:' up'}
		{$path 	= $path|cat:$breadcrumb|cat:'/'}
		<li class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="{$relUp}" href="{$smarty.const._URL}{$path}" itemprop="url"><span class="value" itemprop="title">{$breadcrumb}</span></a>
		</li>
		{/foreach}
		{$cRawName = $request->controller->rawName}
		{$bCount = count($breadcrumbs)}
		{if $cRawName !== $breadcrumbs[$bCount-1]}
		<li class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="{$relUp} up" href="{$smarty.const._URL}{$path}{$request->controller->rawName}" itemprop="url"><span class="value" itemprop="title">{$request->controller->rawName}</span></a>
		</li>
		{/if}
		{* TODO: how to handle current resource *}
		{* TODO: how to handle filtered resources list *}
	</ol>
</nav>