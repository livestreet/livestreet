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
        useAvatar    = false
        hasPhoto     = $blog->getAvatar()
        editable     = $blog->isAllowEdit()
        targetId     = $blog->getId()
        url          = $blog->getUrlFull()
        photoPath    = $blog->getAvatarPath(500)
        photoAltText = $blog->getTitle()|escape}
{/block}