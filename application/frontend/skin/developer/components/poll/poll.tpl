{**
 * Опрос
 *
 * @param ModulePoll_EntityPoll $poll Опрос
 *}

<div class="poll poll-type-{$poll->getTargetType()} js-poll" data-poll-id="{$poll->getId()}" data-poll-answer-max="{$poll->getCountAnswerMax()}">
	<h3 class="poll-title">{$poll->getTitle()}</h3>

	{if ! $poll->getVoteCurrent()}
		{include './poll.vote.tpl' poll=$poll}
	{else}
		{include './poll.result.tpl' oPoll=$poll}
	{/if}
</div>