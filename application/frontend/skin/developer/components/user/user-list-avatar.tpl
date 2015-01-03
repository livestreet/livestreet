{**
 * Список пользователей (аватары)
 *
 * @param array $users      Список пользователей
 * @param array $showPagination Показывать или нет пагинацию (false)
 *}

{$users = $smarty.local.users}

{if $users}
	<ul class="user-list-avatar">
		{foreach $users as $oUser}
			{* TODO: Костыль для блогов *}
			{if $oUser->getUser()}{$oUser = $oUser->getUser()}{/if}

			<li>{component 'user' template='item' user=$oUser avatarSize=64}</li>
		{/foreach}
	</ul>
{else}
	{if $sUserListEmpty}
		{component 'alert' text=$sUserListEmpty mods='empty'}
	{else}
		{component 'alert' text=$aLang.common.empty mods='empty'}
	{/if}
{/if}

{if $showPagination}
	{component 'pagination' paging=$aPaging}
{/if}