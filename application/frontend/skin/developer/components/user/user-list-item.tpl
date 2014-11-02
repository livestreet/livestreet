{extends 'components/item/item.tpl'}

{block 'options' append}
    {if $user->getUser()}
        {$user = $user->getUser()}
    {/if}

    {* Заголовок *}
    {capture 'user_list_item_title'}
        <a href="{$user->getUserWebPath()}">{$user->getDisplayName()}</a>
    {/capture}

    {$title = $smarty.capture.user_list_item_title}

    {* Описание *}
    {capture 'user_list_item_content'}
        {$session = $user->getSession()}
        {$usernote = $user->getUserNote()}

        {* Заметка *}
        {if $usernote}
            {include 'components/note/note.tpl' classes='js-user-note' note=$usernote targetId=$user->getId()}
        {/if}

        {* Информация *}
        {if $session}
            {$lastSessionDate = {date_format date=$session->getDateLast() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}}
        {/if}

        {include 'components/info-list/info-list.tpl' classes='object-list-item-info' list=[
            [ 'label' => "{$aLang.user.date_last_session}:", 'content' => ( $session ) ? $lastSessionDate : '&mdash;' ],
            [ 'label' => "{$aLang.user.date_registration}:", 'content' => {date_format date=$user->getDateRegister() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"} ],
            [ 'label' => "{$aLang.vote.rating}:",            'content' => $user->getRating() ]
        ]}
    {/capture}

    {$content = $smarty.capture.user_list_item_content}

    {* Изображение *}
    {$image = [
        'url' => $user->getUserWebPath(),
        'path' => $user->getProfileAvatarPath( 100 ),
        'alt' => $user->getLogin()
    ]}
{/block}