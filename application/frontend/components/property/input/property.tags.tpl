{$value = $property->getValue()}

{component 'field' template='text'
    name  = "property[{$property->getId()}]"
    value = $value->getValueVarchar()
    id    = "property-value-tags-{$property->getId()}"
    inputAttributes=[ "data-property-id" => $property->getId() ]
    inputClasses="autocomplete-property-tags-sep"
    note  = $property->getDescription()
    label = $property->getTitle()}