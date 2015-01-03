{$valueType = $property->getValue()->getValueTypeObject()}
{$uploadedFiles = $valueType->getImageWebPath( $valueType->getImageSizeFirst() )}

{component 'field' template='image'
    name          = "property[{$property->getId()}][file]"
    removeName    = "property[{$property->getId()}][remove]"
    uploadedFiles = ( $uploadedFiles ) ? [ $uploadedFiles ] : false
    note          = $property->getDescription()
    label         = $property->getTitle()}