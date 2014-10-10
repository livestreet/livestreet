{**
 * Список пользователей (аватары)
 *
 * @param array $aUsersList      Список пользователей
 * @param array $bShowPagination Показывать или нет пагинацию (false)
 *}

{if $aUsersList}
	<ul class="user-list-avatar">
		{foreach $aUsersList as $oUser}
			{* TODO: Костыль для блогов *}
			{if $oUser->getUser()}{$oUser = $oUser->getUser()}{/if}
			
			<li>{include 'components/user_item/user_item.tpl' oUser=$oUser iUserItemAvatarSize=64}</li>
		{/foreach}
	</ul>
{else}
	{if $sUserListEmpty}
		{include 'components/alert/alert.tpl' text=$sUserListEmpty mods='empty'}
	{else}
		{include 'components/alert/alert.tpl' text=$aLang.common.empty mods='empty'}
	{/if}
{/if}

{if isset($bShowPagination) && bShowPagination === true}
	{include 'components/pagination/pagination.tpl' aPaging=$aPaging}
{/if}