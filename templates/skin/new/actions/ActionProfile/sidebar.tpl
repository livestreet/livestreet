			{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
			<div class="block actions white friend">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">					
					<ul>
						{include file='actions/ActionProfile/friend_item.tpl' oUserFriend=$oUserProfile->getUserFriend()}
						<li><a href="{router page='talk'}add/?talk_users={$oUserProfile->getLogin()}">{$aLang.user_write_prvmsg}</a></li>						
					</ul>
				</div></div>

				<div class="bl"><div class="br"></div></div>
			</div>
			{/if}
			
			<div class="block contacts nostyle">
				{if $oUserProfile->getProfileIcq()}
				<strong>{$aLang.profile_social_contacts}</strong>
				<ul>
					{if $oUserProfile->getProfileIcq()}
						<li class="icq"><a href="http://www.icq.com/people/about_me.php?uin={$oUserProfile->getProfileIcq()|escape:'html'}" target="_blank">{$oUserProfile->getProfileIcq()}</a></li>
					{/if}					
				</ul>
				{/if}
				
				{if $oUserProfile->getProfileFoto()}
				<img src="{$oUserProfile->getProfileFoto()}" alt="photo" />
				{/if}
			</div>