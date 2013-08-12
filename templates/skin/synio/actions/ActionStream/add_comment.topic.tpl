{if $bUserIsMale}
    {$aLang.stream_list_event_add_comment}
{else}
    {$aLang.stream_list_event_add_comment_female}
{/if}

<a href="{$oTarget->getTarget()->getUrl()}#comment{$oTarget->getId()}">{$oTarget->getTarget()->getTitle()|escape:'html'}</a>

{$sTextEvent = $oTarget->getText()}

{if trim($sTextEvent)}
    <div class="activity-event-text">
        <div class="text">
            {$sTextEvent}
        </div>
    </div>
{/if}