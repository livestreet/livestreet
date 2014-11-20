{**
 * 
 *}

{extends 'components/block/block.tpl'}

{block 'block_type'}blog-actions{/block}

{block 'block_options' append}
    {$mods = "{$mods} blog-actions"}
{/block}

{block 'block_content'}
    {include 'components/photo/photo.tpl'
        classes      = 'js-blog-avatar'
        hasPhoto     = $oBlog->getAvatar()
        editable     = $oBlog->isAllowEdit()
        targetId     = $oBlog->getId()
        url          = $oBlog->getUrlFull()
        photoPath    = $oBlog->getAvatarPath(500)
        photoAltText = $oBlog->getTitle()|escape}
{/block}