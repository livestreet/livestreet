{**
 * Список стран в которых проживают пользователи
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
    {lang 'user.blocks.countries.title'}
{/block}

{block 'block_content'}
    {include 'components/tags/tag-cloud.tpl'
        tags = $smarty.local.countries
        url  = '{router page=\'people\'}country/{$tag->getId()}/'
        text = '{$tag->getName()|escape}'}
{/block}