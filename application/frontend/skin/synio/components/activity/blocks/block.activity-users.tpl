{**
 * Выбор пользователей для чтения в ленте активности
 *}

{component 'block'
    mods     = 'activity-users'
    title    = {lang 'activity.users.title'}
    content  = {component 'activity' template='users' users=$users}}