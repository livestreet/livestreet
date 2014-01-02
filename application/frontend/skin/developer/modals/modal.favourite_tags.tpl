{**
 * Добавление пользовательских тегов к топику
 *
 * @styles css/modals.css
 * @scripts <common>/js/tags.js
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}favourite-form-tags{/block}
{block name='modal_class'}modal-favourite-tags js-modal-default{/block}
{block name='modal_title'}{$aLang.add_favourite_tags}{/block}

{block name='modal_content'}
	<form id="js-favourite-form">
		{include file='forms/fields/form.field.text.tpl'
				 sFieldName        = 'tags'
				 bFieldNoMargin    = true
				 bFieldIsAutofocus = true
				 sFieldClasses     = 'width-full autocomplete-tags-sep js-tags-form-input-list'}
	</form>
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary js-tags-form-submit" data-button-submit-form="js-favourite-form">{$aLang.favourite_form_tags_button_save}</button>
{/block}