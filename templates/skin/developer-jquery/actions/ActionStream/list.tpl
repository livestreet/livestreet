{include file='header.tpl'}

{include file='stream_list.tpl'}

{if count($aStreamEvents)}
    <div id="stream_loaded_events"></div>
    <input type="hidden" id="stream_last_id" value="{$iStreamLastId}" />
    <a class="stream-get-more" id="stream_get_more" href="javascript:ls.stream.getMore()">{$aLang.stream_get_more}...</a>
{/if}
{include file='footer.tpl'}