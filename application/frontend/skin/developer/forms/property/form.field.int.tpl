{$oValue = $oProperty->getValue()}

{include "components/field/field.text.tpl"
		 name    = "property[{$oProperty->getId()}]"
		 value   = $oValue->getValueInt()
		 classes = 'width-150'
		 note = $oProperty->getDescription()
		 label   = $oProperty->getTitle()}