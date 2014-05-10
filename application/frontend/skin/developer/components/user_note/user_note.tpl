{**
 * Заметка
 *
 * @param object   $oObject      Заметка
 * @param integer  $iUserId      ID сущности
 * @param boolean  $bIsEditable  Можно редактировать заметку или нет
 *
 * @styles assets/css/common.css
 * @scripts <common>/js/usernote.js
 *}

{* Название компонента *}
{$_sComponentName = 'user-note'}

{* Установка дефолтных значений *}
{$_oNote = $smarty.local.oObject}
{$_bIsEditable = $smarty.local.bIsEditable|default:true}

<div class="{$_sComponentName} {mod name=$_sComponentName mods=$sMods} {$smarty.local.sClasses}" data-param-i-user-id="{$smarty.local.iUserId}" {$smarty.local.sAttributes}>
	{* Заметка *}
	<div class="{$_sComponentName}-body js-{$_sComponentName}-body">
		{* Текст *}
		<p class="{$_sComponentName}-text js-{$_sComponentName}-text" {if ! $_oNote}style="display: none"{/if}>
			{if $_oNote}
				{$_oNote->getText()}
			{/if}
		</p>

		{* Действия *}
		{if $_bIsEditable}
			<ul class="{$_sComponentName}-actions js-{$_sComponentName}-actions" {if ! $_oNote}style="display: none;"{/if}>
				<li><a href="#" class="link-dotted js-{$_sComponentName}-actions-edit">{$aLang.common.edit}</a></li>
				<li><a href="#" class="link-dotted js-{$_sComponentName}-actions-remove">{$aLang.common.remove}</a></li>
			</ul>

			{* Добавить *}
			<a href="#" class="link-dotted {$_sComponentName}-add js-{$_sComponentName}-add" {if $_oNote}style="display:none;"{/if}>{$aLang.user_note.add}</a>
		{/if}
	</div>

	{* Форма редактирования *}
	{if $_bIsEditable}
		<form class="{$_sComponentName}-form js-{$_sComponentName}-form" style="display: none;">
			{include 'components/field/field.textarea.tpl' sClasses="width-full $_sComponentName-form-text js-$_sComponentName-form-text"}

			{include 'components/button/button.tpl' sStyle='primary' sClasses="js-$_sComponentName-form-save" sText=$aLang.common.save}
			{include 'components/button/button.tpl' sType='button' sClasses="js-$_sComponentName-form-cancel" sText=$aLang.common.cancel}
		</form>
	{/if}
</div>