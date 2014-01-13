{$oValue = $oProperty->getValue()}

{include file="forms/fields/form.field.text.tpl"
		 sFieldName  = "property[{$oProperty->getId()}]"
		 sFieldValue = $oValue->getValueVarchar()
		 sFieldNote = $oProperty->getDescription()
		 sFieldLabel = $oProperty->getTitle()}