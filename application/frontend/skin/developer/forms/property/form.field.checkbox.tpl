{$oValue = $oProperty->getValue()}

{include file="forms/fields/form.field.checkbox.tpl"
		 sFieldName    = "property[{$oProperty->getId()}]"
		 bFieldChecked = $oValue->getValueInt()
		 sFieldNote = $oProperty->getDescription()
		 sFieldLabel   = $oProperty->getTitle()}