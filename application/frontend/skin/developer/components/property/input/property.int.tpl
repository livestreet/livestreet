{include "components/field/field.text.tpl"
    name    = "property[{$property->getId()}]"
    value   = $property->getValue()->getValueInt()
    classes = 'width-150'
    note    = $property->getDescription()
    label   = $property->getTitle()}