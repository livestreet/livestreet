{**
 * Выбор файла
 *}

{extends file='forms/form.field.base.tpl'}

{block name='field_holder' prepend}
	<input type="file" 
		   id="{$sFieldName}" 
		   name="{$sFieldName}"
		   class="{if $sFieldClasses}{$sFieldClasses}{/if}"
		   {if $bFieldIsDisabled}disabled{/if} />
{/block}