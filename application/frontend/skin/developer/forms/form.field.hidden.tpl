{**
 * Скрытое поле
 *}

<input type="hidden" 
	   id="{$sFieldName}" 
	   name="{$sFieldName}" 
	   value="{if $sFieldValue}{$sFieldValue}{else}{if $_aRequest[$sFieldName]}{$_aRequest[$sFieldName]}{/if}{/if}" />