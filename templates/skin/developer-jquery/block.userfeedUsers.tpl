{literal}
<script language="JavaScript" type="text/javascript">
    jQuery(document).ready( function() {
        ls.autocomplete.add(jQuery('#userfeed_users_complete'), aRouter['ajax']+'autocompleter/user/?security_ls_key='+LIVESTREET_SECURITY_KEY);
        jQuery('#userfeed_users_complete').keydown(function (event) {
            if (event.which == 13) {
                ls.userfeed.appendUser()
            }
        });
     });
</script>
{/literal}


<div class="block stream-settings">
	<h2>{$aLang.userfeed_block_users_title}</h2>
	
	<p class="note">{$aLang.userfeed_settings_note_follow_user}</p>
	
	<div class="stream-settings-userlist">
		<p><input type="text" id="userfeed_users_complete" autocomplete="off" onclick/>
		<a href="javascript:ls.userfeed.appendUser()">{$aLang.userfeed_block_users_append}</a></p>
		
		<ul id="userfeed_block_users_list">
		{if count($aUserfeedSubscribedUsers)}
			{foreach from=$aUserfeedSubscribedUsers item=oUser}
				{assign var=iUserId value=$oUser->getId()}
				{if !isset($aUserfeedFriends.$iUserId)}
					<li><input class="userfeedUserCheckbox input-checkbox"
								type="checkbox"
								id="usf_u_{$iUserId}"
								checked="checked"
								onClick="if ($(this).get('checked')) { ls.userfeed.subscribe('users',{$iUserId}) } else { ls.userfeed.unsubscribe('users',{$iUserId}) } " />
						<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
					</li>
				{/if}
			{/foreach}
		{/if}
		</ul>
	</div>
	
	
	{if count($aUserfeedFriends)}
		<h3>{$aLang.userfeed_block_users_friends}</h3>
		
		<p class="note">{$aLang.userfeed_settings_note_follow_friend}</p>
		
		<ul class="stream-settings-friends">
			{foreach from=$aUserfeedFriends item=oUser}
				{assign var=iUserId value=$oUser->getId()}
				<li><input class="userfeedUserCheckbox input-checkbox"
							type="checkbox"
							id="usf_u_{$iUserId}"
							{if isset($aUserfeedSubscribedUsers.$iUserId)} checked="checked"{/if}
							onClick="if ($(this).get('checked')) { ls.userfeed.subscribe('users',{$iUserId}) } else { ls.userfeed.unsubscribe('users',{$iUserId}) } " />
					<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
				</li>
			{/foreach}
		</ul>
	{/if}
</div>