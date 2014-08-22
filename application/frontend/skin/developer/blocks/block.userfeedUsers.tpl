{**
 * Выбор пользователей для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
	{$mods = "{$mods} feed-users"}
{/block}

{block 'block_title'}
	{$aLang.feed.users.title}
{/block}

{block 'block_content'}
	{include 'components/feed/users.tpl' users=$users}
{/block}