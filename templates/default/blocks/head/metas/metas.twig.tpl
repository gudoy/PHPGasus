<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="{{ view.description|default(constant('_APP_META_DECRIPTION'))|replace({'&':'&amp;'}) }}" />
	<meta name="keywords" content="{{ view.keywords }}" />
	<meta name="robots" content="{% if view.robotsIndexable %}index,follow,{% else %}noindex,nofollow,{% endif %}{% if not view.robotsArchivable %}noarchive,{% endif %}{% if not view.robotsImagesIndexable %}noimageindex,{% endif %}" />
{% if view.robotsIndexable %}<meta name="revisit-after" content="7" />{% endif %}
	{% if not view.googleTranslatable %}<meta name="google" content="notranslate" />{% endif %}
	
	<meta name="rating" content="General" />
	<meta name="distribution" content="Global" />
	<meta name="author" content="{{ constant('_APP_AUTHOR_NAME') }}" />
	<meta name="reply-to" content="{{ constant('_APP_AUTHOR_MAIL') }}" />
	<meta name="owner" content="{{ constant('_APP_OWNER_MAIL') }}" />
	{% if view.refresh %}<meta http-equiv="refresh" content="{{ view.refresh }}" />{% endif %}
	{#
	http://www.google.com/support/news_pub/bin/answer.py?answer=191283
	<meta name="original-source" content="{{ request.url }}">
	<meta name="syndication-source" content="http://www.example.com/wire_story_1.html">
	<meta http-equiv="X-UA-Compatible" content="IE=edge{% if constant('_APP_USE_CHROME_FRAME') %},chrome=1{% endif %}" />
	#}
	<meta name="apple-mobile-web-app-capable" content="{% if view.iosWebappCapable %}yes{% else %}no{% endif %}" />
	<meta name="viewport" content="width={{ view.viewportWidth }}, initial-scale={{ view.viewportIniScale }}, maximum-scale={{ view.viewportMaxScale }}, user-scalable={% if view.viewportUserScalable %}yes{% else %}no{% endif %}" />
	{% if view.allowPrerendering and request.url %}<link rel="prerender" href="{{ request.url }}">{% endif %}