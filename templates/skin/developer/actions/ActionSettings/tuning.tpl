{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}


{include file='actions/ActionProfile/profile_top.tpl'}
{include file='menu.settings.tpl'}


{hook run='settings_tuning_begin'}

<form action="{router page='settings'}tuning/" method="POST" enctype="multipart/form-data">
	{hook run='form_settings_tuning_begin'}

	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
	
	<fieldset>
		<legend>{$aLang.settings_tuning_notice}</legend>

		<label><input {if $oUserCurrent->getSettingsNoticeNewTopic()}checked{/if} type="checkbox" id="settings_notice_new_topic" name="settings_notice_new_topic" value="1" class="input-checkbox" /> {$aLang.settings_tuning_notice_new_topic}</label>
		<label><input {if $oUserCurrent->getSettingsNoticeNewComment()}checked{/if} type="checkbox" id="settings_notice_new_comment" name="settings_notice_new_comment" value="1" class="input-checkbox" /> {$aLang.settings_tuning_notice_new_comment}</label>
		<label><input {if $oUserCurrent->getSettingsNoticeNewTalk()}checked{/if} type="checkbox" id="settings_notice_new_talk" name="settings_notice_new_talk" value="1" class="input-checkbox" /> {$aLang.settings_tuning_notice_new_talk}</label>
		<label><input {if $oUserCurrent->getSettingsNoticeReplyComment()}checked{/if} type="checkbox" id="settings_notice_reply_comment" name="settings_notice_reply_comment" value="1" class="input-checkbox" /> {$aLang.settings_tuning_notice_reply_comment}</label>
		<label><input {if $oUserCurrent->getSettingsNoticeNewFriend()}checked{/if} type="checkbox" id="settings_notice_new_friend" name="settings_notice_new_friend" value="1" class="input-checkbox" /> {$aLang.settings_tuning_notice_new_friend}</label>
	</fieldset>
	
	{hook run='form_settings_tuning_end'}
	
	<button type="submit" name="submit_settings_tuning" class="button button-primary">{$aLang.settings_profile_submit}</button>
</form>

{hook run='settings_tuning_end'}

{include file='footer.tpl'}