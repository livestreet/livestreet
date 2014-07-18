{**
 * Блок настройки ленты активности
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.activity.settings.title}{/block}
{block 'block_type'}activity{/block}

{block 'block_content'}
	{include 'components/activity/settings.tpl' typesActive=$typesActive types=$types}
{/block}