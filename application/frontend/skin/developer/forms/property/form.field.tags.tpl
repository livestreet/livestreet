{$oValue = $oProperty->getValue()}

{include "components/field/field.text.tpl"
		 name  = "property[{$oProperty->getId()}]"
		 value = $oValue->getValueVarchar()
		 id    = "property-value-tags-{$oProperty->getId()}"
		 note = $oProperty->getDescription()
		 label = $oProperty->getTitle()}

<script>
	jQuery(function($){
        ls.autocomplete.add($("#property-value-tags-{$oProperty->getId()}"), aRouter['ajax']+'property/tags/autocompleter/', true, { property_id: '{$oValue->getPropertyId()}' });
	});
</script>