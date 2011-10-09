{include file='header.tpl' menu='people'}


<h2>{$aLang.user_list_new}</h2>

{if $aUsersRegister}
	<table class="table table-people">
		<thead>
			<tr>
				<td class="user-login">{$aLang.user}</td>
				<td class="user-date-registration">{$aLang.user_date_registration}</td>
				<td class="user-skill">{$aLang.user_skill}</td>
				<td class="user-rating">{$aLang.user_rating}</td>
			</tr>
		</thead>
		
		<tbody>
		{foreach from=$aUsersRegister item=oUser}
			<tr>
				<td class="user-login"><a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" class="avatar" /></a><a href="{$oUser->getUserWebPath()}" class="username">{$oUser->getLogin()}</a></td>														
				<td class="user-date-registration date">{date_format date=$oUser->getDateRegister()}</td>
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