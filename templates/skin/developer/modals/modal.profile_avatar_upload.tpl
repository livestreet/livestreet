{extends file='modals/modal_base.tpl'}

{block name='options'}
	{assign var='noCancel' value=true}
{/block}

{block name='id'}avatar-resize{/block}
{block name='class'}modal-avatar-resize js-modal-default{/block}
{block name='title'}{$aLang.uploadimg}{/block}

{block name='content'}
	<img src="" alt="" id="avatar-resize-original-img">
{/block}

{block name='footer'}
	<button type="submit" class="button button-primary" onclick="return ls.user.resizeAvatar();">{$aLang.settings_profile_avatar_resize_apply}</button>
	<button type="submit" class="button" onclick="return ls.user.cancelAvatar();">{$aLang.settings_profile_avatar_resize_cancel}</button>
{/block}