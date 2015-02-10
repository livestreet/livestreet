{**
 * Выбор пользователей для чтения в ленте
 *}

{component 'block'
    mods     = 'feed-users'
    title    = {lang 'feed.users.title'}
    content  = {include '../users.tpl' users=$users}}