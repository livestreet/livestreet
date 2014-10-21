{**
 * Модальное окно с предпросмотром видео для свойства с типом video
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-property-type-video-{$value->getId()}{/block}
{block 'modal_class'}modal-property js-modal-default{/block}
{block 'modal_title'}Предпросмотр видео{/block}

{block 'modal_content'}
    {$value->getValueTypeObject()->getVideoCodeFrame()}
{/block}

{block 'modal_footer'}{/block}