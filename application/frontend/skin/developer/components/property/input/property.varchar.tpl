{include "components/field/field.text.tpl"
    name  = "property[{$property->getId()}]"
    value = $property->getValue()->getValueVarchar()
    note  = $property->getDescription()
    label = $property->getTitle()}