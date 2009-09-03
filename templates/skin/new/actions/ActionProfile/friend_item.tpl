{if $oUserFriend and ($oUserFriend->getFriendStatus()==$USER_FRIEND_ACCEPT+$USER_FRIEND_OFFER or $oUserFriend->getFriendStatus()==$USER_FRIEND_ACCEPT+$USER_FRIEND_ACCEPT) }
	<li class="del"><a href="#"  title="{$aLang.user_friend_del}" onclick="ajaxDeleteUserFriend(this,{$oUserProfile->getId()},'del'); return false;">{$aLang.user_friend_del}</a></li>
{elseif $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_OFFER+$USER_FRIEND_REJECT}
	<li class="del">{$aLang.user_friend_offer_reject}</li>							
{elseif $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_OFFER+$USER_FRIEND_NULL}
	<li class="add">{$aLang.user_friend_offer_send}</li>						
{elseif !$oUserFriend}	
	<li class="add">
		<a href="#"  title="{$aLang.user_friend_add}" onclick="toogleFriendForm(this); return false;">{$aLang.user_friend_add}</a>
		<form id="add_friend_form" onsubmit="ajaxAddUserFriend(this,{$oUserProfile->getId()},'add'); return false;"  style="display:none;">
			<label for="add_friend_text">{$aLang.user_friend_add_text_label}</label>
			<textarea id="add_friend_text"></textarea>
			<input type="submit" value="{$aLang.user_friend_add_submit}" />
			<input type="submit" value="{$aLang.user_friend_add_cansel}" onclick="toogleFriendForm(this); return false;" />
		</form>							
	</li>
{else}
	<li class="add">
		<a href="#"  title="{$aLang.user_friend_add}" onclick="ajaxAddUserFriend(this,{$oUserProfile->getId()},'link'); return false;">{$aLang.user_friend_add}</a>
	</li>
{/if}