{component 'field' template='text'
    name    = "property[{$property->getId()}]"
    value   = $property->getValue()->getValueForForm()
    classes = 'ls-width-150'
    note    = $property->getDescription()
    label   = $property->getTitle()}