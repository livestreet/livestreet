{**
 * Результат опроса
 *
 * @styles css/topic.css
 * @scripts <framework>/js/livestreet/poll.js
 *}

<ul class="poll-result" id="poll-result-{$oTopic->getId()}">
	{$iPollItemsCount = count($oTopic->getQuestionAnswers())}

	{foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer name=poll}
		<li {if $oTopic->getQuestionAnswerMax() == $aAnswer.count}class="most"{/if} 
			data-poll-item-count="{$aAnswer.count}" 
			data-poll-item-pos="{$iPollItemsCount - $smarty.foreach.poll.index - 1}">

			<dl>
				<dt>
					<strong>{$oTopic->getQuestionAnswerPercent($key)}%</strong><br />
					<span>({$aAnswer.count})</span>
				</dt>
				<dd>{$aAnswer.text|escape:'html'}<div style="width: {$oTopic->getQuestionAnswerPercent($key)}%;" ></div></dd>
			</dl>
		</li>
	{/foreach}
</ul>


<div class="poll-total">
	{* Кнопка сортировки *}
	<i class="poll-sort" title="{$aLang.topic_question_vote_result_sort}" onclick="return ls.poll.toggleSortResult(this, {$oTopic->getId()});"></i>
	
	{$aLang.topic_question_vote_result} &mdash; {$oTopic->getQuestionCountVote()}<br />
	{$aLang.topic_question_abstain_result} &mdash; {$oTopic->getQuestionCountVoteAbstain()}
</div>