{**
 * Настройки профиля
 *}

{$user = $smarty.local.user}

{hook run='settings_profile_begin'}


{* Шаблон пользовательского поля (userfield) *}
{function name=userfield}
    <div class="mb-15 js-user-field-item" {if ! $field}id="user-field-template" style="display:none;"{/if}>
        <select name="profile_user_field_type[]">
            {foreach $aUserFieldsContact as $fieldAll}
                <option value="{$fieldAll->getId()}" {if $field && $fieldAll->getId() == $field->getId()}selected{/if}>
                    {$fieldAll->getTitle()|escape}
                </option>
            {/foreach}
        </select>

        <input type="text" name="profile_user_field_value[]" value="{if $field}{$field->getValue()|escape}{/if}" class="width-200">
        <div class="icon-remove js-user-field-item-remove" title="{$aLang.common.remove}"></div>
    </div>
{/function}

{* Скрытое пользовательское поле для вставки через js *}
{* Вынесено за пределы формы, чтобы не передавалось при отправке формы *}
{call userfield field=false}


<form method="post" enctype="multipart/form-data" class="js-form-validate">
    {hook run='form_settings_profile_begin'}

    {* Основная информация *}
    <fieldset>
        <legend>{lang name='user.settings.profile.generic'}</legend>

        {* Имя *}
        {include 'components/field/field.text.tpl'
            name   = 'profile_name'
            rules  = [ 'rangelength' => "[2,{Config::Get('module.user.name_max')}]" ]
            inline = true
            value  = $user->getProfileName()|escape
            label  = {lang name='user.settings.profile.fields.name.label'}}


        {* Пол *}
        {$sex = [
            [ 'value' => 'man',   'text' => {lang name='user.gender.male'} ],
            [ 'value' => 'woman', 'text' => {lang name='user.gender.female'} ],
            [ 'value' => 'other', 'text' => {lang name='user.gender.none'} ]
        ]}

        {include 'components/field/field.select.tpl'
            name          = 'profile_sex'
            inline        = true
            label         = {lang name='user.settings.profile.fields.sex.label'}
            items         = $sex
            selectedValue = $user->getProfileSex()}


        {* Дата рождения *}
        {include 'components/field/field.date.tpl'
            name   = 'profile_birthday'
            items  = $user->getProfileBirthday()
            inline = true
            label  = {lang name='user.settings.profile.fields.birthday.label'}}


        {* Местоположение *}
        {include 'components/field/field.geo.tpl'
            name   = 'geo'
            inline = true
            label  = {lang name='user.settings.profile.fields.place.label'}
            place   = $oGeoTarget}


        {* О себе *}
        {include 'components/field/field.textarea.tpl'
            name   = 'profile_about'
            rules  = [ 'rangelength' => '[1,3000]' ]
            inline = true
            rows   = 5
            value  = $user->getProfileAbout()|escape
            label  = {lang name='user.settings.profile.fields.about.label'}}


        {* Пользовательские поля *}
        {$userfields = $user->getUserFieldValues(false, '')}

        {foreach $userfields as $field}
            {include 'components/field/field.text.tpl'
                name   = "profile_user_field_`$field->getId()`"
                inline = true
                value  = $field->getValue()|escape
                label  = $field->getTitle()|escape}
        {/foreach}
    </fieldset>


    {* Контакты *}
    <fieldset class="js-user-fields">
        <legend>{lang name='user.settings.profile.contact'}</legend>

        {$contacts = $user->getUserFieldValues( true, array('contact', 'social') )}

        {* Список пользовательских полей, шаблон определен в начале файла *}
        <div class="js-user-field-list mb-15">
            {foreach $contacts as $contact}
                {call userfield field=$contact}
            {foreachelse}
                {include 'components/alert/alert.tpl' mods='empty' classes='js-user-fields-empty' text=$aLang.common.empty}
            {/foreach}
        </div>

        {if $aUserFieldsContact}
            {include 'components/button/button.tpl' type='button' classes='js-user-fields-submit' text=$aLang.common.add}
        {/if}
    </fieldset>

    {hook run='form_settings_profile_end'}

    {* Скрытые поля *}
    {include 'components/field/field.hidden.security_key.tpl'}

    {* Кнопки *}
    {include 'components/button/button.tpl' name='submit_profile_edit' mods='primary' text=$aLang.common.save}
</form>

{hook run='settings_profile_end'}