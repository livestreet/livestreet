{**
 * Добавление пользовательских тегов к топику
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}favourite-form-tags{/block}
{block name='modal_class'}modal-favourite-tags js-modal-default{/block}
{block name='modal_title'}{$aLang.add_favourite_tags}{/block}

{block name='modal_content'}
	<form onsubmit="return ls.favourite.saveTags(this);" id="js-favourite-form">
		{include file='forms/form.field.text.tpl'
				 sFieldName     = 'tags'
				 bFieldNoMargin = true
				 sFieldClasses  = 'width-full autocomplete-tags-sep js-form-favourite-tags-list'}
	</form>
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary js-favourite-form-submit" onclick="jQuery('#js-favourite-form').submit()">{$aLang.favourite_form_tags_button_save}</button>
{/block}