{**
 * Редактор
 *}

{* Название компонента *}
{$component = 'editor'}

{* Получаем тип редактора *}
{$type = ( ( $smarty.local.type ) ? $smarty.local.type : ( Config::Get('view.wysiwyg') ) ? 'visual' : 'markup' )}
{$set = $smarty.local.set|default:'default'}

{* Уникальный ID *}
{$_uid = $smarty.local.id|default:($component|cat:rand(0, 10e10))}

{* Уникальный ID окна загрузки файлов *}
{$_mediaUid = "media{$_uid}"}


{**
 * Textarea
 *}
{function editor_textarea}
	{component 'field' template='textarea'
		name            = $smarty.local.name
		value           = $smarty.local.value
		label           = $smarty.local.label
		mods            = $smarty.local.mods
		classes         = $smarty.local.classes
		id              = $_uid
		attributes      = $smarty.local.attributes
		rules           = $smarty.local.rules
		entityField     = $smarty.local.entityField
		entity          = $smarty.local.entity
		inputClasses    = "{$smarty.local.classes} {$smarty.local.inputClasses}"
		inputAttributes = array_merge( $smarty.local.attributes|default:[], [ 'data-editor-type' => $type, 'data-editor-set' => $set, 'data-editor-media' => $_mediaUid ] )
		note            = $smarty.local.note
		rows            = $smarty.local.rows|default:10}
{/function}

{* Визуальный редактор *}
{if $type == 'visual'}
	{hookb run='editor_visual'}
		{asset type='js' name='editor_visual' file="{Config::Get('path.skin.web')}/components/ls-vendor/tinymce/js/tinymce/tinymce.min.js"}
		{asset type='js' name='editor_visual_1' file="{Config::Get('path.skin.web')}/components/ls-vendor/tinymce/js/tinymce/jquery.tinymce.min.js"}
		{asset type='js' name='editor_visual_2' file="{Config::Get('path.skin.web')}/components/editor/js/editor.visual.js"}

		{editor_textarea}
	{/hookb}

{* Markup редактор *}
{else}
	{hookb run='editor_markup'}
		{asset type='js' name='editor_markup' file="Component@ls-vendor.markitup/jquery.markitup"}
		{asset type='js' name='editor_markup_options' file="Component@editor.markup"}

		{asset type='css' name='editor_markup' file="Component@ls-vendor.markitup/skins/livestreet/style"}
		{asset type='css' name='editor_markup_set' file="Component@ls-vendor.markitup/sets/livestreet/style"}
		{asset type='css' name='editor_markup_help' file="Component@editor.editor"}

		{editor_textarea}

		{if $smarty.local.help|default:true}
			{include './editor.markup.help.tpl' targetId=$_uid}
		{/if}
	{/hookb}
{/if}

{* Управление медиа-файлами *}
{component 'media'
	sMediaTargetType = $smarty.local.mediaTargetType
	sMediaTargetId   = $smarty.local.mediaTargetId
	id               = $_mediaUid
	assign           = 'mediaModal'}

{* Добавляем модальное окно (компонент media) в конец лэйаута чтобы избежать вложенных форм *}
{$sLayoutAfter = "$sLayoutAfter $mediaModal" scope='root'}