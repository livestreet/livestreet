{**
 * Настройка уведомлений
 *}

{extends file='layouts/layout.user.settings.tpl'}

{block name='layout_content'}
	{hook run='settings_tuning_begin'}

	<form action="{router page='settings'}tuning/" method="POST" enctype="multipart/form-data" class="wrapper-content">
		{hook run='form_settings_tuning_begin'}

		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
		
		<h3>{$aLang.settings_tuning_notice}</h3>

		<label><input {if $oUserCurrent->getSettingsNoticeNewTopic()}checked{/if} type="checkbox" id="settings_notice_new_topic" name="settings_notice_new_topic" value="1" class="input-checkbox" /> {$aLang.settings_tuning_notice_new_topic}</label>
		<label><input {if $oUserCurrent->getSettingsNoticeNewComment()}checked{/if} type="checkbox" id="settings_notice_new_comment" name="settings_notice_new_comment" value="1" class="input-checkbox" /> {$aLang.settings_tuning_notice_new_comment}</label>
		<label><input {if $oUserCurrent->getSettingsNoticeNewTalk()}checked{/if} type="checkbox" id="settings_notice_new_talk" name="settings_notice_new_talk" value="1" class="input-checkbox" /> {$aLang.settings_tuning_notice_new_talk}</label>
		<label><input {if $oUserCurrent->getSettingsNoticeReplyComment()}checked{/if} type="checkbox" id="settings_notice_reply_comment" name="settings_notice_reply_comment" value="1" class="input-checkbox" /> {$aLang.settings_tuning_notice_reply_comment}</label>
		<label><input {if $oUserCurrent->getSettingsNoticeNewFriend()}checked{/if} type="checkbox" id="settings_notice_new_friend" name="settings_notice_new_friend" value="1" class="input-checkbox" /> {$aLang.settings_tuning_notice_new_friend}</label>

		<br />
		<h3>{$aLang.settings_tuning_general}</h3>
		<label>{$aLang.settings_tuning_general_timezone}:
			<select name="settings_general_timezone" class="input-width-400">
				{foreach $aTimezoneList as $sTimezone}
					<option value="{$sTimezone}" {if $_aRequest.settings_general_timezone==$sTimezone}selected="selected"{/if}>{$aLang.timezone_list[$sTimezone]}</option>
				{/foreach}
			</select>
		</label>


		{hook run='form_settings_tuning_end'}
		<br />
		<button type="submit"  name="submit_settings_tuning" class="button button-primary">{$aLang.settings_profile_submit}</button>
	</form>

	{hook run='settings_tuning_end'}
{/block}