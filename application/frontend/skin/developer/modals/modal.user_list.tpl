{**
 * Модальное с меню "Создать"
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block name='modal_id'}modal-users-select{/block}
{block name='modal_class'}modal-users-select js-modal-default{/block}
{block name='modal_title'}{$aLang.block_friends}{/block}

{block name='modal_content'}
	{* Экшнбар *}
	{if $aUserList && $bSelectable}
		{include 'components/actionbar/actionbar.item.select.tpl' sItemSelector='.js-user-list-select .js-user-list-small-item' assign=sUsersSelect}
		{include 'components/actionbar/actionbar.tpl' aItems=[
			[ 'html' => $sUsersSelect ]
		]}
	{/if}

	{* Список *}
	{include 'components/user_list_small/user_list_small.tpl' aUserList=$aUserList bUserListSmallSelectable=$bSelectable bUserListSmallShowEmpty=true sUserListSmallClasses='js-user-list-select'}
{/block}

{block name='modal_footer_begin'}
	{if $aUserList && $bSelectable}
		{include 'forms/fields/form.field.button.tpl'
				 sFieldText = $aLang.common.add
				 sFieldStyle = 'primary'
				 sFieldClasses = 'js-user-list-select-add'
				 sFieldAttributes = "data-target=\"{$sTarget}\""}
	{/if}
{/block}