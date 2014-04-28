{**
 * Модальное окно с предпросмотром видео для свойства с типом video
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block name='modal_id'}modal-property-type-video-{$oValue->getId()}{/block}
{block name='modal_class'}modal-property js-modal-default{/block}
{block name='modal_title'}Предпросмотр видео{/block}

{block name='modal_content'}
	<div>
        {$oValue->getValueTypeObject()->getVideoCodeFrame()}
	</div>
{/block}

{block name='modal_footer'}{/block}