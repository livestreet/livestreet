{include file='header.tpl' menu='people'}

	
<h2>{$aLang.user_list}: {$aStat.count_all}</h2>

<ul class="switcher">
	<li {if $sEvent=='good'}class="active"{/if}><a href="{router page='people'}good/">{$aLang.user_good}</a></li>
	<li {if $sEvent=='bad'}class="active"{/if}><a href="{router page='people'}bad/">{$aLang.user_bad}</a></li>
</ul>

{if $aUsersRating}
	<table class="table">
		<thead>
			<tr>
				<td>{$aLang.user}</td>													
				<td align="center" width="80">{$aLang.user_skill}</td>
				<td align="center" width="80">{$aLang.user_rating}</td>
			</tr>
		</thead>
		
		<tbody>
		{foreach from=$aUsersRating item=oUser}
			<tr>
				<td><a href="{router page='profile'}{$oUser->getLogin()}/">{$oUser->getLogin()}</a></td>														
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