{include file='header.tpl'}

{include file='menu.settings.tpl'}

{include file='system_message.tpl'}

<BR>
<table width="100%"  border="0" cellspacing="4" cellpadding="4">
<tr>

<td align="left">



<p><span class="header">Настройки сайта</span>

<div class="backoffice">
<form action="{$DIR_WEB_ROOT}/settings/tuning/" method="POST" enctype="multipart/form-data">
<fieldset >
<legend >Уведомления на e-mail</legend>

	<input {if $oUserCurrent->getSettingsNoticeNewTopic()}checked{/if}  type="checkbox" id="settings_notice_new_topic" name="settings_notice_new_topic" value="1" {if $_aRequest.settings_notice_new_topic==1}checked{/if}/>
      <label for="settings_notice_new_topic"> &mdash; при новом топике в блоге</label>
	<br>
	
	<input {if $oUserCurrent->getSettingsNoticeNewComment()}checked{/if} type="checkbox"   id="settings_notice_new_comment" name="settings_notice_new_comment" value="1" {if $_aRequest.settings_notice_new_comment==1}checked{/if}/>
      <label for="settings_notice_new_comment"> &mdash; при новом комментарии в топике</label>
	<br>
	
	<input {if $oUserCurrent->getSettingsNoticeNewTalk()}checked{/if} type="checkbox" id="settings_notice_new_talk" name="settings_notice_new_talk" value="1" {if $_aRequest.settings_notice_new_talk==1}checked{/if}/>
      <label for="settings_notice_new_talk"> &mdash; при новом личном сообщении</label>
	<br>
	
	<input {if $oUserCurrent->getSettingsNoticeReplyComment()}checked{/if} type="checkbox" id="settings_notice_reply_comment" name="settings_notice_reply_comment" value="1" {if $_aRequest.settings_notice_reply_comment==1}checked{/if}/>
      <label for="settings_notice_reply_comment"> &mdash; при ответе на комментарий</label>
	<br>
	
	<input {if $oUserCurrent->getSettingsNoticeNewFriend()}checked{/if} type="checkbox" id="settings_notice_new_friend" name="settings_notice_new_friend" value="1" {if $_aRequest.settings_notice_new_friend==1}checked{/if}/>
      <label for="settings_notice_new_friend"> &mdash; при добавлении вас в друзья</label>
	<br>
</fieldset>

	<p class="l-bot"><input type="submit" name="submit_settings_tuning" tabindex="6" value="сохранить настройки" /></p>
</form>
</div>


</td>
</tr>
</table>


{include file='footer.tpl'}

