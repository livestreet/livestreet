{**
 * Результат опроса
 *
 * @param ModulePoll_EntityPoll $poll Опрос
 *}

{* Список ответов *}
<ul class="ls-poll-result js-poll-result">
    {$answers = $poll->getAnswers()}
    {$count = count($answers)}
    {$answersCurrent=$poll->getVoteCurrent()->getAnswers()}

    {foreach $answers as $answer}
        {$votes = $answer->getCountVote()}
        {$percent = $poll->getAnswerPercent($answer)}

        {* Ответ *}
        <li class="ls-poll-result-item
                {if $poll->getCountVoteAnswerMax() == $votes}ls-poll-result-item--most{/if}
                {if in_array( $answer->getId(), $answersCurrent )}ls-poll-result-item--voted{/if}
                js-poll-result-item"
            data-count    = "{$votes}"
            data-position = "{$count - $answer@index - 1}">

            {* Кол-во и процент проголосовавших *}
            <div class="ls-poll-result-item-count">
                <strong>{$percent}%</strong>
                <span>({$votes})</span>
            </div>

            {* Визуальное отображения процента проголосовавших *}
            <div class="ls-poll-result-item-chart">
                <div class="ls-poll-result-item-label">{$answer->getTitle()}</div>
                <div class="ls-poll-result-item-bar" style="width: {$percent}%;"></div>
            </div>
        </li>
    {/foreach}
</ul>

<div class="ls-poll-footer">
    {* Кнопка сортировки *}
    <div class="ls-poll-sort js-poll-result-sort" title="{lang 'poll.result.sort'}"></div>

    {* Статистика голосования *}
    <div class="ls-poll-result-total">
        {$aLang.poll.result.voted_total} — {$poll->getCountVote()} <br>
        {$aLang.poll.result.abstained_total} — {$poll->getCountAbstain()}
    </div>
</div>