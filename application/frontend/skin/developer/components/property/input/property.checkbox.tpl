{include "components/field/field.checkbox.tpl"
    name    = "property[{$property->getId()}]"
    value   = $property->getParam( 'default_value' )
    checked = $property->getValue()->getValueForForm()
    note    = $property->getDescription()
    label   = $property->getTitle()}