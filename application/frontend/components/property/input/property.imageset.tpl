{$valueType = $property->getValue()->getValueTypeObject()}
{$imagePreviewItems = []}
    
{component 'field' template='imageset-ajax'
        label      = 'Фотосет'
        modalTitle = 'Выбор фото'
        targetType = 'imageset'
        targetId   = ( $valueType ) ? $valueType->getId() : ''
        classes    = 'js-imageset-field'}