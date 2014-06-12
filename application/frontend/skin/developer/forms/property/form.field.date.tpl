{$oValue = $oProperty->getValue()}
{$oValueType = $oValue->getValueTypeObject()}

{include file="components/field/field.text.tpl"
		sName         = "property[{$oProperty->getId()}][date]"
		sValue        = $oValue->getValueForForm()
		sInputClasses = 'width-150 js-date-picker'
		sNote         = $oProperty->getDescription()
		sLabel        = $oProperty->getTitle()}

{if $oProperty->getParam('use_time')}

	<select name="property[{$oProperty->getId()}][time][h]">
		{section name=time_h start=0 loop=24  step=1}
			<option value="{$smarty.section.time_h.index}" {if $oValueType->getValueTimeH()==$smarty.section.time_h.index}selected="selected" {/if}>{$smarty.section.time_h.index}</option>
		{/section}
	</select>
	:
	<select name="property[{$oProperty->getId()}][time][m]">
	{section name=time_m start=0 loop=60  step=5}
		<option value="{$smarty.section.time_m.index}" {if $oValueType->getValueTimeM()==$smarty.section.time_m.index}selected="selected" {/if}>{$smarty.section.time_m.index}</option>
	{/section}
	</select>

	<br/>
	<br/>

{/if}