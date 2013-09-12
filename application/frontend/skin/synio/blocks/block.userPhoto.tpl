{**
 * Блок с фотографией пользователя в профиле
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_type'}profile-photo{/block}

{block name='block_content_after'}
	<div class="profile-photo-wrapper">
		<div class="status {if $oUserProfile->isOnline()}status-online{else}status-offline{/if}">{if $oUserProfile->isOnline()}{$aLang.user_status_online}{else}{$aLang.user_status_offline}{/if}</div>
		<a href="{$oUserProfile->getUserWebPath()}"><img src="{$oUserProfile->getProfileFotoPath()}" alt="photo" class="profile-photo" id="foto-img" /></a>
	</div>
	
	{if $sAction=='settings' and $oUserCurrent and $oUserCurrent->getId() == $oUserProfile->getId()}
		<script type="text/javascript">
			jQuery(function($){
				$('#foto-upload').file({ name:'foto' }).choose(function(e, input) {
					ls.user.uploadFoto(null,input);
				});
			});
		</script>
		
		<p class="upload-photo">
			<a href="#" id="foto-upload" class="link-dotted">{if $oUserCurrent->getProfileFoto()}{$aLang.settings_profile_photo_change}{else}{$aLang.settings_profile_photo_upload}{/if}</a>&nbsp;&nbsp;&nbsp;
			<a href="#" id="foto-remove" class="link-dotted" onclick="return ls.user.removeFoto();" style="{if !$oUserCurrent->getProfileFoto()}display:none;{/if}">{$aLang.settings_profile_foto_delete}</a>
		</p>
	{/if}
{/block}