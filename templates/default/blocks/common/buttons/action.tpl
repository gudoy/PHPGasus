{* options: mode, class, id, title, href, value, type, label *}

{if $mode === 'input'}
	<input type="button" class="action {$class}"{if $id} id="{$id}"{/if}{if $title} title="{$title}"{/if} />
{elseif $mode === 'button'}
<div class="action {$class}"{if $id} id="{$id}"{/if}{if $title} title="{$title}"{/if}>
	<button type="{$type|default:'button'}" {if $value}name="{$name}"{/if} {if $value}value="{$value}"{/if}>{$label|escape:'html'}</button>
</div>
{else}
<a {if $id}id="{$id}"{/if} class="action {$class}" {if $href}href="{$href}{if $smarty.get.redirect}?redirect={$smarty.get.redirect}{/if}"{/if}{if $title} title="{$title}"{/if}><span class="value">{$label|escape:'html'|gettext}</span></a>
{/if}