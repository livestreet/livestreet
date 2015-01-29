{**
 * Блок с навигацией по профилю пользователя
 *
 * @styles css/blocks.css
 *}

{extends 'Component@block.block'}

{block 'block_options' append}
	{$mods = "{$mods} nopadding transparent user-nav"}
{/block}

{block 'block_content_after'}
	{include 'navs/nav.user.tpl'}
{/block}