

{* Case request type rows *}
{if $request->pattern === 'rows'}
{include file='default/blocks/admin/_resource/list/list.tpl'}

{* Case request type row *}
{elseif $request->pattern === 'row'}
{include file='default/blocks/admin/_resource/retrieve/row.tpl'}

{* Case request type columns *}
{elseif $request->pattern === 'columns'}
{include file='default/blocks/admin/_resource/retrieve/columns.tpl'}

{* Case request type column *}
{elseif $request->pattern === 'column'}
{include file='default/blocks/admin/_resource/retrieve/column.tpl'}
{/if}
