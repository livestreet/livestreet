{**
 * Результат опроса
 *
 * @styles assets/css/poll.css
 * @scripts <framework>/js/livestreet/poll.js
 *}

<ul class="poll-result js-poll-result">
	{$iPollItemsCount = count($oTopic->getQuestionAnswers())}

	{foreach $oTopic->getQuestionAnswers() as $aAnswer}
		<li class="poll-result-item {if $oTopic->getQuestionAnswerMax() == $aAnswer.count}poll-result-item-most{/if} js-poll-result-item"
			data-poll-item-count="{$aAnswer.count}" 
			data-poll-item-pos="{$iPollItemsCount - $aAnswer@index - 1}">

			<div class="poll-result-item-count">
				<strong>{$oTopic->getQuestionAnswerPercent($aAnswer@key)}%</strong>
				<span>({$aAnswer.count})</span>
			</div>

			<div class="poll-result-item-chart">
				<div class="poll-result-item-label">{$aAnswer.text|escape}</div>
				<div class="poll-result-item-bar" style="width: {$oTopic->getQuestionAnswerPercent($aAnswer@key)}%;" ></div>
			</div>
		</li>
	{/foreach}
</ul>


<div class="poll-result-total">
	{* Кнопка сортировки *}
	<button class="button button-small button-icon js-poll-result-button-sort" title="{$aLang.topic_question_vote_result_sort}"><i class="icon-poll-sort"></i></button>
	
	{$aLang.topic_question_vote_result} &mdash; {$oTopic->getQuestionCountVote()}<br />
	{$aLang.topic_question_abstain_result} &mdash; {$oTopic->getQuestionCountVoteAbstain()}
</div>