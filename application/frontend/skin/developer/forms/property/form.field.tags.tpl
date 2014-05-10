{$oValue = $oProperty->getValue()}

{include file="components/field/field.text.tpl"
		 sName  = "property[{$oProperty->getId()}]"
		 sValue = $oValue->getValueVarchar()
		 sId    = "property-value-tags-{$oProperty->getId()}"
		 sNote = $oProperty->getDescription()
		 sLabel = $oProperty->getTitle()}

<script>
	jQuery(function($){
        ls.autocomplete.add($("#property-value-tags-{$oProperty->getId()}"), aRouter['ajax']+'property/tags/autocompleter/', true, { property_id: '{$oValue->getPropertyId()}' });
	});
</script>