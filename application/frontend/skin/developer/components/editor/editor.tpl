{**
 * Редактор
 *}

{* Название компонента *}
{$_sComponentName = 'editor'}

{* Получаем тип редактора *}
{$_sType = ( Config::Get('view.wysiwyg') ) ? 'visual' : 'markup'}
{$_sSet = $smarty.local.sSet|default:'default'}

{* Уникальный ID *}
{$_uid = $smarty.local.sId|default:($_sComponentName|cat:rand(0, 10e10))}

{* Класс на который вешается обработчик редактора *}
{$_sBindClass = $smarty.local.sBindClass|default:"js-{$_sComponentName}"}

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
			sInputClasses    = "$_sBindClass {$smarty.local.sInputClasses}"
			sInputAttributes = "{$smarty.local.sAttributes} data-editor-type=\"{$_sType}\" data-editor-set=\"{$_sSet}\""
			sNote            = $smarty.local.sNote
			iRows            = $smarty.local.iRows|default:10}
{/function}

{* Визуальный редактор *}
{if Config::Get('view.wysiwyg')}
	{hookb run='editor_visual'}
		{editor_textarea}
	{/hookb}

{* Markup редактор *}
{else}
	{hookb run='editor_markup'}
		{editor_textarea}

		{if $smarty.local.bShowHelp|default:true}
			{include './editor.markup.help.tpl' sTargetId=$_uid}
		{/if}
	{/hookb}
{/if}

{* TODO: Исправить повторный инклуд при подключении нескольких редакторов *}
{include 'modals/modal.upload_image.tpl' sMediaTargetType=$smarty.local.sMediaTargetType sMediaTargetId=$smarty.local.sMediaTargetId assign='sMediaModal'}

{* Добавляем модальное окно в конец лэйаута чтобы избежать вложенных форм *}
{$sLayoutAfter = "$sLayoutAfter $sMediaModal" scope='root'}