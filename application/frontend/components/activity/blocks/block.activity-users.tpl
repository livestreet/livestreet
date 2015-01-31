{**
 * Выбор пользователей для чтения в ленте активности
 *}

{extends 'Component@block.block'}

{block 'block_title'}
    {$aLang.activity.users.title}
{/block}

{block 'block_options' append}
	{$mods = "{$mods} activity-users"}
{/block}

{block 'block_content'}
	{include '../users.tpl' users=$users}
{/block}