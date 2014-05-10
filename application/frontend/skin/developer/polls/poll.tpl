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
							{include 'components/field/field.checkbox.tpl'
									 sName    = 'answers[]'
									 sValue   = $oAnswer->getId()
									 sLabel   = $oAnswer->getTitle()
									 sClasses = 'js-poll-answer-checkbox'}
						{else}
							{include 'components/field/field.radio.tpl'
									 sName    = 'answers[]'
									 sValue   = $oAnswer->getId()
									 sLabel   = $oAnswer->getTitle()
									 sClasses = 'js-poll-answer-radio'}
						{/if}
					</li>
				{/foreach}
			</ul>

			{include file='components/field/field.hidden.tpl' sName='id' sValue=$oPoll->getId()}

			{if $oUserCurrent}
				{include 'components/button/button.tpl'
						 sText    = $aLang.poll.vote
						 sType    = 'button'
						 sStyle   = 'primary'
						 sClasses = 'js-poll-vote'}

				{include 'components/button/button.tpl'
						 sText    = $aLang.poll.abstain
						 sType    = 'button'
						 sStyle   = 'primary'
						 sClasses = 'js-poll-abstain'}
			{else}
				{$aLang.poll.only_auth}
			{/if}
		</form>
	{else}
		{include file='polls/poll.result.tpl' oPoll=$oPoll}
	{/if}
</div>