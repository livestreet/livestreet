{include file='header.tpl' menu='topic_action' showWhiteBack=true}


{literal}
<script language="JavaScript" type="text/javascript">
document.addEvent('domready', function() {	
	new Autocompleter.Request.HTML($('topic_tags'), DIR_WEB_ROOT+'/include/ajax/tagAutocompleter.php?security_ls_key='+LIVESTREET_SECURITY_KEY, {
		'indicatorClass': 'autocompleter-loading', // class added to the input during request
		'minLength': 2, // We need at least 1 character
		'selectMode': 'pick', // Instant completion
		'multiple': true // Tag support, by default comma separated
	}); 
});
</script>
{/literal}



			<div class="topic" style="display: none;">
				<div class="content" id="text_preview"></div>
			</div>

			<div class="profile-user">
				{if $sEvent=='add'}
					<h1>{$aLang.topic_question_create}</h1>
				{else}
					<h1>{$aLang.topic_question_edit}</h1>
				{/if}
				<form action="" method="POST" enctype="multipart/form-data">
					{hook run='form_add_topic_question_begin'}
					<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
					
					<p><label for="blog_id">{$aLang.topic_create_blog}</label>
					<select name="blog_id" id="blog_id" onChange="ajaxBlogInfo(this.value);">
     					<option value="0">{$aLang.topic_create_blog_personal}</option>
     					{foreach from=$aBlogsAllow item=oBlog}
     						<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()}</option>
     					{/foreach}
     				</select></p>
					
     				<script language="JavaScript" type="text/javascript">
     					ajaxBlogInfo($('blog_id').value);
     				</script>
					
					<p><label for="topic_title">{$aLang.topic_question_create_title}:</label><br />
					<input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="w100p" {if $bEditDisabled}disabled{/if} /><br />
       				<span class="form_note">{$aLang.topic_question_create_title_notice}</span></p>
					
					<p>
      				{$aLang.topic_question_create_answers}:
      				<table>
      				{if count($_aRequest.answer)>=2}
        				{foreach from=$_aRequest.answer item=sAnswer}
 							<tr valign="top">
 								<td>
 									<nobr> <input type="radio" disabled /></nobr>
 								</td>
  								<td width="100%">      
    								<input type="text" value="{$sAnswer}" name="answer[]" class="w100p" {if $bEditDisabled}disabled{/if}>     
  								</td>
  								<td nowrap align="center">
   									<input type="button"  name="drop_answer" value=" - " onClick="dropField(this);" {if $bEditDisabled}disabled{/if}>
   									<input type="button"  value=" + " onClick="addField(this);" {if $bEditDisabled}disabled{/if}>   
  								</td>
 							</tr> 
 						{/foreach}
 					{else}
 			 			<tr valign="top">
 							<td>
 								<nobr> <input type="radio" disabled /></nobr>
 							</td>
  							<td width="100%">      
    							<input type="text" value="" name="answer[]" class="w100p" >     
  							</td>
  							<td nowrap align="center">
   								<input type="button"  name="drop_answer" value=" - " onClick="dropField(this);">
   								<input type="button"  value=" + " onClick="addField(this);">   
  							</td>
 						</tr>
 						<tr valign="top">
 							<td>
 								<nobr> <input type="radio" disabled /></nobr>
 							</td>
  							<td width="100%">      
    							<input type="text" value="" name="answer[]" class="w100p" >     
  							</td>
  							<td nowrap align="center">
   								<input type="button"  name="drop_answer" value=" - " onClick="dropField(this);">
   								<input type="button"  value=" + " onClick="addField(this);">   
  							</td>
 						</tr>
 					{/if} 
					</table>
					{if !$bEditDisabled}
						{literal}<script>checkFieldForLast();</script>{/literal}
					{/if}
      				</p><br />

					<p><label for="topic_text">{$aLang.topic_question_create_text}:</label><br />
					<textarea name="topic_text" id="topic_text" rows="20">{$_aRequest.topic_text}</textarea></p>
					
					<p><label for="topic_tags">{$aLang.topic_create_tags}:</label><br />
					<input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" class="w100p" /><br />
       				<span class="form_note">{$aLang.topic_create_tags_notice}</span></p>
												
					<p><label for="topic_forbid_comment"><input type="checkbox" id="topic_forbid_comment" name="topic_forbid_comment" class="checkbox" value="1" {if $_aRequest.topic_forbid_comment==1}checked{/if}/> 
					&mdash; {$aLang.topic_create_forbid_comment}</label><br />
					<span class="form_note">{$aLang.topic_create_forbid_comment_notice}</span></p>

					{if $oUserCurrent->isAdministrator()}
						<p><label for="topic_publish_index"><input type="checkbox" id="topic_publish_index" name="topic_publish_index" class="checkbox" value="1" {if $_aRequest.topic_publish_index==1}checked{/if}/> 
						&mdash; {$aLang.topic_create_publish_index}</label><br />
						<span class="form_note">{$aLang.topic_create_publish_index_notice}</span></p>
					{/if}
					{hook run='form_add_topic_question_end'}
					<p class="buttons">
					<input type="submit" name="submit_topic_publish" value="{$aLang.topic_create_submit_publish}" class="right" />
					<input type="submit" name="submit_preview" value="{$aLang.topic_create_submit_preview}" onclick="$('text_preview').getParent('div').setStyle('display','block'); ajaxTextPreview('topic_text',true); return false;" />&nbsp;
					<input type="submit" name="submit_topic_save" value="{$aLang.topic_create_submit_save}" />
					</p>
				</form>

			</div>


{include file='footer.tpl'}

