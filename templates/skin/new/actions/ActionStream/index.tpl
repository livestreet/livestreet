{include file='header.tpl'}


<h2 class="stream-header">{$aLang.stream_personal_title}</h2>

{if count($aStreamEvents)}
	<ul class="stream-list" id="stream-list">
		{include file='actions/ActionStream/events.tpl'}
	</ul>

    {if !$bDisableGetMoreButton}
        <input type="hidden" id="stream_last_id" value="{$iStreamLastId}" />
        <a class="stream-get-more" id="stream_get_more" href="javascript:lsStream.getMore()">{$aLang.stream_get_more} &darr;</a>
    {/if}
{else}
    <p style="margin-left:30px">{$aLang.stream_no_events}</p>
{/if}


{include file='footer.tpl'}