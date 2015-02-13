{**
 * Модальное окно с предпросмотром видео для свойства с типом video
 *}

{component 'modal'
    title   = {lang 'property.video.preview'}
    content = $value->getValueTypeObject()->getVideoCodeFrame()
    classes = 'js-modal-default'
    mods    = 'property property-video'
    id      = "modal-property-type-video-{$value->getId()}"}