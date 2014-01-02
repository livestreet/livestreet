{**
 * Настройка уведомлений
 *}

{extends file='layouts/layout.user.settings.tpl'}

{block name='layout_content'}
	{hook run='settings_tuning_begin'}

	<form action="{router page='settings'}tuning/" method="POST" enctype="multipart/form-data">
		{hook run='form_settings_tuning_begin'}

		{include file='forms/fields/form.field.hidden.security_key.tpl'}
		
		<fieldset>
			<legend>{$aLang.settings_tuning_notice}</legend>

			{include file='forms/fields/form.field.checkbox.tpl' 
					 sFieldName        = 'settings_notice_new_topic'
					 bFieldChecked     = $oUserCurrent->getSettingsNoticeNewTopic() != 0
					 bFieldNoMargin = true
					 sFieldLabel       = $aLang.settings_tuning_notice_new_topic}

			{include file='forms/fields/form.field.checkbox.tpl' 
					 sFieldName        = 'settings_notice_new_comment'
					 bFieldChecked     = $oUserCurrent->getSettingsNoticeNewComment() != 0
					 bFieldNoMargin = true
					 sFieldLabel       = $aLang.settings_tuning_notice_new_comment}

			{include file='forms/fields/form.field.checkbox.tpl' 
					 sFieldName        = 'settings_notice_new_talk'
					 bFieldChecked     = $oUserCurrent->getSettingsNoticeNewTalk() != 0
					 bFieldNoMargin = true
					 sFieldLabel       = $aLang.settings_tuning_notice_new_talk}

			{include file='forms/fields/form.field.checkbox.tpl' 
					 sFieldName        = 'settings_notice_reply_comment'
					 bFieldChecked     = $oUserCurrent->getSettingsNoticeReplyComment() != 0
					 bFieldNoMargin = true
					 sFieldLabel       = $aLang.settings_tuning_notice_reply_comment}

			{include file='forms/fields/form.field.checkbox.tpl' 
					 sFieldName        = 'settings_notice_new_friend'
					 bFieldChecked     = $oUserCurrent->getSettingsNoticeNewFriend() != 0
					 bFieldNoMargin = true
					 sFieldLabel       = $aLang.settings_tuning_notice_new_friend}
		</fieldset>

		<fieldset>
			<legend>{$aLang.settings_tuning_general}</legend>

			{foreach $aTimezoneList as $sTimezone}
				{$aTimezones[] = [
					'value' => $sTimezone,
					'text' => $aLang.timezone_list[$sTimezone]
				]}
			{/foreach}

			{include file='forms/fields/form.field.select.tpl' 
					 sFieldName          = 'settings_general_timezone'
					 sFieldLabel         = $aLang.settings_tuning_general_timezone
					 sFieldClasses       = 'width-500 js-topic-add-title' 
					 aFieldItems         = $aTimezones
					 sFieldSelectedValue = $_aRequest.settings_general_timezone}
		</fieldset>
		
		{hook run='form_settings_tuning_end'}

        {include file='forms/fields/form.field.button.tpl' sFieldName='submit_settings_tuning' sFieldText=$aLang.settings_profile_submit sFieldStyle='primary'}
	</form>

	{hook run='settings_tuning_end'}
{/block}