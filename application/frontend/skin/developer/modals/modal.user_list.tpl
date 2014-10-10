{**
 * Список пользователей
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-users-select{/block}
{block 'modal_class'}modal-users-select js-modal-default{/block}
{block 'modal_title'}{$aLang.user.users}{/block}

{block 'modal_content'}
	{* Экшнбар *}
	{if $aUserList && $bSelectable}
		{include 'components/actionbar/actionbar-item.select.tpl'
			classes = 'js-user-list-modal-actionbar'
			target  = '.js-user-list-select .js-user-list-small-item'
			assign  = users}

		{include 'components/actionbar/actionbar.tpl' items=[
			[ 'html' => $users ]
		]}
	{/if}

	{* Список *}
	{include 'components/user_list_small/user_list_small.tpl'
		aUserList                = $aUserList
		bUserListSmallSelectable = $bSelectable
		bUserListSmallShowEmpty  = true
		sUserListSmallClasses    = 'js-user-list-select'}
{/block}

{block 'modal_footer_begin'}
	{if $aUserList && $bSelectable}
		{include 'components/button/button.tpl'
				 text = $aLang.common.add
				 mods = 'primary'
				 classes = 'js-user-list-select-add'
				 attributes = "data-target=\"{$sTarget}\""}
	{/if}
{/block}