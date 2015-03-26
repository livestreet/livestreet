{**
 * Информация о голосовании
 *
 * @param object $target
 *}

{$component = 'vote-info'}

{$target = $smarty.local.target}

<ul class="{$component}">
	<li>{component 'icon' icon='plus' mods='white'} {$target->getCountVoteUp()}</li>
	<li>{component 'icon' icon='minus' mods='white'} {$target->getCountVoteDown()}</li>
	<li>{component 'icon' icon='eye-open' mods='white'} {$target->getCountVoteAbstain()}</li>
	<li>{component 'icon' icon='asterisk' mods='white'} {$target->getCountVote()}</li>

	{hook run='topic_show_vote_stats' topic=$target}
</ul>