{**
 * Информация о голосовании
 *
 * @param object $target
 *}

{$component = 'vote-info'}

{$target = $smarty.local.target}

<ul class="{$component}">
	<li><i class="icon-plus icon-white"></i> {$target->getCountVoteUp()}</li>
	<li><i class="icon-minus icon-white"></i> {$target->getCountVoteDown()}</li>
	<li><i class="icon-eye-open icon-white"></i> {$target->getCountVoteAbstain()}</li>
	<li><i class="icon-asterisk icon-white"></i> {$target->getCountVote()}</li>

	{hook run='topic_show_vote_stats' topic=$target}
</ul>