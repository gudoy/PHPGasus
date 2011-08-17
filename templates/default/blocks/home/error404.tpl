<section id="error404">
	<header>
		<hgroup>
			<h1 class="title">Whoooops, 404 !</h1>
			<h2 class="subtitle">This is not the droid you're looking for</h2>
		<hgroup>
	</header>
	<div class="content">
		<p>
			{t}Sorry but the page you are looking for could not be found.{/t}
		</p>
		{if $data.suggestions}
		<h2 class="subtitle">Did you mean?</h2>
		<ul>
		{foreach $data.suggestions as $suggestion}
			<li><a href="{$suggestion.url}">{$suggestion.name}</a></li>
		{/foreach}
		<ul>
		{/if}
		<p>
			{t}maybe:{/t}
		</p>
		<ul>
			<li>
				{t}This page has moved or no longer exists.{/t}
			</li>
			<li>
				{t}You misspelled the address if you typed it.{/t}
			</li>
		</ul>
		<nav class="actions">
			{include file='default/blocks/actionBtn.tpl' href="{$smarty.const._URL}" label={'back to home'|gettext}}
			<span class="or">
			{include file='default/blocks/actionBtn.tpl' href="mailto:{$smarty.const._APP_OWNER_CONTACT_MAIL}" label={'contact us'|gettext}}
		</nav>
	</div>
</section>