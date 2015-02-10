{**
 * Выбор пользователей для чтения в ленте активности
 *}

{component 'block'
    mods     = 'activity-users'
    title    = {lang 'activity.users.title'}
    content  = {include '../users.tpl' users=$users}}