{**
 * Заметка
 *
 * @param object   $note        Заметка
 * @param integer  $targetId    ID сущности
 * @param boolean  $isEditable  Можно редактировать заметку или нет
 *}

{* Название компонента *}
{$component = 'user-note'}

{* Установка дефолтных значений *}
{$note = $smarty.local.note}
{$isEditable = $smarty.local.isEditable|default:true}

<div class="{$component} {mod name=$component mods=$mods} {$smarty.local.classes}" data-param-i-user-id="{$smarty.local.targetId}" {$smarty.local.attributes}>
	{* Заметка *}
	<div class="{$component}-body js-{$component}-body">
		{* Текст *}
		<p class="{$component}-text js-{$component}-text" {if ! $note}style="display: none"{/if}>
			{if $note}
				{$note->getText()}
			{/if}
		</p>

		{* Действия *}
		{if $isEditable}
			<ul class="{$component}-actions js-{$component}-actions clearfix" {if ! $note}style="display: none;"{/if}>
				<li><a href="#" class="js-{$component}-actions-edit">{$aLang.common.edit}</a></li>
				<li><a href="#" class="js-{$component}-actions-remove">{$aLang.common.remove}</a></li>
			</ul>

			{* Добавить *}
			<ul class="{$component}-actions {$component}-actions--add clearfix js-{$component}-add" {if $note}style="display: none;"{/if}>
				<li><a href="#" class="">{$aLang.user_note.add}</a></li>
			</ul>
		{/if}
	</div>

	{* Форма редактирования *}
	{if $isEditable}
		<form class="{$component}-form js-{$component}-form" style="display: none;">
			{include 'components/field/field.textarea.tpl' inputClasses="$component-form-text js-$component-form-text"}

			{include 'components/button/button.tpl' mods='primary' text=$aLang.common.save}
			{include 'components/button/button.tpl' type='button' classes="js-$component-form-cancel" text=$aLang.common.cancel}
		</form>
	{/if}
</div>