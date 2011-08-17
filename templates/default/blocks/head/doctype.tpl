{strip}

{$doctype 			= $view.doctype|default:$smarty.const._APP_DOCTYPE|default:'html5'}
{$dtd 				= ''}
{$attrs 			= ''}
{$curLang 			= $smarty.session.lang|default:$smarty.const._APP_DEFAULT_LANGUAGE|truncate:2:''}
{$xmlns 			= ' xmlns="http://www.w3.org/1999/xhtml"'}
{$xmllang 			= 'xml:lang="'|cat:$curLang|cat:'"'}
{$langAttr 			= 'lang="'|cat:$curLang|cat:'"'}

{if $doctype === 'html5' && $request->outputFormat !== 'xhtml'}
{elseif $doctype === 'xhtml-strict-1.1'}
	{$dtd 		= ' PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"'}
	{$attrs 	= " $xmlns $xmllang"}
{elseif $doctype === 'xhtml-strict'}
	{$dtd 		= ' PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"'}
	{$attrs 	= " $xmlns $langAttr $xmllang"}
{else}
	{$dtd 		= ' PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"'}
	{$attrs 	= " $xmlns $langAttr $xmllang"}
{/if}
{/strip}
<!DOCTYPE html{$doctypeCompl}>
<html{if $view.smartname} {$view.smartname}{/if} id="{$view.name}" class="no-js {$view.classes}"{if $smarty.const._APP_USE_MANIFEST} manifest="{$smarty.const._APP_MANIFEST_FILENAME}"{/if}{$attributes}{$htmlAttrbitues}>
