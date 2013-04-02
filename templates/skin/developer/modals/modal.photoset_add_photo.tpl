{extends file='modals/modal_base.tpl'}

{block name='id'}photoset-upload-form{/block}
{block name='class'}js-modal-default{/block}
{block name='title'}{$aLang.uploadimg}{/block}

{block name='content'}
	<div id="topic-photo-upload-input" class="topic-photo-upload-input">
		<label for="photoset-upload-file">{$aLang.topic_photoset_choose_image}:</label>
		<input type="file" id="photoset-upload-file" name="Filedata" /><br><br>

		<input type="hidden" name="is_iframe" value="true" />
		<input type="hidden" name="topic_id" value="{$_aRequest.topic_id}" />
	</div>
{/block}

{block name='footer'}
	<button type="submit" class="button button-primary" onclick="ls.photoset.upload();">{$aLang.topic_photoset_upload_choose}</button>
{/block}