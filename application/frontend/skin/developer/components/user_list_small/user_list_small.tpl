{**
 * Список пользователей с элементами управления
 *}

{* Заголовок *}
{if $sUserListSmallTitle}
	<h3 class="user-list-small-title">{$sUserListSmallTitle}</h3>
{/if}

{* Уведомление о пустом списке *}
{if ! $aUserList || $smarty.local.bHideableEmptyAlert}
	{include 'components/alert/alert.tpl'
			 mAlerts  = $aLang.common.empty
			 sMods    = 'empty'
			 sClasses = 'js-user-list-small-empty'
			 bVisible = ! $aUserList}
{/if}

{* Список пользователей *}
{if $aUserList || ! $bUserListDisplay|default:true}
	<ul class="user-list-small js-user-list-small {$sUserListSmallClasses}" {if ! $bUserListDisplay|default:true}style="display: none"{/if}>
		{foreach $aUserList as $oUser}
			{if $oUser->getUser()}
				{$oUserContainer = $oUser}
				{$oUser = $oUser->getUser()}
			{/if}

			{if ! $aUserListSmallExclude || ! in_array($oUser->getId(), $aUserListSmallExclude)}
				{include $sUserListSmallItemPath|default:'./user_list_small_item.tpl' bUserListItemShowRemove=! $aUserListSmallExcludeRemove || ! in_array($iUserId, $aUserListSmallExcludeRemove)}
			{/if}
		{/foreach}
	</ul>
{/if}