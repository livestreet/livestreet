{if $bUserIsMale}
    {$aLang.stream_list_event_vote_comment}
{else}
    {$aLang.stream_list_event_vote_comment_female}
{/if}

<a href="{$oTarget->getTarget()->getUrl()}#comment{$oTarget->getId()}">{$oTarget->getTarget()->getTitle()|escape:'html'}</a>