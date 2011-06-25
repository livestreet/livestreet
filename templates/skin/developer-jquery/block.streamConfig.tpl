{literal}
<script language="JavaScript" type="text/javascript">
    jQuery(document).ready( function() {
        ls.autocomplete.add(jQuery('#stream_users_complete'), aRouter['ajax']+'autocompleter/user/?security_ls_key='+LIVESTREET_SECURITY_KEY);
        jQuery('#stream_users_complete').keydown(function (event) {
            if (event.which == 13) {
                ls.stream.appendUser()
            }
        });
     });
</script>
{/literal}
<div class="block">
    <div class="tl"><div class="tr"></div></div>
    <div class="cl"><div class="cr">
        <h1>{$aLang.stream_block_config_title}</h1>

        <ul>
            {foreach from=$STREAM_EVENT_TYPE item=aEventType}
                <li><input class="streamEventTypeCheckbox"
                            type="checkbox"
                            id="strn_et_{$aEventType.id}"
                            {math equation="x & y" x=$aEventType.id y=$aStreamConfig.event_types assign=bStreamChecked}
                            {if $bStreamChecked}checked="checked"{/if}
                            onClick="if ($(this).get('checked')) { ls.stream.switchEventType( {$aEventType.id}) } else { ls.stream.switchEventType( {$aEventType.id})  } " />
                    {$aEventType.name}
                </li>
            {/foreach}
        </ul>
                    <hr />
                    <strong>{$aLang.stream_block_users_title}</strong><br />
        <input type="text" id="stream_users_complete" autocomplete="off" onclick/>
        <a href="javascript:ls.stream.appendUser()">{$aLang.stream_block_config_append}</a>
        <ul id="userfeed_block_users_list">
            {if count($aStreamSubscribedUsers)}
                {foreach from=$aStreamSubscribedUsers item=oUser}
                    {assign var=iUserId value=$oUser->getId()}
                    {if !isset($aStreamFriends.$iUserId)}
                        <li><input class="streamUserCheckbox"
                                    type="checkbox"
                                    id="strm_u_{$iUserId}"
                                    checked="checked"
                                    onClick="if ($(this).get('checked')) { ls.stream.subscribe({$iUserId}) } else { ls.stream.unsubscribe({$iUserId}) } " />
                            <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
                        </li>
                    {/if}
                {/foreach}
            {/if}
        </ul>
        {if count($aStreamFriends)}
            <strong>{$aLang.stream_block_users_friends}</strong>
            <ul>
                {foreach from=$aStreamFriends item=oUser}
                    {assign var=iUserId value=$oUser->getId()}
                    <li><input class="streamUserCheckbox"
                                type="checkbox"
                                id="strm_u_{$iUserId}"
                                {if isset($aStreamSubscribedUsers.$iUserId)} checked="checked"{/if}
                                onClick="if ($(this).get('checked')) { ls.stream.subscribe({$iUserId}) } else { ls.stream.unsubscribe({$iUserId}) } " />
                        <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
                    </li>
                {/foreach}
            </ul>
        {/if}
    </div></div>
    <div class="bl"><div class="br"></div></div>
</div>