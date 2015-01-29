{**
 * Статистика по пользователям
 *}

{extends 'Component@block.block'}

{block 'block_options' append}
	{$mods = "{$mods} users-stats"}
{/block}

{block 'block_title'}
	{$aLang.user.stats.title}
{/block}

{block 'block_content'}
	{component 'user' template='stat' stat=$usersStat}
{/block}