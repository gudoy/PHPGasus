{strip}
{$chain=''}
{$jsBasePath={$smarty.const._URL_JAVASCRIPTS_REL|regex_replace:'/^\/(.*)/':'$1'}}
{foreach $data.js as $item}
{* If the file link is asbolute, do not add base path *}
{if strpos($item, 'http://') !== false || strpos($item, 'http://') !== false}{$basePath=''}{else}{$basePath=$jsBasePath}{/if}
{if !$item@last}{$sep=','}{else}{$sep=''}{/if}
{$chain=$chain|cat:$basePath|cat:$item|cat:$sep}
{/foreach}
{if $chain !== ''}
<script src="{$smarty.const._URL_PUBLIC}min/?f={$chain}&{$version}"></script>
{/if}
{/strip}