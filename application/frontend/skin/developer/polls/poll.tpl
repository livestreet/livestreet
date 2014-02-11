{**
 * Опрос
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<div class="poll poll-type-{$oPoll->getTargetType()} js-poll" data-poll-id="{$oPoll->getId()}" data-poll-answer-max="{$oPoll->getCountAnswerMax()}">
	<h3 class="poll-title">{$oPoll->getTitle()}</h3>

	{if ! $oPoll->getVoteCurrent()}
		<form action="" method="post" class="js-poll-vote-form" onsubmit="return false;">
			<ul class="poll-answer-list">
				{foreach $oPoll->getAnswers() as $oAnswer}
					<li class="poll-answer-list-item js-poll-answer-list-item" data-answer-id="{$oAnswer->getId()}">
						<label>
							{if $oPoll->getCountAnswerMax()>1}
								<input type="checkbox" name="answers[]" value="{$oAnswer->getId()}" class="js-poll-answer-checkbox" />
							{else}
								<input type="radio" name="answers[]" value="{$oAnswer->getId()}" class="js-poll-answer-radio" />
							{/if}

							{$oAnswer->getTitle()}
						</label>
					</li>
				{/foreach}
			</ul>

			{include file='forms/fields/form.field.hidden.tpl' sFieldName='id' sFieldValue=$oPoll->getId()}

			{if $oUserCurrent}
				<button class="button button-primary js-poll-vote">{$aLang.poll.vote}</button>
				<button class="button js-poll-abstain">{$aLang.poll.abstain}</button>
			{else}
				{$aLang.poll.only_auth}
			{/if}
		</form>
	{else}
		{include file='polls/poll.result.tpl' oPoll=$oPoll}
	{/if}
</div>