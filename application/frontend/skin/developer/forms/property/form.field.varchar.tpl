{$oValue = $oProperty->getValue()}

{include file="components/field/field.text.tpl"
		 sName  = "property[{$oProperty->getId()}]"
		 sValue = $oValue->getValueVarchar()
		 sNote = $oProperty->getDescription()
		 sLabel = $oProperty->getTitle()}