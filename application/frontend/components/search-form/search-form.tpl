{**
 * Форма поиска
 *}

{* Название компонента *}
{$component = 'ls-search-form'}
{component_define_params params=[ 'action', 'method', 'placeholder', 'placeholder', 'note', 'value', 'inputClasses', 'inputAttributes', 'inputName', 'noSubmitButton', 'mods', 'classes', 'attributes' ]}

<form action="{$action}" method="{$method|default:'get'}" class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
    {block 'search_form'}
        {component 'field' template='text'
            placeholder  = ( $placeholder ) ? $placeholder : $aLang.search.search
            note         = $note
            value        = $value
            inputClasses = "{$component}-input {$inputClasses}"
            inputAttributes   = $inputAttributes
            name         = $inputName|default:'q'}

        {if ! $noSubmitButton}
            {component 'button' mods='icon' classes="{$component}-submit" icon='search'}
        {/if}
    {/block}
</form>