{**
 * Кнопка
 *}

<button type="{if $sFieldType}{$sFieldType}{else}submit{/if}" 
	    id="{$sFieldName}" 
	    name="{$sFieldName}" 
	    value="{if isset($sFieldValue)}{$sFieldValue}{else}{if isset($_aRequest[$sFieldName])}{$_aRequest[$sFieldName]}{/if}{/if}"
	    class="button {if $sFieldStyle}button-{$sFieldStyle}{/if} {$sFieldClasses}"
	    {if $bFieldIsDisabled}disabled{/if}>
	{if $sFieldIcon}<i class="{$sFieldIcon}"></i>{/if}
	{$sFieldText}
</button>