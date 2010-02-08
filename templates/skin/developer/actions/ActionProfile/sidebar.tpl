{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
<div class="block">				
	<ul>
		{include file='actions/ActionProfile/friend_item.tpl' oUserFriend=$oUserProfile->getUserFriend()}
		<li><a href="{router page='talk'}add/?talk_users={$oUserProfile->getLogin()}">{$aLang.user_write_prvmsg}</a></li>						
	</ul>
</div>
{/if}


{if $oUserProfile->getProfileIcq() || $oUserProfile->getProfileFoto()}
<div class="block nostyle">
	{if $oUserProfile->getProfileFoto()}
		<img src="{$oUserProfile->getProfileFoto()}" alt="photo" /><br /><br />
	{/if}
	
	{if $oUserProfile->getProfileIcq()}
	<strong>{$aLang.profile_social_contacts}</strong>
	<ul>
		{if $oUserProfile->getProfileIcq()}
			<li><strong>ICQ:</strong> <a href="http://www.icq.com/people/about_me.php?uin={$oUserProfile->getProfileIcq()|escape:'html'}" target="_blank">{$oUserProfile->getProfileIcq()}</a></li>
		{/if}					
	</ul>
	{/if}
</div>
{/if}