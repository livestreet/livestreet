{**
 * Настройка уведомлений
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
	{hook run='settings_tuning_begin'}

	<form action="{router page='settings'}tuning/" method="POST" enctype="multipart/form-data">
		{hook run='form_settings_tuning_begin'}

		{include 'components/field/field.hidden.security_key.tpl'}

		<fieldset>
			<legend>{$aLang.settings_tuning_notice}</legend>

			{include 'components/field/field.checkbox.tpl'
					 sName     = 'settings_notice_new_topic'
					 bChecked  = $oUserCurrent->getSettingsNoticeNewTopic() != 0
					 bNoMargin = true
					 sLabel    = $aLang.settings_tuning_notice_new_topic}

			{include 'components/field/field.checkbox.tpl'
					 sName     = 'settings_notice_new_comment'
					 bChecked  = $oUserCurrent->getSettingsNoticeNewComment() != 0
					 bNoMargin = true
					 sLabel    = $aLang.settings_tuning_notice_new_comment}

			{include 'components/field/field.checkbox.tpl'
					 sName     = 'settings_notice_new_talk'
					 bChecked  = $oUserCurrent->getSettingsNoticeNewTalk() != 0
					 bNoMargin = true
					 sLabel    = $aLang.settings_tuning_notice_new_talk}

			{include 'components/field/field.checkbox.tpl'
					 sName     = 'settings_notice_reply_comment'
					 bChecked  = $oUserCurrent->getSettingsNoticeReplyComment() != 0
					 bNoMargin = true
					 sLabel    = $aLang.settings_tuning_notice_reply_comment}

			{include 'components/field/field.checkbox.tpl'
					 sName     = 'settings_notice_new_friend'
					 bChecked  = $oUserCurrent->getSettingsNoticeNewFriend() != 0
					 bNoMargin = true
					 sLabel    = $aLang.settings_tuning_notice_new_friend}
		</fieldset>

		<fieldset>
			<legend>{$aLang.settings_tuning_general}</legend>

			{foreach $aTimezoneList as $sTimezone}
				{$aTimezones[] = [
					'value' => $sTimezone,
					'text' => $aLang.timezone_list[$sTimezone]
				]}
			{/foreach}

			{include 'components/field/field.select.tpl'
					 sName          = 'settings_general_timezone'
					 sLabel         = $aLang.settings_tuning_general_timezone
					 sClasses       = 'width-500 js-topic-add-title'
					 aItems         = $aTimezones
					 sSelectedValue = $_aRequest.settings_general_timezone}
		</fieldset>

		{hook run='form_settings_tuning_end'}

        {include 'components/button/button.tpl' sName='submit_settings_tuning' sText=$aLang.settings_profile_submit sMods='primary'}
	</form>

	{hook run='settings_tuning_end'}
{/block}