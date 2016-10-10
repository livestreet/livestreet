<table class="ls-table">
    <tbody>
        {foreach [ true, false ] as $showRating}
        {foreach [ true, false ] as $locked}
            {foreach [ true, false ] as $isVoted}
                {foreach [ 0, 1, -1 ] as $direction}
                    {foreach [ 0, 99, -99 ] as $rating}
                        <tr>
                            <td width="200">
                                {($locked) ? 'locked' : ''}
                                {($isVoted) ? 'voted' : ''}

                                {if $isVoted}
                                    {if $direction == 0}
                                        d:zero
                                    {elseif $direction > 0}
                                        d:up
                                    {else}
                                        d:down
                                    {/if}
                                {/if}

                                {if $rating == 0}
                                    r:zero
                                {elseif $rating > 0}
                                    r:positive
                                {else}
                                    r:negative
                                {/if}
                            </td>

                            <td>
                                {component 'vote' targetId=1 showRating=$showRating useAbstain=true isVoted=$isVoted rating=$rating direction=$direction isLocked=$locked}
                            </td>
                        </tr>
                    {/foreach}
                {/foreach}
            {/foreach}
        {/foreach}
        {/foreach}
    </tbody>
</table>