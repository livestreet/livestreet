{**
 * Информация о голосовании
 *}

{* Название компонента *}
{$component = 'vote'}

<ul class="{$component}-info">
	<li><i class="icon-plus icon-white"></i> {$oObject->getCountVoteUp()}</li>
	<li><i class="icon-minus icon-white"></i> {$oObject->getCountVoteDown()}</li>
	<li><i class="icon-eye-open icon-white"></i> {$oObject->getCountVoteAbstain()}</li>
	<li><i class="icon-asterisk icon-white"></i> {$oObject->getCountVote()}</li>

	{hook run='topic_show_vote_stats' topic=$oObject}
</ul>