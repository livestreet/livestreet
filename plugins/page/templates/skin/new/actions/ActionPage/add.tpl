				{include file='window_load_img.tpl' sToLoad='page_text'}

{if $oConfig->GetValue('view.tinymce')}
<script type="text/javascript" src="{cfg name='path.root.engine_lib'}/external/tinymce_3.2.7/tiny_mce.js"></script>
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
    plugins : "lseditor,safari,inlinepopups,media",
    convert_urls : false,
    extended_valid_elements : "embed[src|type|allowscriptaccess|allowfullscreen|width|height]",
    language : TINYMCE_LANG   
});
</script>
{/literal}
{/if}
				
				<form action="" method="POST">
					{hook run='plugin_page_form_add_begin'}
					<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" /> 
					
					<p><label for="page_pid">{$aLang.page_create_parent_page}</label>
     				<select name="page_pid" id="page_pid" >
     					<option value="0"></option>
     					{foreach from=$aPages item=oPage}
     						<option style="margin-left: {$oPage->getLevel()*20}px;" value="{$oPage->getId()}" {if $_aRequest.page_pid==$oPage->getId()}selected{/if}>{$oPage->getTitle()}(/{$oPage->getUrlFull()}/)</option>
     					{/foreach}     		
     				</select>
					</p>
					
					<p><label for="page_title">{$aLang.page_create_title}:</label>
       				<input type="text" id="page_title" name="page_title" value="{$_aRequest.page_title}"  class="w100p" /><br />      
      				</p>
      				
      				<p><label for="page_url">{$aLang.page_create_url}:</label>
       				<input type="text" id="page_url" name="page_url" value="{$_aRequest.page_url}" class="w100p" /><br />      
      				</p>
					
					<p><label for="topic_text">{$aLang.page_create_text}:</label>
					{if !$oConfig->GetValue('view.tinymce')}
            			<div class="panel_form" style="background: #eaecea; ">       	 
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','b'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/bold_ru.gif" width="20" height="20" title="{$aLang.panel_b}"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','i'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/italic_ru.gif" width="20" height="20" title="{$aLang.panel_i}"></a>	 			
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','u'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/underline_ru.gif" width="20" height="20" title="{$aLang.panel_u}"></a>	 			
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','s'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/strikethrough.gif" width="20" height="20" title="{$aLang.panel_s}"></a>	 			
	 						&nbsp;
	 						<a href="#" onclick="lsPanel.putTagUrl('page_text','Введите ссылку'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/link.gif" width="20" height="20"  title="{$aLang.panel_url}"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','code'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/code.gif" width="30" height="20" title="{$aLang.panel_code}"></a>
	 						<a href="#" onclick="lsPanel.putTagAround('page_text','video'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/video.gif" width="20" height="20" title="{$aLang.panel_video}"></a>
	 				
	 						<a href="#" onclick="showImgUploadForm(); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/img.gif" width="20" height="20" title="{$aLang.panel_image}"></a> 			
	 						<a href="#" onclick="lsPanel.putText('page_text','<cut>'); return false;" class="button"><img src="{cfg name='path.static.skin'}/images/panel/cut.gif" width="20" height="20" title="{$aLang.panel_cut}"></a>	
	 					</div>
	 				{/if}
	 				<textarea name="page_text" id="page_text" rows="20">{$_aRequest.page_text}</textarea></p>
					
					<p><label for="page_seo_keywords">{$aLang.page_create_seo_keywords}:</label>
      				<input type="text" id="page_seo_keywords" name="page_seo_keywords" value="{$_aRequest.page_seo_keywords}" class="w100p" /><br />
            		<span class="form_note">{$aLang.page_create_seo_keywords_notice}</span>
     				</p>
     
     				<p><label for="page_seo_description">{$aLang.page_create_seo_description}:</label>
     				<input type="text" id="page_seo_description" name="page_seo_description" value="{$_aRequest.page_seo_description}" class="w100p" /><br />
            		<span class="form_note">{$aLang.page_create_seo_description_notice}</span>
    	 			</p>

    	 			<p><label for="page_sort">{$aLang.page_create_sort}:</label><br />
					<input type="text" id="page_sort" name="page_sort" value="{$_aRequest.page_sort}" class="w100p" />
					<span class="form_note">{$aLang.page_create_sort_notice}</span></p>
    	 			
       				<p><input type="checkbox" id="page_active" name="page_active" value="1" {if $_aRequest.page_active==1}checked{/if}/>
      				<label for="page_active"> &mdash; {$aLang.page_create_active}</label>	     				            
     				</p>
     				
     				<p><input type="checkbox" id="page_main" name="page_main" value="1" {if $_aRequest.page_main==1}checked{/if}/>
      				<label for="page_main"> &mdash; {$aLang.page_create_main}</label>	     				            
     				</p>
	 				
					{hook run='plugin_page_form_add_end'}
					<p class="buttons">					
					<input type="submit" name="submit_page_save" value="{$aLang.page_create_submit_save}">&nbsp;  
					<input type="submit" name="submit_page_cancel" value="{$aLang.page_create_submit_cancel}" onclick="window.location='{router page='page'}admin/'; return false;">&nbsp;
					</p>
					
					<input type="hidden" name="page_id" value="{$_aRequest.page_id}">
				</form>