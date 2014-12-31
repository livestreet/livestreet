{**
 * Выпадающий список
 *}

{extends './field.tpl'}

{block 'field_input'}
    {function field_select_loop items=[]}
        {foreach $items as $item}
            {if is_array( $item.value )}
                <optgroup label="{$item.text}">
                    {field_select_loop items=$item.value}
                </optgroup>
            {else}
                {$isSelected = ( is_array( $selectedValue ) ) ? in_array( $item.value, $selectedValue ) : ( $item.value == $selectedValue )}

                <option value="{$item.value}" {if $isSelected}selected{/if} {cattr list=$item.attributes}>
                    {$item.text|indent:( $item.level * 5 ):'&nbsp;'}
                </option>
            {/if}
        {/foreach}
    {/function}

    {* data-placeholder нужен для плагина chosen *}
    <select {field_input_attr_common useValue=false} {if $smarty.local.placeholder}data-placeholder="{$smarty.local.placeholder}"{/if} {if $smarty.local.isMultiple}multiple{/if}>
        {field_select_loop items=$smarty.local.items}
    </select>
{/block}