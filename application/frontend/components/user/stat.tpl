{$stat = $smarty.local.stat}

<div class="user-stat">
	{* Кол-во пользователей *}
	{component 'info-list' list=[
		[ 'label' => "{lang name='user.stats.all'}:",      'content' => $stat.count_all ],
		[ 'label' => "{lang name='user.stats.active'}:",   'content' => $stat.count_active ],
		[ 'label' => "{lang name='user.stats.not_active'}:", 'content' => $stat.count_inactive ]
	]}

	{* Пол *}
	{component 'info-list' list=[
		[ 'label' => "{lang name='user.stats.men'}:",   'content' => $stat.count_sex_man ],
		[ 'label' => "{lang name='user.stats.women'}:", 'content' => $stat.count_sex_woman ],
		[ 'label' => "{lang name='user.stats.none'}:", 'content' => $stat.count_sex_other ]
	]}
</div>