{* TODO: Добавить поля datetime и time *}

{$template = ($property->getParam( 'use_time' )) ? 'datetime' : 'date'}

{component 'field' template=$template
    name         = "property[{$property->getId()}][date]"
    inputClasses = "js-field-{$template}-default"
    value        = $property->getValue()->getValueForForm()
    note         = $property->getDescription()
    label        = $property->getTitle()}