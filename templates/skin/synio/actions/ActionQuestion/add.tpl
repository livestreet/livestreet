{if $sEvent=='add'}
	{include file='header.tpl' menu_content='create'}
{else}
	{include file='header.tpl'}
	<h2 class="page-header">{$aLang.topic_question_edit}</h2>
{/if}


{include file='editor.tpl'}

{hook run='add_topic_question_begin'}


<form action="" method="POST" enctype="multipart/form-data" id="form-topic-add" class="wrapper-content">
	{hook run='form_add_topic_question_begin'}
	
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
	
	
	<p><label for="topic_title">{$aLang.topic_question_create_title}:</label>
	<input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="input-text input-width-full" {if $bEditDisabled}disabled{/if} /><br />
	<small class="note">{$aLang.topic_question_create_title_notice}</small></p>

	
	<div class="poll-create">
		<label>{$aLang.topic_question_create_answers}:</label>
		<ul class="question-list" id="question_list">
			{if count($_aRequest.answer)>=2}
				{foreach from=$_aRequest.answer item=sAnswer key=i}
					<li>
						<input type="text" value="{$sAnswer}" name="answer[]" class="input-text input-width-300" {if $bEditDisabled}disabled{/if} />
						{if !$bEditDisabled and $i>1} <a href="#" class="icon-synio-remove" onClick="return ls.poll.removeAnswer(this);">{$aLang.topic_question_create_answers_delete}</a>{/if}
					</li>
				{/foreach}
			{else}
				<li><input type="text" value="" name="answer[]" class="input-text input-width-300" {if $bEditDisabled}disabled{/if} /></li>
				<li><input type="text" value="" name="answer[]" class="input-text input-width-300" {if $bEditDisabled}disabled{/if} /></li>
			{/if}
		</ul>
	
		{if !$bEditDisabled}
			<a href="#" onClick="ls.poll.addAnswer(); return false;" class="link-dotted">{$aLang.topic_question_create_answers_add}</a>
		{/if}
	</div>

	
	<p><label for="topic_text">{$aLang.topic_question_create_text}:</label>
	<textarea name="topic_text" id="topic_text" rows="10" class="input-width-full mce-editor markitup-editor input-width-full">{$_aRequest.topic_text}</textarea>
	{if !$oConfig->GetValue('view.tinymce')}
		{include file='tags_help.tpl' sTagsTargetId="topic_text"}
	{/if}
	</p>
		
	<p><label for="topic_tags">{$aLang.topic_create_tags}:</label>
	<input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" class="input-text input-width-full autocomplete-tags-sep" />
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

	<input type="hidden" name="topic_type" value="question" />

	{hook run='form_add_topic_question_end'}

	<button type="submit"  name="submit_topic_publish" id="submit_topic_publish" class="button button-primary fl-r">{$aLang.topic_create_submit_publish}</button>
	<button type="submit"  name="submit_preview" onclick="jQuery('#text_preview').parent().show(); ls.topic.preview('form-topic-add','text_preview'); return false;" class="button">{$aLang.topic_create_submit_preview}</button>
	<button type="submit"  name="submit_topic_save" id="submit_topic_save" class="button">{$aLang.topic_create_submit_save}</button>
</form>

<div class="topic-preview" id="text_preview"></div>

{hook run='add_topic_question_end'}


{include file='footer.tpl'}