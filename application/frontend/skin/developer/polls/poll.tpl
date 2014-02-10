{**
 * Опрос
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<div class="poll poll-type-{$oPoll->getTargetType()} js-poll" data-poll-id="{$oPoll->getId()}" data-poll-answer-max="{$oPoll->getCountAnswerMax()}">
	<h3 class="poll-title">{$oPoll->getTitle()}</h3>

	{if ! $oPoll->getVoteCurrent()}
		<form action="" method="post" onsubmit="return false;" class="js-poll-vote-form">
			<ul class="poll-answer-list">
				{foreach $oPoll->getAnswers() as $oAnswer}
					<li class="poll-answer-list-item">
						<label>
							{if $oPoll->getCountAnswerMax()>1}
								<input type="checkbox" name="answers[]" value="{$oAnswer->getId()}" />
							{else}
								<input type="radio" name="answers[]" value="{$oAnswer->getId()}" />
							{/if}

							{$oAnswer->getTitle()}
						</label>
					</li>
				{/foreach}
			</ul>
			
			{include file='forms/fields/form.field.hidden.tpl' sFieldName='id' sFieldValue=$oPoll->getId()}
			
			<button type="submit" class="button button-primary js-poll-vote">{$aLang.poll.vote}</button>
			<button type="submit" class="button js-poll-abstain">{$aLang.poll.abstain}</button>
		</form>
	{else}
		{include file='polls/poll.result.tpl' oPoll=$oPoll}
	{/if}
</div>