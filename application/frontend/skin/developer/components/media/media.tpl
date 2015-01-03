{**
 * Загрузка медиа-файлов
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_options' append}
    {$mods = "$mods media"}
    {$classes = "$classes js-modal-media"}
    {$title = {lang name='media.title'}}
    {$attributes = array_merge( $attributes|default:[], [ 'data-modal-center' => 'false' ] )}
{/block}

{block 'modal_content_after'}
	{include './media-content.tpl'}
{/block}

{* Убираем подвал *}
{block 'modal_footer'}{/block}