{**
 * Выбор блогов для чтения в ленте
 *}

{component 'block'
    mods     = 'feed-blogs'
    title    = {lang 'feed.blogs.title'}
    content  = {include '../blogs.tpl' blogsJoined=$blogsJoined blogsSubscribed=$blogsSubscribed}}