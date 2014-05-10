{**
 * Удаление блога
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

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

		{include file='components/field/field.select.tpl'
				 sName  = 'topic_move_to'
				 sLabel = $aLang.blog.remove.move_to
				 aItems = $aBlogsCustom}


		{* Скрытые поля *}
		{include file='components/field/field.hidden.security_key.tpl'}
	</form>
{/block}

{block name='modal_footer_begin'}
	{include 'components/button/button.tpl' sForm='#js-blog-remove-form' sText=$aLang.common.remove sStyle='primary'}
{/block}