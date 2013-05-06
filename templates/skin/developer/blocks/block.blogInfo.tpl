{**
 * Информация о блоге показываемая при создании топика
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_options'}
	{assign var='noBlockFooter' value=true}
	{assign var='noBlockNav' value=true}
{/block}

{block name='block_title'}{$aLang.block_blog_info}{/block}
{block name='block_content'}<p id="block_blog_info" class="text"></p>{/block}