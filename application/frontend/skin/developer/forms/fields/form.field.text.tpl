{**
 * Текстовое поле
 *}

{extends file='forms/fields/form.field.base.tpl'}

{block name='field_holder' prepend}
	<input type="{if $sFieldType}{$sFieldType}{else}text{/if}"
		   id="{if $sFieldId}{$sFieldId}{else}{$sFieldName}{/if}" 
		   name="{$sFieldName}" 
		   value="{if isset($sFieldValue)}{$sFieldValue}{elseif isset($_aRequest[$sFieldName])}{$_aRequest[$sFieldName]}{/if}"
		   class="{if $sFieldClasses}{$sFieldClasses}{else}width-full{/if} js-input-{$sFieldName}"
		   {if $sFieldPlaceholder}placeholder="{$sFieldPlaceholder}"{/if}
           {foreach $aFieldRules as $sRule}data-{$sRule} {/foreach}
		   {if $bFieldIsAutofocus}autofocus{/if}
		   {if $bFieldIsDisabled}disabled{/if} />
{/block}