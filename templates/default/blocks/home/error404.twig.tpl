<section id="error404">
	<header>
		<hgroup>
			<h1 class="title">Whoooops, 404 !</h1>
			<h2 class="subtitle">This is not the droid you're looking for</h2>
		<hgroup>
	</header>
	<div class="content">
		<p>
			{% trans 'Sorry but the page you are looking for could not be found.' %}
		</p>
		{% if data.suggestions %}
		<h2 class="subtitle">Did you mean?</h2>
		<ul>
		{% for suggestion in data.suggestions %}
			<li><a href="{{ suggestion.url }}">{{ suggestion.name }}</a></li>
		{% endfor %}
		<ul>
		{% endif %}
		<p>
			{% trans 'maybe' %}
		</p>
		<ul>
			<li>
				{% trans 'This page has moved or no longer exists.' %}
			</li>
			<li>
				{% trans 'you misspelled the address if you typed it.' %}
			</li>
		</ul>
		<nav class="actions">
			{#
			{% include 'default/blocks/actionBtn.twig.tpl' with {'href':constant('_URL'), 'label':{% trans 'get back to home' %}} %}
			<span class="or">
			{% include 'default/blocks/actionBtn.twig.tpl' with {'href':"mailto:" ~ constant('_APP_OWNER_CONTACT_MAIL'), 'label':{% trans 'contact us' %} %>
			#}
		</nav>
	</div>
</section>