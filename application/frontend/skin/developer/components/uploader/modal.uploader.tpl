{**
 * Загрузка медиа-файлов
 *}

{extends 'Component@modal.modal'}

{block 'modal_options' append}
    {$mods = "$mods uploader"}
    {$options = array_merge( $options|default:[], [ 'center' => 'false' ] )}
{/block}

{block 'modal_content'}
    {component 'uploader' classes='js-uploader-modal'}
{/block}

{block 'modal_footer_cancel' append}
    {component 'button' type='button' mods='primary' text={lang 'common.choose'} classes='js-uploader-modal-choose'}
{/block}