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
    photoPath    = $blog->getAvatarBig()
    photoAltText = $blog->getTitle()|escape
    assign       = blockContent}

{component 'block'
    mods     = 'nopadding transparent blog-photo'
    content = $blockContent}