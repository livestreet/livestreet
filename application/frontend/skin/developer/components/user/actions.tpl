{**
 * Список действий
 * TODO: Add hook
 *}

{$user = $smarty.local.user}

<ul class="profile-actions" id="profile_actions">
	{* Добавление в друзья *}
	{include './friend-item.tpl' friendship=$user->getUserFriend() userTarget=$oUserProfile classes='js-user-friend'}

	{* Отправить сообщение *}
	<li>
		<a href="{router page='talk'}add/?talk_users={$user->getLogin()}">
			{lang name='user.actions.send_message'}
		</a>
	</li>

	{* Подписаться *}
	<li>
		<a href="#" class="js-user-follow {if $user->isFollow()}active{/if}" data-id="{$user->getId()}" data-login="{$user->getLogin()}">
			{lang name="user.actions.{( $user->isFollow() ) ? 'unfollow' : 'follow'}"}
		</a>
	</li>

	{* Пожаловаться *}
	<li>
		<a href="#" class="js-user-report" data-param-target_id="{$user->getId()}">
			{lang 'user.actions.report'}
		</a>
	</li>
</ul>