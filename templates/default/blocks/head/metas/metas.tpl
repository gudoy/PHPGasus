<<<<<<< HEAD
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="{$view.description|default:$smarty.const._APP_META_DECRIPTION|replace:'&':'&amp;'}" />
	<meta name="keywords" content="{$view.keywords}" />
	<meta name="robots" content="{if $view.robotsIndexable}index,follow,{else}noindex,nofollow,{/if}{if !$view.robotsArchivable}noarchive,{/if}{if !$view.robotsImagesIndexable}noimageindex,{/if}" />
{if $view.robotsIndexable}<meta name="revisit-after" content="7" />{/if}
	{if !$view.googleTranslatable}<meta name="google" content="notranslate" />{/if}
	
	<meta name="rating" content="General" />
	<meta name="distribution" content="Global" />
	<meta name="author" content="{$smarty.const._APP_AUTHOR_NAME}" />
	<meta name="reply-to" content="{$smarty.const._APP_AUTHOR_MAIL}" />
	<meta name="owner" content="{$smarty.const._APP_OWNER_MAIL}" />
	{if $view.refresh}<meta http-equiv="refresh" content="{$view.refresh}" />{/if}
	{*
	http://www.google.com/support/news_pub/bin/answer.py?answer=191283
	<meta name="original-source" content="{$smarty.const._URL}{* current url *}{*">
	<meta name="syndication-source" content="http://www.example.com/wire_story_1.html">
	*}
	{*<meta http-equiv="X-UA-Compatible" content="IE=edge{if $smarty.const._APP_USE_CHROME_FRAME},chrome=1{/if}" />*}
	<meta name="apple-mobile-web-app-capable" content="{if $view.iosWebappCapable}yes{else}no{/if}" />
	<meta name="viewport" content="width={$view.viewportWidth}, initial-scale={$view.viewportIniScale}, maximum-scale={$view.viewportMaxScale}, user-scalable={if $view.viewportUserScalable}yes{else}no{/if}" />
	
=======
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="{$view.description|default:$smarty.const._APP_META_DECRIPTION|replace:'&':'&amp;'}" />
	<meta name="keywords" content="{$view.keywords}" />
	<meta name="robots" content="{if $view.robotsIndexable}index,follow,{else}noindex,nofollow,{/if}{if !$view.robotsArchivable}noarchive,{/if}{if !$view.robotsImagesIndexable}noimageindex,{/if}" />
{if $view.robotsIndexable}<meta name="revisit-after" content="7" />{/if}
	{if !$view.googleTranslatable}<meta name="google" content="notranslate" />{/if}
	
	<meta name="rating" content="General" />
	<meta name="distribution" content="Global" />
	<meta name="author" content="{$smarty.const._APP_AUTHOR_NAME}" />
	<meta name="reply-to" content="{$smarty.const._APP_AUTHOR_MAIL}" />
	<meta name="owner" content="{$smarty.const._APP_OWNER_MAIL}" />
	{if $view.refresh}<meta http-equiv="refresh" content="{$view.refresh}" />{/if}
	{*
	http://www.google.com/support/news_pub/bin/answer.py?answer=191283
	<meta name="original-source" content="{$smarty.const._URL}{* current url *}{*">
	<meta name="syndication-source" content="http://www.example.com/wire_story_1.html">
	*}
	{*<meta http-equiv="X-UA-Compatible" content="IE=edge{if $smarty.const._USE_CHROME_FRAME},chrome=1{/if}" />*}
	<meta name="apple-mobile-web-app-capable" content="{if $view.iosWebappCapable}yes{else}no{/if}" />
	<meta name="viewport" content="width={$view.viewportWidth}, initial-scale={$view.viewportIniScale}, maximum-scale={$view.viewportMaxScale}, user-scalable={if $view.viewportUserScalable}yes{else}no{/if}" />
	
>>>>>>> 4b7d7f5ab0689830a94602ff9dd5b692a524dda3
	{if $view.allowPrerendering && $request->url}<link rel="prerender" href="{$request->url}">{/if}