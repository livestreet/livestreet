{$value = $property->getValue()}

{component 'field' template='text'
    name    = "property[{$property->getId()}]"
    value   = $value->getValueVarchar()
    note    = $property->getDescription()
    label   = $property->getTitle()}

{include './modal.property-input-video.tpl' value=$value}

<p class="mb-20">
    <a href="#" class="link-dotted" data-modal-target="modal-property-type-video-{$value->getId()}">Смотреть</a>
</p>