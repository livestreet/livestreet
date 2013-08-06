{**
 * Добавление пользовательских тегов к топику
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}favourite-form-tags{/block}
{block name='modal_class'}favourite-form-tags js-modal-default{/block}
{block name='modal_title'}{$aLang.add_favourite_tags}{/block}

{block name='modal_content'}
	<form onsubmit="return ls.favourite.saveTags(this);" id="js-favourite-form">
		<input type="hidden" name="target_type" value="" id="favourite-form-tags-target-type">
		<input type="hidden" name="target_id" value="" id="favourite-form-tags-target-id">

		<input type="text" name="tags" value="" id="favourite-form-tags-tags" class="autocomplete-tags-sep input-text input-width-full">
	</form>
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary" onclick="jQuery('#js-favourite-form').submit()" />{$aLang.favourite_form_tags_button_save}</button>
{/block}