{**
 * Загрузка медиа-файлов
 *}

{extends 'Component@modal.modal'}

{block 'modal_options' append}
    {$mods = "$mods media"}
    {$classes = "$classes js-modal-media"}
    {$title = {lang name='media.title'}}
    {$options = array_merge( $options|default:[], [ 'center' => 'false' ] )}
    {$showFooter = false}
    {$body = {include './media-content.tpl'}}
{/block}