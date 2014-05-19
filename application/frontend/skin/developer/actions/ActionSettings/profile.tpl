{**
 * Основные настройки профиля
 *
 * @scripts <framework>/js/livestreet/userfield.js
 *}

{extends 'layouts/layout.user.settings.tpl'}

{block 'layout_content' append}
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

	<form method="post" enctype="multipart/form-data" class="js-form-validate">
		{hook run='form_settings_profile_begin'}

        {* Основная информация *}
		<fieldset>
			<legend>{$aLang.settings_profile_section_base}</legend>

			{* Имя *}
			{include 'components/field/field.text.tpl'
                     sName   = 'profile_name'
					 aRules  = [ 'rangelength' => "[2,{Config::Get('module.user.name_max')}]" ]
					 bInline = true
					 sValue  = $oUserCurrent->getProfileName()|escape
					 sNote   = $aLang.settings_profile_name_notice
					 sLabel  = $aLang.settings_profile_name}


			{* Пол *}
            {$aSex = [
                [ 'value' => 'man',   'text' => $aLang.settings_profile_sex_man ],
                [ 'value' => 'woman', 'text' => $aLang.settings_profile_sex_woman ],
                [ 'value' => 'other', 'text' => $aLang.settings_profile_sex_other ]
            ]}

            {include 'components/field/field.select.tpl'
                     sName          = 'profile_sex'
                     bInline        = true
                     sLabel         = $aLang.settings_profile_sex
                     aItems         = $aSex
                     sSelectedValue = $oUserCurrent->getProfileSex()}


            {* Дата рождения *}
            {include 'components/field/field.date.tpl'
                     sName   = 'profile_birthday'
                     aItems  = $oUserCurrent->getProfileBirthday()
                     bInline = true
                     sLabel  = $aLang.settings_profile_birthday}


            {* Местоположение *}
            {include 'components/field/field.geo.tpl'
                     sName           = 'geo'
                     bInline         = true
                     sLabel          = $aLang.profile_place
                     oFieldGeoTarget = $oGeoTarget}


			{* О себе *}
			{include 'components/field/field.textarea.tpl'
					 sName   = 'profile_about'
                     aRules  = [ 'rangelength' => '[1,3000]' ]
					 bInline = true
					 iRows   = 5
					 sValue  = $oUserCurrent->getProfileAbout()|escape
					 sLabel  = $aLang.settings_profile_about}


            {* Пользовательские поля *}
			{$aUserFieldValues = $oUserCurrent->getUserFieldValues(false, '')}

            {foreach $aUserFieldValues as $oField}
                {include 'components/field/field.text.tpl'
                         sName   = "profile_user_field_`$oField->getId()`"
                         bInline = true
                         sValue  = $oField->getValue()|escape
                         sLabel  = $oField->getTitle()|escape}
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

        {hook run='form_settings_profile_end'}

        {* Скрытые поля *}
        {include 'components/field/field.hidden.security_key.tpl'}

        {* Кнопки *}
        {include 'components/button/button.tpl' sName='submit_profile_edit' sMods='primary' sText=$aLang.settings_profile_submit}
	</form>

	{hook run='settings_profile_end'}
{/block}