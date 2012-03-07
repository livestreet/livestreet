{include file='header.tpl' menu='people'}



<h2 class="page-header">{$aLang.user_list}: <span>{$oCountry->getName()|escape:'html'}</span></h2>


{if $aUsersCountry}
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
			{foreach from=$aUsersCountry item=oUser}
				{assign var="oSession" value=$oUser->getSession()}
				<tr>
					<td class="table-users-cell-name"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></td>														
					<td>{if $oSession}{date_format date=$oSession->getDateLast()}{/if}</td>
					<td>{date_format date=$oUser->getDateRegister()}</td>
					<td class="table-users-cell-skill">{$oUser->getSkill()}</td>							
					<td class="table-users-cell-rating"><strong>{$oUser->getRating()}</strong></td>
				</tr>
			{/foreach}						
		</tbody>
	</table>
{else}
	{$aLang.user_empty}
{/if}



{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}