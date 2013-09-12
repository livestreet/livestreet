{if !empty($aTopicIds)}
    <ul>
        {foreach from=$aTopicIds item=sTopicId}
            <li>
                {$sTopicId}
            </li>
        {/foreach}
    </ul>
{/if}