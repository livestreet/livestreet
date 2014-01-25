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
			
			<li>{include 'user_item.tpl' oUser=$oUser iUserItemAvatarSize=64}</li>
		{/foreach}
	</ul>
{else}
	{if $sUserListEmpty}
		{include 'alert.tpl' mAlerts=$sUserListEmpty sAlertStyle='empty'}
	{else}
		{include 'alert.tpl' mAlerts=$aLang.user_empty sAlertStyle='empty'}
	{/if}
{/if}

{if isset($bShowPagination) && bShowPagination === true}
	{include 'pagination.tpl' aPaging=$aPaging}
{/if}