{include file='header.tpl'}

{include file='menu.action.tpl'}

{include file='system_message.tpl'}


{literal}
<script>
document.addEvent('domready', function() {	
	var inputTags = $('topic_tags');
 
	new Autocompleter.Request.HTML(inputTags, DIR_WEB_ROOT+'/include/ajax/tagAutocompleter.php', {
		'indicatorClass': 'autocompleter-loading', // class added to the input during request
		'minLength': 1, // We need at least 1 character
		'selectMode': 'type-ahead', // Instant completion
		'multiple': true // Tag support, by default comma separated
	}); 
});
</script>
{/literal}


<div id="window_load_img">
	<form method="POST" action="" enctype="multipart/form-data" id="form_upload_img" >
	<table width="500px"  border="0">
		<tr>
			<th align="center" colspan="2">Вставка изображения</th>			
		</tr>
		<tr>
			<td align="right">Файл:</td>
			<td width="100%"><input type="file" name="img_file" style="width: 100%;" value=""></td>
		</tr>
		<tr>
			<td align="right">Ссылка:</td>
			<td><input type="text" name="img_url" value="http://" style="width: 100%;">
		</tr>
		<tr>
			<td align="right">Выравнивание:</td>

			<td>
				<select name="align">
					<option value="">нет</option>
					<option value="left">слева</option>
					<option value="right">справа</option>
				</select>
		</tr>
		<tr>
			<td align="right">Описание:</td>
			<td><input type="text" name="title" style="width: 100%;"></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="button" value="Загрузить" onclick="ajaxUploadImg(document.getElementById('form_upload_img'));">
				<input type="button" value="Отмена" onclick="closeWindow('window_load_img'); return false;">
			</td>
		</tr>
	</table>
	</form>
</div>



<div class="backoffice">

   
       <form action="" method="POST" id="thisform" enctype="multipart/form-data">

       <label for="blog_id">В какой блог публикуем?</label>
     	<select name="blog_id" id="blog_id" style="width: 100%;" onChange="ajaxBlogInfo(this.value);">
     		<option value="0">мой персональный блог</option>
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
       <label for="topic_title">Заголовок:</label>
       <input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" style="width: 100%;" /><br />

       <span class="form_note">Заголовок должен быть наполнен смыслом, чтобы можно было понять, о чем будет топик.</span><br />
       <span class="form_note_red"></span>
      </p>
     
     
            <label for="topic_text">Текст:</label>
            
            <div class="panel_topic_form" style="background: #eaecea; ">    
            	<select onchange="voidPutTag2('topic_text',this.value);" tabindex="-1">
					<option value="" class="title">Заголовок</option>
					<option value="h4">H4</option>
					<option value="h5">H5</option>
					<option value="h6">H6</option>
				</select>  
				
	 			<a href="#" onclick="voidPutB('topic_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/bold_ru.gif" width="20" height="20" title="жирный"></a>
	 			<a href="#" onclick="voidPutI('topic_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/italic_ru.gif" width="20" height="20" title="курсив"></a>	 			
	 			<a href="#" onclick="voidPutU('topic_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/underline_ru.gif" width="20" height="20" title="подчеркнуть"></a>	 			
	 			<a href="#" onclick="voidPutS('topic_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/strikethrough.gif" width="20" height="20" title="зачеркнутый"></a>	 			
	 			&nbsp;
	 			<a href="#" onclick="voidPutURL('topic_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/link.gif" width="20" height="20"  title="вставить ссылку"></a>
	 			<a href="#" onclick="voidPutCode('topic_text'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/code.gif" width="30" height="20" title="код"></a>
	 			<a href="#" onclick="voidPutTag2('topic_text','video'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/video.gif" width="20" height="20" title="видео"></a>
	 			
	 			
	 			<a href="#" onclick="showWindow('window_load_img'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/img.gif" width="20" height="20" title="изображение"></a>
	 			
	 			
	 			<a href="#" onclick="voidPutTag('topic_text','<cut>'); return false;" class="button"><img src="{$DIR_STATIC_SKIN}/img/cut.gif" width="20" height="20" title="кат"></a>	
	 		</div>
<textarea style="width: 100%;" name="topic_text" id="topic_text" rows="20">{$_aRequest.topic_text}</textarea><br>

            
<span class="form_note">Между прочим, можно использовать html-теги</span>
<br />





     <p>
      <label for="topic_tags">Метки:</label>
      <input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" style="width: 100%;" /><br />
            <span class="form_note">Метки нужно разделять запятой. Например: <i>клон хабры</i>, <i>блоги</i>, <i>рейтинг</i>, <i>google</i>, <i>сиськи</i>, <i>кирпич</i>.</span>

     </p>

     

    <p class="l-bot">     
     <input type="submit" name="submit_topic_publish" value="опубликовать">&nbsp;
     <input type="submit" name="submit_topic_save" value="сохранить в черновиках">&nbsp;
    </p>

    <div class="form_note">Если нажать кнопку &laquo;Сохранить в черновиках&raquo;, текст топика 
    будет виден только Вам, а рядом с его заголовком будет отображаться замочек. 
    Чтобы топик был виден всем, нажмите &laquo;Опубликовать&raquo;.</div>

    <p>Может быть, перейти на <a href="{$DIR_WEB_ROOT}/blog/">заглавную страницу блогов</a>?</p>
	<input type="hidden" name="topic_id" value="{$_aRequest.topic_id}">
    </form>
     </div>
 </div>
</div>


{include file='footer.tpl'}

