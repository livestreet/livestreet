{**
 * Содержимое тултипа с информацией о голосовании за топик
 *}

<div class="tip-arrow"></div>
<div class="tooltip-content" data-type="tooltip-content">
	<ul class="vote-topic-info">
		<li><i class="icon-plus icon-white"></i> {$oTopic->getCountVoteUp()}</li>
		<li><i class="icon-minus icon-white"></i> {$oTopic->getCountVoteDown()}</li>
		<li><i class="icon-eye-open icon-white"></i> {$oTopic->getCountVoteAbstain()}</li>
		<li><i class="icon-asterisk icon-white"></i> {$oTopic->getCountVote()}</li>

		{hook run='topic_show_vote_stats' topic=$oTopic}
	</ul>
</div>