{**
 * Удаление блога
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-blog-delete{/block}
{block name='modal_class'}modal-blog-delete js-modal-default{/block}
{block name='modal_title'}{$aLang.blog.remove.title}{/block}

{block name='modal_content'}
	<form action="{router page='blog'}delete/{$oBlog->getId()}/" method="POST" id="js-blog-remove-form">
		{* Переместить топики в блог *}
		{$aBlogsCustom = [
			[ 'value' => -1, 'text' => $aLang.blog.remove.remove_topics ]
		]}

		{foreach $aBlogs as $oBlog}
			{$aBlogsCustom[] = [
				'value' => $oBlog->getId(),
				'text' => $oBlog->getTitle()|escape
			]}
		{/foreach}

		{include file='forms/fields/form.field.select.tpl'
				 sFieldName  = 'topic_move_to'
				 sFieldLabel = $aLang.blog.remove.move_to
				 aFieldItems = $aBlogsCustom}


		{* Скрытые поля *}
		{include file='forms/fields/form.field.hidden.security_key.tpl'}
	</form>
{/block}

{block name='modal_footer_begin'}
	{include file='forms/fields/form.field.button.tpl'
			 sFieldAttributes = 'data-button-submit-form="js-blog-remove-form"'
			 sFieldText       = $aLang.common.remove
			 sFieldStyle      = 'primary'}
{/block}