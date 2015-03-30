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
        {component 'field' template='text'
            name   = 'profile_name'
            rules  = [ 'length' => "[2,{Config::Get('module.user.name_max')}]" ]
            value  = $user->getProfileName()|escape
            label  = {lang name='user.settings.profile.fields.name.label'}}


        {* Пол *}
        {$sex = [
            [ 'value' => 'man',   'text' => {lang name='user.gender.male'} ],
            [ 'value' => 'woman', 'text' => {lang name='user.gender.female'} ],
            [ 'value' => 'other', 'text' => {lang name='user.gender.none'} ]
        ]}

        {component 'field' template='select'
            name          = 'profile_sex'
            label         = {lang name='user.settings.profile.fields.sex.label'}
            items         = $sex
            selectedValue = $user->getProfileSex()}


        {* Дата рождения *}
        {component 'field' template='date'
            name         = 'profile_birthday'
            inputClasses = 'js-field-date-default'
            label        = {lang name='user.settings.profile.fields.birthday.label'}}


        {* Местоположение *}
        {component 'field' template='geo'
            classes   = 'js-field-geo-default'
            name      = 'geo'
            label     = {lang name='user.settings.profile.fields.place.label'}
            countries = $aGeoCountries
            regions   = $aGeoRegions
            cities    = $aGeoCities
            place     = $oGeoTarget}


        {* О себе *}
        {component 'field' template='textarea'
            name   = 'profile_about'
            rules  = [ 'length' => '[1,3000]' ]
            rows   = 5
            value  = $user->getProfileAbout()|escape
            label  = {lang name='user.settings.profile.fields.about.label'}}


        {* Пользовательские поля *}
        {$userfields = $user->getUserFieldValues(false, '')}

        {foreach $userfields as $field}
            {component 'field' template='text'
                name   = "profile_user_field_`$field->getId()`"
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
                {component 'alert' mods='empty' classes='js-user-fields-empty' text=$aLang.common.empty}
            {/foreach}
        </div>

        {if $aUserFieldsContact}
            {component 'button' type='button' classes='js-user-fields-submit' text=$aLang.common.add}
        {/if}
    </fieldset>

    {hook run='form_settings_profile_end'}

    {* Скрытые поля *}
    {component 'field' template='hidden.security-key'}

    {* Кнопки *}
    {component 'button' name='submit_profile_edit' mods='primary' text=$aLang.common.save}
</form>

{hook run='settings_profile_end'}