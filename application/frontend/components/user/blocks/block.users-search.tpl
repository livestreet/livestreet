{**
 * Статистика по пользователям
 *}

{capture 'block_content'}
    {* Сейчас на сайте *}
    {component 'field' template='checkbox'
        name         = 'is_online'
        inputClasses = 'js-search-ajax-user-online'
        checked      = false
        label        = 'Сейчас на сайте'}

    {* Пол *}
    <p class="mb-10">Пол</p>
    <div class="field-checkbox-group">
        {component 'field' template='radio' inputClasses='js-search-ajax-user-sex' name='sex' value='' checked=true label='Любой'}
        {component 'field' template='radio' inputClasses='js-search-ajax-user-sex' name='sex' value='man' label='Мужской'}
        {component 'field' template='radio' inputClasses='js-search-ajax-user-sex' name='sex' value='woman' label='Женский'}
    </div>

    {* Страна/город *}
    {component 'field' template='geo'
        classes    = 'js-field-geo-default'
        targetType = 'user'
        countries  = $countriesUsed
        name       = 'geo'
        label      = {lang name='user.settings.profile.fields.place.label'} }
{/capture}

{component 'block'
    mods     = 'users-search'
    title    = {lang 'user.search.title'}
    content  = $smarty.capture.block_content}