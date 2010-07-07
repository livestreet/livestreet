{include file='header.tpl' menu="profile" showWhiteBack=true}

{assign var="oSession" value=$oUserProfile->getSession()}
{assign var="oVote" value=$oUserProfile->getVote()}

<div class="profile-user">
			
				<div class="strength">
					<div class="clear">{$aLang.user_skill}</div>
					<div class="total" id="user_skill_{$oUserProfile->getId()}">{$oUserProfile->getSkill()}</div>
				</div>
				
				
				<div class="voting {if $oUserProfile->getRating()>=0}positive{else}negative{/if} {if !$oUserCurrent || $oUserProfile->getId()==$oUserCurrent->getId()}guest{/if} {if $oVote} voted {if $oVote->getDirection()>0}plus{elseif $oVote->getDirection()<0}minus{/if}{/if}">
					<div class="clear">{$aLang.user_rating}</div>
					
					<a href="#" class="plus" onclick="lsVote.vote({$oUserProfile->getId()},this,1,'user'); return false;"></a>
					<div class="total">{if $oUserProfile->getRating()>0}+{/if}{$oUserProfile->getRating()}</div>
					<a href="#" class="minus" onclick="lsVote.vote({$oUserProfile->getId()},this,-1,'user'); return false;"></a>
					
					<div class="clear"></div>
					<div class="text">{$aLang.user_vote_count}:</div><div class="count">{$oUserProfile->getCountVote()}</div>
				</div>
				
				<div class="name">
					<img src="{$oUserProfile->getProfileAvatarPath(100)}" alt="avatar" class="avatar" />
					<p class="nickname">{$oUserProfile->getLogin()}</p>
					{if $oUserProfile->getProfileName()}
						<p class="realname">{$oUserProfile->getProfileName()|escape:'html'}</p>						
					{/if}										
				</div>
				
				
				{if $oUserProfile->getProfileSex()!='other' || $oUserProfile->getProfileBirthday() || ($oUserProfile->getProfileCountry() || $oUserProfile->getProfileRegion() || $oUserProfile->getProfileCity()) || $oUserProfile->getProfileAbout() || $oUserProfile->getProfileSite()}
				<h1 class="title">{$aLang.profile_privat}</h1>
				<table>		
					{if $oUserProfile->getProfileSex()!='other'}
					<tr>
						<td class="var">{$aLang.profile_sex}:</td>
						<td>
							{if $oUserProfile->getProfileSex()=='man'}
								{$aLang.profile_sex_man}
							{else}
								{$aLang.profile_sex_woman}
							{/if}
						</td>
					</tr>
					{/if}
						
					{if $oUserProfile->getProfileBirthday()}
					<tr>
						<td class="var">{$aLang.profile_birthday}:</td>
						<td>{date_format date=$oUserProfile->getProfileBirthday() format="j F Y"}</td>
					</tr>
					{/if}
					
					{if ($oUserProfile->getProfileCountry()|| $oUserProfile->getProfileRegion() || $oUserProfile->getProfileCity())}
					<tr>
						<td class="var">{$aLang.profile_place}:</td>
						<td>
						{if $oUserProfile->getProfileCountry()}
							<a href="{router page='people'}country/{$oUserProfile->getProfileCountry()|escape:'html'}/">{$oUserProfile->getProfileCountry()|escape:'html'}</a>{if $oUserProfile->getProfileCity()},{/if}
						{/if}						
						{if $oUserProfile->getProfileCity()}
							<a href="{router page='people'}city/{$oUserProfile->getProfileCity()|escape:'html'}/">{$oUserProfile->getProfileCity()|escape:'html'}</a>
						{/if}
						</td>
					</tr>
					{/if}
										
					{if $oUserProfile->getProfileAbout()}					
					<tr>
						<td class="var">{$aLang.profile_about}:</td>
						<td>{$oUserProfile->getProfileAbout()|escape:'html'}</td>
					</tr>	
					{/if}
					
					{if $oUserProfile->getProfileSite()}
					<tr>
						<td class="var">{$aLang.profile_site}:</td>
						<td>
						<noindex>
						<a href="{$oUserProfile->getProfileSite(true)|escape:'html'}" rel="nofollow">
						{if $oUserProfile->getProfileSiteName()}
							{$oUserProfile->getProfileSiteName()|escape:'html'}
						{else}
							{$oUserProfile->getProfileSite()|escape:'html'}
						{/if}
						</a>
						</noindex>
						</td>
					</tr>
					{/if}
					
					{hook run='profile_whois_privat_item' oUserProfile=$oUserProfile}
				</table>
				<br />	
				{/if}
				
				{hook run='profile_whois_item' oUserProfile=$oUserProfile}
				
				<br />
				<h1 class="title">{$aLang.profile_activity}</h1>
				<table>
					{if $aUsersFriend}
					<tr>
						<td class="var">{$aLang.profile_friends}:</td>
						<td class="friends">
							{foreach from=$aUsersFriend item=oUser}        						
        						<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>&nbsp; 
        					{/foreach}
						</td>
					</tr>
					{/if}
					
					{if $oConfig->GetValue('general.reg.invite') and $oUserInviteFrom}
					<tr>
						<td class="var">{$aLang.profile_invite_from}:</td>
						<td class="friends">							       						
        					<a href="{$oUserInviteFrom->getUserWebPath()}">{$oUserInviteFrom->getLogin()}</a>&nbsp;         					
						</td>
					</tr>
					{/if}
					
					{if $oConfig->GetValue('general.reg.invite') and $aUsersInvite}
					<tr>
						<td class="var">{$aLang.profile_invite_to}:</td>
						<td class="friends">
							{foreach from=$aUsersInvite item=oUserInvite}        						
        						<a href="{$oUserInvite->getUserWebPath()}">{$oUserInvite->getLogin()}</a>&nbsp; 
        					{/foreach}
						</td>
					</tr>
					{/if}
					
					{if $aBlogsOwner}
					<tr>
						<td class="var">{$aLang.profile_blogs_self}:</td>
						<td>							
							{foreach from=$aBlogsOwner item=oBlog name=blog_owner}
								<a href="{router page='blog'}{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a>{if !$smarty.foreach.blog_owner.last}, {/if}								      		
        					{/foreach}
						</td>
					</tr>
					{/if}
					
					{if $aBlogAdministrators}
					<tr>
						<td class="var">{$aLang.profile_blogs_administration}:</td>
						<td>
							{foreach from=$aBlogAdministrators item=oBlogUser name=blog_user}
								{assign var="oBlog" value=$oBlogUser->getBlog()}
								<a href="{router page='blog'}{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a>{if !$smarty.foreach.blog_user.last}, {/if}
							{/foreach}
						</td>
					</tr>
					{/if}
					
					{if $aBlogModerators}
					<tr>
						<td class="var">{$aLang.profile_blogs_moderation}:</td>
						<td>
							{foreach from=$aBlogModerators item=oBlogUser name=blog_user}
								{assign var="oBlog" value=$oBlogUser->getBlog()}
								<a href="{router page='blog'}{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a>{if !$smarty.foreach.blog_user.last}, {/if}
							{/foreach}
						</td>
					</tr>
					{/if}
					
					{if $aBlogUsers}
					<tr>
						<td class="var">{$aLang.profile_blogs_join}:</td>
						<td>
							{foreach from=$aBlogUsers item=oBlogUser name=blog_user}
								{assign var="oBlog" value=$oBlogUser->getBlog()}
								<a href="{router page='blog'}{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a>{if !$smarty.foreach.blog_user.last}, {/if}
							{/foreach}
						</td>
					</tr>
					{/if}

					{hook run='profile_whois_activity_item' oUserProfile=$oUserProfile}
					
					<tr>
						<td class="var">{$aLang.profile_date_registration}:</td>
						<td>{date_format date=$oUserProfile->getDateRegister()}</td>
					</tr>	
					{if $oSession}				
					<tr>
						<td class="var">{$aLang.profile_date_last}:</td>
						<td>{date_format date=$oSession->getDateLast()}</td>
					</tr>
					{/if}
				</table>
			</div>



{include file='footer.tpl'}

