{**
 * Выбор пользователей для чтения в ленте
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.feed.users.title}{/block}
{block 'block_type'}feed-users{/block}

{block 'block_content'}
	{include 'components/feed/users.tpl' users=$users}
{/block}