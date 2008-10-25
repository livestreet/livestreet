{include file='header.tpl'}


{if $sEvent=='add'}
	<h1>Создание нового блога</h1>
{else}
	<h1>Управление блогом: <a href="{$DIR_WEB_ROOT}/blog/{$oBlogEdit->getUrl()}/"  class="blog_headline_group">{$oBlogEdit->getTitle()}</a></h1>
	{include file='menu.blog_edit.tpl'}
{/if}

{include file='system_message.tpl'}

<div class="backoffice">

   
       <form action="" method="POST" id="thisform" enctype="multipart/form-data">
       
       <label for="blog_title">Название блога:</label>
       <input type="text" id="blog_title" name="blog_title" value="{$_aRequest.blog_title}" style="width: 100%;" /><br />
       <span class="form_note">Название блога должно быть наполнено смыслом, чтобы можно было понять, о чем будет блог.</span><br />
       <span class="form_note_red"></span>
       </p>
       
       <p>
       <label for="blog_url">URL блога:</label>
       <input type="text" id="blog_url" name="blog_url" value="{$_aRequest.blog_url}" style="width: 100%;" {if $_aRequest.blog_id}disabled{/if} /><br />
       <span class="form_note">URL блога по которому он будет доступен, по смыслу должен совпадать с названием блога и быть на латинице. Пробелы заменяться на "_". Внимание! URL нельзя изменить после создания блога!</span><br />
       <span class="form_note_red"></span>
       </p>
       
       <p>
       <label for="blog_type">Тип блога:</label>
     	<select name="blog_type" id="blog_type" style="width: 100%;" onChange="">
      	<option value="open">Открытый</option>
      	</select><br />
      	<span class="form_note">Открытый &mdash; к этому блогу может присоедениться любой желающий, топики видны всем</span><br />
      	<span class="form_note_red"></span>
      	</p>
     
     
        <label for="blog_description">Описание блога:</label>            
		<textarea style="width: 100%;" name="blog_description" id="blog_description" rows="10">{$_aRequest.blog_description}</textarea><br>            
		<span class="form_note">Между прочим, можно использовать html-теги</span>
		<br />


		<p>
     	 <label for="blog_limit_rating_topic">Ограничение по рейтингу:</label>
      	<input type="text" id="blog_limit_rating_topic" name="blog_limit_rating_topic" value="{$_aRequest.blog_limit_rating_topic}" style="width: 100%;" /><br />
        <span class="form_note">Рейтинг который необходим пользователю, чтобы написать в этот блог</span>
    	</p>
    	
	{if $oBlogEdit and $oBlogEdit->getAvatar()}
		<img src="{$oBlogEdit->getAvatarPath(48)}" border="0">
		<img src="{$oBlogEdit->getAvatarPath(24)}" border="0">
		<input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"> &mdash; <label for="avatar_delete"><span class="form">удалить</span></label><br /><br>
	{/if}
     <span class="form">Аватар:</span><br /> <input type="file" name="avatar" ><br>

    <p class="l-bot">     
     <input type="submit" name="submit_blog_add" value="сохранить">&nbsp;    
    </p>

    <div class="form_note">После нажатия на кнопку &laquo;Сохранить&raquo;, блог будет создан</div>

    <p>Может быть, перейти на <a href="{$DIR_WEB_ROOT}/topic/add/">страницу создания топиков</a>?</p>
	<input type="hidden" name="blog_id" value="{$_aRequest.blog_id}">
    </form>
     </div>
 </div>
</div>




{include file='footer.tpl'}

