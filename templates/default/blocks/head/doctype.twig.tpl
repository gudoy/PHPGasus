{% spaceless %}
{% set doctype = view.doctype|default(constant('_APP_DOCTYPE'))|default('html5') %}
{% set dtd = '' %}
{% set attrs = '' %}
{% set curLang = session.lang|default(constant('_APP_DEFAULT_LANGUAGE'))|truncate(2) %}
{% set xmlns = ' xmlns="http://www.w3.org/1999/xhtml"' %}
{% set xmllang = 'xml:lang="' ~ curLang ~ '"' %}
{% set langAttr = 'lang="' ~ curLang ~ '"' %}

{% if doctype is sameas('html5') and request.outputFormat is not sameas('xhtml') %}
	{% set attrs 	= ' ' ~ langAttr ~ ' ' ~ xmllang %}
{% elseif doctype is sameas('xhtml-strict-1.1') %}
	{% set dtd 		= ' PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"' %}
	{% set attrs 	= ' ' ~ xmlns ~ ' ' ~ xmllang %}
{% elseif doctype is sameas('xhtml-strict') %}
	{% set dtd 		= ' PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"' %}
	{% set attrs 	= ' ' ~ xmlns ~ ' ' ~ langAtt ~ ' ' ~ xmllang %}
{% else %}
	{% set dtd 		= ' PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"' %}
	{% set attrs 	= ' ' ~ xmlns ~ ' ' ~ langAtt ~ ' ' ~ xmllang %}
{% endif %}
{% endspaceless %}
<!DOCTYPE html{{ doctypeCompl }}>
<html{% if view.smartname %} {{ view.smartname }}{% endif %} id="{{ view.name }}" class="no-js {{ view.classes }}"{% if constant('_APP_USE_MANIFEST') %} manifest="{{ constant('_APP_MANIFEST_FILENAME') }}"{% endif %}{{ attributes }}{{ attrs }}>
