{**
 * Базовый шаблон модальных окон
 *
 * Доступные опции:
 *     noModalHeader (bool)  - Не выводить шапку
 *     noModalTitle (bool)   - Не выводить заголовок
 *     noModalContent (bool) - Не выводить контент
 *     noModalFooter (bool)  - Не выводить подвал
 *     noModalCancel (bool)  - Не выводить кнопку "Отмена"
 *}

{block name='modal_options'}{/block}


<div class="modal {block name='modal_class'}{/block}" id="{block name='modal_id'}{/block}" data-type="modal" {block name='modal_attributes'}{/block}>
	{* Header *}
	{if !$noModalHeader}
		<header class="modal-header">
			{if !$noModalTitle}<h3>{block name='modal_title'}Modal window{/block}</h3>{/if}
			<a href="#" class="modal-close" data-type="modal-close"></a>
		</header>
	{/if}
	
	{block name='modal_header_after'}{/block}
	
	{* Content *}
	{if !$noModalContent}
		<div class="modal-content">
			{block name='modal_content'}{/block}
		</div>
	{/if}
	
	{block name='modal_content_after'}{/block}

	{* Footer *}
	{if !$noModalFooter}
		<div class="modal-footer">
			{block name='modal_footer'}{/block}
			
			{if !$noModalCancel}
				<button type="button" class="button" data-type="modal-close">{$aLang.favourite_form_tags_button_cancel}</button>
			{/if}
		</div>	
	{/if}
	
	{block name='modal_footer_after'}{/block}
</div>