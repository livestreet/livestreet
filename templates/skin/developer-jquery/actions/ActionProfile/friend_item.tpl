{if $oUserFriend and ($oUserFriend->getFriendStatus()==$USER_FRIEND_ACCEPT+$USER_FRIEND_OFFER or $oUserFriend->getFriendStatus()==$USER_FRIEND_ACCEPT+$USER_FRIEND_ACCEPT) }
	<li id="delete_friend_item"><a href="#"  title="{$aLang.user_friend_del}" onclick="ajaxDeleteUserFriend(this,{$oUserProfile->getId()},'del'); return false;">{$aLang.user_friend_del}</a></li>
{elseif $oUserFriend and $oUserFriend->getStatusTo()==$USER_FRIEND_REJECT and $oUserFriend->getStatusFrom()==$USER_FRIEND_OFFER and $oUserFriend->getUserTo()==$oUserCurrent->getId()}
	<li id="add_friend_item"><a href="#"  title="{$aLang.user_friend_add}" onclick="ajaxAddUserFriend(this,{$oUserProfile->getId()},'accept'); return false;">{$aLang.user_friend_add}</a></li>
{elseif $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_OFFER+$USER_FRIEND_REJECT and $oUserFriend->getUserTo()!=$oUserCurrent->getId()}
	<li>{$aLang.user_friend_offer_reject}</li>							
{elseif $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_OFFER+$USER_FRIEND_NULL and $oUserFriend->getUserFrom()==$oUserCurrent->getId()}
	<li>{$aLang.user_friend_offer_send}</li>						
{elseif $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_OFFER+$USER_FRIEND_NULL and $oUserFriend->getUserTo()==$oUserCurrent->getId()}
	<li id="add_friend_item"><a href="#"  title="{$aLang.user_friend_add}" onclick="ajaxAddUserFriend(this,{$oUserProfile->getId()},'accept'); return false;">{$aLang.user_friend_add}</a></li>
{elseif !$oUserFriend}	
	<li id="add_friend_item"><a href="#"  title="{$aLang.user_friend_add}" id="add_friend_show">{$aLang.user_friend_add}</a>						</li>

	<form id="add_friend_form" class="add-friend-form" onsubmit="ajaxAddUserFriend(this,{$oUserProfile->getId()},'add'); return false;">
		<a href="#" class="close jqmClose"></a>
		
		<label for="add_friend_text">{$aLang.user_friend_add_text_label}</label><br />
		<textarea id="add_friend_text" rows="3"></textarea>
		
		<input type="submit" value="{$aLang.user_friend_add_submit}" />
	</form>	
{else}
	<li id="add_friend_item"><a href="#" title="{$aLang.user_friend_add}" onclick="ajaxAddUserFriend(this,{$oUserProfile->getId()},'link'); return false;">{$aLang.user_friend_add}</a></li>
{/if}