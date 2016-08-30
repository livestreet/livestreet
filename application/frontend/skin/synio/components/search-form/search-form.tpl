{**
 * Форма поиска
 *}

{* Название компонента *}
{$component = 'ls-search-form'}
{component_define_params params=[ 'action', 'method', 'placeholder', 'placeholder', 'note', 'value', 'inputClasses', 'inputAttributes', 'inputName', 'noSubmitButton', 'mods', 'classes', 'attributes' ]}

<form action="{$action}" method="{$method|default:'get'}" class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
    {block 'search_form'}
        <div class="{$component}-input-wrapper">
            {component 'field' template='text'
                placeholder  = ( $placeholder ) ? $placeholder : $aLang.search.search
                note         = $note
                value        = $value
                inputClasses = "{$component}-input {$inputClasses}"
                inputAttributes   = $inputAttributes
                name         = $inputName|default:'q'}

            {if ! $noSubmitButton}
                <button class="{$component}-submit">
                    {component 'syn-icon' icon='submit'}
                </button>
            {/if}
        </div>
    {/block}
</form>