{include file='window_load_img.tpl' sToLoad='page_text'}

<div class="backoffice">
   		
       <form action="" method="POST" id="thisform" enctype="multipart/form-data">

       <label for="page_pid">Вложить в</label>
     	<select name="page_pid" id="page_pid" style="width: 100%;">
     		<option value="0"></option>
     		{foreach from=$aPages item=oPage}
     			<option style="margin-left: {$oPage->getLevel()*20}px;" value="{$oPage->getId()}" {if $_aRequest.page_pid==$oPage->getId()}selected{/if}>{$oPage->getTitle()}(/{$oPage->getUrlFull()}/)</option>
     		{/foreach}     		
     	</select>
     	
        <br /><span class="form_note_red"></span></p>
    
               <p>
       <label for="page_title">Название:</label>
       <input type="text" id="page_title" name="page_title" value="{$_aRequest.page_title}" style="width: 100%;" /><br />      
      </p>
     
      <p>
       <label for="page_url">URL:</label>
       <input type="text" id="page_url" name="page_url" value="{$_aRequest.page_url}" style="width: 100%;" /><br />      
      </p>
     
            <label for="topic_text">Текст:</label>
            
            <div class="panel_topic_form" style="background: #eaecea; ">    
            	<select onchange="voidPutTag2('page_text',this.value); this.value='';" tabindex="-1">
					<option value="" class="title">Заголовок</option>
					<option value="h4">H4</option>
					<option value="h5">H5</option>
					<option value="h6">H6</option>
				</select>  
				
	 			<a href="#" onclick="voidPutTag2('page_text','b'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/bold_ru.gif" width="20" height="20" title="жирный"></a>
	 			<a href="#" onclick="voidPutTag2('page_text','i'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/italic_ru.gif" width="20" height="20" title="курсив"></a>	 			
	 			<a href="#" onclick="voidPutTag2('page_text','u'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/underline_ru.gif" width="20" height="20" title="подчеркнуть"></a>	 			
	 			<a href="#" onclick="voidPutTag2('page_text','s'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/strikethrough.gif" width="20" height="20" title="зачеркнутый"></a>	 			
	 			&nbsp;
	 			<a href="#" onclick="voidPutURL('page_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/link.gif" width="20" height="20"  title="вставить ссылку"></a>
	 			<a href="#" onclick="voidPutTag2('page_text','code'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/code.gif" width="30" height="20" title="код"></a>
	 			<a href="#" onclick="voidPutTag2('page_text','video'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/video.gif" width="20" height="20" title="видео"></a>
	 				 			
	 			<a href="#" onclick="showWindow('window_load_img'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/img.gif" width="20" height="20" title="изображение"></a> 			
	 			
	 		</div>
			<textarea style="width: 100%;" name="page_text" id="page_text" rows="20">{$_aRequest.page_text}</textarea><br>

            
		<span class="form_note">Разрешены ВСЕ html-теги, перевод строки ставится автоматически</span>
<br />


     <p>
      <label for="page_seo_keywords">SEO keywords:</label>
      <input type="text" id="page_seo_keywords" name="page_seo_keywords" value="{$_aRequest.page_seo_keywords}" style="width: 100%;" /><br />
            <span class="form_note">Ключевые слова для SEO-оптимизации</span>
     </p>
     
     <p>
      <label for="page_seo_description">SEO description:</label>
      <input type="text" id="page_seo_description" name="page_seo_description" value="{$_aRequest.page_seo_description}" style="width: 100%;" /><br />
            <span class="form_note">Описание для SEO-оптимизации</span>
     </p>

     
     <p>
     <input type="checkbox" id="page_active" name="page_active" value="1" {if $_aRequest.page_active==1}checked{/if}/>
      <label for="page_active"> &mdash; показывать страницу</label>	
     <br />            
     </p>
     

    <p class="l-bot">     
     <input type="submit" name="submit_page_save" value="сохранить">&nbsp;   <input type="submit" name="submit_page_cancel" value="отмена" onclick="window.location='{$DIR_WEB_ROOT}/page/admin/'; return false;">&nbsp;   
    </p>

	<input type="hidden" name="page_id" value="{$_aRequest.page_id}">
    </form>
</div>
 

