{if $aUsersList}
	<table class="table table-users">
		<thead>
		<tr>
			<th class="table-users-cell-name">{$aLang.user}</th>
			<th>{$aLang.user_date_last}</th>
			<th>{$aLang.user_date_registration}</th>
			<th class="table-users-cell-skill">{$aLang.user_skill}</th>
			<th class="table-users-cell-rating">{$aLang.user_rating}</th>
		</tr>
		</thead>

		<tbody>
			{foreach from=$aUsersList item=oUserList}
				{assign var="oSession" value=$oUserList->getSession()}
			<tr>
				<td class="table-users-cell-name"><a href="{$oUserList->getUserWebPath()}">{$oUserList->getLogin()}</a></td>
				<td>{if $oSession}{date_format date=$oSession->getDateLast()}{/if}</td>
				<td>{date_format date=$oUserList->getDateRegister()}</td>
				<td class="table-users-cell-skill">{$oUserList->getSkill()}</td>
				<td class="table-users-cell-rating"><strong>{$oUserList->getRating()}</strong></td>
			</tr>
			{/foreach}
		</tbody>
	</table>
{else}
	{if $sUserListEmpty}
		{$sUserListEmpty}
	{else}
		{$aLang.user_empty}
	{/if}
{/if}



{include file='paging.tpl' aPaging="$aPaging"}