{**
 * Информация о голосовании
 *
 * @param object $target
 *}

{$component = 'ls-vote-info'}
{component_define_params params=[ 'target' ]}

<ul class="{$component}">
    <li class="{$component}-item {$component}-item--up">{$target->getCountVoteUp()}</li>
    <li class="{$component}-item {$component}-item--down">{$target->getCountVoteDown()}</li>
    <li class="{$component}-item {$component}-item--abstain">{$target->getCountVoteAbstain()}</li>

    {hook run='topic_show_vote_stats' topic=$target}
</ul>