{**
 * Основные настройки профиля
 *
 * @scripts <framework>/js/livestreet/userfield.js
 *}

{extends file='layouts/layout.user.settings.tpl'}

{block name='layout_content'}
	<script type="text/javascript">
		jQuery(document).ready(function($){
			ls.geo.initSelect();
			ls.userfield.iCountMax='{cfg name="module.user.userfield_max_identical"}';
		});
	</script>

    {* Шаблон пользовательского поля (userfield) *}
    {function name=userfield}
        <div class="form-field js-user-field-item" {if ! $oField}id="profile_user_field_template" style="display:none;"{/if}>
            <select name="profile_user_field_type[]"  onchange="ls.userfield.changeFormField(this);">
                {foreach $aUserFieldsContact as $oFieldAll}
                    <option value="{$oFieldAll->getId()}" {if $oField && $oFieldAll->getId() == $oField->getId()}selected{/if}>{$oFieldAll->getTitle()|escape:'html'}</option>
                {/foreach}
            </select>

            <input type="text" name="profile_user_field_value[]" value="{if $oField}{$oField->getValue()|escape}{/if}" class="width-200">
            <a class="icon-remove" title="{$aLang.user_field_delete}" href="#" onclick="return ls.userfield.removeFormField(this);"></a>
        </div>
    {/function}

    {* Скрытое пользовательское поле для вставки через js *}
    {call userfield oField=false}


	{hook run='settings_profile_begin'}

	<form method="post" enctype="multipart/form-data" class="form-profile" data-validate="parsley">
		{hook run='form_settings_profile_begin'}

        {* Основная информация *}
		<fieldset>
			<legend>{$aLang.settings_profile_section_base}</legend>

			{* Имя *}
			{include file='forms/form.field.text.tpl'
                     sFieldName   = 'profile_name'
					 sFieldRules  = 'rangelength="[2,'|cat:$oConfig->Get('module.user.name_max')|cat:']"'
					 bFieldInline = true
					 sFieldValue  = $oUserCurrent->getProfileName()|escape
					 sFieldNote   = $aLang.settings_profile_name_notice
					 sFieldLabel  = $aLang.settings_profile_name}


			{* Пол *}
            {$aSex = [
                [ 'value' => 'man',   'text' => $aLang.settings_profile_sex_man ],
                [ 'value' => 'woman', 'text' => $aLang.settings_profile_sex_woman ],
                [ 'value' => 'other', 'text' => $aLang.settings_profile_sex_other ]
            ]}

            {include file='forms/form.field.select.tpl'
                     sFieldName          = 'profile_sex'
                     bFieldInline        = true
                     sFieldLabel         = $aLang.settings_profile_sex
                     aFieldItems         = $aSex
                     sFieldSelectedValue = $oUserCurrent->getProfileSex()}


            {* Дата рождения *}
            {include file='forms/form.field.select.date.tpl'
                    sFieldNamePrefix    = 'profile_birthday'
                    aFieldItems         = $oUserCurrent->getProfileBirthday()
                    bFieldInline        = true
                    sFieldLabel         = $aLang.settings_profile_birthday}


            {* Местоположение *}
            {include file='forms/form.field.select.geo.tpl'
                    sFieldNamePrefix    = 'geo'
                    bFieldInline        = true
                    sFieldLabel         = $aLang.profile_place
                    oInputGeoTarget     = $oGeoTarget}


			{* О себе *}
			{include file='forms/form.field.textarea.tpl'
					 sFieldName   = 'profile_about'
                     sFieldRules  = 'rangelength="[1,3000]"'
					 bFieldInline = true
					 iFieldRows   = 5
					 sFieldValue  = $oUserCurrent->getProfileAbout()|escape
					 sFieldLabel  = $aLang.settings_profile_about}


            {* Пользовательские поля *}
			{$aUserFieldValues = $oUserCurrent->getUserFieldValues(false, '')}

            {foreach $aUserFieldValues as $oField}
                {include file='forms/form.field.text.tpl'
                         sFieldName   = "profile_user_field_`$oField->getId()`"
                         bFieldInline = true
                         sFieldValue  = $oField->getValue()|escape
                         sFieldLabel  = $oField->getTitle()|escape}
            {/foreach}
		</fieldset>


        {* Контакты *}
		<fieldset>
			<legend>{$aLang.settings_profile_section_contacts}</legend>

			{$aUserFieldContactValues = $oUserCurrent->getUserFieldValues(true,array('contact','social'))}

            {* Список пользовательских полей, шаблон определен в начале файла *}
			<div id="user-field-contact-contener">
                {foreach $aUserFieldContactValues as $oField}
                    {call userfield oField=$oField}
                {/foreach}
			</div>

			{if $aUserFieldsContact}
				<button type="button" class="button" onclick="return ls.userfield.addFormField();">{$aLang.user_field_add}</button>
			{/if}
		</fieldset>


        {* Аватар *}
		{include file='forms/form.field.file.image.tpl'
                 sFieldName      = 'avatar'
                 sFieldImagePath = $oUserCurrent->getProfileAvatarPath(100)
                 bFieldIsImage   = $oUserCurrent->getProfileAvatar()}


        {hook run='form_settings_profile_end'}

        {* Скрытые поля *}
        {include file='forms/form.field.hidden.security_key.tpl'}

        {* Кнопки *}
        {include file='forms/form.field.button.tpl' sFieldName='submit_profile_edit' sFieldStyle='primary' sFieldText=$aLang.settings_profile_submit}
	</form>

	{hook run='settings_profile_end'}
{/block}