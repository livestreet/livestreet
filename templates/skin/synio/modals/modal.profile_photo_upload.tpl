{extends file='modals/modal_base.tpl'}

{block name='options'}
	{assign var='noCancel' value=true}
{/block}

{block name='id'}foto-resize{/block}
{block name='class'}modal-upload-photo js-modal-default{/block}
{block name='title'}{$aLang.uploadimg}{/block}

{block name='content'}
	<div class="clearfix">
		<div class="image-border">
			<img src="" alt="" id="foto-resize-original-img">
		</div>
	</div>
{/block}

{block name='footer'}
	<button type="submit" class="button button-primary" onclick="return ls.user.resizeFoto();">{$aLang.settings_profile_avatar_resize_apply}</button>
	<button type="submit" class="button" onclick="return ls.user.cancelFoto();">{$aLang.settings_profile_avatar_resize_cancel}</button>
{/block}