{**
 * Пополняемый список пользователей
 *}

{* Название компонента *}
{$_sComponentName = 'user-list-add'}

{* Форма добавления *}
<div class="{$_sComponentName} {mod name=$_sComponentName mods=$sUserListAddMods} js-{$_sComponentName} {$sUserListAddClasses}" {$sUserListAddAttributes}>
	{* Заголовок *}
	{if $sUserListTitle}
		<h3 class="{$_sComponentName}-title">{$sUserListTitle}</h3>
	{/if}

	{* Описание *}
	{if $sUserListNote}
		<p class="{$_sComponentName}-note">{$sUserListNote}</p>
	{/if}

	{* Форма добавления *}
	<form class="{$_sComponentName}-form js-{$_sComponentName}-form">
		{$sClass = "js-$_sComponentName-form-users-"|cat:rand(0, 9999)}

		{include 'forms/fields/form.field.text.tpl'
				 sFieldName    = 'add'
				 sFieldClasses = "width-full autocomplete-users-sep {$sClass}"
				 sFieldLabel   = $aLang.user_list_add.form.fields.add.label
				 sFieldNote    = "<a href=\"#\" class=\"link-dotted\" data-type=\"modal-toggle\" data-modal-url=\"{router page='ajax/modal-friend-list'}\" data-param-selectable=\"true\" data-param-target=\".{$sClass}\">Выбрать из списка друзей</a>"}

		{include 'forms/fields/form.field.button.tpl' sFieldText=$aLang.common.add sFieldStyle='primary' sFieldClasses='js-{$_sComponentName}-form-submit'}
	</form>

	{* Список пользователей *}
	{* TODO: Изменить порядок вывода - сначало новые *}
	{include 'components/user_list_small/user_list_small.tpl'
			 aUserList                 = $aUserList
			 bUserListSmallShowActions = true
			 bUserListDisplay          = !! $aUserList
			 sUserListSmallClasses     = "js-$_sComponentName-users"
			 sUserListSmallItemClasses = "js-$_sComponentName-user"}
</div>