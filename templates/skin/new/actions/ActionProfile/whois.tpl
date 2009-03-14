{include file='header.tpl' menu="profile" showWhiteBack=true}



<div class="profile-user">
			
				<div class="strength">
					<div class="clear">Сила</div>
					<div class="total" id="user_skill_{$oUserProfile->getId()}">{$oUserProfile->getSkill()}</div>
				</div>
				
				
				<div class="voting {if $oUserProfile->getRating()>=0}positive{else}negative{/if} {if !$oUserCurrent || $oUserProfile->getId()==$oUserCurrent->getId()}guest{/if} {if $oUserProfile->getUserIsVote()} voted {if $oUserProfile->getUserVoteDelta()>0}plus{elseif $oUserProfile->getUserVoteDelta()<0}minus{/if}{/if}">
					<div class="clear">Рейтинг</div>
					
					<a href="#" class="plus" onclick="lsVote.vote({$oUserProfile->getId()},this,1,'user'); return false;"></a>
					<div class="total">{if $oUserProfile->getRating()>0}+{/if}{$oUserProfile->getRating()}</div>
					<a href="#" class="minus" onclick="lsVote.vote({$oUserProfile->getId()},this,-1,'user'); return false;"></a>
					
					<div class="clear"></div>
					<div class="text">голосов:</div><div class="count">{$oUserProfile->getCountVote()}</div>
				</div>
				
				<div class="name">
					<img src="{$oUserProfile->getProfileAvatarPath(100)}" alt="avatar" class="avatar" />
					<p class="nickname">{$oUserProfile->getLogin()}</p>
					{if $oUserProfile->getProfileName()}
						<p class="realname">{$oUserProfile->getProfileName()|escape:'html'}</p>						
					{/if}										
				</div>
				
				
				{if $oUserProfile->getProfileBirthday() || ($oUserProfile->getProfileCountry() || $oUserProfile->getProfileRegion() || $oUserProfile->getProfileCity()) || $oUserProfile->getProfileAbout() || $oUserProfile->getProfileSite()}
				<h1 class="title">Личное</h1>
				<table>				
					{if $oUserProfile->getProfileBirthday()}
					<tr>
						<td class="var">Дата рождения:</td>
						<td>{date_format date=$oUserProfile->getProfileBirthday() format="j rus_mon Y"}</td>
					</tr>
					{/if}
					
					{if ($oUserProfile->getProfileCountry()|| $oUserProfile->getProfileRegion() || $oUserProfile->getProfileCity())}
					<tr>
						<td class="var">Местоположение:</td>
						<td>
						{if $oUserProfile->getProfileCountry()}
							<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/country/{$oUserProfile->getProfileCountry()|escape:'html'}/">{$oUserProfile->getProfileCountry()|escape:'html'}</a>{if $oUserProfile->getProfileCity()},{/if}
						{/if}						
						{if $oUserProfile->getProfileCity()}
							<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PEOPLE}/city/{$oUserProfile->getProfileCity()|escape:'html'}/">{$oUserProfile->getProfileCity()|escape:'html'}</a>
						{/if}
						</td>
					</tr>
					{/if}
										
					{if $oUserProfile->getProfileAbout()}					
					<tr>
						<td class="var">О себе:</td>
						<td>{$oUserProfile->getProfileAbout()|escape:'html'}</td>
					</tr>	
					{/if}
					
					{if $oUserProfile->getProfileSite()}
					<tr>
						<td class="var">Сайт:</td>
						<td>
						<a href="{$oUserProfile->getProfileSite(true)|escape:'html'}">
						{if $oUserProfile->getProfileSiteName()}
							{$oUserProfile->getProfileSiteName()|escape:'html'}
						{else}
							{$oUserProfile->getProfileSite()|escape:'html'}
						{/if}
						</a>
						</td>
					</tr>
					{/if}
				</table>
				<br />	
				{/if}
				<br />
				<h1 class="title">Активность</h1>
				<table>
					{if $aUsersFrend}
					<tr>
						<td class="var">Друзья:</td>
						<td class="friends">
							{foreach from=$aUsersFrend item=oUserFrend}        						
        						<a href="{$oUserFrend->getUserWebPath()}">{$oUserFrend->getLogin()}</a>&nbsp; 
        					{/foreach}
						</td>
					</tr>
					{/if}
					
					{if $aUsersSelfFrend}
					<tr>
						<td class="var">В друзьях у:</td>
						<td class="friends">
							{foreach from=$aUsersSelfFrend item=oUserFrend}        						
        						<a href="{$oUserFrend->getUserWebPath()}">{$oUserFrend->getLogin()}</a>&nbsp; 
        					{/foreach}
						</td>
					</tr>
					{/if}
					
					{if $USER_USE_INVITE and $oUserInviteFrom}
					<tr>
						<td class="var">Пригласил:</td>
						<td class="friends">							       						
        					<a href="{$oUserInviteFrom->getUserWebPath()}">{$oUserInviteFrom->getLogin()}</a>&nbsp;         					
						</td>
					</tr>
					{/if}
					
					{if $USER_USE_INVITE and $aUsersInvite}
					<tr>
						<td class="var">Приглашенные:</td>
						<td class="friends">
							{foreach from=$aUsersInvite item=oUserInvite}        						
        						<a href="{$oUserInvite->getUserWebPath()}">{$oUserInvite->getLogin()}</a>&nbsp; 
        					{/foreach}
						</td>
					</tr>
					{/if}
					
					{if $aBlogsOwner}
					<tr>
						<td class="var">Администрирует:</td>
						<td>							
							{foreach from=$aBlogsOwner item=oBlog name=blog_owner}
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a>{if !$smarty.foreach.blog_owner.last}, {/if}								      		
        					{/foreach}
						</td>
					</tr>
					{/if}
					
					{if $aBlogsUser}
					<tr>
						<td class="var">Состоит в:</td>
						<td>
							{foreach from=$aBlogsUser item=oBlogUser name=blog_user}
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/{$oBlogUser->getBlogUrl()}/">{$oBlogUser->getBlogTitle()|escape:'html'}</a>{if !$smarty.foreach.blog_user.last}, {/if}
							{/foreach}
						</td>
					</tr>
					{/if}
										
					<tr>
						<td class="var">Зарегистрирован:</td>
						<td>{date_format date=$oUserProfile->getDateRegister()}</td>
					</tr>					
					<tr>
						<td class="var">Последний визит:</td>
						<td>{date_format date=$oUserProfile->getDateLast()}</td>
					</tr>
				</table>
			</div>



{include file='footer.tpl'}

