{**
 * Список пользователей
 *
 * @param array $users
 *}

{foreach $smarty.local.users as $user}
	{* TODO: Убрать костыль для блогов *}
	{if $user->getUser()}
		{$user = $user->getUser()}
	{/if}

	{$session = $user->getSession()}
	{$usernote = $user->getUserNote()}

	<li class="object-list-item">
		{* Аватар *}
		<a href="{$user->getUserWebPath()}">
			<img src="{$user->getProfileAvatarPath(100)}" width="100" height="100" alt="{$user->getLogin()}" class="object-list-item-image" />
		</a>

		{* Заголовок *}
		<h2 class="object-list-item-title">
			<a href="{$user->getUserWebPath()}">{$user->getDisplayName()}</a>
		</h2>

		{* Заметка *}
		{if $usernote}
			{include 'components/note/note.tpl' classes='js-user-note' oObject=$usernote iUserId=$user->getId()}
		{/if}

		{* Информация *}
		{if $session}
			{$lastSessionDate = {date_format date=$session->getDateLast() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}}
		{/if}

		{include 'components/info-list/info-list.tpl' sInfoListClasses='object-list-item-info' aInfoList=[
			[ 'label' => "{$aLang.user.date_last_session}:", 'content' => ( $session ) ? $lastSessionDate : '&mdash;' ],
			[ 'label' => "{$aLang.user.date_registration}:", 'content' => {date_format date=$user->getDateRegister() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"} ],
			[ 'label' => "{$aLang.vote.rating}:",            'content' => $user->getRating() ]
		]}
	</li>
{/foreach}