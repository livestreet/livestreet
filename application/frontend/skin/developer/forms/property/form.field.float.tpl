{$oValue = $oProperty->getValue()}

{include file="components/field/field.text.tpl"
		 sName    = "property[{$oProperty->getId()}]"
		 sValue   = $oValue->getValueFloat()
		 sClasses = 'width-150'
		 sNote = $oProperty->getDescription()
		 sLabel   = $oProperty->getTitle()}