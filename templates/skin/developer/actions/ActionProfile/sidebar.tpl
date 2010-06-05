{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
	<div class="block">				
		<ul>
			{include file='actions/ActionProfile/friend_item.tpl' oUserFriend=$oUserProfile->getUserFriend()}
			<li><a href="{router page='talk'}add/?talk_users={$oUserProfile->getLogin()}">{$aLang.user_write_prvmsg}</a></li>						
		</ul>
	</div>
{/if}


<div class="block">
	{if $oUserProfile->getProfileIcq()}
		<h2>{$aLang.profile_social_contacts}</h2>
		<ul>
			{if $oUserProfile->getProfileIcq()}
				<li>ICQ: <a href="http://www.icq.com/people/about_me.php?uin={$oUserProfile->getProfileIcq()|escape:'html'}" target="_blank">{$oUserProfile->getProfileIcq()}</a></li>
			{/if}					
		</ul>
	{/if}
	
	<br />
	
	{if $oUserProfile->getProfileFoto()}
		<img src="{$oUserProfile->getProfileFoto()}" alt="photo" />
	{/if}
</div>