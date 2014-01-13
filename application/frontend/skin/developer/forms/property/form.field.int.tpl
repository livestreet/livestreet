{$oValue = $oProperty->getValue()}

{include file="forms/fields/form.field.text.tpl"
		 sFieldName    = "property[{$oProperty->getId()}]"
		 sFieldValue   = $oValue->getValueInt()
		 sFieldClasses = 'width-150'
		 sFieldNote = $oProperty->getDescription()
		 sFieldLabel   = $oProperty->getTitle()}