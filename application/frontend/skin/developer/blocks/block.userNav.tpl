{**
 * Блок с навигацией по профилю пользователя
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_type'}profile-nav{/block}

{block 'block_content_after'}
	{include 'navs/nav.user.tpl'}
{/block}