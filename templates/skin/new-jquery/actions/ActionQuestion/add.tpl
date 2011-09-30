{include file='header.tpl' menu='topic_action'}


<div class="topic" style="display: none;">
	<div class="content" id="text_preview"></div>
</div>


{if $sEvent=='add'}
	<h2>{$aLang.topic_question_create}</h2>
{else}
	<h2>{$aLang.topic_question_edit}</h2>
{/if}

{hook run='add_topic_question_begin'}
<form action="" method="POST" enctype="multipart/form-data">
	{hook run='form_add_topic_question_begin'}
	
	<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />

	<p><label for="blog_id">{$aLang.topic_create_blog}</label><br />
	<select name="blog_id" id="blog_id" onChange="ls.blog.loadInfo(this.value);" class="input-wide">
		<option value="0">{$aLang.topic_create_blog_personal}</option>
		{foreach from=$aBlogsAllow item=oBlog}
			<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()|escape:'html'}</option>
		{/foreach}
	</select></p>

	<script language="JavaScript" type="text/javascript">
		jQuery(document).ready(function($){
			ls.blog.loadInfo($('#blog_id').val());
		});
    </script>
	
	<p><label for="topic_title">{$aLang.topic_question_create_title}:</label><br />
	<input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="input-wide" {if $bEditDisabled}disabled{/if} /><br />
	<span class="note">{$aLang.topic_question_create_title_notice}</span></p>

	{$aLang.topic_question_create_answers}:

	<ul class="question-list" id="question_list">
		{if count($_aRequest.answer)>=2}
			{foreach from=$_aRequest.answer item=sAnswer key=i}
				<li>
					<input type="text" value="{$sAnswer}" name="answer[]" class="input input-300" {if $bEditDisabled}disabled{/if} />
					{if !$bEditDisabled and $i>1} <a href="#" onClick="return ls.poll.removeAnswer(this);" class="dashed">{$aLang.topic_question_create_answers_delete}</a>{/if}
				</li>
			{/foreach}
		{else}
			<li><input type="text" value="" name="answer[]" class="input input-300" {if $bEditDisabled}disabled{/if} /></li>
			<li><input type="text" value="" name="answer[]" class="input input-300" {if $bEditDisabled}disabled{/if} /></li>
		{/if}
	</ul>
	{if !$bEditDisabled}<p><a href="#" onClick="ls.poll.addAnswer(); return false;" class="dashed">{$aLang.topic_question_create_answers_add}</a></p>{/if}

	<p><label for="topic_text">{$aLang.topic_question_create_text}:</label><br />
	<textarea name="topic_text" id="topic_text" rows="20" class="input-wide">{$_aRequest.topic_text}</textarea></p>

	<p><label for="topic_tags">{$aLang.topic_create_tags}:</label><br />
	<input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" class="input-wide autocomplete-tags-sep" /><br />
	<span class="note">{$aLang.topic_create_tags_notice}</span></p>

	<p><label for="topic_forbid_comment"><input type="checkbox" id="topic_forbid_comment" name="topic_forbid_comment" class="checkbox" value="1" {if $_aRequest.topic_forbid_comment==1}checked{/if} />
	{$aLang.topic_create_forbid_comment}</label><br />
	<span class="note">{$aLang.topic_create_forbid_comment_notice}</span></p>

	{if $oUserCurrent->isAdministrator()}
		<p><label for="topic_publish_index"><input type="checkbox" id="topic_publish_index" name="topic_publish_index" class="checkbox" value="1" {if $_aRequest.topic_publish_index==1}checked{/if} />
		{$aLang.topic_create_publish_index}</label><br />
		<span class="note">{$aLang.topic_create_publish_index_notice}</span></p>
	{/if}

	{hook run='form_add_topic_question_end'}

	<p class="buttons">
			<input type="submit" name="submit_topic_publish" value="{$aLang.topic_create_submit_publish}" class="right" />
			<input type="submit" name="submit_preview" value="{$aLang.topic_create_submit_preview}" onclick="jQuery('#text_preview').parent().show(); ls.tools.textPreview('topic_text',true); return false;" />&nbsp;
			<input type="submit" name="submit_topic_save" value="{$aLang.topic_create_submit_save}" />
	</p>
</form>
{hook run='add_topic_question_end'}

{include file='footer.tpl'}