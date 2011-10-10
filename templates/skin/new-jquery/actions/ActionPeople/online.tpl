{include file='header.tpl' menu='people'}


<h2>{$aLang.user_list_online_last}</h2>

{if $aUsersLast}
	<table class="table table-people">
		<thead>
			<tr>
				<td class="user-login">{$aLang.user}</td>
				<td class="user-date-last">{$aLang.user_date_last}</td>
				<td class="user-skill">{$aLang.user_skill}</td>
				<td class="user-rating">{$aLang.user_rating}</td>
			</tr>
		</thead>

		<tbody>
		{foreach from=$aUsersLast item=oUser}
			{assign var="oSession" value=$oUser->getSession()}
			<tr>
				<td class="user-login"><a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" class="avatar" /></a><a href="{$oUser->getUserWebPath()}" class="username">{$oUser->getLogin()}</a></td>
				<td class="user-date-last date">{date_format date=$oSession->getDateLast()}</td>
				<td class="user-skill strength">{$oUser->getSkill()}</td>
				<td class="user-rating rating"><strong>{$oUser->getRating()}</strong></td>
			</tr>
		{/foreach}
		</tbody>
	</table>
{else}
	{$aLang.user_empty}
{/if}


{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}