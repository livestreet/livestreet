{**
 * Выпадающий список
 *}

{extends './field.tpl'}

{block 'field_input'}
    <select {field_input_attr_common bUseValue=false} {if $smarty.local.isMultiple}multiple{/if}>
        {foreach $aItems as $aItem}
			{$isSelected = ( is_array( $sSelectedValue ) ) ? in_array( $aItem.value, $sSelectedValue ) : ( $aItem.value == $sSelectedValue )}

            <option value="{$aItem.value}" {if $isSelected}selected{/if}>
            	{$aItem.text|indent:( $aItem.level * 5 ):'&nbsp;'}
            </option>
        {/foreach}
    </select>
{/block}