{**
 * Настройки профиля
 *}

{$user = $smarty.local.user}

<script type="text/javascript">
	jQuery(document).ready(function($){
		ls.geo.initSelect();
		ls.userfield.iCountMax='{cfg name="module.user.userfield_max_identical"}';
	});
</script>

{* Шаблон пользовательского поля (userfield) *}
{function name=userfield}
    <div class="form-field js-user-field-item" {if ! $field}id="profile_user_field_template" style="display:none;"{/if}>
        <select name="profile_user_field_type[]" onchange="ls.userfield.changeFormField(this);">
            {foreach $aUserFieldsContact as $fieldAll}
                <option value="{$fieldAll->getId()}" {if $field && $fieldAll->getId() == $field->getId()}selected{/if}>{$fieldAll->getTitle()|escape}</option>
            {/foreach}
        </select>

        <input type="text" name="profile_user_field_value[]" value="{if $field}{$field->getValue()|escape}{/if}" class="width-200">
        <a class="icon-remove" title="{$aLang.common.remove}" href="#" onclick="return ls.userfield.removeFormField(this);"></a>
    </div>
{/function}

{* Скрытое пользовательское поле для вставки через js *}
{call userfield field=false}


{hook run='settings_profile_begin'}

<form method="post" enctype="multipart/form-data" class="js-form-validate">
	{hook run='form_settings_profile_begin'}

    {* Основная информация *}
	<fieldset>
		<legend>{lang name='user.settings.profile.generic'}</legend>

		{* Имя *}
		{include 'components/field/field.text.tpl'
                 sName   = 'profile_name'
				 aRules  = [ 'rangelength' => "[2,{Config::Get('module.user.name_max')}]" ]
				 bInline = true
				 sValue  = $user->getProfileName()|escape
				 sLabel  = {lang name='user.settings.profile.fields.name.label'}}


		{* Пол *}
        {$aSex = [
            [ 'value' => 'man',   'text' => {lang name='user.gender.male'} ],
            [ 'value' => 'woman', 'text' => {lang name='user.gender.female'} ],
            [ 'value' => 'other', 'text' => {lang name='user.gender.none'} ]
        ]}

        {include 'components/field/field.select.tpl'
                 sName          = 'profile_sex'
                 bInline        = true
                 sLabel         = {lang name='user.settings.profile.fields.sex.label'}
                 aItems         = $aSex
                 sSelectedValue = $user->getProfileSex()}


        {* Дата рождения *}
        {include 'components/field/field.date.tpl'
                 sName   = 'profile_birthday'
                 aItems  = $user->getProfileBirthday()
                 bInline = true
                 sLabel  = {lang name='user.settings.profile.fields.birthday.label'}}


        {* Местоположение *}
        {include 'components/field/field.geo.tpl'
                 sName           = 'geo'
                 bInline         = true
                 sLabel          = {lang name='user.settings.profile.fields.place.label'}
                 fieldGeoTarget = $oGeoTarget}


		{* О себе *}
		{include 'components/field/field.textarea.tpl'
				 sName   = 'profile_about'
                 aRules  = [ 'rangelength' => '[1,3000]' ]
				 bInline = true
				 iRows   = 5
				 sValue  = $user->getProfileAbout()|escape
				 sLabel  = {lang name='user.settings.profile.fields.about.label'}}


        {* Пользовательские поля *}
		{$userfields = $user->getUserFieldValues(false, '')}

        {foreach $userfields as $field}
            {include 'components/field/field.text.tpl'
                     sName   = "profile_user_field_`$field->getId()`"
                     bInline = true
                     sValue  = $field->getValue()|escape
                     sLabel  = $field->getTitle()|escape}
        {/foreach}
	</fieldset>


    {* Контакты *}
	<fieldset>
		<legend>{lang name='user.settings.profile.contact'}</legend>

		{$contacts = $user->getUserFieldValues( true, array('contact', 'social') )}

        {* Список пользовательских полей, шаблон определен в начале файла *}
		<div id="user-field-contact-contener">
            {foreach $contacts as $contact}
                {call userfield field=$contact}
            {/foreach}
		</div>

		{if $aUserFieldsContact}
			<button type="button" class="button" onclick="return ls.userfield.addFormField();">{$aLang.common.add}</button>
		{/if}
	</fieldset>

    {hook run='form_settings_profile_end'}

    {* Скрытые поля *}
    {include 'components/field/field.hidden.security_key.tpl'}

    {* Кнопки *}
    {include 'components/button/button.tpl' sName='submit_profile_edit' sMods='primary' sText=$aLang.common.save}
</form>

{hook run='settings_profile_end'}