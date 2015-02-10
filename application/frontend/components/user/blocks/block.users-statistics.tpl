{**
 * Статистика по пользователям
 *}

{component 'block'
    mods    = 'info users-stats'
    title   = {lang 'user.stats.title'}
    content = {component 'user' template='stat' stat=$usersStat}}