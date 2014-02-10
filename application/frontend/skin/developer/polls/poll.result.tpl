{**
 * Результат опроса
 *
 * @styles poll.css
 * @scripts <common>/js/poll.js
 *}

<ul class="poll-result js-poll-result">
    {$aAnswerItems = $oPoll->getAnswers()}
    {$iPollItemsCount = count($aAnswerItems)}

    {foreach $aAnswerItems as $oAnswer}
        <li class="poll-result-item {if $oPoll->getCountVoteAnswerMax() == $oAnswer->getCountVote()}poll-result-item-most{/if} js-poll-result-item"
            data-poll-item-count="{$oAnswer->getCountVote()}"
            data-poll-item-pos="{$iPollItemsCount - $oAnswer@index - 1}">

            <div class="poll-result-item-count">
                <strong>{$oPoll->getAnswerPercent($oAnswer)}%</strong>
                <span>({$oAnswer->getCountVote()})</span>
            </div>

            <div class="poll-result-item-chart">
                <div class="poll-result-item-label">{$oAnswer->getTitle()}</div>
                <div class="poll-result-item-bar" style="width: {$oPoll->getAnswerPercent($oAnswer)}%;" ></div>
            </div>
        </li>
    {/foreach}
</ul>

{* Кнопка сортировки *}
<button class="button button-icon js-poll-result-sort" title="{$aLang.poll.result.sort}"><i class="icon-align-left"></i></button>

<span class="poll-result-total">{$aLang.poll.result.voted_total}: {$oPoll->getCountVote()} | {$aLang.poll.result.abstained_total}: {$oPoll->getCountAbstain()}</span>