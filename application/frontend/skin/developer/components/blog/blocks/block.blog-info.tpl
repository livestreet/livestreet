{**
 * Информация о блоге показываемая при создании топика
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
    {lang 'blog.blocks.info.title'}
{/block}

{block 'block_options' append}
    {$mods = "{$mods} blog-info"}
{/block}

{block 'block_content'}
    <p class="text js-blog-info"></p>
{/block}