{include "components/field/field.date.tpl"
    name    = "property[{$property->getId()}][date]"
    value   = $property->getValue()->getValueForForm()
    note    = $property->getDescription()
    label   = $property->getTitle()
    useTime = $property->getParam( 'use_time' )}