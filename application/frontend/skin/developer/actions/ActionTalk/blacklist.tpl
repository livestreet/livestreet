{**
 * Черный список
 *
 * @param array $talkBlacklistUsers
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
    {component 'talk' template='blacklist' users=$talkBlacklistUsers}
{/block}