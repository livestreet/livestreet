{component 'field' template='date'
    name    = "property[{$property->getId()}][date]"
    value   = $property->getValue()->getValueForForm()
    note    = $property->getDescription()
    label   = $property->getTitle()
    useTime = $property->getParam( 'use_time' )}