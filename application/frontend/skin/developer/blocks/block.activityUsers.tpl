{**
 * Выбор пользователей для чтения в ленте активности
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.activity.users.title}{/block}
{block 'block_type'}activity-users{/block}

{block 'block_content'}
	{include 'components/activity/users.tpl' users=$users}
{/block}