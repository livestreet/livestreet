{**
 * Событие
 *
 * @param object $event
 *}

{$component = 'activity-event'}

{$event  = $smarty.local.event}
{$type   = $event->getEventType()}
{$target = $event->getTarget()}
{$user   = $event->getUser()}
{$gender = ( $user->getProfileSex() == 'woman' ) ? 'female' : 'male'}

<li class="{$component} {$component}--{$type} js-{$component}">
	{* Аватар *}
	<a href="{$user->getUserWebPath()}">
		<img src="{$user->getProfileAvatarPath(48)}" alt="{$user->getDisplayName()}" class="activity-event-avatar" />
	</a>

	{* Дата *}
	<time datetime="{date_format date=$event->getDateAdded() format='c' notz=1}"
		  class="{$component}-date"
		  title="{date_format date=$event->getDateAdded()}">
		{date_format date=$event->getDateAdded() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
	</time>

	{* Логин *}
	<a href="{$user->getUserWebPath()}" class="{$component}-username">
		{$user->getDisplayName()}
	</a>

	{* Текст события *}
	{$aLang.activity.events["{$type}_{$gender}"]}

	{if $type == 'add_topic'}
		{* Добавлен топик *}
		<a href="{$target->getUrl()}">{$target->getTitle()|escape}</a>
	{elseif $type == 'add_comment'}
		{* Добавлен комментарий *}
		<a href="{$target->getTarget()->getUrl()}#comment{$target->getId()}">{$target->getTarget()->getTitle()|escape}</a>

		{$sTextEvent = $target->getText()}

		{if trim($sTextEvent)}
			<div class="{$component}-text text">{$sTextEvent}</div>
		{/if}
	{elseif $type == 'add_blog'}
		{* Создан блог *}
		<a href="{$target->getUrlFull()}">{$target->getTitle()|escape}</a>
	{elseif $type == 'vote_blog'}
		{* Проголосовали за блог *}
		<a href="{$target->getUrlFull()}">{$target->getTitle()|escape}</a>
	{elseif $type == 'vote_topic'}
		{* Проголосовали за топик *}
		<a href="{$target->getUrl()}">{$target->getTitle()|escape}</a>
	{elseif $type == 'vote_comment_topic'}
		{* Проголосовали за комментарий *}
		<a href="{$target->getTarget()->getUrl()}#comment{$target->getId()}">{$target->getTarget()->getTitle()|escape}</a>
	{elseif $type == 'vote_user'}
		{* Проголосовали за пользователя *}
		<a href="{$target->getUserWebPath()}">{$target->getDisplayName()}</a>
	{elseif $type == 'join_blog'}
		{* Вступили в блог *}
		<a href="{$target->getUrlFull()}">{$target->getTitle()|escape}</a>
	{elseif $type == 'add_friend'}
		{* Добавили в друзья *}
		<a href="{$target->getUserWebPath()}">{$target->getDisplayName()}</a>
	{elseif $type == 'add_wall'}
		{* Написали на стене *}
		<a href="{$target->getUrlWall()}">{$target->getWallUser()->getDisplayName()}</a>

		{$sTextEvent = $target->getText()}

		{if trim($sTextEvent)}
			<div class="{$component}-text text">{$sTextEvent}</div>
		{/if}
	{else}
		{hook run="activity_event_`$type`" event=$event}
	{/if}
</li>