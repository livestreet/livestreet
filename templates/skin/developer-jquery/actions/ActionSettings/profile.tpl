{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}



{include file='actions/ActionProfile/profile_top.tpl'}
<h3 class="profile-page-header">{$aLang.settings_menu}</h3>

{include file='menu.settings.tpl'}


<script type="text/javascript">
	jQuery(document).ready(function($){
		ls.lang.load({lang_load name="geo_select_city,geo_select_region"});
		ls.geo.initSelect();
	});
</script>


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

		<div class="js-geo-select">

			<p>
				<select class="js-geo-country" name="geo_country">
					<option value="">{$aLang.geo_select_country}</option>
					{if $aGeoCountries}
						{foreach from=$aGeoCountries item=oGeoCountry}
							<option value="{$oGeoCountry->getId()}" {if $oGeoTarget and $oGeoTarget->getCountryId()==$oGeoCountry->getId()}selected="selected"{/if}>{$oGeoCountry->getName()}</option>
						{/foreach}
					{/if}
				</select>
			</p>

			<p>
				<select class="js-geo-region" name="geo_region" {if !$oGeoTarget or !$oGeoTarget->getCountryId()}style="display:none;"{/if}>
					<option value="">{$aLang.geo_select_region}</option>
					{if $aGeoRegions}
						{foreach from=$aGeoRegions item=oGeoRegion}
							<option value="{$oGeoRegion->getId()}" {if $oGeoTarget and $oGeoTarget->getRegionId()==$oGeoRegion->getId()}selected="selected"{/if}>{$oGeoRegion->getName()}</option>
						{/foreach}
					{/if}
				</select>
			</p>

			<p>
				<select class="js-geo-city" name="geo_city" {if !$oGeoTarget or !$oGeoTarget->getRegionId()}style="display:none;"{/if}>
					<option value="">{$aLang.geo_select_city}</option>
					{if $aGeoCities}
						{foreach from=$aGeoCities item=oGeoCity}
							<option value="{$oGeoCity->getId()}" {if $oGeoTarget and $oGeoTarget->getCityId()==$oGeoCity->getId()}selected="selected"{/if}>{$oGeoCity->getName()}</option>
						{/foreach}
					{/if}
				</select>
			</p>

		</div>
		
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


	<fieldset>
		<legend>Аватар</legend>
		
		<img src="{$oUserCurrent->getProfileAvatarPath(64)}" id="avatar-img" />

		<p><a href="#" id="avatar-upload" class="link-dotted">{if $oUserCurrent->getProfileAvatar()}{$aLang.settings_profile_avatar_change}{else}{$aLang.settings_profile_avatar_upload}{/if}</a>&nbsp;&nbsp;&nbsp;
		<a href="#" id="avatar-remove" class="link-dotted" onclick="return ls.user.removeAvatar();" style="{if !$oUserCurrent->getProfileAvatar()}display:none;{/if}">{$aLang.settings_profile_avatar_delete}</a></p>

		<div id="avatar-resize" style="display:none;">
			<p><img src="" alt="" id="avatar-resize-original-img"></p>
			<button class="button button-primary" onclick="return ls.user.resizeAvatar();">{$aLang.settings_profile_avatar_resize_apply}</button>
			<button class="button" onclick="return ls.user.cancelAvatar();">{$aLang.settings_profile_avatar_resize_cancel}</button>
		</div>
	</fieldset>

	
	
	<fieldset>
		<legend>Фотография</legend>
		
		<img src="{$oUserCurrent->getProfileFoto()}" id="foto-img" />

		<p><a href="#" id="foto-upload" class="link-dotted">{if $oUserCurrent->getProfileFoto()}{$aLang.settings_profile_avatar_change}{else}{$aLang.settings_profile_avatar_upload}{/if}</a>&nbsp;&nbsp;&nbsp;
		<a href="#" id="foto-remove" class="link-dotted" onclick="return ls.user.removeFoto();" style="{if !$oUserCurrent->getProfileFoto()}display:none;{/if}">{$aLang.settings_profile_avatar_delete}</a></p>

		<div id="foto-resize" style="display:none;">
			<p><img src="" alt="" id="foto-resize-original-img"></p>
			<button class="button button-primary" onclick="return ls.user.resizeFoto();">{$aLang.settings_profile_avatar_resize_apply}</button>
			<button class="button" onclick="return ls.user.cancelFoto();">{$aLang.settings_profile_avatar_resize_cancel}</button>
		</div>
	</fieldset>
	
	
	{hook run='form_settings_profile_end'}
	
	
	<button name="submit_profile_edit" class="button button-primary" />{$aLang.settings_profile_submit}</button>
</form>



{include file='footer.tpl'}