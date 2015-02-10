{**
 * Аватара блога
 *}

{component 'photo'
    classes      = 'js-blog-avatar'
    useAvatar    = false
    hasPhoto     = $blog->getAvatar()
    editable     = $blog->isAllowEdit()
    targetId     = $blog->getId()
    url          = $blog->getUrlFull()
    photoPath    = $blog->getAvatarPath(500)
    photoAltText = $blog->getTitle()|escape
    assign       = blockContent}

{component 'block'
    mods     = 'nopadding transparent blog-actions'
    content = $blockContent}