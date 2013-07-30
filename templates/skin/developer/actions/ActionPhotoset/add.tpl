{**
 * Создание топика-фотосета
 *
 * @styles css/topic.css
 *}

{extends file='forms/form.add.topic.base.tpl'}


{block name='add_topic_type'}photoset{/block}

{block name='add_topic_form_text_after'}
	<script type="text/javascript">
		jQuery(function($){
			if (jQuery.browser.flash) {
				ls.photoset.initSwfUpload({
					post_params: { 'topic_id': {json var=$_aRequest.topic_id} }
				});
			}
		});
	</script>


	<div class="fieldset photoset-upload">
		<header class="fieldset-header">
			<h2 class="fieldset-title">{$aLang.topic_photoset_upload_title}</h2>

			<div class="note fieldset-note">
				{$aLang.topic_photoset_upload_rules|ls_lang:"SIZE%%`$oConfig->get('module.topic.photoset.photo_max_size')`":"COUNT%%`$oConfig->get('module.topic.photoset.count_photos_max')`"}
			</div>
		</header>

		<ul class="fieldset-body photoset-upload-images" id="swfu_images">
			{if count($aPhotos)}
				{foreach $aPhotos as $oPhoto}
					{if $_aRequest.topic_main_photo && $_aRequest.topic_main_photo == $oPhoto->getId()}
						{$bIsMainPhoto = true}
					{/if}

					<li id="photo_{$oPhoto->getId()}" class="photoset-upload-images-item {if $bIsMainPhoto}marked-as-preview{/if}">
						<img src="{$oPhoto->getWebPath('100crop')}" alt="image" />
						<textarea onBlur="ls.photoset.setPreviewDescription({$oPhoto->getId()}, this.value)" class="width-full">{$oPhoto->getDescription()}</textarea><br />
						<a href="javascript:ls.photoset.deletePhoto({$oPhoto->getId()})" class="link-dotted ">{$aLang.topic_photoset_photo_delete}</a>
						<span id="photo_preview_state_{$oPhoto->getId()}" class="photo-preview-state">
							{if $bIsMainPhoto}
								{$aLang.topic_photoset_is_preview}
							{else}
								<a href="javascript:ls.photoset.setPreview({$oPhoto->getId()})" class="link-dotted mark-as-preview">{$aLang.topic_photoset_mark_as_preview}</a>
							{/if}
						</span>
					</li>

					{$bIsMainPhoto = false}
				{/foreach}
			{/if}
		</ul>

		<footer class="fieldset-footer">
			{include file='forms/form.field.hidden.tpl' sFieldName='topic_main_photo' value=$_aRequest.topic_main_photo}

			<label class="form-input-file">
				<span class="button">{$aLang.topic_photoset_upload_choose}</span>
				<input type="file" name="Filedata" id="js-photoset-image-upload">
			</label>
		</footer>
	</div>
{/block}