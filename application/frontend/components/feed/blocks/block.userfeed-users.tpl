{**
 * Выбор пользователей для чтения в ленте
 *}

{component 'block'
    mods     = 'feed-users'
    title    = {lang 'feed.users.title'}
    content  = {component 'feed' template='users' users=$users}}