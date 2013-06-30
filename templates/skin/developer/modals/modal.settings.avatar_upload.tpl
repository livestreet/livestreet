{**
 * Загрузка аватара пользователя
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}avatar-resize{/block}
{block name='modal_class'}modal-avatar-resize js-modal-default{/block}
{block name='modal_title'}{$aLang.uploadimg}{/block}

{block name='modal_content'}
	<img src="" alt="" id="avatar-resize-original-img">
{/block}

{block name='modal_footer_begin'}
	<button type="submit" class="button button-primary" onclick="return ls.user.resizeAvatar();">{$aLang.settings_profile_avatar_resize_apply}</button>
	<button type="submit" class="button" onclick="return ls.user.cancelAvatar();">{$aLang.settings_profile_avatar_resize_cancel}</button>
{/block}

{block name='modal_footer_cancel'}{/block}