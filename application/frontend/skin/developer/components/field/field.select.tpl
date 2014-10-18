{**
 * Выпадающий список
 *}

{extends './field.tpl'}

{block 'field_input'}
    <select {field_input_attr_common useValue=false} {if $smarty.local.isMultiple}multiple{/if}>
        {foreach $smarty.local.items as $item}
			{$isSelected = ( is_array( $selectedValue ) ) ? in_array( $item.value, $selectedValue ) : ( $item.value == $selectedValue )}

            <option value="{$item.value}" {if $isSelected}selected{/if}>
            	{$item.text|indent:( $item.level * 5 ):'&nbsp;'}
            </option>
        {/foreach}
    </select>
{/block}