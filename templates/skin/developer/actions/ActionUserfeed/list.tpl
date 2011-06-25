{include file='header.tpl'}

{include file='topic_list.tpl'}
{if count($aTopics)}
    <div id="userfeed_loaded_topics"></div>
    <input type="hidden" id="userfeed_last_id" value="{$iUserfeedLastId}" />
    <a class="userfeed-get-more" id="userfeed_get_more" href="javascript:lsUserfeed.getMore()">{$aLang.userfeed_get_more}...</a>
{/if}
{include file='footer.tpl'}