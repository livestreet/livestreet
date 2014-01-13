{**
 * Выбор файла
 *}

{extends file='forms/fields/form.field.base.tpl'}

{block name='field_holder' prepend}
	<input type="file" 
		   id="{if $sFieldId}{$sFieldId}{else}{$sFieldName}{/if}" 
		   name="{$sFieldName}"
		   class="{if $sFieldClasses}{$sFieldClasses}{/if}"
		   {if $bFieldIsDisabled}disabled{/if} />
{/block}