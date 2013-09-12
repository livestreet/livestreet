{**
 * Ресайз загруженного изображения
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-image-crop{/block}
{block name='modal_class'}modal-image-crop js-modal-default{/block}
{block name='modal_title'}{$aLang.uploadimg}{/block}

{block name='modal_content'}
	<img src="" alt="" class="js-image-crop">
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary js-ajax-image-upload-crop-submit">{$aLang.settings_profile_avatar_resize_apply}</button>
	<button type="submit" class="button js-ajax-image-upload-crop-cancel">{$aLang.settings_profile_avatar_resize_cancel}</button>
{/block}

{block name='modal_footer_cancel'}{/block}