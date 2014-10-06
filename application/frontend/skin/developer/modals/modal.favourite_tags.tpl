{**
 * Добавление пользовательских тегов к топику
 *
 * @styles css/modals.css
 * @scripts <common>/js/tags.js
 *}

{extends 'components/modal/modal.tpl'}

{block name='modal_id'}favourite-form-tags{/block}
{block name='modal_class'}modal-favourite-tags js-modal-default{/block}
{block name='modal_title'}{lang 'favourite_tags.title'}{/block}

{block name='modal_content'}
	<form id="js-favourite-form">
		{include file='components/field/field.text.tpl'
				 sName        = 'tags'
				 bNoMargin    = true
				 bIsAutofocus = true
				 sClasses     = 'width-full autocomplete-tags-sep js-tags-form-input-list'}
	</form>
{/block}

{block name='modal_footer_begin'}
	{include 'components/button/button.tpl'
			 sForm    = '#js-favourite-form'
			 sText    = $aLang.common.save
			 sClasses = 'js-tags-form-submit'
			 sMods   = 'primary'}
{/block}