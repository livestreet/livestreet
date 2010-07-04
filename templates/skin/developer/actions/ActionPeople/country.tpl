{include file='header.tpl' menu='people'}


<h2>{$aLang.user_list}: {$oCountry->getName()}</h2>

{if $aUsersCountry}
	<table class="table">
		<thead>
			<tr>
				<td >{$aLang.user}</td>	
				<td>{$aLang.user_date_last}</td>												
				<td>{$aLang.user_date_registration}</td>
				<td>{$aLang.user_skill}</td>
				<td>{$aLang.user_rating}</td>
			</tr>
		</thead>

		<tbody>
		{foreach from=$aUsersCountry item=oUser}
			{assign var="oSession" value=$oUser->getSession()}
			<tr>
				<td><a href="{router page='profile'}{$oUser->getLogin()}/">{$oUser->getLogin()}</a></td>														
				<td>{if $oSession}{date_format date=$oSession->getDateLast()}{/if}</td>
				<td>{date_format date=$oUser->getDateRegister()}</td>
				<td>{$oUser->getSkill()}</td>							
				<td><strong>{$oUser->getRating()}</strong></td>
			</tr>
		{/foreach}						
		</tbody>
	</table>
{else}
	{$aLang.user_empty}
{/if}


{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}