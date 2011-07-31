{if $oUserCurrent}
{literal}
<script language="JavaScript" type="text/javascript">
    document.addEvent('domready', function() {
        new Autocompleter.Request.LS.JSON($('userfeed_users_complete'), aRouter['ajax']+'autocompleter/user/?security_ls_key='+LIVESTREET_SECURITY_KEY, {
            'indicatorClass': 'autocompleter-loading', // class added to the input during request
            'minLength': 1, // We need at least 1 character
            'selectMode': 'pick', // Instant completion
            'multiple': false, // Tag support, by default comma separated
        });
        $('userfeed_users_complete').addEvent('keydown', function (event) {
            if (event.key == 'enter') {
                lsUserfeed.appendUser()
            }
        });
	});
</script>
{/literal}


<div class="block stream-settings">
	<h2>{$aLang.userfeed_block_users_title}</h2>
	
	<p class="sp-note">{$aLang.userfeed_settings_note_follow_user}</p>
	
	<div class="stream-settings-userlist">
		<p><input type="text" id="userfeed_users_complete" autocomplete="off" />
		<a href="javascript:lsUserfeed.appendUser()">{$aLang.userfeed_block_users_append}</a></p>
		{if count($aUserfeedSubscribedUsers)}
		<ul id="userfeed_block_users_list">

			{foreach from=$aUserfeedSubscribedUsers item=oUser}
				{assign var=iUserId value=$oUser->getId()}
				{if !isset($aUserfeedFriends.$iUserId)}
					<li><input class="userfeedUserCheckbox input-checkbox"
								type="checkbox"
								id="usf_u_{$iUserId}"
								checked="checked"
								onClick="if ($(this).get('checked')) { lsUserfeed.subscribe('users',{$iUserId}) } else { lsUserfeed.unsubscribe('users',{$iUserId}) } " />
						<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
					</li>
				{/if}
			{/foreach}
		</ul>
                {else}
                    <ul id="userfeed_block_users_list"></ul>
                    <p id="userfeed_no_subscribed_users">{$aLang.userfeed_no_subscribed_users}</p>
                {/if}
	</div>
	

	{if count($aUserfeedFriends)}
		<h3>{$aLang.userfeed_block_users_friends}</h3>
		
		<p class="sp-note">{$aLang.userfeed_settings_note_follow_friend}</p>
		
		<ul class="stream-settings-friends">
			{foreach from=$aUserfeedFriends item=oUser}
				{assign var=iUserId value=$oUser->getId()}
				<li><input class="userfeedUserCheckbox input-checkbox"
							type="checkbox"
							id="usf_u_{$iUserId}"
							{if isset($aUserfeedSubscribedUsers.$iUserId)} checked="checked"{/if}
							onClick="if ($(this).get('checked')) { lsUserfeed.subscribe('users',{$iUserId}) } else { lsUserfeed.unsubscribe('users',{$iUserId}) } " />
					<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
				</li>
			{/foreach}
		</ul>
	{/if}
</div>
{/if}