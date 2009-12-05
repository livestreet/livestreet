{include file='header.tpl' showWhiteBack=true menu='people'}

			<div class="page people">
				
				<h1>{$aLang.user_list}: {$oCity->getName()}</h1>
				
				{if $aUsersCity}
				<table>
					<thead>
						<tr>
							<td class="user">{$aLang.user}</td>	
							<td class="date">{$aLang.user_date_last}</td>												
							<td class="date">{$aLang.user_date_registration}</td>
							<td class="strength">{$aLang.user_skill}</td>
							<td class="rating">{$aLang.user_rating}</td>
						</tr>
					</thead>
					
					<tbody>
					{foreach from=$aUsersCity item=oUser}
					{assign var="oSession" value=$oUser->getSession()}
						<tr>
							<td class="user"><a href="{router page='profile'}{$oUser->getLogin()}/"><img src="{$oUser->getProfileAvatarPath(24)}" alt="" /></a><a href="{router page='profile'}{$oUser->getLogin()}/" class="link">{$oUser->getLogin()}</a></td>														
							<td class="date">{if $oSession}{date_format date=$oSession->getDateLast()}{/if}</td>
							<td class="date">{date_format date=$oUser->getDateRegister()}</td>
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