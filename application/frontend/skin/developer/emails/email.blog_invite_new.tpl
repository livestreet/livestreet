{**
 * Приглашение в закрытый блог
 *}

{extends 'Component@email.email'}

{block 'content'}
    {lang name='emails.blog_invite_new.text' params=[
        'user_url'   => $oUserFrom->getUserWebPath(),
        'user_name'  => $oUserFrom->getDisplayName(),
        'blog_url'   => $oBlog->getUrlFull(),
        'blog_name'  => $oBlog->getTitle()|escape,
        'invite_url' => $sPath
    ]}
{/block}