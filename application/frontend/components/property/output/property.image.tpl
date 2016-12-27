{$valueType = $property->getValue()->getValueTypeObject()}

<div class="ls-property">
    <div class="ls-property-list-item-label">
        {$property->getTitle()}
    </div>

    {if $valueType->getImageWebPath()}
        <a href="{$valueType->getImageWebPath()}" class="js-lbx" target="_blank">
            <img src="{$valueType->getImageWebPath( $valueType->getImageSizeFirst() )}" >
        </a>
    {else}
        {lang 'property.image.empty'}
    {/if}
</div>