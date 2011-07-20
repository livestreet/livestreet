<ul class="poll-new">	
    {foreach from=$oTopic->getQuestionAnswers() key=key item=aAnswer}				
        <li><label for="topic_answer_{$oTopic->getId()}_{$key}"><input type="radio" id="topic_answer_{$oTopic->getId()}_{$key}" name="topic_answer_{$oTopic->getId()}"  value="{$key}" onchange="$('topic_answer_{$oTopic->getId()}_value').setProperty('value',this.value);"/> {$aAnswer.text|escape:'html'}</label></li>				
    {/foreach}
        <li>
        <input type="submit"  value="{$aLang.topic_question_vote}" onclick="ajaxQuestionVote({$oTopic->getId()},$('topic_answer_{$oTopic->getId()}_value').getProperty('value'));">
        <input type="submit"  value="{$aLang.topic_question_abstain}"  onclick="ajaxQuestionVote({$oTopic->getId()},-1)">
        </li>				
        <input type="hidden" id="topic_answer_{$oTopic->getId()}_value" value="-1">				
</ul>				
<span>{$aLang.topic_question_vote_result}: {$oTopic->getQuestionCountVote()}. {$aLang.topic_question_abstain_result}: {$oTopic->getQuestionCountVoteAbstain()}</span><br>