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
<p>
	<button onclick="return ls.poll.switchResult(false,{$oTopic->getId()});">sort 1</button>
	<button onclick="return ls.poll.switchResult(true,{$oTopic->getId()});">sort 2</button>
</p>
<p class="poll-total">{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()} | {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</p>