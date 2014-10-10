{**
 * Список пользователей
 *}

{if $aUsersList}
	{if $iSearchCount}
		<h3 class="h3">{lang name='user.search.result_title' count=$iSearchCount plural=true}</h3>
	{/if}

	{* Список пользователей *}
	<ul class="object-list user-list js-more-users-container">
		{include './user-list-loop.tpl' users=$aUsersList}
	</ul>

	{if $bUseMore}
		{if ! $bHideMore}
			{include 'components/more/more.tpl'
				classes    = 'js-more-search'
				target     = '.js-more-users-container'
				attributes = 'data-search-type="users" data-proxy-page-next="2"'}
		{/if}
	{else}
		{include 'components/pagination/pagination.tpl' aPaging=$aPaging}
	{/if}

{else}
	{include 'components/alert/alert.tpl' text=$sUserListEmpty|default:{lang name='user.notices.empty'} mods='empty'}
{/if}