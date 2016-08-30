{**
 * Заявка в друзья
 *}

{extends 'Component@email.email'}

{block 'content'}
    {lang name='emails.user_friend_new.text' params=[
        'user_url'  => $oUserFrom->getUserWebPath(),
        'user_name' => $oUserFrom->getDisplayName(),
        'text'      => $sText,
        'url'       => $sPath
    ]}
{/block}