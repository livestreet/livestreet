{**
 * 
 *}

{extends 'components/block/block.tpl'}

{block 'block_type'}blog-actions{/block}

{block 'block_options' append}
    {$mods = "{$mods} blog-actions"}
{/block}

{block 'block_content'}
    <a href="{$oBlog->getUrlFull()}">
        <img src="{$oBlog->getAvatarPath(500)}" alt="{$oBlog->getTitle()|escape}" class="avatar" />
    </a>
{/block}