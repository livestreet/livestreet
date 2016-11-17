{component 'field.time'
    name         = "property[{$property->getId()}][date]"
    inputClasses = "js-field-{$template}-default"
    value        = $property->getValue()->getValueForForm()
    note         = $property->getDescription()
    label        = $property->getTitle()}