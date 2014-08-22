{**
 * Выбор пользователей для чтения в ленте активности
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}{$aLang.activity.users.title}{/block}

{block 'block_options' append}
	{$mods = "{$mods} activity-users"}
{/block}

{block 'block_content'}
	{include 'components/activity/users.tpl' users=$users}
{/block}