{if $aUsersList}
	<ul class="user-list-avatar">
		{foreach from=$aUsersList item=oUserList}
			{assign var="oSession" value=$oUserList->getSession()}
			
			<li>
				<a href="{$oUserList->getUserWebPath()}" title="{$oUserList->getLogin()}"><img src="{$oUserList->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
			</li>
		{/foreach}
	</ul>
{else}
	{if $sUserListEmpty}
		<div class="notice-empty">{$sUserListEmpty}</div>
	{else}
		<div class="notice-empty">{$aLang.user_empty}</div>
	{/if}
{/if}


{include file='paging.tpl' aPaging=$aPaging}