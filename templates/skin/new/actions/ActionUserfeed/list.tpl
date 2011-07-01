{include file='header.tpl' menu='blog'}


{include file='topic_list.tpl'}

{if count($aTopics)}
    <div id="userfeed_loaded_topics"></div>
    <input type="hidden" id="userfeed_last_id" value="{$iUserfeedLastId}" />
    <a class="stream-get-more" id="userfeed_get_more" href="javascript:lsUserfeed.getMore()">{$aLang.userfeed_get_more} &darr;</a>
{/if}


{include file='footer.tpl'}