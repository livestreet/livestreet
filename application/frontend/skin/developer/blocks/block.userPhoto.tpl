{**
 * Блок с фотографией пользователя в профиле
 *
 * @styles css/blocks.css
 * @scripts <framework>/js/livestreet/user.js
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_type'}profile-photo{/block}
{block name='block_class'}js-ajax-photo-upload{/block}

{block name='block_content'}
	<div class="profile-photo-wrapper">
		<div class="status {if $oUserProfile->isOnline()}status-online{else}status-offline{/if}">{if $oUserProfile->isOnline()}{$aLang.user_status_online}{else}{$aLang.user_status_offline}{/if}</div>
		<a href="{$oUserProfile->getUserWebPath()}">
			<img src="{$oUserProfile->getProfileFotoPath()}" alt="{$oUserProfile->getLogin()} photo" class="profile-photo js-ajax-image-upload-image" />
		</a>
	</div>
	
	{if $sAction=='settings' and $oUserCurrent and $oUserCurrent->getId() == $oUserProfile->getId()}
		<p class="upload-photo">
			<label for="foto" class="form-input-file">
                <span class="js-ajax-image-upload-choose link-dotted">{if $oUserCurrent->getProfileFoto()}{$aLang.settings_profile_photo_change}{else}{$aLang.settings_profile_photo_upload}{/if}</span>
                <input type="file" name="foto" id="foto" class="js-ajax-image-upload-file">
            </label>
            &nbsp;&nbsp;&nbsp;
			<a href="#" class="js-ajax-image-upload-remove link-dotted" style="{if ! $oUserCurrent->getProfileFoto()}display:none;{/if}">{$aLang.settings_profile_foto_delete}</a>
		</p>
	{/if}
{/block}