{**
 * Список пользователей (таблица)
 *}

<table class="table table-users">
	{if $bUsersUseOrder}
		<thead>
			<tr>
				<th class="cell-name cell-tab">
					<div class="cell-tab-inner {if $sUsersOrder=='user_login'}active{/if}"><a href="{$sUsersRootPage}?order=user_login&order_way={if $sUsersOrder=='user_login'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_login'}class="{$sUsersOrderWay}"{/if}><span>{$aLang.user}</span></a></div>
				</th>
				<th>&nbsp;</th>
				{if $bTableShowDateLast}
					<th class="cell-date cell-tab"><div class="cell-tab-inner"><span>{$aLang.user_date_last}</span></div></th>
				{/if}
				<th class="cell-rating cell-tab">
					<div class="cell-tab-inner {if $sUsersOrder=='user_rating'}active{/if}"><a href="{$sUsersRootPage}?order=user_rating&order_way={if $sUsersOrder=='user_rating'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_rating'}class="{$sUsersOrderWay}"{/if}><span>{$aLang.user_rating}</span></a></div>
				</th>
			</tr>
		</thead>
	{else}
		<thead>
			<tr>
				<th class="cell-name cell-tab"><div class="cell-tab-inner">{$aLang.user}</div></th>
				<th>&nbsp;</th>
				{if $bTableShowDateLast}
					<th class="cell-date cell-tab"><div class="cell-tab-inner"><span>{$aLang.user_date_last}</span></div></th>
				{/if}
				<th class="cell-rating cell-tab">
					<div class="cell-tab-inner active"><span>{$aLang.user_rating}</span></div>
				</th>
			</tr>
		</thead>
	{/if}

	<tbody>
		{foreach $aUsersList as $oUserList}
			{$oSession = $oUserList->getSession()}
			{$oUserNote = $oUserList->getUserNote()}

			<tr>
				<td class="cell-name">
					<a href="{$oUserList->getUserWebPath()}"><img src="{$oUserList->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
					<div class="name {if !$oUserList->getProfileName()}no-realname{/if}">
						<p class="username word-wrap"><a href="{$oUserList->getUserWebPath()}">{$oUserList->getLogin()}</a></p>
						{if $oUserList->getProfileName()}<p class="realname">{$oUserList->getProfileName()}</p>{/if}
					</div>
				</td>
				<td>
					{if $oUserCurrent}
						<a href="{router page='talk'}add/?talk_users={$oUserList->getLogin()}" class="button button-action button-action-send-message button-icon" title="{$aLang.user_write_prvmsg}">
							<i class="icon-synio-send-message"></i>
						</a>
						
						{if $oUserNote}
							<button type="button" class="button button-icon button-note js-tooltip" title="{$oUserNote->getText()|escape:'html'}"><i class="icon-synio-comments-green"></i></button>
						{/if}
					{/if}
				</td>
				{if $bTableShowDateLast}
					<td class="cell-date">
						{if $oSession}
							{date_format date=$oSession->getDateLast() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F, H:i"}
						{/if}
					</td>
				{/if}
				<td class="cell-rating {if $oUserList->getRating() < 0}negative{/if}"><strong>{$oUserList->getRating()}</strong></td>
			</tr>
		{foreachelse}
			<tr>
				<td colspan="4">
					{if $sUserListEmpty}
						{$sUserListEmpty}
					{else}
						{$aLang.user_empty}
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>

{include file='pagination.tpl' aPaging=$aPaging}