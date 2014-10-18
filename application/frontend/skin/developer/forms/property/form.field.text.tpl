{$oValue = $oProperty->getValue()}

{include "components/field/field.textarea.tpl"
		 name  = "property[{$oProperty->getId()}]"
		 value = $oValue->getValueForForm()
		 rows  = 10
		 note = $oProperty->getDescription()
		 label = $oProperty->getTitle()}