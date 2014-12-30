{**
 * Загрузка медиа-файлов
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_options' append}
    {$mods = "$mods uploader"}
    {$attributes = array_merge( $attributes|default:[], [ 'data-modal-center' => 'false' ] )}
{/block}

{block 'modal_content'}
    {include 'components/uploader/uploader.tpl' classes='js-uploader-modal'}
{/block}

{block 'modal_footer_cancel' append}
    {include 'components/button/button.tpl' type='button' mods='primary' text={lang 'common.choose'} classes='js-uploader-modal-choose'}
{/block}