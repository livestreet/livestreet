{**
 * Чекбокс
 *}

{extends file='forms/form.field.base.tpl'}

{block name='field_holder_input'}
	{if $sFieldLabel}<label>{/if}
	<input type="checkbox" 
		   id="{$sFieldName}" 
		   name="{$sFieldName}" 
		   value="{if $sFieldValue}{$sFieldValue}{else}1{/if}" 
		   {if $bFieldChecked}checked{else}{if $_aRequest[$sFieldName] == 1}checked{/if}{/if} />
	{if $sFieldLabel}{$sFieldLabel}</label>{/if}
{/block}