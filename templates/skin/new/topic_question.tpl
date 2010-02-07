				<ul class="poll">				
				{foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer}	
				<li {if $oTopic->getQuestionAnswerMax()==$aAnswer.count}class="most"{/if}>		
					<dl>
					<dt><span>{$oTopic->getQuestionAnswerPercent($key)}%</span><br />({$aAnswer.count})</dt>
					<dd>{$aAnswer.text|escape:'html'}<br /><div style="width: {$oTopic->getQuestionAnswerPercent($key)}%;" ><span></span></div></dd>
					</dl>
				</li>
				{/foreach}	
				</ul>						
				<span>{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()}. {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</span><br>
			