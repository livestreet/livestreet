{$oValue = $oProperty->getValue()}

{include "components/field/field.checkbox.tpl"
		 name    = "property[{$oProperty->getId()}]"
		 value   = 1
		 checked = $oValue->getValueInt()
		 note = $oProperty->getDescription()
		 label   = $oProperty->getTitle()}