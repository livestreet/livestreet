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
{$component = 'user-note'}

{* Установка дефолтных значений *}
{$_oNote = $smarty.local.oObject}
{$_bIsEditable = $smarty.local.bIsEditable|default:true}

<div class="{$component} {mod name=$component mods=$mods} {$smarty.local.classes}" data-param-i-user-id="{$smarty.local.iUserId}" {$smarty.local.attributes}>
	{* Заметка *}
	<div class="{$component}-body js-{$component}-body">
		{* Текст *}
		<p class="{$component}-text js-{$component}-text" {if ! $_oNote}style="display: none"{/if}>
			{if $_oNote}
				{$_oNote->getText()}
			{/if}
		</p>

		{* Действия *}
		{if $_bIsEditable}
			<ul class="{$component}-actions js-{$component}-actions" {if ! $_oNote}style="display: none;"{/if}>
				<li><a href="#" class="link-dotted js-{$component}-actions-edit">{$aLang.common.edit}</a></li>
				<li><a href="#" class="link-dotted js-{$component}-actions-remove">{$aLang.common.remove}</a></li>
			</ul>

			{* Добавить *}
			<a href="#" class="link-dotted {$component}-add js-{$component}-add" {if $_oNote}style="display:none;"{/if}>{$aLang.user_note.add}</a>
		{/if}
	</div>

	{* Форма редактирования *}
	{if $_bIsEditable}
		<form class="{$component}-form js-{$component}-form" style="display: none;">
			{include 'components/field/field.textarea.tpl' inputClasses="$component-form-text js-$component-form-text"}

			{include 'components/button/button.tpl' mods='primary' classes="js-$component-form-save" text=$aLang.common.save}
			{include 'components/button/button.tpl' type='button' classes="js-$component-form-cancel" text=$aLang.common.cancel}
		</form>
	{/if}
</div>