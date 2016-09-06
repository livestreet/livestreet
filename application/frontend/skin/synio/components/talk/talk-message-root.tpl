{**
 * Первое сообщение в диалоге
 *}

{$component = 'ls-talk-message-root'}
{component_define_params params=[ 'talk', 'mods', 'classes', 'attributes' ]}


<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
    {* Заголовок *}
    <h2 class="{$component}-title">
        {$talk->getTitle()}
    </h2>

    {* Содержимое *}
    <div class="{$component}-text ls-text">
        {$talk->getText()}
    </div>

    {* Участники личного сообщения *}
    {capture 'talk_message_root_participants'}
        {component 'talk' template='participants'
            users         = $talk->getTalkUsers()
            classes       = 'message-users js-message-users'
            attributes    = [ 'data-param-target_id' => $talk->getId() ]
            editable      = $talk->getUserId() == $oUserCurrent->getId() || $oUserCurrent->isAdministrator()
            excludeRemove = [ $oUserCurrent->getId() ]}
    {/capture}

    {component 'details'
        classes = 'js-details-default ls-talk-participants-details'
        title   = "{lang 'talk.users.title'} ({count($talk->getTalkUsers())})"
        content = $smarty.capture.talk_message_root_participants}

    {* Информация *}
    <ul class="{$component}-info ls-clearfix">
        {* Автор *}
        <li class="{$component}-info-item {$component}-info-item--author">
            {component 'user' template='avatar' user=$talk->getUser() size='text' mods='inline'}
        </li>

        <li class="{$component}-info-item {$component}-info-item--date">
            <time datetime="{date_format date=$talk->getDate() format='c'}" title="{date_format date=$talk->getDate() format='j F Y, H:i'}">
                {date_format date=$talk->getDate() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
            </time>
        </li>

        <li class="{$component}-info-item {$component}-info-item--favourite">
            {component 'favourite' classes="js-favourite-talk" target=$talk}
        </li>

        {if $oUserCurrent->getId() == $talk->getUser()->getId() || $oUserCurrent->isAdministrator()}
            <li class="{$component}-info-item {$component}-info-item--remove">
                <a href="{$talk->getUrlDelete()}?security_ls_key={$LIVESTREET_SECURITY_KEY}" class="js-confirm-remove-default">{lang 'common.remove'}</a>
            </li>
        {/if}
    </ul>
</div>