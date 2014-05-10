{$oValue = $oProperty->getValue()}

{include file="components/field/field.checkbox.tpl"
		 sName    = "property[{$oProperty->getId()}]"
		 bChecked = $oValue->getValueInt()
		 sNote = $oProperty->getDescription()
		 sLabel   = $oProperty->getTitle()}