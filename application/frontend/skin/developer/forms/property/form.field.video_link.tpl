{$oValue = $oProperty->getValue()}

{include file="components/field/field.text.tpl"
		 sName    = "property[{$oProperty->getId()}]"
		 sValue   = $oValue->getValueVarchar()
		 sClasses = 'width-300'
		 sNote = $oProperty->getDescription()
		 sLabel   = $oProperty->getTitle()}

{include file="modals/modal.property_type_video.tpl" oValue=$oValue}
<p class="mb-20"><a href="#" class="link-dotted" data-modal-target="modal-property-type-video-{$oValue->getId()}">Смотреть</a></p>