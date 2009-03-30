{include file='header.tpl' showWhiteBack=true menu='people'}

			<div class="page people">
				
				<h1>{$aLang.user_list} <span>({$aStat.count_all})</span></h1>

				
				<ul class="block-nav">
					<li {if $sEvent=='good'}class="active"{/if}><strong></strong><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/good/">{$aLang.user_good}</a></li>
					<li {if $sEvent=='bad'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/bad/">{$aLang.user_bad}</a><em></em></li>
				</ul>
				
				{if $aUsersRating}
				<table>
					<thead>
						<tr>
							<td class="user">{$aLang.user}</td>													
							<td class="strength">{$aLang.user_skill}</td>
							<td class="rating">{$aLang.user_rating}</td>
						</tr>
					</thead>
					
					<tbody>
					{foreach from=$aUsersRating item=oUser}
						<tr>
							<td class="user"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oUser->getLogin()}/"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" /></a><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oUser->getLogin()}/" class="link">{$oUser->getLogin()}</a></td>														
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