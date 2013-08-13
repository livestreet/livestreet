{**
 * Основные настройки профиля
 *}

{extends file='layouts/layout.user.settings.tpl'}

{block name='layout_content'}
	<script type="text/javascript">
		jQuery(document).ready(function($){
			ls.lang.load({lang_load name="geo_select_city,geo_select_region"});
			ls.geo.initSelect();
			ls.userfield.iCountMax='{cfg name="module.user.userfield_max_identical"}';
		});
	</script>

	{hook run='settings_profile_begin'}

	<form method="post" enctype="multipart/form-data" class="form-profile">
		<div class="wrapper-content">
			<p id="profile_user_field_template" style="display:none;" class="js-user-field-item">
				<select name="profile_user_field_type[]" onchange="ls.userfield.changeFormField(this);">
				{foreach $aUserFieldsContact as $oFieldAll}
					<option value="{$oFieldAll->getId()}">{$oFieldAll->getTitle()|escape:'html'}</option>
				{/foreach}
				</select>
				<input type="text" name="profile_user_field_value[]" value="" class="input-text input-width-200">
				<a class="icon-synio-remove" title="{$aLang.user_field_delete}" href="#" onclick="return ls.userfield.removeFormField(this);"></a>
			</p>
			
			
			<div class="avatar-change">
				<div class="avatar"><img src="{$oUserCurrent->getProfileAvatarPath(100)}" id="avatar-img" /></div>

				<div>
					<a href="#" id="avatar-upload" class="link-dotted">{if $oUserCurrent->getProfileAvatar()}{$aLang.settings_profile_avatar_change}{else}{$aLang.settings_profile_avatar_upload}{/if}</a><br />
					<a href="#" id="avatar-remove" class="link-dotted" onclick="return ls.user.removeAvatar();" style="{if !$oUserCurrent->getProfileAvatar()}display:none;{/if}">{$aLang.settings_profile_avatar_delete}</a>
				</div>
			</div>

		
			{hook run='form_settings_profile_begin'}

			<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}">
			
			
			
			<dl class="form-item">
				<dt><label for="profile_name">{$aLang.settings_profile_name}:</label></dt>
				<dd>
					<input type="text" name="profile_name" id="profile_name" value="{$oUserCurrent->getProfileName()|escape:'html'}" class="input-text input-width-250">
				</dd>
			</dl>
			
			<dl class="form-item">
				<dt><label for="profile_sex">{$aLang.settings_profile_sex}:</label></dt>
				<dd>
					<select name="profile_sex" id="profile_sex" class="input-width-250">
						<option value="man" {if $oUserCurrent->getProfileSex()=='man'}selected{/if}>{$aLang.settings_profile_sex_man}</option>
						<option value="woman" {if $oUserCurrent->getProfileSex()=='woman'}selected{/if}>{$aLang.settings_profile_sex_woman}</option>
						<option value="other" {if $oUserCurrent->getProfileSex()=='other'}selected{/if}>{$aLang.settings_profile_sex_other}</option>
					</select>
				</dd>
			</dl>
			
			<dl class="form-item">
				<dt><label for="">{$aLang.settings_profile_birthday}:</label></dt>
				<dd>
					<select name="profile_birthday_day" style="width: 58px; margin-right: 4px;">
						<option value="">{$aLang.date_day}</option>
						{section name=date_day start=1 loop=32 step=1}
							<option value="{$smarty.section.date_day.index}" {if $smarty.section.date_day.index==$oUserCurrent->getProfileBirthday()|date_format:"%d"}selected{/if}>{$smarty.section.date_day.index}</option>
						{/section}
					</select>
					
					<select name="profile_birthday_month" style="width: 103px; margin-right: 4px;">
						<option value="">{$aLang.date_month}</option>
						{section name=date_month start=1 loop=13 step=1}
							<option value="{$smarty.section.date_month.index}" {if $smarty.section.date_month.index==$oUserCurrent->getProfileBirthday()|date_format:"%m"}selected{/if}>{$aLang.month_array[$smarty.section.date_month.index][0]}</option>
						{/section}
					</select>
					
					<select name="profile_birthday_year" style="width: 72px">
						<option value="">{$aLang.date_year}</option>
						{section name=date_year loop=$smarty.now|date_format:"%Y"+1 max=$smarty.now|date_format:"%Y"-2012+130 step=-1}
							<option value="{$smarty.section.date_year.index}" {if $smarty.section.date_year.index==$oUserCurrent->getProfileBirthday()|date_format:"%Y"}selected{/if}>{$smarty.section.date_year.index}</option>
						{/section}
					</select>
				</dd>
			</dl>
			
			
			<br />
			<label for="profile_about" style="margin-bottom: 7px">{$aLang.settings_profile_about}:</label>
			<textarea name="profile_about" id="profile_about" class="input-text input-width-full" rows="7">{$oUserCurrent->getProfileAbout()|escape:'html'}</textarea>
			<br />
			<br />
			<br />

			<div class="js-geo-select">
			<label for="" style="margin-bottom: 7px">{$aLang.profile_place}:</label>
			<p style="margin-bottom: 15px">
				<select class="js-geo-country input-width-200" name="geo_country">
					<option value="">{$aLang.geo_select_country}</option>
					{if $aGeoCountries}
						{foreach $aGeoCountries as $oGeoCountry}
							<option value="{$oGeoCountry->getId()}" {if $oGeoTarget and $oGeoTarget->getCountryId()==$oGeoCountry->getId()}selected="selected"{/if}>{$oGeoCountry->getName()}</option>
						{/foreach}
					{/if}
				</select>
			</p>

			<p style="margin-bottom: 15px">
				<select class="js-geo-region input-width-200" name="geo_region" {if !$oGeoTarget or !$oGeoTarget->getCountryId()}style="display:none;"{/if}>
					<option value="">{$aLang.geo_select_region}</option>
					{if $aGeoRegions}
						{foreach $aGeoRegions as $oGeoRegion}
							<option value="{$oGeoRegion->getId()}" {if $oGeoTarget and $oGeoTarget->getRegionId()==$oGeoRegion->getId()}selected="selected"{/if}>{$oGeoRegion->getName()}</option>
						{/foreach}
					{/if}
				</select>
			</p>

			<p style="margin-bottom: 15px">
				<select class="js-geo-city input-width-200" name="geo_city" {if !$oGeoTarget or !$oGeoTarget->getRegionId()}style="display:none;"{/if}>
					<option value="">{$aLang.geo_select_city}</option>
					{if $aGeoCities}
						{foreach $aGeoCities as $oGeoCity}
							<option value="{$oGeoCity->getId()}" {if $oGeoTarget and $oGeoTarget->getCityId()==$oGeoCity->getId()}selected="selected"{/if}>{$oGeoCity->getName()}</option>
						{/foreach}
					{/if}
				</select>
			</p>
			</div>
			
			{$aUserFieldValues = $oUserCurrent->getUserFieldValues(false,'')}
			{if count($aUserFieldValues)}
				{foreach $aUserFieldValues as $oField}
					<dl class="form-item">
						<dt><label for="profile_user_field_{$oField->getId()}">{$oField->getTitle()|escape:'html'}:</label></dt>
						<dd><input type="text" class="input-text input-width-300" name="profile_user_field_{$oField->getId()}" id="profile_user_field_{$oField->getId()}" value="{$oField->getValue()|escape:'html'}"/></dd>
					</dl>
				{/foreach}
			{/if}
		</div>
			
			
			
		<div class="wrapper-content wrapper-content-dark">
			<h3>{$aLang.settings_profile_section_contacts}</h3>

			{$aUserFieldContactValues = $oUserCurrent->getUserFieldValues(true,array('contact','social'))}
			<div id="user-field-contact-contener">
			{foreach $aUserFieldContactValues as $oField}
				<p class="js-user-field-item">
					<select name="profile_user_field_type[]" onchange="ls.userfield.changeFormField(this);">
					{foreach $aUserFieldsContact as $oFieldAll}
						<option value="{$oFieldAll->getId()}" {if $oFieldAll->getId()==$oField->getId()}selected="selected"{/if}>{$oFieldAll->getTitle()|escape:'html'}</option>
					{/foreach}
					</select>
					<input type="text" name="profile_user_field_value[]" value="{$oField->getValue()|escape:'html'}" class="input-text input-width-200">
					<a class="icon-synio-remove" title="{$aLang.user_field_delete}" href="#" onclick="return ls.userfield.removeFormField(this);"></a>
				</p>
			{/foreach}
			</div>
			{if $aUserFieldsContact}
				<a href="#" onclick="return ls.userfield.addFormField();" class="link-dotted">{$aLang.user_field_add}</a>
			{/if}
		</div>

		
		<div class="wrapper-content">
			<script type="text/javascript">
				jQuery(function($){
					$('#avatar-upload').file({ name:'avatar' }).choose(function(e, input) {
						ls.user.uploadAvatar(null,input);
					});
				});
			</script>

			
			{hook run='form_settings_profile_end'}
			
			
			<button type="submit"  name="submit_profile_edit" class="button button-primary" />{$aLang.settings_profile_submit}</button>
		</div>
	</form>



	{hook run='settings_profile_end'}
{/block}