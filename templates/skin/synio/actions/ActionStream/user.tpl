{include file='header.tpl' menu="stream"}

<h2 class="page-header">{$aLang.stream_menu}</h2>

{if count($aStreamEvents)}
	<ul class="stream-list" id="stream-list">
		{include file='actions/ActionStream/events.tpl'}
	</ul>

    {if !$bDisableGetMoreButton}
        <input type="hidden" id="stream_last_id" value="{$iStreamLastId}" />
        <a class="stream-get-more" id="stream_get_more" href="javascript:ls.stream.getMore()">{$aLang.stream_get_more} &darr;</a>
    {/if}
{else}
    {$aLang.stream_no_events}
{/if}


{include file='footer.tpl'}