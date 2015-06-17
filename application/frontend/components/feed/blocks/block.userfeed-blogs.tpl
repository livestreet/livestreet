{**
 * Выбор блогов для чтения в ленте
 *}

{component 'block'
    mods     = 'feed-blogs'
    title    = {lang 'feed.blogs.title'}
    content  = {component 'feed' template='blogs' blogsJoined=$blogsJoined blogsSubscribed=$blogsSubscribed}}