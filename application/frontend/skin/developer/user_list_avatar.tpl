{**
 * Список пользователей (аватары)
 *}

{if $aUsersList}
	<ul class="user-list-avatar">
		{foreach $aUsersList as $oUserList}
			{$oSession = $oUserList->getSession()}
			
			<li>
				<a href="{$oUserList->getUserWebPath()}"><img src="{$oUserList->getProfileAvatarPath(64)}" alt="avatar" class="avatar" /></a>
				<a href="{$oUserList->getUserWebPath()}">{$oUserList->getDisplayName()}</a>
			</li>
		{/foreach}
	</ul>
{else}
	{if $sUserListEmpty}
		{include file='alert.tpl' mAlerts=$sUserListEmpty sAlertStyle='empty'}
	{else}
		{include file='alert.tpl' mAlerts=$aLang.user_empty sAlertStyle='empty'}
	{/if}
{/if}

{include file='pagination.tpl' aPaging=$aPaging}