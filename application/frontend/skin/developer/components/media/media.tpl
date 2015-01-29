{**
 * Загрузка медиа-файлов
 *}

{extends 'Component@modal.modal'}

{block 'modal_options' append}
    {$mods = "$mods media"}
    {$classes = "$classes js-modal-media"}
    {$title = {lang name='media.title'}}
    {$options = array_merge( $options|default:[], [ 'center' => 'false' ] )}
{/block}

{block 'modal_content_after'}
	{include './media-content.tpl'}
{/block}

{* Убираем подвал *}
{block 'modal_footer'}{/block}