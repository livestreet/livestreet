{**
 * Список пользователей
 *}

{if $aUsersList}
	{if $iSearchCount}
		<h3 class="h3">Найдено {$iSearchCount} человек</h3>
	{/if}

	{* Список пользователей *}
	<ul class="object-list user-list js-more-users-container">
		{include './user_list.items.tpl' aUsersList=$aUsersList}
	</ul>

	{if $bUseMore}
		{if !$bHideMore}
			{include 'components/more/more.tpl'
				sClasses    = 'js-more-search'
				sTarget     = '.js-more-users-container'
				sAttributes = 'data-search-type="users" data-proxy-page-next="2"'}
		{/if}
	{else}
		{include 'components/pagination/pagination.tpl' aPaging=$aPaging}
	{/if}

{else}
	{include 'components/alert/alert.tpl' mAlerts=(($sUserListEmpty) ? $sUserListEmpty : $aLang.blog.alerts.empty) sMods='empty'}
{/if}


