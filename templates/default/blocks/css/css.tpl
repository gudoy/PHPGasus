{$version 		= 'v='|cat:$smarty.const._CSS_VERSION|default:''}
{* The following CC prevents IE8 from blocking css & scripts download until the main css is arrived
{* cf: http://www.phpied.com/conditional-comments-block-downloads/ *}
<!--[if IE]><![endif]-->
{foreach $view.css as $css}
	<link rel="stylesheet" href="{$css}{if strpos($css, '?') !== false}&{else}?{/if}{$version}" media="screen" />
{/foreach}
{block name='cssIE'}{include file='default/blocks/css/IE/ie.tpl'}{/block}
{block name='dynamicCss'}{/block}