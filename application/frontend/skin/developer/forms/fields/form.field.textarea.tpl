{extends file='forms/fields/form.field.base.tpl'}

{block name='field_holder' prepend}
	<textarea id="{$sFieldName}" 
			  name="{$sFieldName}" 
			  class="{if $sFieldClasses}{$sFieldClasses}{else}width-full{/if}" 
			  rows="{$iFieldRows}"
			  {if $sFieldPlaceholder}placeholder="{$sFieldPlaceholder}"{/if}
			  {if $bFieldIsDisabled}disabled{/if}
			  {foreach $aFieldRules as $sRule}data-{$sRule} {/foreach}>{if $sFieldValue}{$sFieldValue|escape:'html'}{else}{if $_aRequest[$sFieldName]}{$_aRequest[$sFieldName]}{/if}{/if}</textarea>
{/block}