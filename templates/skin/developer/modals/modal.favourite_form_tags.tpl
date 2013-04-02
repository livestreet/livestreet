{extends file='modals/modal_base.tpl'}

{block name='id'}favourite-form-tags{/block}
{block name='class'}modal-favourite-tags js-modal-default{/block}
{block name='title'}{$aLang.add_favourite_tags}{/block}

{block name='content'}
	<form onsubmit="return ls.favourite.saveTags(this);" id="js-favourite-form">
		<input type="hidden" name="target_type" value="" id="favourite-form-tags-target-type">
		<input type="hidden" name="target_id" value="" id="favourite-form-tags-target-id">

		<input type="text" name="tags" value="" id="favourite-form-tags-tags" class="autocomplete-tags-sep input-text input-width-full">
	</form>
{/block}

{block name='footer'}
	<button type="submit" class="button button-primary" onclick="jQuery('#js-favourite-form').submit()" />{$aLang.favourite_form_tags_button_save}</button>
{/block}