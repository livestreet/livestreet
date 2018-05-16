{$valueType = $property->getValueTypeObject()}
{$imagePreviewItems = []}
    
{component 'field' template='imageset-ajax'
        name       = "property[{$property->getId()}]"
        label      = $aLang.property.imageset.label
        modalTitle = $aLang.property.imageset.modalTitle
        targetType = 'imageset'
        targetId   = $valueType->getValueForForm()
        classes    = 'js-imageset-field'}