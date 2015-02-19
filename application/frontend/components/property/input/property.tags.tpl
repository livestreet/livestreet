{$value = $property->getValue()}

{component 'field' template='text'
    name  = "property[{$property->getId()}]"
    value = $value->getValueVarchar()
    id    = "property-value-tags-{$property->getId()}"
    note  = $property->getDescription()
    label = $property->getTitle()}

<script>
    jQuery(function($) {
        $( "#property-value-tags-{$property->getId()}" ).lsAutocomplete({
            multiple: true,
            urls: {
                load: aRouter['ajax']+'property/tags/autocompleter/'
            },
            params: {
                property_id: '{$value->getPropertyId()}'
            }
        });
    });
</script>