{include file='header.tpl' menu='people'}

	<div class="topic">
		<h2>{$aLang.user_list_online_last}</h2>
		
		{if $aUsersLast}
		<table class="people">
			<thead>
				<tr>
					<td class="user">{$aLang.user}</td>													
					<td class="date">{$aLang.user_date_last}</td>
					<td class="strength">{$aLang.user_skill}</td>
					<td class="rating">{$aLang.user_rating}</td>
				</tr>
			</thead>
			
			<tbody>
			{foreach from=$aUsersLast item=oUser}
				<tr>
					<td class="user"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oUser->getLogin()}/"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" /></a><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oUser->getLogin()}/">{$oUser->getLogin()}</a></td>														
					<td class="date">{date_format date=$oUser->getDateLast()}</td>
					<td class="strength">{$oUser->getSkill()}</td>							
					<td class="rating"><strong>{$oUser->getRating()}</strong></td>
				</tr>
			{/foreach}						
			</tbody>
		</table>
		{else}
			{$aLang.user_empty}
		{/if}
	</div>

	{include file='paging.tpl' aPaging=`$aPaging`}
			
			
{include file='footer.tpl'}