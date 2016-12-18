{**
 * Загрузка медиа-файлов
 *}

{extends 'Component@modal.modal'}

{block 'modal_options' append}
    {$classes = "$classes ls-media js-modal-media"}
    {$title = {lang name='media.title'}}
    {$options = array_merge( $options|default:[], [ 'center' => 'false' ] )}
    {$showFooter = false}
    {$body = {component 'media' template='content'}}
{/block}