{**
 * Модальное окно с предпросмотром видео для свойства с типом video
 *}

{extends 'Component@modal.modal'}

{block 'modal_options' append}
    {$id = "modal-property-type-video-{$value->getId()}"}
    {$mods = "$mods property property-video"}
    {$classes = "$classes js-modal-media"}
    {$title = 'Предпросмотр видео'}
{/block}

{block 'modal_content'}
    {$value->getValueTypeObject()->getVideoCodeFrame()}
{/block}