{**
 * Черный список
 *
 * @param array $talkBlacklistUsers
 *}

{extends 'layouts/layout.user.messages.tpl'}

{block 'layout_content'}
    {include 'components/talk/blacklist.tpl' users=$talkBlacklistUsers}
{/block}