{include file='header.tpl' menu='settings' showWhiteBack=true}


<h2>{$aLang.settings_profile_edit}</h2>

<form action="#" method="POST" enctype="multipart/form-data">

	{hook run='form_settings_profile_begin'}

	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<p>
		<label for="profile_name">{$aLang.settings_profile_name}:</label><br />
		<input type="text" name="profile_name" id="profile_name" value="{$oUserCurrent->getProfileName()|escape:'html'}" class="input-200" /><br />
		<span class="note">{$aLang.settings_profile_name_notice}</span>
	</p>
	<p>
		<label for="mail">{$aLang.settings_profile_mail}:</label><br />
		<input type="text" name="mail" id="mail" value="{$oUserCurrent->getMail()|escape:'html'}" class="input-200" /><br />
		<span class="note">{$aLang.settings_profile_mail_notice}</span>
	</p>
	<p>
		{$aLang.settings_profile_sex}:<br />
		<label><input type="radio" name="profile_sex" id="profile_sex_m" value="man" {if $oUserCurrent->getProfileSex()=='man'}checked{/if} class="checkbox" />{$aLang.settings_profile_sex_man}</label><br />
		<label><input type="radio" name="profile_sex" id="profile_sex_w" value="woman" {if $oUserCurrent->getProfileSex()=='woman'}checked{/if} class="checkbox" />{$aLang.settings_profile_sex_woman}</label><br />
		<label><input type="radio" name="profile_sex" id="profile_sex_o" value="other" {if $oUserCurrent->getProfileSex()=='other'}checked{/if} class="checkbox" />{$aLang.settings_profile_sex_other}</label>
	</p>
	<p>
		<label>{$aLang.settings_profile_birthday}:</label><br />
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
		<label for="profile_country">{$aLang.settings_profile_country}:</label><br /><input type="text" id="profile_country" name="profile_country" class="autocomplete-country" value="{$oUserCurrent->getProfileCountry()|escape:'html'}" /><br />
		<label for="profile_city">{$aLang.settings_profile_city}:</label><br /><input type="text" id="profile_city" name="profile_city" class="autocomplete-city" value="{$oUserCurrent->getProfileCity()|escape:'html'}" /><br />
	</p>

	<p><label for="profile_icq">{$aLang.settings_profile_icq}:</label><br /><input type="text" name="profile_icq" id="profile_icq" value="{$oUserCurrent->getProfileIcq()|escape:'html'}"/></p>

	<p>
		<label for="profile_site">{$aLang.settings_profile_site}:</label><br />
		<label for="profile_site"><input type="text" style="margin-bottom: 5px;" id="profile_site" name="profile_site" class="input-200" value="{$oUserCurrent->getProfileSite()|escape:'html'}" /> &mdash; {$aLang.settings_profile_site_url}</label><br />
		<label for="profile_site_name"><input type="text" id="profile_site_name" name="profile_site_name" class="input-200" value="{$oUserCurrent->getProfileSiteName()|escape:'html'}" /> &mdash; {$aLang.settings_profile_site_name}</label>
	</p>
	{if count($aUserFields)}
		{foreach from=$aUserFields item=oField}
			<p><label for="profile_user_field_{$oField->getId()}">{$oField->getTitle()|escape:'html'}:</label><br /><input type="text" class="input-200" name="profile_user_field_{$oField->getId()}" id="profile_user_field_{$oField->getId()}" value="{$oField->getValue()|escape:'html'}"/></p>
		{/foreach}
	{/if}
	<p>
		<label for="profile_about">{$aLang.settings_profile_about}:</label><br />
		<textarea cols="50" rows="5" name="profile_about" id="profile_about">{$oUserCurrent->getProfileAbout()|escape:'html'}</textarea>
	</p>

	<p>
		<label for="password_now">{$aLang.settings_profile_password_current}:</label><br /><input type="password" name="password_now" id="password_now" value="" /><br />
		<label for="password">{$aLang.settings_profile_password_new}:</label><br /><input type="password" id="password"	name="password" value="" /><br />
		<label for="password_confirm">{$aLang.settings_profile_password_confirm}:</label><br /><input type="password" id="password_confirm"	name="password_confirm" value="" />
	</p>

	{if $oUserCurrent->getProfileAvatar()}
		<img src="{$oUserCurrent->getProfileAvatarPath(100)}" />
		<img src="{$oUserCurrent->getProfileAvatarPath(64)}" />
		<img src="{$oUserCurrent->getProfileAvatarPath(24)}" /><br />
		<input type="checkbox" id="avatar_delete" name="avatar_delete" value="on" class="checkbox" /><label for="avatar_delete">{$aLang.settings_profile_avatar_delete}</label><br /><br />
	{/if}
	<p><label for="avatar">{$aLang.settings_profile_avatar}:</label><br /><input type="file" id="avatar" name="avatar" accept="image/png,image/gif,image/pjpeg,image/jpeg,image/jpg" /></p>

	{if $oUserCurrent->getProfileFoto()}
		<img src="{$oUserCurrent->getProfileFoto()}" /><br />
		<input type="checkbox" id="foto_delete" name="foto_delete" value="on" class="checkbox" /><label for="foto_delete">{$aLang.settings_profile_foto_delete}</label><br /><br />
	{/if}
	<p><label for="foto">{$aLang.settings_profile_foto}:</label><br /><input type="file" id="foto" name="foto" accept="image/png,image/gif,image/pjpeg,image/jpeg,image/jpg" /></p>

	{hook run='form_settings_profile_end'}
	<p><input type="submit" value="{$aLang.settings_profile_submit}" name="submit_profile_edit" /></p>
</form>


{include file='footer.tpl'}