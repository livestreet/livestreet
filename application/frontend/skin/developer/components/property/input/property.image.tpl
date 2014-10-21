{$valueType = $property->getValue()->getValueTypeObject()}
{$uploadedFiles = $valueType->getImageWebPath( $valueType->getImageSizeFirst() )}

{include 'components/field/field.image.tpl'
    name          = "property[{$property->getId()}][file]"
    removeName    = "property[{$property->getId()}][remove]"
    uploadedFiles = ( $uploadedFiles ) ? [ $uploadedFiles ] : false
    note          = $property->getDescription()
    label         = $property->getTitle()}