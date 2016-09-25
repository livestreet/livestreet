{**
 * Опрос
 *
 * @param ModulePoll_EntityPoll $poll Опрос
 *}

<div class="ls-poll poll-type-{$poll->getTargetType()} js-poll" data-poll-id="{$poll->getId()}" data-poll-answer-max="{$poll->getCountAnswerMax()}">
    <h3 class="ls-poll-title">{$poll->getTitle()}</h3>

    <div class="js-poll-result-container">
        {if ! $poll->getVoteCurrent()}
            {component 'poll' template='vote' poll=$poll}
        {else}
            {component 'poll' template='result' poll=$poll}
        {/if}
    </div>
</div>