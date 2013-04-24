{if $sEvent=='add'}
	{include file='header.tpl' nav_content='create'}
{else}
	{include file='header.tpl'}
	<h2 class="page-header">{$aLang.topic_photoset_edit}</h2>
{/if}


{include file='editor.tpl'}
{include file='modals/modal.photoset_add_photo.tpl'}


<script type="text/javascript">
	jQuery(function($){
		if (jQuery.browser.flash) {
			ls.photoset.initSwfUpload({
				post_params: { 'topic_id': {json var=$_aRequest.topic_id} }
			});
		}
	});
</script>
	

{hook run='add_topic_photoset_begin'}


<form action="" method="POST" enctype="multipart/form-data" id="form-topic-add" class="wrapper-content">
	{hook run='form_add_topic_photoset_begin'}
	
	
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
	
	
	<p><label for="blog_id">{$aLang.topic_create_blog}</label>
	<select name="blog_id" id="blog_id" onChange="ls.blog.loadInfo(jQuery(this).val());" class="input-width-full">
		<option value="0">{$aLang.topic_create_blog_personal}</option>
		{foreach from=$aBlogsAllow item=oBlog}
			<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()|escape:'html'}</option>
		{/foreach}     					
	</select>
	<small class="note">{$aLang.topic_create_blog_notice}</small></p>
	
	
	<script type="text/javascript">
		jQuery(document).ready(function($){
			ls.blog.loadInfo($('#blog_id').val());
		});
	</script>
	
	
	<p><label for="topic_title">{$aLang.topic_create_title}:</label>
	<input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="input-text input-width-full" /><br />
	<small class="note">{$aLang.topic_create_title_notice}</small></p>

	
	<p><label for="topic_text">{$aLang.topic_create_text}:</label>
	<textarea name="topic_text" class="mce-editor markitup-editor input-width-full" id="topic_text" rows="20">{$_aRequest.topic_text}</textarea>
	{if !$oConfig->GetValue('view.tinymce')}
		{include file='tags_help.tpl' sTagsTargetId="topic_text"}
	{/if}
	</p>


	
	<div class="photoset-upload">
		<header>
			<h2>{$aLang.topic_photoset_upload_title}</h2>
			
			<div class="note">
				{$aLang.topic_photoset_upload_rules|ls_lang:"SIZE%%`$oConfig->get('module.topic.photoset.photo_max_size')`":"COUNT%%`$oConfig->get('module.topic.photoset.count_photos_max')`"}
			</div>

			<input type="hidden" name="topic_main_photo" id="topic_main_photo" value="{$_aRequest.topic_main_photo}" />
		</header>
		
		<ul class="photoset-upload-images" id="swfu_images">
			{if count($aPhotos)}
				{foreach from=$aPhotos item=oPhoto}
					{if $_aRequest.topic_main_photo && $_aRequest.topic_main_photo == $oPhoto->getId()}
						{assign var=bIsMainPhoto value=true}
					{/if}
					
					<li id="photo_{$oPhoto->getId()}" {if $bIsMainPhoto}class="marked-as-preview"{/if}>
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
					
					{assign var=bIsMainPhoto value=false}
				{/foreach}
			{/if}
		</ul>
		
		<footer>
			<a href="#" data-type="modal-toggle" data-option-target="modal-photoset-upload" class="link-dotted" id="photoset-start-upload">{$aLang.topic_photoset_upload_choose}</a>
		</footer>
	</div>
	
	  
	<p><label for="topic_tags">{$aLang.topic_create_tags}:</label>
	<input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" class="input-text input-width-full autocomplete-tags-sep" /><br />
	<small class="note">{$aLang.topic_create_tags_notice}</small></p>

	
	<p><label for="topic_forbid_comment">
	<input type="checkbox" id="topic_forbid_comment" name="topic_forbid_comment" class="input-checkbox" value="1" {if $_aRequest.topic_forbid_comment==1}checked{/if} />
	{$aLang.topic_create_forbid_comment}</label>
	<small class="note">{$aLang.topic_create_forbid_comment_notice}</small></p>

	
	{if $oUserCurrent->isAdministrator()}
		<p><label for="topic_publish_index">
		<input type="checkbox" id="topic_publish_index" name="topic_publish_index" class="input-checkbox" value="1" {if $_aRequest.topic_publish_index==1}checked{/if} />
		{$aLang.topic_create_publish_index}</label>
		<small class="note">{$aLang.topic_create_publish_index_notice}</small></p>
	{/if}

	<input type="hidden" name="topic_type" value="photoset" />
	
	{hook run='form_add_topic_photoset_end'}
			
			
	<button type="submit"  name="submit_topic_publish" id="submit_topic_publish" class="button button-primary fl-r">{if $sEvent == 'add' or ($oTopicEdit and $oTopicEdit->getPublish() == 0)}{$aLang.topic_create_submit_publish}{else}{$aLang.topic_create_submit_update}{/if}</button>
	<button type="submit"  name="submit_preview" onclick="ls.topic.preview('form-topic-add','text_preview'); return false;" class="button">{$aLang.topic_create_submit_preview}</button>
	<button type="submit"  name="submit_topic_save" id="submit_topic_save" class="button">{$aLang.topic_create_submit_save}</button>
</form>

<div class="topic-preview" id="text_preview"></div>
	
{hook run='add_topic_photoset_end'}



{include file='footer.tpl'}