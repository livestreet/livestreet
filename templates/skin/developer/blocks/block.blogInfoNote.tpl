{**
 * Подсказка отображаемая при создании топика
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='options'}
	{assign var='noFooter' value=true}
	{assign var='noNav' value=true}
{/block}

{block name='title'}{$aLang.block_blog_info_note}{/block}
{block name='content'}{$aLang.block_blog_info_note_text}{/block}