<div class="column">
	<div class="label">
		<span>{$column}</span>
	</div>
	<div class="field">
		{if $type === 'boolean'}
			{include file = 'default/blocks/common/forms/fields/caseBoolean.tpl'}
		{else}
			{include file = 'default/blocks/common/forms/fields/caseDefault.tpl'}
		{/if}
	</div>
</div>
