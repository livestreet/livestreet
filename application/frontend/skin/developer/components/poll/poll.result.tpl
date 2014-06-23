{**
 * Результат опроса
 *
 * @param ModulePoll_EntityPoll $oPoll Опрос
 *}

{* Список ответов *}
<ul class="poll-result js-poll-result">
    {$answers = $oPoll->getAnswers()}
    {$count = count($answers)}

    {foreach $answers as $answer}
        {$votes = $answer->getCountVote()}
        {$percent = $oPoll->getAnswerPercent($answer)}

        {* Ответ *}
        <li class         = "poll-result-item {if $oPoll->getCountVoteAnswerMax() == $votes}poll-result-item--most{/if} js-poll-result-item"
            data-count    = "{$votes}"
            data-position = "{$count - $answer@index - 1}">

            {* Кол-во и процент проголосовавших *}
            <div class="poll-result-item-count">
                <strong>{$percent}%</strong>
                <span>({$votes})</span>
            </div>

            {* Визуальное отображения процента проголосовавших *}
            <div class="poll-result-item-chart">
                <div class="poll-result-item-label">{$answer->getTitle()}</div>
                <div class="poll-result-item-bar" style="width: {$percent}%;"></div>
            </div>
        </li>
    {/foreach}
</ul>

{* Кнопка сортировки *}
{include 'components/button/button.tpl' sMods='icon' sClasses='js-poll-result-sort' sIcon='icon-align-left' sAttributes="title=\"{$aLang.poll.result.sort}\""}

{* Статистика голосования *}
<span class="poll-result-total">
    {$aLang.poll.result.voted_total}: {$oPoll->getCountVote()} |
    {$aLang.poll.result.abstained_total}: {$oPoll->getCountAbstain()}
</span>