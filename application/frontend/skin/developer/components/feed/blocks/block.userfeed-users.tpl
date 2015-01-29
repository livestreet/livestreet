{**
 * Выбор пользователей для чтения в ленте
 *}

{extends 'Component@block.block'}

{block 'block_options' append}
	{$mods = "{$mods} feed-users"}
{/block}

{block 'block_title'}
	{$aLang.feed.users.title}
{/block}

{block 'block_content'}
	{include '../users.tpl' users=$users}
{/block}