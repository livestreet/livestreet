{$valueType = $property->getValue()->getValueTypeObject()}

<div class="property">
    <div class="property-list-item-label">
        {$property->getTitle()}
    </div>

    <a href="{$valueType->getImageWebPath()}" class="js-lbx" target="_blank">
        <img src="{$valueType->getImageWebPath( $valueType->getImageSizeFirst() )}" >
    </a>
</div>