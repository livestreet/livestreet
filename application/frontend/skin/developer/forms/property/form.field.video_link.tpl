{$oValue = $oProperty->getValue()}

{include "components/field/field.text.tpl"
		 name    = "property[{$oProperty->getId()}]"
		 value   = $oValue->getValueVarchar()
		 classes = 'width-300'
		 note = $oProperty->getDescription()
		 label   = $oProperty->getTitle()}

{include "modals/modal.property_type_video.tpl" oValue=$oValue}
<p class="mb-20"><a href="#" class="link-dotted" data-modal-target="modal-property-type-video-{$oValue->getId()}">Смотреть</a></p>