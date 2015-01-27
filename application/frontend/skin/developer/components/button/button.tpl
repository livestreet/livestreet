{**
 * Кнопка
 *
 * @param type       string  (submit)   Тип кнопки (submit, reset, button)
 * @param text       string  (null)     Текст кнопки
 * @param url        string  (null)     Ссылка
 * @param id         string  (null)     Атрибут id
 * @param name       string  (null)     Атрибут name
 * @param isDisabled boolean (false)    Атрибут disabled
 * @param form       string  (null)     Селектор формы для сабмита
 * @param icon       string  (null)     Класс иконки
 * @param classes    string  (null)     Дополнительные классы (указываются через пробел)
 * @param mods       string  (null)     Список классов-модификаторов (указываются через пробел)
 * @param attributes array   (null)     Атрибуты
 *}

{* Название компонента *}
{$component = 'button'}

{$mods = $smarty.local.mods}
{$icon = $smarty.local.icon}

{if $icon && ! $text}
    {$mods = "$mods icon"}
{/if}

{* Если указана ссылка url то заменяем тег <button> на <a> *}
<{( $smarty.local.url ) ? 'a' : 'button'}
        {if ! $smarty.local.url}
            type="{$smarty.local.type|default:'submit'}"
            value="{if $smarty.local.value}{$smarty.local.value}{elseif isset( $_aRequest[ $smarty.local.name ] )}{$_aRequest[ $smarty.local.name ]}{/if}"
            {if $smarty.local.isDisabled}disabled{/if}
            {if $smarty.local.form}form="{$smarty.local.form}"{/if}
        {else}
            href="{$smarty.local.url}"
            role="button"
        {/if}
        {if $smarty.local.id}id="{$smarty.local.id}"{/if}
        {if $smarty.local.name}name="{$smarty.local.name}"{/if}
        class="{$component} {cmods name=$component mods=$mods} {$smarty.local.classes}"
        {cattr list=$smarty.local.attributes}>
    {* Иконка *}
    {if $icon}
        {component 'icon' icon=$icon attributes=[ 'aria-hidden' => 'true' ]}
    {/if}

    {* Текст *}
    {$smarty.local.text}
</{($smarty.local.url) ? 'a' : 'button'}>