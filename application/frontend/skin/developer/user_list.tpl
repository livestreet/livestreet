{**
 * Список пользователей (таблица)
 *}

<table class="table table-users">
	{if $bUsersUseOrder}
		<thead>
			<tr>
				<th class="cell-name"><a href="{$sUsersRootPage}?order=user_login&order_way={if $sUsersOrder=='user_login'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_login'}class="{$sUsersOrderWay}"{/if}>{$aLang.user}</a></th>
				<th>{$aLang.user_date_last}</th>
				<th><a href="{$sUsersRootPage}?order=user_date_register&order_way={if $sUsersOrder=='user_date_register'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_date_register'}class="{$sUsersOrderWay}"{/if}>{$aLang.user_date_registration}</a></th>
				<th class="cell-rating"><a href="{$sUsersRootPage}?order=user_rating&order_way={if $sUsersOrder=='user_rating'}{$sUsersOrderWayNext}{else}{$sUsersOrderWay}{/if}" {if $sUsersOrder=='user_rating'}class="{$sUsersOrderWay}"{/if}>{$aLang.user_rating}</a></th>
			</tr>
		</thead>
	{else}
		<thead>
			<tr>
				<th class="cell-name">{$aLang.user}</th>
				<th class="cell-date">{$aLang.user_date_last}</th>
				<th class="cell-date">{$aLang.user_date_registration}</th>
				<th class="cell-rating">{$aLang.user_rating}</th>
			</tr>
		</thead>
	{/if}

	<tbody>
		{foreach $aUsersList as $oUserList}
			{$oSession = $oUserList->getSession()}
			{$oUserNote = $oUserList->getUserNote()}

			<tr>
				<td class="cell-name">
					<a href="{$oUserList->getUserWebPath()}"><img src="{$oUserList->getProfileAvatarPath(24)}" alt="avatar" class="avatar" /></a>
					<p class="username word-wrap"><a href="{$oUserList->getUserWebPath()}">{$oUserList->getLogin()}</a>
						{if $oUserNote}
							<i class="icon-comment js-tooltip" title="{$oUserNote->getText()|escape:'html'}"></i>
						{/if}
					</p>
				</td>
				<td class="cell-date">{if $oSession}{date_format date=$oSession->getDateLast() format="d.m.y, H:i"}{/if}</td>
				<td class="cell-date">{date_format date=$oUserList->getDateRegister() format="d.m.y, H:i"}</td>
				<td class="cell-rating"><strong>{$oUserList->getRating()}</strong></td>
			</tr>
		{foreachelse}
			<tr>
				<td colspan="5">
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