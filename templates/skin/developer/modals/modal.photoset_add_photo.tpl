{extends file='modals/modal_base.tpl'}

{block name='id'}modal-photoset-upload{/block}
{block name='class'}js-modal-default{/block}
{block name='title'}{$aLang.uploadimg}{/block}

{block name='content'}
	<form id="photoset-upload-form" method="POST" enctype="multipart/form-data" onsubmit="return false;">
		<label>{$aLang.topic_photoset_choose_image}:</label>
		<input type="file" id="photoset-upload-file" name="Filedata" />
		
		<input type="hidden" name="is_iframe" value="true" />
		<input type="hidden" name="topic_id" value="{$_aRequest.topic_id}" />
	</form>
{/block}

{block name='footer'}
	<button type="submit" class="button button-primary" onclick="ls.photoset.upload();">{$aLang.topic_photoset_upload_choose}</button>
{/block}