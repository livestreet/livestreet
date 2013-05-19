{**
 * Информация о блоге показываемая при создании топика
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.block_blog_info}{/block}
{block name='block_content'}
	{* Загрузка описания блога *}
	<script>
		jQuery(document).ready(function($){
			ls.blog.loadInfo($('#blog_id').val());
		});
	</script>
	
	<p id="block_blog_info" class="text"></p>
{/block}