<ul class="poll-result" id="poll-result-original-{$oTopic->getId()}">
	{foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer}
		<li {if $oTopic->getQuestionAnswerMax()==$aAnswer.count}class="most"{/if}>
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


<ul class="poll-result" id="poll-result-sort-{$oTopic->getId()}" style="display: none;">
	{foreach from=$oTopic->getQuestionAnswers(true) key=key item=aAnswer}
		<li {if $oTopic->getQuestionAnswerMax()==$aAnswer.count}class="most"{/if}>
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


<button type="submit" class="button button-icon" title="{$aLang.topic_question_vote_result_sort}" onclick="return ls.poll.switchResult(this, {$oTopic->getId()});"><i class="icon-align-left icon-white"></i></button>

<span class="poll-total poll-total-result">{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()} | {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</span>