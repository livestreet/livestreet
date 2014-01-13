{$oValue = $oProperty->getValue()}

{include file="forms/fields/form.field.textarea.tpl"
		 sFieldName  = "property[{$oProperty->getId()}]"
		 sFieldValue = $oValue->getValueForForm()
		 iFieldRows  = 10
		 sFieldNote = $oProperty->getDescription()
		 sFieldLabel = $oProperty->getTitle()}