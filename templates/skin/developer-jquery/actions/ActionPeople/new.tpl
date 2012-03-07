{include file='header.tpl' menu='people'}

				

{if $aUsersRegister}
	<table class="table table-users">
		<thead>
			<tr>
				<th>{$aLang.user}</th>													
				<th>{$aLang.user_date_registration}</th>
				<th>{$aLang.user_skill}</th>
				<th>{$aLang.user_rating}</th>
			</tr>
		</thead>
		
		<tbody>
			{foreach from=$aUsersRegister item=oUser}
				<tr>
					<td><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></td>														
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