<label class="inline" for="{$colPostName}{$itemIndex}Y">
	<input type="radio" name="{$colPostName}{$useArray}" id="{$colPostName}{$itemIndex}Y" class="check multi" {if $checked}checked="checked"{/if} {if !$disabled}disabled="disabled"{/if} value="1"{if $required} required="required"{/if} />
	<span>{t}Yes{/t}</span>
</label>
<label class="span multi" for="{$colPostName}{$itemIndex}N">
	<input type="radio" name="{$colPostName}{$useArray}" id="{$colPostName}{$itemIndex}N" class="check multi" {if !$checked}checked="checked"{/if} {if !$disabled}disabled="disabled"{/if}  value="0"{if $required} required="required"{/if} />
	<span>{t}No{/t}</span>
</label>