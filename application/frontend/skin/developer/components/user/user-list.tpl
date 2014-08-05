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
				sClasses    = 'js-more-search'
				sTarget     = '.js-more-users-container'
				sAttributes = 'data-search-type="users" data-proxy-page-next="2"'}
		{/if}
	{else}
		{include 'components/pagination/pagination.tpl' aPaging=$aPaging}
	{/if}

{else}
	{include 'components/alert/alert.tpl' mAlerts=$sUserListEmpty|default:{lang name='user.notices.empty'} sMods='empty'}
{/if}


