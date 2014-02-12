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
		{include 'actionbar.tpl' aActionbarItems=[
			[ 'icon' => 'icon-ok', 'classes' => 'js-temp', 'text' => $aLang.block_friends_check ],
			[ 'classes' => 'js-temp', 'text' => $aLang.block_friends_uncheck ]
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