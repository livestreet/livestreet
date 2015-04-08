{**
 * Список пользователей (аватары)
 *
 * @param array $users      Список пользователей
 * @param array $pagination Массив с параметрами пагинации
 * @param array $emptyText
 *}

{$users = $smarty.local.users}
{$pagination = $smarty.local.pagination}
{$emptyText = $smarty.local.emptyText}

{$emptyText = $emptyText|default:$aLang.common.empty}

{if $users}
	<ul class="user-list-avatar">
		{foreach $users as $user}
			{* TODO: Костыль для блогов *}
			{if $user->getUser()}{$user = $user->getUser()}{/if}

			<li>{component 'user' template='item' user=$user avatarSize=64}</li>
		{/foreach}
	</ul>
{else}
	{component 'blankslate' text=$emptyText}
{/if}

{component 'pagination' total=+$pagination.iCountPage current=+$pagination.iCurrentPage url="{$pagination.sBaseUrl}/page__page__/"}