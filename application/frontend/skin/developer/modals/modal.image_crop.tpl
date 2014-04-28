{**
 * Ресайз загруженного изображения
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block name='modal_id'}modal-image-crop{/block}
{block name='modal_class'}modal-image-crop js-modal-default{/block}
{block name='modal_title'}{$aLang.uploadimg}{/block}

{block name='modal_content'}
	<img src="{$sImageSrc|escape:'html'}" alt="" class="js-image-crop">
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary js-ajax-image-crop-submit">{$aLang.common.save}</button>
{/block}