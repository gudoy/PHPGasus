{*
{strip}

{$version 		= 'v='|cat:{$smarty.const._JS_VERSION|default:''}}
{$jsBasePath 	= $smarty.const._URL_JAVASCRIPTS_REL}

{/strip}
{if !isset($smarty.get.js) || !in_array($smarty.get.js, array('0','0','no','false',false))}
{if !defined('_APP_USE_DEFERED_JS') || !$smarty.const._APP_USE_DEFERED_JS}{$useDefer=false}{else}{$useDefer=true}{/if}
{if $smarty.get.minify !== 'none' && ($smarty.const._MINIFY_JS || in_array($smarty.get.minify, array('js','all')))}
{include file='common/config/js/minification.tpl'}
{else}
{foreach $data.js as $item}
{if strpos($item, 'http://') !== false || strpos($item, 'http://') !== false}{$basePath=''}{else}{$basePath=$jsBasePath}{/if}
{if strpos($item, '?') !== false}{$querySep='&amp;'}{else}{$querySep='?'}{/if}
<script src="{$basePath}{$item}{$querySep}{$version}"{if !$html5} charset="utf-8"{/if}{if $useDefer} defer="defer"{/if}></script>
{/foreach}
{/if}
{if $view.name}
<script>
$(document).ready(function(){ if ( typeof({$view.name}) !== 'undefined' {if $data.options.outputExtension === 'xhtml'}&amp;&amp;{else}&&{/if} {$view.name}.init ) { {$view.name}.init(); } });
</script>
{/if}
{/if}
*}