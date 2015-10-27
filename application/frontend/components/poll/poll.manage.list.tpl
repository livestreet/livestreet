{**
 * Список добавленных опросов в форме добавления
 *}

<ul class="ls-poll-manage-list js-poll-manage-list">
    {if $aPollItems}
        {foreach $aPollItems as $poll}
            {component 'poll' template='manage.item' poll=$poll}
        {/foreach}
    {/if}
</ul>