{component 'field' template='textarea'
    name  = "property[{$property->getId()}]"
    value = $property->getValue()->getValueForForm()
    rows  = 10
    note  = $property->getDescription()
    label = $property->getTitle()}