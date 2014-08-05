{**
 * Добавление / удаление из друзей
 *}

{if $oUserFriend and ($oUserFriend->getFriendStatus()==$USER_FRIEND_ACCEPT+$USER_FRIEND_OFFER or $oUserFriend->getFriendStatus()==$USER_FRIEND_ACCEPT+$USER_FRIEND_ACCEPT)}
	<li id="delete_friend_item"><a href="#"  title="{$aLang.user.friends.remove}" onclick="return ls.user.removeFriend(this,{$oUserProfile->getId()},'del');">{$aLang.user.friends.remove}</a></li>
{elseif $oUserFriend and $oUserFriend->getStatusTo()==$USER_FRIEND_REJECT and $oUserFriend->getStatusFrom()==$USER_FRIEND_OFFER and $oUserFriend->getUserTo()==$oUserCurrent->getId()}
	<li id="add_friend_item"><a href="#"  title="{$aLang.user.friends.add}" onclick="return ls.user.addFriend(this,{$oUserProfile->getId()},'accept');">{$aLang.user.friends.add}</a></li>
{elseif $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_OFFER+$USER_FRIEND_REJECT and $oUserFriend->getUserTo()!=$oUserCurrent->getId()}
	<li>{$aLang.user.friends.rejected}</li>
{elseif $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_OFFER+$USER_FRIEND_NULL and $oUserFriend->getUserFrom()==$oUserCurrent->getId()}
	<li>{$aLang.user.friends.sent}</li>
{elseif $oUserFriend and $oUserFriend->getFriendStatus()==$USER_FRIEND_OFFER+$USER_FRIEND_NULL and $oUserFriend->getUserTo()==$oUserCurrent->getId()}
	<li id="add_friend_item"><a href="#"  title="{$aLang.user.friends.add}" onclick="return ls.user.addFriend(this,{$oUserProfile->getId()},'accept');">{$aLang.user.friends.add}</a></li>
{elseif !$oUserFriend}
	{include file='modals/modal.add_friend.tpl'}

	<li id="add_friend_item"><a href="#"  title="{$aLang.user.friends.add}" data-type="modal-toggle" data-modal-target="modal-add-friend">{$aLang.user.friends.add}</a></li>
{else}
	<li id="add_friend_item"><a href="#" title="{$aLang.user.friends.add}" onclick="return ls.user.addFriend(this,{$oUserProfile->getId()},'link');">{$aLang.user.friends.add}</a></li>
{/if}