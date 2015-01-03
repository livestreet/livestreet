{**
 * Статистика по пользователям
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
	{$mods = "{$mods} users-stats"}
{/block}

{block 'block_title'}
	{$aLang.user.stats.title}
{/block}

{block 'block_content'}
	{component 'user' template='stat' stat=$usersStat}
{/block}