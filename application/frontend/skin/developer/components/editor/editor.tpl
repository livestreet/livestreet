{**
 * Редактор
 *}

{* Название компонента *}
{$component = 'editor'}

{* Получаем тип редактора *}
{$type = ( ( $smarty.local.type ) ? $smarty.local.type : ( Config::Get('view.wysiwyg') ) ? 'visual' : 'markup' )}
{$set = $smarty.local.sSet|default:'default'}

{* Уникальный ID *}
{$_uid = $smarty.local.sId|default:($component|cat:rand(0, 10e10))}

{* Уникальный ID окна загрузки файлов *}
{$_mediaUid = "media{$_uid}"}

{**
 * Textarea
 *}
{function editor_textarea}
	{include 'components/field/field.textarea.tpl'
		sName            = $smarty.local.sName
		sValue           = $smarty.local.sValue
		sLabel           = $smarty.local.sLabel
		sMods            = $smarty.local.sMods
		sClasses         = $smarty.local.sClasses
		sId              = $_uid
		sAttributes      = $smarty.local.sAttributes
		aRules           = $smarty.local.aRules
		sEntityField     = $smarty.local.sEntityField
		sEntity          = $smarty.local.sEntity
		sInputClasses    = "{$smarty.local.classes} {$smarty.local.sInputClasses}"
		sInputAttributes = "{$smarty.local.sAttributes} data-editor-type=\"{$type}\" data-editor-set=\"{$set}\" data-editor-media=\"{$_mediaUid}\""
		sNote            = $smarty.local.sNote
		iRows            = $smarty.local.iRows|default:10}
{/function}

{* Визуальный редактор *}
{if $type == 'visual'}
	{hookb run='editor_visual'}
		{asset type='js' name='editor_visual' file="{Config::Get('path.skin.web')}/components/editor/vendor/tinymce/js/tinymce/tinymce.min.js"}
		{asset type='js' name='editor_visual_1' file="{Config::Get('path.skin.web')}/components/editor/vendor/tinymce/js/tinymce/jquery.tinymce.min.js"}
		{asset type='js' name='editor_visual_2' file="{Config::Get('path.skin.web')}/components/editor/js/editor.visual.js"}

		{editor_textarea}
	{/hookb}

{* Markup редактор *}
{else}
	{hookb run='editor_markup'}
		{asset type='js' name='editor_markup' file="{Config::Get('path.skin.web')}/components/editor/vendor/markitup/jquery.markitup.js"}
		{asset type='js' name='editor_markup_options' file="{Config::Get('path.skin.web')}/components/editor/js/editor.markup.js"}

		{asset type='css' name='editor_markup' file="{Config::Get('path.skin.web')}/components/editor/vendor/markitup/skins/livestreet/style.css"}
		{asset type='css' name='editor_markup_set' file="{Config::Get('path.skin.web')}/components/editor/vendor/markitup/sets/livestreet/style.css"}
		{asset type='css' name='editor_markup_help' file="{Config::Get('path.skin.web')}/components/editor/css/editor.css"}

		{editor_textarea}

		{if $smarty.local.help|default:true}
			{include './editor.markup.help.tpl' sTargetId=$_uid}
		{/if}
	{/hookb}
{/if}

{* Управление медиа-файлами *}
{include 'components/media/media.tpl'
	sMediaTargetType = $smarty.local.sMediaTargetType
	sMediaTargetId   = $smarty.local.sMediaTargetId
	id               = $_mediaUid
	assign           = 'sMediaModal'}

{* Добавляем модальное окно (компонент media) в конец лэйаута чтобы избежать вложенных форм *}
{$sLayoutAfter = "$sLayoutAfter $sMediaModal" scope='root'}