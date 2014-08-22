{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
	{$mods = "{$mods} users-stats"}
{/block}

{block 'block_title'}
	{$aLang.user.stats.title}
{/block}

{block 'block_content'}
	{include 'components/user/stat.tpl' stat=$aStat}
{/block}