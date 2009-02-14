{include file='header.tpl' menu='topic_action' showWhiteBack=true}


{literal}
<script>
document.addEvent('domready', function() {	
	new Autocompleter.Request.HTML($('topic_tags'), DIR_WEB_ROOT+'/include/ajax/tagAutocompleter.php', {
		'indicatorClass': 'autocompleter-loading', // class added to the input during request
		'minLength': 2, // We need at least 1 character
		'selectMode': 'pick', // Instant completion
		'multiple': true // Tag support, by default comma separated
	}); 
});
</script>
{/literal}


{if $BLOG_USE_TINYMCE}
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/tiny_mce/tiny_mce.js"></script>
{literal}
<script type="text/javascript">
tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_buttons1 : "lshselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,undo,redo,|,lslink,unlink,lsvideo,lsimage,code",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	theme_advanced_resize_horizontal : 0,
	theme_advanced_resizing_use_cookie : 0,
	theme_advanced_path : false,
	object_resizing : true,
	force_br_newlines : true,
    forced_root_block : '', // Needed for 3.x
    force_p_newlines : false,    
    plugins : "lslink,lsimage,lsvideo,safari,inlinepopups,media,lshselect",
    convert_urls : false,
    extended_valid_elements : "embed[src|type|allowscriptaccess|allowfullscreen|width|height]"     
});
</script>
{/literal}
{/if}


			{include file='window_load_img.tpl' sToLoad='topic_text'}

			<div class="topic" style="display: none;">
				<div class="content" id="text_preview"></div>
			</div>

			<div class="profile-user">
				{if $sEvent=='add'}
					<h1>Создание топика</h1>
				{else}
					<h1>Редактирование топика</h1>
				{/if}
				<form action="" method="POST" enctype="multipart/form-data">
					<p><label for="blog_id">{$aLang.topic_create_blog}</label>
					<select name="blog_id" id="blog_id" onChange="ajaxBlogInfo(this.value);">
     					<option value="0">{$aLang.topic_create_blog_personal}</option>
     					{foreach from=$aBlogsOwner item=oBlog}
     						<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()}</option>
     					{/foreach}
     					{foreach from=$aBlogsUser item=oBlogUser}
     						<option value="{$oBlogUser->getBlogId()}" {if $_aRequest.blog_id==$oBlogUser->getBlogId()}selected{/if}>{$oBlogUser->getBlogTitle()}</option>
     					{/foreach}
     				</select>
     				</p>
     				<script>
     					ajaxBlogInfo($('blog_id').value);
     				</script>
					
					<p><label for="topic_title">{$aLang.topic_create_title}:</label>
					<input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="input-text" /><br />
       				<span class="form_note">{$aLang.topic_create_title_notice}</span>
					</p>

					<p>{if !$BLOG_USE_TINYMCE}<div class="note"><a href="#" onclick="return false;">{$aLang.topic_create_text_notice}</a></div>{/if}<label for="topic_text">{$aLang.topic_create_text}:</label>
					
					{if !$BLOG_USE_TINYMCE}
            			<div class="panel_form" style="background: #eaecea; ">       	 
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','b'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/bold_ru.gif" width="20" height="20" title="жирный"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','i'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/italic_ru.gif" width="20" height="20" title="курсив"></a>	 			
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','u'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/underline_ru.gif" width="20" height="20" title="подчеркнуть"></a>	 			
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','s'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/strikethrough.gif" width="20" height="20" title="зачеркнутый"></a>	 			
	 						&nbsp;
	 						<a href="#" onclick="lsPanel.putTagUrl('topic_text','Введите ссылку'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/link.gif" width="20" height="20"  title="вставить ссылку"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','code'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/code.gif" width="30" height="20" title="код"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('topic_text','video'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/video.gif" width="20" height="20" title="видео"></a>
	 				
	 						<a href="#" onclick="showImgUploadForm(); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/img.gif" width="20" height="20" title="изображение"></a> 			
	 						<a href="#" onclick="lsPanel.putText('topic_text','<cut>'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/cut.gif" width="20" height="20" title="кат"></a>	
	 					</div>
	 				{/if}
					
					<textarea name="topic_text" id="topic_text" rows="20">{$_aRequest.topic_text}</textarea>
					</p>
					
					<p><label for="topic_tags">{$aLang.topic_create_tags}:</label>
					<input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" class="input-text" /><br />
       				<span class="form_note">{$aLang.topic_create_tags_notice}</span>
					</p>
												
					 <p><label for=""><input type="checkbox" id="topic_forbid_comment" name="topic_forbid_comment" value="1" {if $_aRequest.topic_forbid_comment==1}checked{/if}/> 
					 &mdash; {$aLang.topic_create_forbid_comment}</label>
					 <span class="form_note">{$aLang.topic_create_forbid_comment_notice}</span>
					 </p>
					
					 {if $oUserCurrent->isAdministrator()}
					 	<p><label for=""><input type="checkbox" id="topic_publish_index" name="topic_publish_index" value="1" {if $_aRequest.topic_publish_index==1}checked{/if}/> 
					 	 &mdash; {$aLang.topic_create_publish_index}</label>
					 	<span class="form_note">{$aLang.topic_create_publish_index_notice}</span>
					 	</p>
					 {/if}
					
					<p class="buttons">
					<input type="submit" name="submit_topic_publish" value="{$aLang.topic_create_submit_publish}" class="right" />
					<input type="submit" name="submit_preview" value="{$aLang.topic_create_submit_preview}" onclick="$('text_preview').getParent('div').setStyle('display','block'); ajaxTextPreview('topic_text',false); return false;" />&nbsp;
					<input type="submit" name="submit_topic_save" value="{$aLang.topic_create_submit_save}" />
					</p>
				</form>

			</div>


{include file='footer.tpl'}

