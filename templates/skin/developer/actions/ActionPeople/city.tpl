{include file='header.tpl' menu='people'}
		
		
<h2>{$aLang.user_list}: {$oCity->getName()}</h2>

{if $aUsersCity}
	<table class="table">
		<thead>
			<tr>
				<td>{$aLang.user}</td>	
				<td align="center" width="160">{$aLang.user_date_last}</td>												
				<td align="center" width="160">{$aLang.user_date_registration}</td>
				<td align="center" width="60">{$aLang.user_skill}</td>
				<td align="center" width="60">{$aLang.user_rating}</td>
			</tr>
		</thead>
		
		<tbody>
		{foreach from=$aUsersCity item=oUser}
		{assign var="oSession" value=$oUser->getSession()}
			<tr>
				<td><a href="{router page='profile'}{$oUser->getLogin()}/">{$oUser->getLogin()}</a></td>														
				<td align="center">{if $oSession}{date_format date=$oSession->getDateLast()}{/if}</td>
				<td align="center">{date_format date=$oUser->getDateRegister()}</td>
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