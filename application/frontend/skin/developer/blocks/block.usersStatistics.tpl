{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}
	{$aLang.user.stats.title}
{/block}

{block 'block_content'}
	{include 'components/user/stat.tpl' stat=$aStat}
{/block}