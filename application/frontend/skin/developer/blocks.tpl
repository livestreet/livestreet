{**
 * Вывод блоков определенной группы
 *}

{get_blocks assign='aBlocksLoad'}

{if isset($aBlocksLoad.$group)}
    {foreach $aBlocksLoad.$group as $aBlock}
        {if $aBlock.type == 'block'}
            {insert name="block" block=$aBlock.name params=$aBlock.params}
        {/if}

        {if $aBlock.type == 'template'}
            {include $aBlock.name params=$aBlock.params}
        {/if}
    {/foreach}
{/if}