			{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
			<div class="block actions white friend">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">					
					<ul>
						{assign var="oUserFriend" value=$oUserProfile->getUserFriend()}
						{if $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_ACCEPT+$USER_FRIEND_OFFER }
							<li class="del"><a href="#"  title="{$aLang.user_friend_del}" onclick="ajaxDeleteUserFriend(this,{$oUserProfile->getId()},'del'); return false;">{$aLang.user_friend_del}</a></li>
						{elseif $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_OFFER+$USER_FRIEND_REJECT}
							<li class="del">{$aLang.user_friend_offer_reject}</li>							
						{elseif $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_OFFER+$USER_FRIEND_NULL}
							<li class="add">{$aLang.user_friend_offer_send}</li>						
						{else}	
							<li class="add">
								<a href="#"  title="{$aLang.user_friend_add}" onclick="toogleFriendForm(this); return false;">{$aLang.user_friend_add}</a>
								<form id="add_friend_form" onsubmit="ajaxAddUserFriend(this,{$oUserProfile->getId()},'add'); return false;"  style="display:none;">
									<label for="add_friend_text">{$aLang.user_friend_add_text_label}</label>
									<textarea id="add_friend_text"></textarea>
									<input type="submit" value="{$aLang.user_friend_add_submit}" />
									<input type="submit" value="{$aLang.user_friend_add_cansel}" onclick="toogleFriendForm(this); return false;" />
								</form>							
							</li>
						{/if}
						
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
				<img src="{$aConfig.path.root.web}{$oUserProfile->getProfileFoto()}" alt="photo" />
				{/if}
			</div>