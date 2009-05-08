<ul class="poll-result">				
	{foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer}	
		<li {if $oTopic->getQuestionAnswerMax()==$aAnswer.count}class="most"{/if}>		
			<dl>
				<dt><strong>{$oTopic->getQuestionAnswerPercent($key)}%</strong><br />({$aAnswer.count})</dt>
				<dd>{$aAnswer.text}<div style="width: {$oTopic->getQuestionAnswerPercent($key)}%;" ></div></dd>
			</dl>
		</li>
	{/foreach}	
</ul>
<span>{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()}. {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</span><br>
