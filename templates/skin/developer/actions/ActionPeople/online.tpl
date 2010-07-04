{include file='header.tpl' menu='people'}

						
<h2>{$aLang.user_list_online_last}</h2>

{if $aUsersLast}
	<table class="table">
		<thead>
			<tr>
				<td>{$aLang.user}</td>													
				<td align="center" width="170">{$aLang.user_date_last}</td>
				<td align="center" width="80">{$aLang.user_skill}</td>
				<td align="center" width="80">{$aLang.user_rating}</td>
			</tr>
		</thead>
		
		<tbody>
		{foreach from=$aUsersLast item=oUser}
			{assign var="oSession" value=$oUser->getSession()}
			<tr>
				<td><a href="{router page='profile'}{$oUser->getLogin()}/">{$oUser->getLogin()}</a></td>														
				<td align="center">{date_format date=$oSession->getDateLast()}</td>
				<td align="center">{$oUser->getSkill()}</td>							
				<td align="center"><strong>{$oUser->getRating()}</strong></td>
			</tr>
		{/foreach}						
		</tbody>
	</table>
{else}
	{$aLang.user_empty}
{/if}

			
{include file='paging.tpl' aPaging="$aPaging"}		
{include file='footer.tpl'}