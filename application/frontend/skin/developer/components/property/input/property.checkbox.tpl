{include "components/field/field.checkbox.tpl"
    name    = "property[{$property->getId()}]"
    value   = 1
    checked = $property->getValue()->getValueInt()
    note    = $property->getDescription()
    label   = $property->getTitle()}