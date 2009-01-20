{include file='header.tpl' showWhiteBack=true}

			<div class="page people">
				
				<h1>Пользователи <span>({$aStat.count_all})</span></h1>

				
				<ul class="block-nav">
					<li {if $sEvent=='good'}class="active"{/if}><strong></strong><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/good/">Позитивные</a></li>
					<li {if $sEvent=='bad'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/bad/">Негативные</a><em></em></li>
				</ul>
				
				{if $aUsersRating}
				<table>
					<thead>
						<tr>
							<td class="user">Пользователь</td>													
							<td class="strength">Сила</td>
							<td class="rating">Рейтинг</td>
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
					нет таких	
				{/if}
			</div>

			{include file='paging.tpl' aPaging=`$aPaging`}
			
			
{include file='footer.tpl'}