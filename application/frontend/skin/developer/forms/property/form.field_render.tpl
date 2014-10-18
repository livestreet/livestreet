{$type = $oProperty->getType()}

<div class="js-property-field-area property-type-{$oProperty->getType()} property-type-{$oProperty->getTargetType()}-{$oProperty->getType()}" data-property-id="{$oProperty->getId()}">
	{include "forms/property/form.field.{$type}.tpl" oProperty=$oProperty}
</div>