<nav class="breadcrumbs" id="breadcrumbs">
	<ol>
		<li class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="home" href="{$smarty.const._URL}" itemprop="url"><span class="value" itemprop="title">{t}home{/t}</span></a>
		</li>
		{$relUp	= ''}
		{$path 	= ''}
		{foreach $request->breadcrumbs as $breadcrumb}
		{$relUp = $relUp|cat:' up'}
		{$path 	= $path|cat:$breadcrumb|cat:'/'}
		<li class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="{$relUp}" href="{$smarty.const._URL}{$path}" itemprop="url"><span class="value" itemprop="title">{$breadcrumb}</span></a>
		</li>
		{/foreach}
		<li class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
			<a rel="{$relUp} up" href="{$smarty.const._URL}{$path}{$request->controller->rawName}" itemprop="url"><span class="value" itemprop="title">{$request->controller->rawName}</span></a>
		</li>
	</ol>
</nav>