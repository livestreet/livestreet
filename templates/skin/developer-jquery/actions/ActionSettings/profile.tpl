{include file='header.tpl' menu='settings' noSidebar=true}

<p id="profile_user_field_template" style="display:none;" class="js-user-field-item">
	<select name="profile_user_field_type[]">
	{foreach from=$aUserFieldsContact item=oFieldAll}
		<option value="{$oFieldAll->getId()}">{$oFieldAll->getTitle()|escape:'html'}</option>
	{/foreach}
	</select>
	<input type="text" name="profile_user_field_value[]" value="" class="input-text input-width-200">
	<a class="icon-remove" title="{$aLang.user_field_delete}" href="#" onclick="return ls.userfield.removeFormField(this);"></a>
</p>


<form method="post" enctype="multipart/form-data">
	{hook run='form_settings_profile_begin'}

	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}">
	
	
	<fieldset>
		<legend>Основная информация</legend>
		<p>
			<label for="profile_name">{$aLang.settings_profile_name}:</label>
			<input type="text" name="profile_name" id="profile_name" value="{$oUserCurrent->getProfileName()|escape:'html'}" class="input-text input-width-300">
			<small class="note">{$aLang.settings_profile_name_notice}</small>
		</p>
		
		<p>
			<label for="mail">{$aLang.settings_profile_mail}:</label>
			<input type="email" name="mail" id="mail" value="{$oUserCurrent->getMail()|escape:'html'}" class="input-text input-width-300" required>
			<small class="note">{$aLang.settings_profile_mail_notice}</small>
		</p>
		
		<p>
			<label for="profile_sex">{$aLang.settings_profile_sex}:</label>
			<select name="profile_sex" id="profile_sex">
				<option value="man" {if $oUserCurrent->getProfileSex()=='man'}selected{/if}>{$aLang.settings_profile_sex_man}</option>
				<option value="woman" {if $oUserCurrent->getProfileSex()=='woman'}selected{/if}>{$aLang.settings_profile_sex_woman}</option>
				<option value="other" {if $oUserCurrent->getProfileSex()=='other'}selected{/if}>{$aLang.settings_profile_sex_other}</option>
			</select>
		</p>

		<p>
			<label for="">{$aLang.settings_profile_birthday}:</label>
			<select name="profile_birthday_day">
				<option value="">{$aLang.date_day}</option>
				{section name=date_day start=1 loop=32 step=1}
					<option value="{$smarty.section.date_day.index}" {if $smarty.section.date_day.index==$oUserCurrent->getProfileBirthday()|date_format:"%d"}selected{/if}>{$smarty.section.date_day.index}</option>
				{/section}
			</select>
			
			<select name="profile_birthday_month">
				<option value="">{$aLang.date_month}</option>
				{section name=date_month start=1 loop=13 step=1}
					<option value="{$smarty.section.date_month.index}" {if $smarty.section.date_month.index==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>{$aLang.month_array[$smarty.section.date_month.index][0]}</option>
				{/section}
			</select>
			
			<select name="profile_birthday_year">
				<option value="">{$aLang.date_year}</option>
				{section name=date_year loop=$smarty.now|date_format:"%Y"+1 max=$smarty.now|date_format:"%Y"-2012+130 step=-1}
					<option value="{$smarty.section.date_year.index}" {if $smarty.section.date_year.index==$oUserCurrent->getProfileBirthday()|date_format:"%Y"}selected{/if}>{$smarty.section.date_year.index}</option>
				{/section}
			</select>
		</p>

		<p>
			<label for="profile_country">{$aLang.settings_profile_country}:</label>
			<input type="text" id="profile_country" name="profile_country" class="input-text input-width-200 autocomplete-country" value="{$oUserCurrent->getProfileCountry()|escape:'html'}" />
		<p>
		
		</p>	
			<label for="profile_city">{$aLang.settings_profile_city}:</label>
			<input type="text" id="profile_city" name="profile_city" class="input-text input-width-200 autocomplete-city" value="{$oUserCurrent->getProfileCity()|escape:'html'}" />
		</p>
		
		<p>
			<label for="profile_about">{$aLang.settings_profile_about}:</label>
			<textarea name="profile_about" id="profile_about" class="input-text input-width-400" rows="5">{$oUserCurrent->getProfileAbout()|escape:'html'}</textarea>
		</p>
	</fieldset>
	
	
	
	<fieldset>
		<legend>Контакты</legend>
		
		<p>
			<label for="profile_icq">{$aLang.settings_profile_icq}:</label>
			<input type="text" name="profile_icq" id="profile_icq" value="{$oUserCurrent->getProfileIcq()|escape:'html'}" class="input-text input-width-200">
		</p>

		<p>
			<label for="">{$aLang.settings_profile_site}:</label>
			<input type="text" id="profile_site" name="profile_site" value="{$oUserCurrent->getProfileSite()|escape:'html'}" placeholder="{$aLang.settings_profile_site_url}" class="input-text input-width-200" />
			<input type="text" id="profile_site_name" name="profile_site_name" value="{$oUserCurrent->getProfileSiteName()|escape:'html'}" placeholder="{$aLang.settings_profile_site_name}" class="input-text input-width-200" />
		</p>

		{assign var="aUserFieldValues" value=$oUserCurrent->getUserFieldValues(false,'')}
		{if count($aUserFieldValues)}
			{foreach from=$aUserFieldValues item=oField}
				<p>
					<label for="profile_user_field_{$oField->getId()}">{$oField->getTitle()|escape:'html'}:</label>
					<input type="text" name="profile_user_field_{$oField->getId()}" id="profile_user_field_{$oField->getId()}" value="{$oField->getValue()|escape:'html'}" class="input-text input-width-200">
				</p>
			{/foreach}
		{/if}

		{assign var="aUserFieldContactValues" value=$oUserCurrent->getUserFieldValues(true,array('contact','social'))}
		<div id="user-field-contact-contener">
		{foreach from=$aUserFieldContactValues item=oField}
			<p class="js-user-field-item">
				<select name="profile_user_field_type[]">
				{foreach from=$aUserFieldsContact item=oFieldAll}
					<option value="{$oFieldAll->getId()}" {if $oFieldAll->getId()==$oField->getId()}selected="selected"{/if}>{$oFieldAll->getTitle()|escape:'html'}</option>
				{/foreach}
				</select>
				<input type="text" name="profile_user_field_value[]" value="{$oField->getValue()|escape:'html'}" class="input-text input-width-200">
				<a class="icon-remove" title="{$aLang.user_field_delete}" href="#" onclick="return ls.userfield.removeFormField(this);"></a>
			</p>
		{/foreach}
		</div>
		{if $aUserFieldsContact}
			<a href="#" onclick="return ls.userfield.addFormField();">{$aLang.user_field_add}</a>
		{/if}
	</fieldset>
	
	
		
	<fieldset>
		<legend>Пароль</legend>
		
		<small class="note note-header">Оставьте поля пустыми если не хотите изменять пароль.</small>
			
		<p><label for="password_now">{$aLang.settings_profile_password_current}:</label>
		<input type="password" name="password_now" id="password_now" value="" class="input-text input-width-200" /></p>
		
		<p><label for="password">{$aLang.settings_profile_password_new}:</label>
		<input type="password" id="password" name="password" value="" class="input-text input-width-200" /></p>
			
		<p><label for="password_confirm">{$aLang.settings_profile_password_confirm}:</label>
		<input type="password" id="password_confirm" name="password_confirm" value="" class="input-text input-width-200" /></p>
	</fieldset>

	{hook run='form_settings_profile_end'}
	
	
	<button name="submit_profile_edit" class="button button-primary" />{$aLang.settings_profile_submit}</button>
</form>





<script type="text/javascript">
	jQuery(function($){
		$('#avatar-upload').file({ name:'avatar' }).choose(function(e, input) {
			ls.user.uploadAvatar(null,input);
		});
		$('#foto-upload').file({ name:'foto' }).choose(function(e, input) {
			ls.user.uploadFoto(null,input);
		});
	});
</script>


<br/><br/>
<img src="{$oUserCurrent->getProfileAvatarPath(64)}" id="avatar-img" />
<br/>

<a href="#" id="avatar-upload">{if $oUserCurrent->getProfileAvatar()}{$aLang.settings_profile_avatar_change}{else}{$aLang.settings_profile_avatar_upload}{/if}</a><br/>
<a href="#" id="avatar-remove" onclick="return ls.user.removeAvatar();" style="{if !$oUserCurrent->getProfileAvatar()}display:none;{/if}">{$aLang.settings_profile_avatar_delete}</a>

<div id="avatar-resize" style="display:none;">
	<div>
		<img src="" alt="" id="avatar-resize-original-img">
	</div>
	<button onclick="return ls.user.resizeAvatar();">{$aLang.settings_profile_avatar_resize_apply}</button>
	<button onclick="return ls.user.cancelAvatar();">{$aLang.settings_profile_avatar_resize_cancel}</button>
</div>



<br/><br/>
<img src="{$oUserCurrent->getProfileFoto()}" id="foto-img" />
<br/>

<a href="#" id="foto-upload">{if $oUserCurrent->getProfileFoto()}{$aLang.settings_profile_avatar_change}{else}{$aLang.settings_profile_avatar_upload}{/if}</a><br/>
<a href="#" id="foto-remove" onclick="return ls.user.removeFoto();" style="{if !$oUserCurrent->getProfileFoto()}display:none;{/if}">{$aLang.settings_profile_avatar_delete}</a>

<div id="foto-resize" style="display:none;">
	<div>
		<img src="" alt="" id="foto-resize-original-img">
	</div>
	<button onclick="return ls.user.resizeFoto();">{$aLang.settings_profile_avatar_resize_apply}</button>
	<button onclick="return ls.user.cancelFoto();">{$aLang.settings_profile_avatar_resize_cancel}</button>
</div>



{include file='footer.tpl'}