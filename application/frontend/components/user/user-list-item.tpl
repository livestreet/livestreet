{if $user->getUser()}
    {$user = $user->getUser()}
{/if}

{* Заголовок *}
{capture 'title'}
    <a href="{$user->getUserWebPath()}">{$user->getDisplayName()}</a>
{/capture}

{* Описание *}
{capture 'content'}
    {$session = $user->getSession()}
    {$usernote = $user->getUserNote()}

    {* Заметка *}
    {if $usernote}
        {component 'note' classes='js-user-note' note=$usernote targetId=$user->getId()}
    {/if}

    {* Информация *}
    {if $session}
        {$lastSessionDate = {date_format date=$session->getDateLast() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}}
    {/if}

    {component 'info-list' classes='object-list-item-info' list=[
        [ 'label' => "{$aLang.user.date_last_session}:", 'content' => ( $session ) ? $lastSessionDate : '&mdash;' ],
        [ 'label' => "{$aLang.user.date_registration}:", 'content' => {date_format date=$user->getDateRegister() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"} ],
        [ 'label' => "{$aLang.vote.rating}:",            'content' => $user->getRating() ]
    ]}
{/capture}


{component 'item'
    title=$smarty.capture.title
    content=$smarty.capture.content
    image=[
        'url' => $user->getUserWebPath(),
        'path' => $user->getProfileAvatarPath( 100 ),
        'alt' => $user->getLogin()
    ]}