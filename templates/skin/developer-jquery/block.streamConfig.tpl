{if $oUserCurrent}
	{literal}
		<script>
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


	<section class="block block-type-activity">
		<header class="block-header">
			<h3>{$aLang.stream_block_config_title}</h3>
		</header>
		
		<div class="block-content">
			<small class="note">{$aLang.stream_settings_note_filter}</small>
			
			<ul class="activity-settings-filter">
				{foreach from=$aStreamEventTypes key=sType item=aEventType}
					{if !($oConfig->get('module.stream.disable_vote_events') && substr($sType, 0, 4) == 'vote')}
						<li>
							<label>
								<input class="streamEventTypeCheckbox input-checkbox"
									type="checkbox"
									id="strn_et_{$sType}"
									{if in_array($sType, $aStreamTypesList)}checked="checked"{/if}
									onClick="ls.stream.switchEventType('{$sType}')" />
								{assign var=langKey value="stream_event_type_`$sType`"}
								{$aLang.$langKey}
							</label>
						</li>
					{/if}
				{/foreach}
			</ul>
		</div>
	</section>
		
		
		
	<section class="block block-type-activity">
		<header class="block-header">
			<h3>{$aLang.stream_block_users_title}</h3>
		</header>
		
		<div class="block-content">
			<small class="note">{$aLang.stream_settings_note_follow_user}</small>
			
			<p><input type="text" id="stream_users_complete" autocomplete="off" class="input-text input-width-200" />
			<a href="javascript:ls.stream.appendUser()" class="button">{$aLang.stream_block_config_append}</a></p>
			
			{if count($aStreamSubscribedUsers)}
				<ul id="stream_block_users_list" class="max-height-200">
					{foreach from=$aStreamSubscribedUsers item=oUser}
						{assign var=iUserId value=$oUser->getId()}
						
						{if !isset($aStreamFriends.$iUserId)}
							<li><input class="streamUserCheckbox input-checkbox"
										type="checkbox"
										id="strm_u_{$iUserId}"
										checked="checked"
										onClick="if (jQuery(this).prop('checked')) { ls.stream.subscribe({$iUserId}) } else { ls.stream.unsubscribe({$iUserId}) } " />
								<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
							</li>
						{/if}
					{/foreach}
				</ul>
			{else}
				<ul id="stream_block_users_list"></ul>
				<p id="stream_no_subscribed_users">{$aLang.stream_no_subscribed_users}</p>
			{/if}
		</div>
	</section>
		
		
		
	<section class="block block-type-activity">
		<header class="block-header">
			<h3>{$aLang.stream_block_users_friends}</h3>
		</header>
		
		<div class="block-content">
			<small class="note">{$aLang.stream_settings_note_follow_friend}</small>
			
			{if count($aStreamFriends)}
				<ul class="stream-settings-friends max-height-200">
					{foreach from=$aStreamFriends item=oUser}
						{assign var=iUserId value=$oUser->getId()}
						<li><input class="streamUserCheckbox input-checkbox"
									type="checkbox"
									id="strm_u_{$iUserId}"
									{if isset($aStreamSubscribedUsers.$iUserId)} checked="checked"{/if}
									onClick="if (jQuery(this).prop('checked')) { ls.stream.subscribe({$iUserId}) } else { ls.stream.unsubscribe({$iUserId}) } " />
							<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
						</li>
					{/foreach}
				</ul>
			{else}
				<small class="note">{$aLang.stream_no_subscribed_users}</small>
			{/if}
		</div>
	</section>
{/if}