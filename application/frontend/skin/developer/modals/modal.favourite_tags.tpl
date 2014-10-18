{**
 * Добавление пользовательских тегов к топику
 *
 * @styles css/modals.css
 * @scripts <common>/js/tags.js
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}favourite-form-tags{/block}
{block 'modal_class'}modal-favourite-tags js-modal-default{/block}
{block 'modal_title'}{lang 'favourite_tags.title'}{/block}

{block 'modal_content'}
	<form id="js-favourite-form">
		{include 'components/field/field.text.tpl'
				 name        = 'tags'
				 noMargin    = true
				 autofocus   = true
				 classes     = 'width-full autocomplete-tags-sep js-tags-form-input-list'}
	</form>
{/block}

{block 'modal_footer_begin'}
	{include 'components/button/button.tpl'
			 form    = 'js-favourite-form'
			 text    = $aLang.common.save
			 classes = 'js-tags-form-submit'
			 mods   = 'primary'}
{/block}