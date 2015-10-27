{**
 * Новое сообщение на стене
 *}

{extends 'Component@email.email'}

{block 'content'}
    {lang name='emails.wall_new.text' params=[
        'user_url'     => $oUser->getUserWebPath(),
        'user_name'    => $oUser->getDisplayName(),
        'wall_url'     => "{$oUserWall->getUserWebPath()}wall/",
        'message_text' => $oWall->getText()
    ]}
{/block}