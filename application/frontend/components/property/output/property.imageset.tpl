{$valueType = $property->getValue()->getValueTypeObject()}

<div class="ls-property">
    <div class="ls-property-list-item-label">
        {$property->getTitle()}
    </div>
    
    {$aMedia = $valueType->getMedia()}
    
    {if $aMedia}
        <div class="ls-property-list-item-label js-lbx-imageset" data-group="group{$valueType->getValueObject()->getId()}">
            {foreach $aMedia as $oMedia}
                <a href="{$oMedia->getFileWebPath()}" class="js-lbx" target="_blank">
                    <img src="{$oMedia->getFileWebPath( $property->getParam('size') )}" >
                </a>
            {/foreach}
        </div>
    {else}
        {lang 'property.image.empty'}
    {/if}
</div>