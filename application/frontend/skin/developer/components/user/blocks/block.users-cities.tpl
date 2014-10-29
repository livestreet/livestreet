{**
 * Список городов в которых проживают пользователи
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
    {lang 'user.blocks.cities.title'}
{/block}

{block 'block_content'}
    {include 'components/tags/tag-cloud.tpl'
        tags = $smarty.local.cities
        url  = '{router page=\'people\'}city/{$tag->getId()}/'
        text = '{$tag->getName()|escape}'}
{/block}