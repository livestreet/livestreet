{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
<div class="block">				
	<ul>
		{if $oUserProfile->getUserIsFrend()}
			<li><a href="#"  title="{$aLang.user_friend_del}" onclick="ajaxToggleUserFrend(this,{$oUserProfile->getId()}); return false;">{$aLang.user_friend_del}</a></li>
		{else}
			<li><a href="#"  title="{$aLang.user_friend_add}" onclick="ajaxToggleUserFrend(this,{$oUserProfile->getId()}); return false;">{$aLang.user_friend_add}</a></li>
		{/if}
		<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/add/?talk_users={$oUserProfile->getLogin()}">{$aLang.user_write_prvmsg}</a></li>						
	</ul>
</div>
{/if}

<div class="block">
	{if $oUserProfile->getProfileIcq()}
	<h3>{$aLang.profile_social_contacts}</h3>
	<ul>
		{if $oUserProfile->getProfileIcq()}
			<li>ICQ: <a href="http://www.icq.com/people/about_me.php?uin={$oUserProfile->getProfileIcq()|escape:'html'}" target="_blank">{$oUserProfile->getProfileIcq()}</a></li>
		{/if}					
	</ul>
	{/if}
	
	{if $oUserProfile->getProfileFoto()}
		<br /><br />
		<img src="{$DIR_WEB_ROOT}{$oUserProfile->getProfileFoto()}" alt="photo" />
	{/if}
</div>	