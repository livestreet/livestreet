{**
 * Модальное с меню "Создать"
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-users-select{/block}
{block name='modal_class'}modal-users-select js-modal-default{/block}
{block name='modal_title'}{$aLang.block_friends}{/block}

{block name='modal_content'}
	{* Экшнбар *}
	{if $aUserList && $bSelectable}
		{include 'actionbar.item.select.tpl' sName='asdfsasaasddf' sItemSelector='.js-user-list-select .js-user-list-small-item' assign=sUsersSelect}
		{include 'actionbar.tpl' aActionbarItems=[
			[ 'html' => $sUsersSelect ]
		]}
	{/if}

	{* Список *}
	{include 'user_list_small.tpl' aUserList=$aUserList bUserListSmallSelectable=$bSelectable bUserListSmallShowEmpty=true sUserListSmallClasses='js-user-list-select'}
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