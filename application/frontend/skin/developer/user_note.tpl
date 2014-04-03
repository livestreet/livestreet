{**
 * Заметка
 *
 * @param object   $oUserNote          Заметка
 * @param integer  $iUserNoteId        ID сущности
 * @param boolean  $bUserNoteEditable  Можно редактировать заметку или нет
 * @param string   $sUserNoteClasses   Дополнительные классы
 *
 * @styles assets/css/common.css
 * @scripts <common>/js/usernote.js
 *}

{* Название компонента *}
{$_sComponentName = 'user-note'}

{* Установка дефолтных значений *}
{$bUserNoteEditable = $bUserNoteEditable|default:true}

<div class="{$_sComponentName} js-{$_sComponentName} {$sUserNoteClasses}" data-param-i-user-id="{$iUserNoteId}" {$sUserNoteAttributes}>
	{* Заметка *}
	<div class="{$_sComponentName}-body js-{$_sComponentName}-body">
		{* Текст *}
		<p class="{$_sComponentName}-text js-{$_sComponentName}-text" {if ! $oUserNote}style="display: none"{/if}>
			{if $oUserNote}
				{$oUserNote->getText()}
			{/if}
		</p>

		{* Действия *}
		{if $bUserNoteEditable}
			<ul class="{$_sComponentName}-actions js-{$_sComponentName}-actions" {if ! $oUserNote}style="display: none;"{/if}>
				<li><a href="#" class="link-dotted js-{$_sComponentName}-actions-edit">{$aLang.common.edit}</a></li>
				<li><a href="#" class="link-dotted js-{$_sComponentName}-actions-remove">{$aLang.common.remove}</a></li>
			</ul>

			{* Добавить *}
			<a href="#" class="link-dotted {$_sComponentName}-add js-{$_sComponentName}-add" {if $oUserNote}style="display:none;"{/if}>{$aLang.user_note.add}</a>
		{/if}
	</div>

	{* Форма редактирования *}
	{if $bUserNoteEditable}
		<form class="{$_sComponentName}-form js-{$_sComponentName}-form" style="display: none;">
			{include 'forms/fields/form.field.textarea.tpl' sFieldClasses="width-full $_sComponentName-form-text js-$_sComponentName-form-text"}

			{include 'forms/fields/form.field.button.tpl' sFieldStyle='primary' sFieldClasses="js-$_sComponentName-form-save" sFieldText=$aLang.common.save}
			{include 'forms/fields/form.field.button.tpl' sFieldType='button' sFieldClasses="js-$_sComponentName-form-cancel" sFieldText=$aLang.common.cancel}
		</form>
	{/if}
</div>