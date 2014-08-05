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
				<div class="status status-online">{$aLang.user.status.online}</div>
			{else}
				<div class="status status-offline">
					{$date = {date_format date=$oSession->getDateLast() hours_back="12" minutes_back="60" day_back="8" now="60*5" day="day H:i" format="j F в G:i"}}

					{if $oUserProfile->getProfileSex() != 'woman'}
						{lang name='user.status.was_online_male' date=$date}
					{else}
						{lang name='user.status.was_online_female' date=$date}
					{/if}
				</div>
			{/if}
		{/if}

		{* Фото *}
		<a href="{$oUserProfile->getUserWebPath()}">
			<img src="{$oUserProfile->getProfileFotoPath()}" alt="{$oUserProfile->getDisplayName()} photo" class="profile-photo js-ajax-user-photo-image" />
		</a>
	</div>

	{if $oUserProfile->isAllowEdit()}
		<p class="upload-photo">
			<label for="photo" class="form-input-file">
                <span class="js-ajax-user-photo-upload-choose link-dotted">{if $oUserProfile->getProfileFoto()}{$aLang.settings_profile_photo_change}{else}{$aLang.settings_profile_photo_upload}{/if}</span>
                <input type="file" name="photo" id="photo" class="js-ajax-user-photo-upload" data-user-id="{$oUserProfile->getId()}">
            </label>
            &nbsp;&nbsp;&nbsp;
			<a href="#" data-user-id="{$oUserProfile->getId()}" class="js-ajax-user-avatar-change link-dotted" style="{if !$oUserProfile->getProfileFoto()}display:none;{/if}">{$aLang.settings_profile_avatar_change}</a>
			<a href="#" data-user-id="{$oUserProfile->getId()}" class="js-ajax-user-photo-upload-remove link-dotted" style="{if !$oUserProfile->getProfileFoto()}display:none;{/if}">{$aLang.settings_profile_foto_delete}</a>
		</p>
	{/if}
{/block}