{$oValue = $oProperty->getValue()}

{include file="components/field/field.textarea.tpl"
		 sName  = "property[{$oProperty->getId()}]"
		 sValue = $oValue->getValueForForm()
		 iRows  = 10
		 sNote = $oProperty->getDescription()
		 sLabel = $oProperty->getTitle()}