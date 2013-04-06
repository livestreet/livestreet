{extends file='modals/modal_base.tpl'}

{block name='options'}
	{assign var='noCancel' value=true}
{/block}

{block name='id'}avatar-resize{/block}
{block name='class'}modal-upload-avatar js-modal-default{/block}
{block name='title'}{$aLang.uploadimg}{/block}

{block name='content'}
	<div class="clearfix">
		<div class="image-border">
			<img src="" alt="" id="avatar-resize-original-img">
		</div>
	</div>
{/block}

{block name='footer'}
	<button type="submit" class="button button-primary" onclick="return ls.user.resizeAvatar();">{$aLang.settings_profile_avatar_resize_apply}</button>
	<button type="submit" class="button" onclick="return ls.user.cancelAvatar();">{$aLang.settings_profile_avatar_resize_cancel}</button>
{/block}