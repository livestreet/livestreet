{**
 * Уведомления о смене емэйла
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {$layoutShowSystemMessages = false}
{/block}

{block 'layout_content'}
    {$sText}
{/block}