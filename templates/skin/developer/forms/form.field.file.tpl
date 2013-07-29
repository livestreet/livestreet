{**
 * Выбор файла
 *}

{extends file='forms/form.field.base.tpl'}

{block name='field_holder_input'}
	<input type="file" 
		   id="{$sFieldName}" 
		   name="{$sFieldName}" 
		   value="{if $sFieldValue}{$sFieldValue}{/if}" 
		   class="{if $sFieldClasses}{$sFieldClasses}{/if}"
		   {if $bFieldIsDisabled}disabled{/if} />
{/block}