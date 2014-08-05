{**
 * Список действий
 *}

{$user = $smarty.local.user}

<ul class="profile-actions" id="profile_actions">
	{include 'components/user/friend_item.tpl' oUserFriend=$user->getUserFriend()}

	<li><a href="{router page='talk'}add/?talk_users={$user->getLogin()}">{lang name='user.actions.send_message'}</a></li>
	<li>
		<a href="#" class="js-user-follow {if $user->isFollow()}active{/if}" data-user-id="{$user->getId()}" data-user-login="{$user->getLogin()}">
			{if $user->isFollow()}
				{$aLang.user.actions.unfollow}
			{else}
				{$aLang.user.actions.follow}
			{/if}
		</a>
	</li>
	<li>
		<a href="#" data-type="modal-toggle" data-modal-url="{router page='profile/ajax-modal-complaint'}" data-param-user_id="{$user->getId()}">{$aLang.user_complaint_title}</a>
	</li>
</ul>