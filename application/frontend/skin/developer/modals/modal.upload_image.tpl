{**
 * Загрузка медиа-файлов
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-image-upload{/block}
{block 'modal_class'}modal-upload-image js-modal-default{/block}
{block 'modal_title'}{lang name='media.title'}{/block}
{block 'modal_attributes'}data-modal-center="false"{/block}

{block 'modal_content_after'}
	{include 'components/media/media.tpl' classes='js-media'}
{/block}

{block 'modal_footer'}{/block}