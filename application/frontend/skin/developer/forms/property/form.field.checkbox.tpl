{$oValue = $oProperty->getValue()}

{include file="components/field/field.checkbox.tpl"
		 sName    = "property[{$oProperty->getId()}]"
		 sValue   = 1
		 bChecked = $oValue->getValueInt()
		 sNote = $oProperty->getDescription()
		 sLabel   = $oProperty->getTitle()}