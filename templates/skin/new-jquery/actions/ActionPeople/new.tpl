{include file='header.tpl' menu='people'}

				
<h2>{$aLang.user_list_new}</h2>

{if $aUsersRegister}
	<table class="table table-people">
		<thead>
			<tr>
				<td>{$aLang.user}</td>													
				<td align="center" width="170">{$aLang.user_date_registration}</td>
				<td align="center" width="60">{$aLang.user_skill}</td>
				<td align="center" width="60">{$aLang.user_rating}</td>
			</tr>
		</thead>
		
		<tbody>
		{foreach from=$aUsersRegister item=oUser}
			<tr>
				<td><a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" class="avatar" /></a><a href="{$oUser->getUserWebPath()}" class="username">{$oUser->getLogin()}</a></td>														
				<td align="center" class="date">{date_format date=$oUser->getDateRegister()}</td>
				<td align="center" class="strength">{$oUser->getSkill()}</td>							
				<td align="center" class="rating"><strong>{$oUser->getRating()}</strong></td>
			</tr>
		{/foreach}						
		</tbody>
	</table>
{else}
	{$aLang.user_empty}
{/if}


{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}