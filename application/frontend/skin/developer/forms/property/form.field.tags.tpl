{$oValue = $oProperty->getValue()}

{include file="forms/fields/form.field.text.tpl"
		 sFieldName  = "property[{$oProperty->getId()}]"
		 sFieldValue = $oValue->getValueVarchar()
		 sFieldId    = "property-value-tags-{$oProperty->getId()}"
		 sFieldLabel = $oProperty->getTitle()}

<script>
	jQuery(function($){
        ls.autocomplete.add($("#property-value-tags-{$oProperty->getId()}"), aRouter['ajax']+'property/tags/autocompleter/', true, { property_id: '{$oValue->getPropertyId()}' });
	});
</script>