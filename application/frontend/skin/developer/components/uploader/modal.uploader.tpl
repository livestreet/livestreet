{**
 * Загрузка медиа-файлов
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}{$smarty.local.id}{/block}
{block 'modal_class'}modal--uploader {$smarty.local.classes}{/block}
{block 'modal_title'}{$smarty.local.title}{/block}
{block 'modal_attributes'}data-modal-center="false"{/block}

{block 'modal_content'}
    {include 'components/uploader/uploader.tpl' classes='js-uploader-modal'}
{/block}

{block 'modal_footer_cancel' append}
    {include 'components/button/button.tpl' type='button' mods='primary' text={lang 'common.choose'} classes='js-uploader-modal-choose'}
{/block}