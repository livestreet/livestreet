				{include file='window_load_img.tpl' sToLoad='page_text'}

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
				
				<form action="" method="POST">
					<p><label for="page_pid">Вложить в</label>
     				<select name="page_pid" id="page_pid" >
     					<option value="0"></option>
     					{foreach from=$aPages item=oPage}
     						<option style="margin-left: {$oPage->getLevel()*20}px;" value="{$oPage->getId()}" {if $_aRequest.page_pid==$oPage->getId()}selected{/if}>{$oPage->getTitle()}(/{$oPage->getUrlFull()}/)</option>
     					{/foreach}     		
     				</select>
					</p>
					
					<p><label for="page_title">Название:</label>
       				<input type="text" id="page_title" name="page_title" value="{$_aRequest.page_title}"  class="w100p" /><br />      
      				</p>
      				
      				<p><label for="page_url">URL:</label>
       				<input type="text" id="page_url" name="page_url" value="{$_aRequest.page_url}" class="w100p" /><br />      
      				</p>
					
					<p><label for="topic_text">Текст:</label>
					{if !$BLOG_USE_TINYMCE}
            			<div class="panel_form" style="background: #eaecea; ">       	 
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','b'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/bold_ru.gif" width="20" height="20" title="жирный"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','i'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/italic_ru.gif" width="20" height="20" title="курсив"></a>	 			
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','u'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/underline_ru.gif" width="20" height="20" title="подчеркнуть"></a>	 			
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','s'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/strikethrough.gif" width="20" height="20" title="зачеркнутый"></a>	 			
	 						&nbsp;
	 						<a href="#" onclick="lsPanel.putTagUrl('page_text','Введите ссылку'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/link.gif" width="20" height="20"  title="вставить ссылку"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','code'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/code.gif" width="30" height="20" title="код"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','video'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/video.gif" width="20" height="20" title="видео"></a>
	 				
	 						<a href="#" onclick="showImgUploadForm(); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/img.gif" width="20" height="20" title="изображение"></a> 			
	 						<a href="#" onclick="lsPanel.putText('page_text','<cut>'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/images/panel/cut.gif" width="20" height="20" title="кат"></a>	
	 					</div>
	 				{/if}
	 				<textarea name="page_text" id="page_text" rows="20">{$_aRequest.page_text}</textarea></p>
					
					<p><label for="page_seo_keywords">SEO keywords:</label>
      				<input type="text" id="page_seo_keywords" name="page_seo_keywords" value="{$_aRequest.page_seo_keywords}" class="w100p" /><br />
            		<span class="form_note">Ключевые слова для SEO-оптимизации</span>
     				</p>
     
     				<p><label for="page_seo_description">SEO description:</label>
     				<input type="text" id="page_seo_description" name="page_seo_description" value="{$_aRequest.page_seo_description}" class="w100p" /><br />
            		<span class="form_note">Описание для SEO-оптимизации</span>
    	 			</p>

       				<p><input type="checkbox" id="page_active" name="page_active" value="1" {if $_aRequest.page_active==1}checked{/if}/>
      				<label for="page_active"> &mdash; показывать страницу</label>	     				            
     				</p>
	 				
					
					<p class="buttons">					
					<input type="submit" name="submit_page_save" value="сохранить">&nbsp;  
					<input type="submit" name="submit_page_cancel" value="отмена" onclick="window.location='{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PAGE}/admin/'; return false;">&nbsp;
					</p>
					
					<input type="hidden" name="page_id" value="{$_aRequest.page_id}">
				</form>