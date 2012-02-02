{$rName 	= $request->resource}
{$rProps 	= $_resources.items[$rName]}
{$rSingular = $request->resource}
{$rModel 	= $_columns[$rName].items}

{* TODO: update when multi-edit *}
{$useArray 	= ''}
{$itemIndex = ''}

{* Loop over the resource colums *}
<div class="resource"> 
	<form action="{$request->url}" class="resourceForm update{$rName|capitalize}" id="update{$rName|capitalize}" method="post" enctype="multipart/form-data">
		
		<fieldset>
			<legend><span class="value">{t 1="{$rSingular}"}edit %1 data{/t}</span></legend>
			
			{foreach array_keys($rModel) as $column}
			
				{$row 			= $data[$rName]}
				{$value 		= $row[$column]}
				{$postedVal 	= $smarty.post[$resourceFieldName]|default:null}
				{$colPostName 	= $rSingular|cat:{$column|ucfirst}}
				{$colProps 		= $rModel[$column]}
				{$required		= $colProps.required}
				{$readonly		= true}
				{$type			= $colProps.type}
				
				{include file='default/blocks/common/forms/field.tpl'}
			{/foreach}
	
		</fieldset>
	
	</form>
</div>
