{**
 * Блок с навигацией по профилю пользователя
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_type'}profile-nav{/block}

{block name='block_content_after'}
	{include 'navs/nav.user.tpl'}
{/block}