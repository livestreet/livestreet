{$oValue = $oProperty->getValue()}

{include "components/field/field.text.tpl"
		 name    = "property[{$oProperty->getId()}]"
		 value   = $oValue->getValueFloat()
		 classes = 'width-150'
		 note = $oProperty->getDescription()
		 label   = $oProperty->getTitle()}