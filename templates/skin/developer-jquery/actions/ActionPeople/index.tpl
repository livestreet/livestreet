{include file='header.tpl' menu='people'}


{if $aUsersRating}
	<table class="table table-users">
		<thead>
			<tr>
				<th class="table-users-cell-name">{$aLang.user}</th>													
				<th class="table-users-cell-skill">{$aLang.user_skill}</th>
				<th class="table-users-cell-rating">{$aLang.user_rating}</th>
			</tr>
		</thead>
		
		<tbody>
			{foreach from=$aUsersRating item=oUser}
				<tr>
					<td class="table-users-cell-name"><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></td>														
					<td class="table-users-cell-skill">{$oUser->getSkill()}</td>
					<td class="table-users-cell-rating">{$oUser->getRating()}</td>
				</tr>
			{/foreach}						
		</tbody>
	</table>
{else}
	{$aLang.user_empty}	
{/if}


{include file='paging.tpl' aPaging="$aPaging"}
{include file='footer.tpl'}