{% block html %}
{% block doctype %}{% include 'default/blocks/head/doctype.twig.tpl' %}{% endblock %}
{% block head %}<head>
	<title>{{ view.title|default(constant('_APP_TITLE')) }}</title>
	{% block metas %}{% include 'default/blocks/head/metas/metas.twig.tpl' %}{% endblock %}
	
	{% block icons %}{% include 'default/blocks/head/icons.twig.tpl' %}{% endblock %}
	{#
	{% block css %}{% include 'default/blocks/head/css/css.twig.tpl' %}{% endblock %}
	#}
	{% block html5shiv %}{% include 'default/blocks/head/js/html5.twig.tpl' %}{% endblock %}
	{% block googleAnalytics %}{% include 'default/blocks/head/js/googleAnalytics.twig.tpl' %}

</head>{% endblock %}
{% block body %}
<body>
	{% block pageContent %}{% endblock %}
</body>
{% endblock %}
</html>
{% endblock %}