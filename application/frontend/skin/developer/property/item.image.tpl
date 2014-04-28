{$oValue=$oPropertyItem->getValue()}
{$oValueType = $oValue->getValueTypeObject()}
<div class="property-list-item">
    <div class="property-list-item-label">{$oPropertyItem->getTitle()}</div>
    <a href="{$oValueType->getImageWebPath()}" class="js-lbx" target="_blank"><img src="{$oValueType->getImageWebPath($oValueType->getImageSizeFirst())}" ></a> <br/>
</div>