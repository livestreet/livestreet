{**
 * Пополняемый список пользователей
 *
 * TODO: Item active/inactive/selected
 *}

{* Форма добавления *}
<div class="user-list-add js-user-list-add" data-type="{$sUserListType}" data-target-id="{$iUserListId}">
	{* Заголовок *}
	{if $sUserListTitle}
		<h3 class="user-list-add-title">{$sUserListTitle}</h3>
	{/if}

	{* Описание *}
	{if $sUserListNote}
		<p class="user-list-add-note">{$sUserListNote}</p>
	{/if}

	{* Форма добавления *}
	<form class="user-list-add-form js-user-list-add-form">
		{include 'forms/fields/form.field.text.tpl'
				 sFieldName    = 'add'
				 sFieldClasses = 'width-full autocomplete-users-sep js-user-list-add-form-users'
				 sFieldLabel   = $aLang.blog.invite.fields.add.label
				 sFieldNote    = "<a href=\"#\" class=\"link-dotted\" data-type=\"modal-toggle\" data-modal-url=\"{router page='ajax/modal-friend-list'}\" data-param-selectable=\"true\">Выбрать из списка друзей</a>"}

		{include 'forms/fields/form.field.button.tpl' sFieldText=$aLang.common.add sFieldStyle='primary' sFieldClasses='js-user-list-add-form-submit'}
	</form>

	{* Список пользователей *}
	{* TODO: Изменить порядок вывода - сначало новые *}
	{include 'user_list_small.tpl'
			 aUserList                 = $aUserList
			 bUserListSmallShowActions = true
			 bUserListDisplay          = !! $aUserList
			 sUserListSmallClasses     = 'js-user-list-add-users'
			 sUserListSmallItemClasses = 'js-user-list-add-user'}
</div>