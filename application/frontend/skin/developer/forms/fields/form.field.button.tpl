{**
 * Кнопка
 *}

<button type="{if $sFieldType}{$sFieldType}{else}submit{/if}" 
	    id="{if $sFieldId}{$sFieldId}{else}{$sFieldName}{/if}" 
	    name="{$sFieldName}" 
	    value="{if isset($sFieldValue)}{$sFieldValue}{elseif isset($_aRequest[$sFieldName])}{$_aRequest[$sFieldName]}{/if}"
	    class="button {if $sFieldStyle}button-{$sFieldStyle}{/if} {$sFieldClasses}"
	    {if $bFieldIsDisabled}disabled{/if}>
	{if $sFieldIcon}<i class="{$sFieldIcon}"></i>{/if}
	{$sFieldText}
</button>