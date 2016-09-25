{**
 * Загрузка медиа-файлов
 *}

{extends 'Component@modal.modal'}

{block 'modal_options' append}
    {$classes = "$classes js-modal-media"}
    {$mods = "$mods media"}
    {$title = {lang name='media.title'}}
    {$options = array_merge( $options|default:[], [ 'center' => 'false' ] )}
    {$showFooter = false}
    {$content = {component 'media' template='content'}}
{/block}