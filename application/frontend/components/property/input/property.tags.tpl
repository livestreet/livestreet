{$value = $property->getValue()}

{component 'field' template='text'
    name  = "property[{$property->getId()}]"
    value = $value->getValueVarchar()
    id    = "property-value-tags-{$property->getId()}"
    note  = $property->getDescription()
    label = $property->getTitle()}

<script>
    jQuery(function($) {
        ls.autocomplete.add(
        	$( "#property-value-tags-{$property->getId()}" ),
        	aRouter.ajax + 'property/tags/autocompleter/',
        	true,
        	{ property_id: '{$value->getPropertyId()}' }
        );
    });
</script>