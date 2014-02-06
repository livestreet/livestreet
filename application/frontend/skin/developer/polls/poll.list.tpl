{foreach $aPollItems as $oPollItem}

	<div class="poll poll-type-{$oPollItem->getTargetType()} js-poll" data-poll-id="{$oPollItem->getId()}" data-poll-answer-max="{$oPollItem->getCountAnswerMax()}">
		{$oPollItem->getTitle()}

		{if !$oPollItem->getVoteCurrent()}
			<form action="" method="post" onsubmit="return false;">
				<ul class="poll-list">
					{foreach $oPollItem->getAnswers() as $oAnswer}
						<li class="poll-item">
							<label>
								{if $oPollItem->getCountAnswerMax()>1}
									<input type="checkbox" name="answers[]" value="{$oAnswer->getId()}" />
								{else}
									<input type="radio" name="answers[]" value="{$oAnswer->getId()}" />
								{/if}
								{$oAnswer->getTitle()}
							</label>
						</li>
					{/foreach}
				</ul>

				<input type="hidden" name="id" value="{$oPollItem->getId()}">
				<button type="submit" class="button button-primary js-poll-button-vote">Голосовать</button>
				<button type="submit" class="button js-poll-button-abstain">Воздержаться</button>
            </form>
		{else}
			{include file='polls/poll.result.tpl' oPoll=$oPollItem}
		{/if}
	</div>

{/foreach}