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
		{* Статус онлайн\оффлайн *}
		{if $oSession}
			{if $oUserProfile->isOnline() &&  $smarty.now - strtotime($oSession->getDateLast()) < 60*5}
				<div class="status status-online">{$aLang.user_status_online}</div>
			{else}
				<div class="status status-offline">

				{if $oUserProfile->getProfileSex() != 'woman'}
					{$aLang.user_status_was_online_male}
				{else}
					{$aLang.user_status_was_online_female}
				{/if}
				 
				{date_format date=$oSession->getDateLast() hours_back="12" minutes_back="60" day_back="8" now="60*5" day="day H:i" format="j F в G:i"}</div>
			{/if}
		{/if}

		{* Фото *}
		<a href="{$oUserProfile->getUserWebPath()}">
			<img src="{$oUserProfile->getProfileFotoPath()}" alt="{$oUserProfile->getDisplayName()} photo" class="profile-photo js-ajax-image-upload-image" />
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