{**
 * Удаление блога
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-blog-delete{/block}
{block name='modal_class'}modal-blog-delete js-modal-default{/block}
{block name='modal_title'}{$aLang.blog_admin_delete_title}{/block}

{block name='modal_content'}
	<form action="{router page='blog'}delete/{$oBlog->getId()}/" method="POST" id="js-blog-delete-form">
		{* Переместить топики в блог *}
		{$aBlogsCustom = [
			[ 'value' => -1, 'text' => $aLang.blog_delete_clear ]
		]}

		{foreach $aBlogs as $oBlog}
			{$aBlogsCustom[] = [
				'value' => $oBlog->getId(),
				'text' => $oBlog->getTitle()|escape:'html'
			]}
		{/foreach}

		{include file='forms/fields/form.field.select.tpl'
				 sFieldName          = 'topic_move_to'
				 sFieldLabel         = $aLang.blog_admin_delete_move
				 aFieldItems         = $aBlogsCustom}


		{* Скрытые поля *}
		{include file='forms/fields/form.field.hidden.security_key.tpl'}
	</form>
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary" onclick="jQuery('#js-blog-delete-form').submit()">{$aLang.blog_delete}</button>
{/block}