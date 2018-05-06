{$valueType = $property->getValue()->getValueTypeObject()}
{$imagePreviewItems = []}
    
{component 'field' template='imageset-ajax'
        name       = "property[{$property->getId()}]"
        label      = 'Фотосет'
        modalTitle = 'Выбор фото'
        targetType = 'imageset'
        targetId   = ( $valueType ) ? $valueType->getId() : ''
        classes    = 'js-imageset-field'}