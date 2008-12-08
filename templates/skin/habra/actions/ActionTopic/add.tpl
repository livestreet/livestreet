{include file='header.tpl'}

{include file='menu.action.tpl'}

{include file='system_message.tpl'}


{literal}
<script>
document.addEvent('domready', function() {	
	var inputTags = $('topic_tags');
 
	new Autocompleter.Request.HTML(inputTags, DIR_WEB_ROOT+'/include/ajax/tagAutocompleter.php', {
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
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,undo,redo,|,lslink,unlink,lsvideo,lsimage,code",
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
    plugins : "lslink,lsimage,lsvideo,safari,inlinepopups,media",
    convert_urls : false,
    extended_valid_elements : "embed[src|type|allowscriptaccess|allowfullscreen|width|height]", 
    
});
</script>
{/literal}
{/if}




{include file='window_load_img.tpl' sToLoad='topic_text'}



<div class="backoffice">


		<div class="groups_topic_text" id="text_preview"></div>		
		<div class="groups_topic_text" id="text_preview2" style="border: 2px #dd0000 solid; display: none;"></div>
   		<div style="clear: both;"></div>
       <form action="" method="POST" id="thisform" enctype="multipart/form-data">

       <label for="blog_id">{$aLang.topic_create_blog}</label>
     	<select name="blog_id" id="blog_id" style="width: 100%;" onChange="ajaxBlogInfo(this.value);">
     		<option value="0">{$aLang.topic_create_blog_personal}</option>
     		{foreach from=$aBlogsOwner item=oBlog}
     			<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()}</option>
     		{/foreach}
     		{foreach from=$aBlogsUser item=oBlogUser}
     			<option value="{$oBlogUser->getBlogId()}" {if $_aRequest.blog_id==$oBlogUser->getBlogId()}selected{/if}>{$oBlogUser->getBlogTitle()}</option>
     		{/foreach}
     	</select>
     	<script>
     		ajaxBlogInfo(document.getElementById('blog_id').value);
     	</script>
        <br /><span class="form_note_red"></span></p>
    
               <p>
       <label for="topic_title">{$aLang.topic_create_title}:</label>
       <input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" style="width: 100%;" /><br />

       <span class="form_note">{$aLang.topic_create_title_notice}</span><br />
       <span class="form_note_red"></span>
      </p>
     
     
            <label for="topic_text">{$aLang.topic_create_text}:</label>
            {if !$BLOG_USE_TINYMCE}
            <div class="panel_topic_form" style="background: #eaecea; ">    
            	<select onchange="voidPutTag2('topic_text',this.value);  this.value='';"  tabindex="-1">
					<option value="" class="title">Заголовок</option>
					<option value="h4">H4</option>
					<option value="h5">H5</option>
					<option value="h6">H6</option>
				</select>  
				
	 			<a href="#" onclick="voidPutTag2('topic_text','b'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/bold_ru.gif" width="20" height="20" title="жирный"></a>
	 			<a href="#" onclick="voidPutTag2('topic_text','i'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/italic_ru.gif" width="20" height="20" title="курсив"></a>	 			
	 			<a href="#" onclick="voidPutTag2('topic_text','u'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/underline_ru.gif" width="20" height="20" title="подчеркнуть"></a>	 			
	 			<a href="#" onclick="voidPutTag2('topic_text','s'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/strikethrough.gif" width="20" height="20" title="зачеркнутый"></a>	 			
	 			&nbsp;
	 			<a href="#" onclick="voidPutURL('topic_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/link.gif" width="20" height="20"  title="вставить ссылку"></a>
	 			<a href="#" onclick="voidPutTag2('topic_text','code'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/code.gif" width="30" height="20" title="код"></a>
	 			<a href="#" onclick="voidPutTag2('topic_text','video'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/video.gif" width="20" height="20" title="видео"></a>
	 			
	 			
	 			<a href="#" onclick="showWindow('window_load_img'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/img.gif" width="20" height="20" title="изображение"></a>
	 			
	 			
	 			<a href="#" onclick="voidPutTag('topic_text','<cut>'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/cut.gif" width="20" height="20" title="кат"></a>	
	 		</div>
	 		{/if}
<textarea style="width: 100%;" name="topic_text" id="topic_text" rows="20">{$_aRequest.topic_text}</textarea><br>

            
<span class="form_note">{$aLang.topic_create_text_notice}</span>
<br />





     <p>
      <label for="topic_tags">{$aLang.topic_create_tags}:</label>
      <input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" style="width: 100%;" /><br />
            <span class="form_note">{$aLang.topic_create_tags_notice}</span>

     </p>

     <p>
     <input type="checkbox" id="topic_forbid_comment" name="topic_forbid_comment" value="1" {if $_aRequest.topic_forbid_comment==1}checked{/if}/>
      <label for="topic_forbid_comment"> &mdash; {$aLang.topic_create_forbid_comment}</label>	
     <br />
            <span class="form_note">{$aLang.topic_create_forbid_comment_notice}</span>
     </p>
     
     {if $oUserCurrent->isAdministrator()}
     <p>
     <input type="checkbox" id="topic_publish_index" name="topic_publish_index" value="1" {if $_aRequest.topic_publish_index==1}checked{/if}/>
      <label for="topic_publish_index"> &mdash; {$aLang.topic_create_publish_index}</label>	
     <br />
            <span class="form_note">{$aLang.topic_create_publish_index_notice}</span>
     </p>
     {/if}

    <p class="l-bot">     
     <input type="submit" name="submit_topic_publish" value="{$aLang.topic_create_submit_publish}">&nbsp;
     <input type="submit" name="submit_topic_save" value="{$aLang.topic_create_submit_save}">&nbsp;
     <input type="submit" name="submit_preview" value="{$aLang.topic_create_submit_preview}" onclick="ajaxTextPreview('topic_text',false); return false;">&nbsp;
    </p>

    <div class="form_note">{$aLang.topic_create_submit_notice}</div>
   
	<input type="hidden" name="topic_id" value="{$_aRequest.topic_id}">
    </form>
     </div>
 </div>
</div>


{include file='footer.tpl'}

