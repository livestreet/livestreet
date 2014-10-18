{**
 * Пополняемый список пользователей
 *}

{* Название компонента *}
{$component = 'user-list-add'}

{* Форма добавления *}
<div class="{$component} {mod name=$component mods=$sUserListAddMods} js-{$component} {$sUserListAddClasses}" {$sUserListAddAttributes}>
	{* Заголовок *}
	{if $sUserListTitle}
		<h3 class="{$component}-title">{$sUserListTitle}</h3>
	{/if}

	{* Описание *}
	{if $sUserListNote}
		<p class="{$component}-note">{$sUserListNote}</p>
	{/if}

	{* Форма добавления *}
	{if $smarty.local.allowManage|default:true}
		<form class="{$component}-form js-{$component}-form">
			{$sClass = "js-$component-form-users-"|cat:rand(0, 9999)}

			{include 'components/field/field.text.tpl'
					 name    = 'add'
					 inputClasses = "autocomplete-users-sep {$sClass}"
					 label   = $aLang.user_list_add.form.fields.add.label
					 note    = "<a href=\"#\" class=\"link-dotted\" data-type=\"modal-toggle\" data-modal-url=\"{router page='ajax/modal-friend-list'}\" data-param-selectable=\"true\" data-param-target=\".{$sClass}\">Выбрать из списка друзей</a>"}

			{include 'components/button/button.tpl' text=$aLang.common.add mods='primary' classes="js-$component-form-submit"}
		</form>
	{/if}

	{* Список пользователей *}
	{* TODO: Изменить порядок вывода - сначало новые *}
	{include 'components/user/user-list-small.tpl'
			 bHideableEmptyAlert       = true
			 aUserList                 = $aUserList
			 bUserListSmallShowActions = true
			 bUserListDisplay          = !! $aUserList
			 sUserListSmallClasses     = "js-$component-users"
			 sUserListSmallItemClasses = "js-$component-user"}
</div>