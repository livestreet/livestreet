{**
 * Результат опроса
 *
 * @styles assets/css/poll.css
 * @scripts <framework>/js/livestreet/poll.js
 *}

<ul class="poll-result js-poll-result">
	{$iPollItemsCount = count($oTopic->getQuestionAnswers())}

	{foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer name=poll}
		<li class="poll-result-item {if $oTopic->getQuestionAnswerMax() == $aAnswer.count}poll-result-item-most{/if} js-poll-result-item"
			data-poll-item-count="{$aAnswer.count}" 
			data-poll-item-pos="{$iPollItemsCount - $smarty.foreach.poll.index - 1}">

			<div class="poll-result-item-count">
				<strong>{$oTopic->getQuestionAnswerPercent($key)}%</strong>
				<span>({$aAnswer.count})</span>
			</div>

			<div class="poll-result-item-chart">
				<div class="poll-result-item-label">{$aAnswer.text|escape:'html'}</div>
				<div class="poll-result-item-bar" style="width: {$oTopic->getQuestionAnswerPercent($key)}%;" ></div>
			</div>
		</li>
	{/foreach}
</ul>

{* Кнопка сортировки *}
<button class="button button-icon js-poll-result-button-sort" title="{$aLang.topic_question_vote_result_sort}"><i class="icon-align-left"></i></button>

<span class="poll-result-total">{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()} | {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</span>