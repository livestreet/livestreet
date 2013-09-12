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

{* Кнопка сортировки *}
<button class="button button-icon js-poll-result-button-sort" title="{$aLang.topic_question_vote_result_sort}"><i class="icon-align-left"></i></button>

<span class="poll-result-total">{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()} | {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</span>