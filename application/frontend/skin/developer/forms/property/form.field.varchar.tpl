{$oValue = $oProperty->getValue()}

{include "components/field/field.text.tpl"
		 name  = "property[{$oProperty->getId()}]"
		 value = $oValue->getValueVarchar()
		 note = $oProperty->getDescription()
		 label = $oProperty->getTitle()}