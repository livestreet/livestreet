{**
 * Список пользователей
 *}

{if $aUsersList}
	{* Сортировка *}
	{include 'sort.tpl'
			 sSortName     = 'sort-user-list'
			 aSortList     = [ [ name => 'user_login',         text => $aLang.sort.by_name ],
							   [ name => 'user_date_register', text => $aLang.user_date_registration ],
							   [ name => 'user_rating',        text => $aLang.user_rating ] ]
			 sSortUrl      = $sUsersRootPage
			 sSortOrder    = $sUsersOrder
			 sSortOrderWay = $sUsersOrderWay}

	{* Список пользователей *}
	<ul class="object-list user-list">
		{foreach $aUsersList as $oUser}
			{* TODO: Убрать костыль для блогов *}
			{if $oUser->getUser()}{$oUser = $oUser->getUser()}{/if}

			{$oSession = $oUser->getSession()}
			{$oUserNote = $oUser->getUserNote()}

			<li class="object-list-item">
				{* Аватар *}
				<a href="{$oUser->getUserWebPath()}">
					<img src="{$oUser->getProfileAvatarPath(100)}" width="100" height="100" alt="{$oUser->getLogin()}" class="object-list-item-image" />
				</a>

				{* Заголовок *}
				<h2 class="object-list-item-title">
					<a href="{$oUser->getUserWebPath()}">{$oUser->getDisplayName()}</a>
				</h2>

				{* Заметка *}
				{if $oUserNote}
					<p class="object-list-item-description user-note">{$oUserNote->getText()|escape}</p>
				{/if}

				{* Информация *}
				<ul class="object-list-item-info">
					{if $oSession}
						<li>{$aLang.user_date_last}: <strong>{date_format date=$oSession->getDateLast() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</strong></li>
					{/if}

					<li>{$aLang.user_date_registration}: <strong>{date_format date=$oUser->getDateRegister() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</strong></li>
					<li>{$aLang.vote.rating}: <strong>{$oUser->getRating()}</strong></li>
				</ul>
			</li>
		{/foreach}
	</ul>
{else}
	{include 'alert.tpl' mAlerts=$aLang.user_empty sAlertStyle='empty'}
{/if}

{include 'pagination.tpl' aPaging=$aPaging}