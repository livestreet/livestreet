{**
 * Загрузка фото пользователя
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}foto-resize{/block}
{block name='modal_class'}modal-photo-resize js-modal-default{/block}
{block name='modal_title'}{$aLang.uploadimg}{/block}

{block name='modal_content'}
	<img src="" alt="" id="foto-resize-original-img">
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary" onclick="return ls.user.resizeFoto();">{$aLang.settings_profile_avatar_resize_apply}</button>
	<button type="submit" class="button" onclick="return ls.user.cancelFoto();">{$aLang.settings_profile_avatar_resize_cancel}</button>
{/block}

{block name='modal_footer_cancel'}{/block}