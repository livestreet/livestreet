{* TODO: Форма с селектом не отправляется (аякс ошибка) *}

{$oValue=$oProperty->getValue()}
{$aValues=$oValue->getValueForForm()}
{$aSelectItems=$oProperty->getSelects()}

{$oProperty->getTitle()}:
<br>

<select name="property[{$oProperty->getId()}][]" {if $oProperty->getValidateRuleOne('allowMany')}multiple="multiple" class="select-multiple" {/if}>
	{if $oProperty->getValidateRuleOne('allowEmpty')}
		<option value=""></option>
	{/if}

	{foreach $aSelectItems as $oSelectItem}
        <option value="{$oSelectItem->getId()}" {if isset($aValues[$oSelectItem->getId()])}selected="selected"{/if}>{$oSelectItem->getValue()}</option>
	{/foreach}
</select>

<br/><br/>