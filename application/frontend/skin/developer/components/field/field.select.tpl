{**
 * Выпадающий список
 *}

{extends './field.tpl'}

{block 'field_input'}
    <select {field_input_attr_common}>
        {foreach $aItems as $aItem}
            <option value="{$aItem.value}" {if $aItem.value == $sSelectedValue}selected{/if}>{$aItem.text}</option>
        {/foreach}
    </select>
{/block}