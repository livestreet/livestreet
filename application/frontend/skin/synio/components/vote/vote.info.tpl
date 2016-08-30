{**
 * Информация о голосовании
 *
 * @param object $target
 *}

{$component = 'ls-vote-info'}
{component_define_params params=[ 'target' ]}

<ul class="{$component}">
    <li>{component 'icon' icon='plus' mods='white'} {$target->getCountVoteUp()}</li>
    <li>{component 'icon' icon='minus' mods='white'} {$target->getCountVoteDown()}</li>
    <li>{component 'icon' icon='eye' mods='white'} {$target->getCountVoteAbstain()}</li>
    <li>{component 'icon' icon='asterisk' mods='white'} {$target->getCountVote()}</li>

    {hook run='topic_show_vote_stats' topic=$target}
</ul>