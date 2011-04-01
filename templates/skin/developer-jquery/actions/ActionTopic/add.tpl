{include file='header.tpl' menu='topic_action'}
{include file='window_load_img.tpl' sToLoad='topic_text'}



<div class="topic" style="display: none;">
	<div class="content" id="text_preview"></div>
</div>


{if $sEvent=='add'}
	<h2>{$aLang.topic_topic_create}</h2>
{else}
	<h2>{$aLang.topic_topic_edit}</h2>
{/if}

<form action="" method="POST" enctype="multipart/form-data">
	{hook run='form_add_topic_topic_begin'}

	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<p><label for="blog_id">{$aLang.topic_create_blog}</label><br />
	<select name="blog_id" id="blog_id" onChange="ajaxBlogInfo($(this).val());" class="input-300">
		<option value="0">{$aLang.topic_create_blog_personal}</option>
		{foreach from=$aBlogsAllow item=oBlog}
			<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()}</option>
		{/foreach}
	</select></p>

	<p><label for="topic_title">{$aLang.topic_create_title}:</label><br />
	<input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="input-wide" /><br />
	<span class="note">{$aLang.topic_create_title_notice}</span></p>

	<label for="topic_text">{$aLang.topic_create_text}{if !$oConfig->GetValue('view.tinymce')} ({$aLang.topic_create_text_notice}){/if}:</label>
	<textarea name="topic_text" id="topic_text" rows="20" class="input-wide">{$_aRequest.topic_text}</textarea><br />

	<p><label for="topic_tags">{$aLang.topic_create_tags}:</label><br />
	<input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" class="input-wide autocomplete-tags-sep" /><br />
	<span class="note">{$aLang.topic_create_tags_notice}</span></p>

	<p><label for=""><input type="checkbox" id="topic_forbid_comment" name="topic_forbid_comment" class="checkbox" value="1" {if $_aRequest.topic_forbid_comment==1}checked{/if} />
	{$aLang.topic_create_forbid_comment}</label><br />
	<span class="note">{$aLang.topic_create_forbid_comment_notice}</span></p>

	{if $oUserCurrent->isAdministrator()}
		<p><label><input type="checkbox" id="topic_publish_index" name="topic_publish_index" class="checkbox" value="1" {if $_aRequest.topic_publish_index==1}checked{/if} />
		{$aLang.topic_create_publish_index}</label><br />
		<span class="note">{$aLang.topic_create_publish_index_notice}</span></p>
	{/if}

	{hook run='form_add_topic_topic_end'}

	<input type="submit" name="submit_topic_publish" value="{$aLang.topic_create_submit_publish}" />
	<input type="submit" name="submit_topic_save" value="{$aLang.topic_create_submit_save}" />
</form>


{include file='footer.tpl'}