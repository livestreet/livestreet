{if $sEvent=='add'}
	{include file='header.tpl' menu_content='create'}
{else}
	{include file='header.tpl'}
	<h2 class="page-header">{$aLang.topic_link_edit}</h2>
{/if}


{include file='editor.tpl'}

{hook run='add_topic_link_begin'}


<form action="" method="POST" enctype="multipart/form-data" id="form-topic-add" class="wrapper-content">
	{hook run='form_add_topic_link_begin'}

	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	
	<p><label for="blog_id">{$aLang.topic_create_blog}</label>
	<select name="blog_id" id="blog_id" onChange="ls.blog.loadInfo(jQuery(this).val());" class="input-width-full">
		<option value="0">{$aLang.topic_create_blog_personal}</option>
		{foreach from=$aBlogsAllow item=oBlog}
			<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()|escape:'html'}</option>
		{/foreach}
	</select></p>

	
	<script type="text/javascript">
		jQuery(document).ready(function($){
			ls.blog.loadInfo($('#blog_id').val());
		});
    </script>
	
	
	<p><label for="topic_title">{$aLang.topic_create_title}:</label>
	<input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="input-text input-width-full" />
	<small class="note">{$aLang.topic_create_title_notice}</small></p>

	
	<p><label for="topic_link_url">{$aLang.topic_link_create_url}:</label>
	<input type="text" id="topic_link_url" name="topic_link_url" value="{$_aRequest.topic_link_url}" class="input-text input-width-full" />
	<small class="note">{$aLang.topic_link_create_url_notice}</small></p>

	
	<label for="topic_text">{$aLang.topic_link_create_text}:</label>
	<textarea name="topic_text" id="topic_text" rows="10" class="input-text input-width-full mce-editor markitup-editor input-width-full">{$_aRequest.topic_text}</textarea>

	
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

	<input type="hidden" name="topic_type" value="link" />
	
	{hook run='form_add_topic_link_end'}

	
	<button type="submit"  name="submit_topic_publish" id="submit_topic_publish" class="button button-primary fl-r">{$aLang.topic_create_submit_publish}</button>
	<button type="submit"  name="submit_preview" onclick="jQuery('#text_preview').parent().show(); ls.topic.preview('form-topic-add','text_preview'); return false;" class="button">{$aLang.topic_create_submit_preview}</button>
	<button type="submit"  name="submit_topic_save" id="submit_topic_save" class="button">{$aLang.topic_create_submit_save}</button>
</form>

<div class="topic-preview" id="text_preview"></div>

{hook run='add_topic_link_end'}


{include file='footer.tpl'}