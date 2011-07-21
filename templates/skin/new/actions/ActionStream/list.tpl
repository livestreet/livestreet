{include file='header.tpl'}


<h2 class="stream-header">{$aLang.stream_personal_title}</h2>

{include file='stream_list.tpl'}

{if count($aStreamEvents)}
    {if !$bDisableGetMoreButton}
        <div id="stream_loaded_events"></div>
        <input type="hidden" id="stream_last_id" value="{$iStreamLastId}" />
        <a class="stream-get-more" id="stream_get_more" href="javascript:lsStream.getMore()">{$aLang.stream_get_more} &darr;</a>
    {/if}
{else}
    <p style="margin-left:30px">{$aLang.stream_no_events}</p>
{/if}


{include file='footer.tpl'}