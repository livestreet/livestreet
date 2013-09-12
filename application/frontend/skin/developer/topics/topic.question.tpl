{**
 * Топик опрос
 *
 * @styles css/topic.css
 * @scripts <framework>/js/livestreet/poll.js
 *}

{extends file='topics/topic_base.tpl'}

{block name='topic_header_after'}
	<div class="poll js-poll" data-poll-id="{$oTopic->getId()}">
		{if ! $oTopic->getUserQuestionIsVote()}
			<ul class="poll-list js-poll-list">
				{foreach $oTopic->getQuestionAnswers() as $iItemId => $aAnswer}
					<li class="poll-item js-poll-item"><label><input type="radio" name="poll-{$oTopic->getId()}" value="{$iItemId}" class="js-poll-item-option" /> {$aAnswer.text|escape}</label></li>
				{/foreach}
			</ul>

			<button type="submit" class="button button-primary js-poll-button-vote">{$aLang.topic_question_vote}</button>
			<button type="submit" class="button js-poll-button-abstain">{$aLang.topic_question_abstain}</button>
		{else}
			{include file='topics/poll_result.tpl'}
		{/if}
	</div>
{/block}