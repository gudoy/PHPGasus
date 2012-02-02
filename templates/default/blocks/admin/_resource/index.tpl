

{* Case request type rows *}
{if $request->pattern === 'rows'}
{include file='default/blocks/admin/_resource/retrieve/caseRows/list/list.tpl'}

{* Case request type row *}
{elseif $request->pattern === 'row'}
{include file='default/blocks/admin/_resource/retrieve/caseRow/row.tpl'}

{* Case request type columns *}
{elseif $request->pattern === 'columns'}
{include file='default/blocks/admin/_resource/retrieve/caseColumns/columns.tpl'}

{* Case request type column *}
{elseif $request->pattern === 'column'}
{include file='default/blocks/admin/_resource/retrieve/caseColumn/column.tpl'}
{/if}
