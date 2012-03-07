{include file='header.tpl' menu='people'}
		

		
<h2 class="page-header">{$aLang.user_list}: {$oCity->getName()|escape:'html'}</h2>


{if $aUsersCity}
	<table class="table table-users">
		<thead>
			<tr>
				<th>{$aLang.user}</th>	
				<th>{$aLang.user_date_last}</th>												
				<th>{$aLang.user_date_registration}</th>
				<th>{$aLang.user_skill}</th>
				<th>{$aLang.user_rating}</th>
			</tr>
		</thead>
		
		<tbody>
			{foreach from=$aUsersCity item=oUser}
				{assign var="oSession" value=$oUser->getSession()}
				
				<tr>
					<td><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></td>														
					<td>{if $oSession}{date_format date=$oSession->getDateLast()}{/if}</td>
					<td>{date_format date=$oUser->getDateRegister()}</td>
					<td>{$oUser->getSkill()}</td>							
					<td>{$oUser->getRating()}</td>
				</tr>
			{/foreach}						
		</tbody>
	</table>
{else}
	{$aLang.user_empty}
{/if}


				
{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}