{include file='header.tpl'}

{include file='menu.profile.tpl'}


<BR>
<div class="userinfo">
	<div class="userinfo_line">
		<div class="left">
		</div>
		<div class="right">
			<span class="username">
			{if $oUserProfile->getProfileName()}
				{$oUserProfile->getProfileName()|escape:'html'}
			{else}
				{$oUserProfile->getLogin()}
			{/if}
			</span>
		</div>
	</div>
	
	{if $oUserProfile->getProfileBirthday()}
	<div class="userinfo_line">
		<div class="left">
			Дата рождения:
		</div>
		<div class="right">
			{date_format date=$oUserProfile->getProfileBirthday() format="j rus_mon Y"}			
		</div>
 	</div>
 	{/if}
 	
 	{if ($oUserProfile->getProfileCountry()|| $oUserProfile->getProfileRegion() || $oUserProfile->getProfileCity())}
	<div class="userinfo_line">
		<div class="left">
			Откуда:
		</div>
		<div class="right">
			{if $oUserProfile->getProfileCountry()}
				{$oUserProfile->getProfileCountry()|escape:'html'}
			{/if}
			{if $oUserProfile->getProfileRegion()}
				, {$oUserProfile->getProfileRegion()|escape:'html'}
			{/if}
			{if $oUserProfile->getProfileCity()}
				, {$oUserProfile->getProfileCity()|escape:'html'}
			{/if}			
		</div>
	</div>
	{/if}
	
	{if $oUserProfile->getProfileAbout()}
	<div class="userinfo_line">
		<div class="left">
			О себе:
		</div>
		<div class="right aboutuser">
			{$oUserProfile->getProfileAbout()|escape:'html'}
		</div>
	</div> 
	{/if}
	
	{if $oUserProfile->getProfileSite()}
	<div class="userinfo_line">
		<div class="left">
			Сайт:
		</div>
		<div class="right">
			<a href="{$oUserProfile->getProfileSite(true)|escape:'html'}" target="_blank">
			{if $oUserProfile->getProfileSiteName()}
				{$oUserProfile->getProfileSiteName()|escape:'html'}
			{else}
				{$oUserProfile->getProfileSite()|escape:'html'}
			{/if}
			</a>
		</div>
	</div>
	{/if}
	
	{if $oUserProfile->getProfileIcq()}
	<div class="userinfo_line">
		<div class="left">
			ICQ:
		</div>
		<div class="right">
			<a class="icon" href="http://wwp.icq.com/scripts/contact.dll?msgto={$oUserProfile->getProfileIcq()|escape:'html'}" target="_blank"><img src="http://web.icq.com/whitepages/online?icq={$oUserProfile->getProfileIcq()|escape:'html'}&img=5" width=18 height=18 align=absmiddle border=0>{$oUserProfile->getProfileIcq()|escape:'html'}</a>
		</div>
	</div>
	{/if}
	
	
	{if $aUsersFrend}
	<div class="logicgroup_menu">		  
  		<div class="userinfo_line">
  			<div class="left">
  				Друзья:
  			</div>
			<div class="right">
				{foreach from=$aUsersFrend item=oUserFrend}
        			<span class="nowrap"><a href="{$DIR_WEB_ROOT}/profile/{$oUserFrend->getLogin()}/"><img src="{$DIR_STATIC_SKIN}/img/user.gif" border="0" alt="посмотреть профиль" title="посмотреть профиль"></a><a href="{$DIR_WEB_ROOT}/profile/{$oUserFrend->getLogin()}/" class="userinfo_nickname_normal">{$oUserFrend->getLogin()}</a></span>
        		{/foreach}        		
       		</div>
		</div>
	</div>
    {/if}
     
	
	{if $aBlogsOwner}
 	<div class="logicgroup">
		<div class="userinfo_line">
   			<div class="left">
   				Администрирует:
   			</div>
			<div class="right">
			{foreach from=$aBlogsOwner item=oBlog}
        		<span class="nowrap"><a href="{$DIR_WEB_ROOT}/blog/{$oBlog->getUrl()}/"><img  src="{$DIR_STATIC_SKIN}/img/blog.gif" width="12" height="12" alt="" title="{$oBlog->getTitle()|escape:'html'}" border="0"></a><a href="{$DIR_WEB_ROOT}/blog/{$oBlog->getUrl()}/" class="userinfo_groups_name">{$oBlog->getTitle()|escape:'html'}</a></span>
        	{/foreach}
       		</div>
		</div>        
	</div>
	{/if}
	
	{if $aBlogsUser}
 	<div class="logicgroup">
		<div class="userinfo_line">
   			<div class="left">
   				Состоит в:
   			</div>
			<div class="right">
			{foreach from=$aBlogsUser item=oBlogUser}
        		<span class="nowrap"><a href="{$DIR_WEB_ROOT}/blog/{$oBlogUser->getBlogUrl()}/"><img  src="{$DIR_STATIC_SKIN}/img/blog.gif" width="12" height="12" alt="" title="{$oBlogUser->getBlogTitle()|escape:'html'}" border="0"></a><a href="{$DIR_WEB_ROOT}/blog/{$oBlogUser->getBlogUrl()}/" class="userinfo_groups_name">{$oBlogUser->getBlogTitle()|escape:'html'}</a></span>
        	{/foreach}
       		</div>
		</div>        
	</div>
	{/if}
   
	<div class="logicgroup">
		<div class="userinfo_line">
			<div class="left">
				Зарегистрирован:
			</div>
			<div class="right">				
				{date_format date=$oUserProfile->getDateRegister()}	
			</div>
		</div>
		<div class="userinfo_line">
			<div class="left">
				Активность:
			</div>
			<div class="right">
				Последний раз был на сайте {date_format date=$oUserProfile->getDateLast()}	
			</div>
		</div>  		
	</div>
</div>


{include file='footer.tpl'}

