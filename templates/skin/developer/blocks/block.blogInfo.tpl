{**
 * Информация о блоге показываемая при создании топика
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='options'}
	{assign var='noFooter' value=true}
	{assign var='noNav' value=true}
{/block}

{block name='title'}{$aLang.block_blog_info}{/block}
{block name='content'}<p id="block_blog_info" class="text"></p>{/block}