{**
 * Содержимое тултипа с информацией о голосовании за топик
 *}

<div class="tip-arrow"></div>
<ul class="tooltip-content" data-type="tooltip-content">
	<ul class="vote-topic-info">
		<li><i class="icon-synio-vote-info-up"></i> {$oTopic->getCountVoteUp()}</li>
		<li><i class="icon-synio-vote-info-down"></i> {$oTopic->getCountVoteDown()}</li>
		<li><i class="icon-synio-vote-info-zero"></i> {$oTopic->getCountVoteAbstain()}</li>
		<li><i class="icon-asterisk icon-white"></i> {$oTopic->getCountVote()}</li>
		
		{hook run='topic_show_vote_stats' topic=$oTopic}
	</ul>
</ul>