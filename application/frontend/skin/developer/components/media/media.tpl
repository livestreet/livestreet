{**
 * Загрузка медиа-файлов
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}{$smarty.local.id}{/block}
{block 'modal_class'}modal--media js-modal-media{/block}
{block 'modal_title'}{lang name='media.title'}{/block}
{block 'modal_attributes'}data-modal-center="false"{/block}

{block 'modal_content_after'}
	{include 'components/media/media-content.tpl'}
{/block}

{block 'modal_footer'}{/block}