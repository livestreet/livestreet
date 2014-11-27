{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
    {$mods = "{$mods} users-search"}
{/block}

{block 'block_title'}
    Поиск по пользователям
{/block}

{block 'block_content'}
    {* Сейчас на сайте *}
    {include 'components/field/field.checkbox.tpl'
        name         = 'is_online'
        inputClasses = 'js-search-ajax-user-online'
        checked      = false
        label        = 'Сейчас на сайте'}

    {* Пол *}
    <p class="mb-10">Пол</p>
    <div class="field-checkbox-group">
        {include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-user-sex' name='sex' value='' checked=true label='Любой'}
        {include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-user-sex' name='sex' value='man' label='Мужской'}
        {include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-user-sex' name='sex' value='woman' label='Женский'}
    </div>

    {* Страна/город *}
    {include 'components/field/field.geo.tpl'
        classes   = 'js-search-ajax-user-geo'
        countries = $aCountriesUsed
        name      = 'geo'
        label     = {lang name='user.settings.profile.fields.place.label'} }
{/block}