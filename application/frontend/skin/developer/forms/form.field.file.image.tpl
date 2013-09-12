{**
 * Выбор изображения для аякс загрузки
 *
 * @scripts <framework>/js/livestreet/user.js
 *}

{extends file='forms/form.field.base.tpl'}

{block name='field_holder' prepend}
	<div class="js-ajax-{$sFieldName}-upload {$sFieldClasses}">
		<img src="{$sFieldImagePath}" class="js-ajax-image-upload-image" />

		<div>
			<label for="{$sFieldName}" class="form-input-file">
				{strip}
					<span class="link-dotted js-ajax-image-upload-choose">
						{if $oUserCurrent->getProfileAvatar()}
							{$aLang.settings_profile_avatar_change}
						{else}
							{$aLang.settings_profile_avatar_upload}
						{/if}
					</span>
				{/strip}

				<input type="file" name="{$sFieldName}" id="{$sFieldName}" class="js-ajax-image-upload-file">
			</label>

			<a href="#" class="js-ajax-image-upload-remove link-dotted" {if ! $bFieldIsImage}style="display: none;"{/if}>
				{$aLang.settings_profile_avatar_delete}
			</a>
		</div>
	</div>
{/block}