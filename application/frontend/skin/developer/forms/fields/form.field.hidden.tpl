{**
 * Скрытое поле
 *}

<input type="hidden" 
	   id="{if $sFieldId}{$sFieldId}{else}{$sFieldName}{/if}" 
	   name="{$sFieldName}" 
	   value="{if $sFieldValue}{$sFieldValue}{elseif $_aRequest[$sFieldName]}{$_aRequest[$sFieldName]}{/if}" />