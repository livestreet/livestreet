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

{**
 * Вывод текста
 *
 * @param $text Текст
 *}
{function activity_event_text text=''}
    {if trim($text)}
        <div class="{$component}-text text">{$text}</div>
    {/if}
{/function}


{* Событие *}
{capture 'event_content'}
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
    {if $type == 'add_topic'}
        {* Добавлен топик *}
        {lang "activity.events.{$type}_{$gender}" topic="<a href=\"{$target->getUrl()}\">{$target->getTitle()|escape}</a>"}
    {elseif $type == 'add_comment'}
        {* Добавлен комментарий *}
        {lang "activity.events.{$type}_{$gender}" topic="<a href=\"{$target->getTarget()->getUrl()}#comment{$target->getId()}\">{$target->getTarget()->getTitle()|escape}</a>"}

        {activity_event_text text=$target->getText()}
    {elseif $type == 'add_blog'}
        {* Создан блог *}
        {lang "activity.events.{$type}_{$gender}" blog="<a href=\"{$target->getUrlFull()}\">{$target->getTitle()|escape}</a>"}
    {elseif $type == 'vote_blog'}
        {* Проголосовали за блог *}
        {lang "activity.events.{$type}_{$gender}" blog="<a href=\"{$target->getUrlFull()}\">{$target->getTitle()|escape}</a>"}
    {elseif $type == 'vote_topic'}
        {* Проголосовали за топик *}
        {lang "activity.events.{$type}_{$gender}" topic="<a href=\"{$target->getUrl()}\">{$target->getTitle()|escape}</a>"}
    {elseif $type == 'vote_comment_topic'}
        {* Проголосовали за комментарий *}
        {lang "activity.events.{$type}_{$gender}" topic="<a href=\"{$target->getTarget()->getUrl()}#comment{$target->getId()}\">{$target->getTarget()->getTitle()|escape}</a>"}
    {elseif $type == 'vote_user'}
        {* Проголосовали за пользователя *}
        {lang "activity.events.{$type}_{$gender}" user="<a href=\"{$target->getUserWebPath()}\">{$target->getDisplayName()}</a>"}
    {elseif $type == 'join_blog'}
        {* Вступили в блог *}
        {lang "activity.events.{$type}_{$gender}" blog="<a href=\"{$target->getUrlFull()}\">{$target->getTitle()|escape}</a>"}
    {elseif $type == 'add_friend'}
        {* Добавили в друзья *}
        {lang "activity.events.{$type}_{$gender}" user="<a href=\"{$target->getUserWebPath()}\">{$target->getDisplayName()}</a>"}
    {elseif $type == 'add_wall'}
        {* Написали на стене *}
        {if $target->getWallUser()->getId() == $user->getId()}
            {lang "activity.events.{$type}_self_{$gender}" url=$target->getUrlWall()}
        {else}
            {lang "activity.events.{$type}_{$gender}" url=$target->getUrlWall() user=$target->getWallUser()->getDisplayName()}
        {/if}

        {activity_event_text text=$target->getText()}
    {else}
        {hook run="activity_event_`$type`" event=$event}
    {/if}
{/capture}

{component 'item'
    element='li'
    classes="{$component} {cmods name=$component mods=$type}"
    mods='image-rounded'
    desc=$smarty.capture.event_content
    image=[
        'url' => $user->getUserWebPath(),
        'path' => $user->getProfileAvatarPath(48),
        'alt' => $user->getDisplayName()
    ]}