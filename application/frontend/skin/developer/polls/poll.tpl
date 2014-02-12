{**
 * Опрос
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<div class="poll poll-type-{$oPoll->getTargetType()} js-poll" data-poll-id="{$oPoll->getId()}" data-poll-answer-max="{$oPoll->getCountAnswerMax()}">
	<h3 class="poll-title">{$oPoll->getTitle()}</h3>

	{if ! $oPoll->getVoteCurrent()}
		<form action="" method="post" class="js-poll-vote-form">
			<ul class="poll-answer-list">
				{foreach $oPoll->getAnswers() as $oAnswer}
					<li class="poll-answer-list-item js-poll-answer-list-item" data-answer-id="{$oAnswer->getId()}">
						{if $oPoll->getCountAnswerMax() > 1}
							{include 'forms/fields/form.field.checkbox.tpl'
									 sFieldName    = 'answers[]'
									 sFieldValue   = $oAnswer->getId()
									 sFieldLabel   = $oAnswer->getTitle()
									 sFieldClasses = 'js-poll-answer-checkbox'}
						{else}
							{include 'forms/fields/form.field.radio.tpl'
									 sFieldName    = 'answers[]'
									 sFieldValue   = $oAnswer->getId()
									 sFieldLabel   = $oAnswer->getTitle()
									 sFieldClasses = 'js-poll-answer-radio'}
						{/if}
					</li>
				{/foreach}
			</ul>

			{include file='forms/fields/form.field.hidden.tpl' sFieldName='id' sFieldValue=$oPoll->getId()}

			{if $oUserCurrent}
				{include 'forms/fields/form.field.button.tpl'
						 sFieldText    = $aLang.poll.vote
						 sFieldType    = 'button'
						 sFieldStyle   = 'primary'
						 sFieldClasses = 'js-poll-vote'}

				{include 'forms/fields/form.field.button.tpl'
						 sFieldText    = $aLang.poll.abstain
						 sFieldType    = 'button'
						 sFieldStyle   = 'primary'
						 sFieldClasses = 'js-poll-abstain'}
			{else}
				{$aLang.poll.only_auth}
			{/if}
		</form>
	{else}
		{include file='polls/poll.result.tpl' oPoll=$oPoll}
	{/if}
</div>