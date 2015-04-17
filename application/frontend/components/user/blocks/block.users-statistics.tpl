{**
 * Статистика по пользователям
 *}

{capture 'user_block_stat'}
    <div class="user-stat">
        {* Кол-во пользователей *}
        {component 'info-list' list=[
            [ 'label' => "{lang name='user.stats.all'}:",      'content' => $usersStat.count_all ],
            [ 'label' => "{lang name='user.stats.active'}:",   'content' => $usersStat.count_active ],
            [ 'label' => "{lang name='user.stats.not_active'}:", 'content' => $usersStat.count_inactive ]
        ]}

        {* Пол *}
        {component 'info-list' list=[
            [ 'label' => "{lang name='user.stats.men'}:",   'content' => $usersStat.count_sex_man ],
            [ 'label' => "{lang name='user.stats.women'}:", 'content' => $usersStat.count_sex_woman ],
            [ 'label' => "{lang name='user.stats.none'}:", 'content' => $usersStat.count_sex_other ]
        ]}
    </div>
{/capture}

{component 'block'
    mods    = 'info users-stats'
    title   = {lang 'user.stats.title'}
    content = $smarty.capture.user_block_stat}