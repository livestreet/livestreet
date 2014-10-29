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
        name            = 'is_online'
        inputClasses    = 'js-search-ajax-option'
        inputAttributes = 'data-search-type="users"'
        checked         = false
        label           = 'Сейчас на сайте'}

    {* Пол *}
    <p class="mb-10">Пол</p>
    {include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-option' inputAttributes='data-search-type="users"' name='sex' value='' checked=true label='Любой'}
    {include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-option' inputAttributes='data-search-type="users"' name='sex' value='man' label='Мужской'}
    {include 'components/field/field.radio.tpl' inputClasses='js-search-ajax-option' inputAttributes='data-search-type="users"' name='sex' value='woman' label='Женский'}
{/block}