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





<div class="backoffice">


		<div class="groups_topic_text" id="text_preview"></div>
   		<div style="clear: both;"></div>
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
     
      <p>
       <label for="topic_link_url">Ссылка:</label>
       <input type="text" id="topic_link_url" name="topic_link_url" value="{$_aRequest.topic_link_url}" style="width: 100%;" /><br />

       <span class="form_note">Например, http://livestreet.ru/blog/dev_livestreet/113.html</span><br />
       <span class="form_note_red"></span>
      </p>
     
            <label for="topic_text">Краткое описание (максимум 500 символов, HTML-теги запрещены):</label>       
           
		<textarea style="width: 100%;" name="topic_text" id="topic_text" rows="6">{$_aRequest.topic_text}</textarea><br>   
		

     <p>
      <label for="topic_tags">Метки:</label>
      <input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" style="width: 100%;" /><br />
            <span class="form_note">Метки нужно разделять запятой. Например: <i>клон хабры</i>, <i>блоги</i>, <i>рейтинг</i>, <i>google</i>, <i>сиськи</i>, <i>кирпич</i>.</span>

     </p>

     

    <p class="l-bot">     
     <input type="submit" name="submit_topic_publish" value="опубликовать">&nbsp;
     <input type="submit" name="submit_topic_save" value="сохранить в черновиках">&nbsp;
     <input type="submit" name="submit_preview" value="предпросмотр" onclick="ajaxTextPreview(document.getElementById('topic_text').value,true); return false;">&nbsp;
    </p>

    <div class="form_note">Если нажать кнопку &laquo;Сохранить в черновиках&raquo;, топик 
    будет виден только Вам, а рядом с его заголовком будет отображаться замочек. 
    Чтобы топик был виден всем, нажмите &laquo;Опубликовать&raquo;.</div>

    <p>Может быть, перейти на <a href="{$DIR_WEB_ROOT}/blog/">заглавную страницу блогов</a>?</p>
	<input type="hidden" name="topic_id" value="{$_aRequest.topic_id}">
    </form>
     </div>
 </div>
</div>


{include file='footer.tpl'}

