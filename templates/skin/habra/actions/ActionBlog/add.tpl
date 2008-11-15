{include file='header.tpl'}


{if $sEvent=='add'}
	<h1>{$aLang.blog_create}</h1>
{else}
	<h1>{$aLang.blog_admin}: <a href="{$DIR_WEB_ROOT}/blog/{$oBlogEdit->getUrl()}/"  class="blog_headline_group">{$oBlogEdit->getTitle()}</a></h1>
	{include file='menu.blog_edit.tpl'}
{/if}

{include file='system_message.tpl'}

<div class="backoffice">

   
       <form action="" method="POST" id="thisform" enctype="multipart/form-data">
       
       <label for="blog_title">{$aLang.blog_create_title}:</label>
       <input type="text" id="blog_title" name="blog_title" value="{$_aRequest.blog_title}" style="width: 100%;" /><br />
       <span class="form_note">{$aLang.blog_create_title_notice}</span><br />
       <span class="form_note_red"></span>
       </p>
       
       <p>
       <label for="blog_url">{$aLang.blog_create_url}:</label>
       <input type="text" id="blog_url" name="blog_url" value="{$_aRequest.blog_url}" style="width: 100%;" {if $_aRequest.blog_id}disabled{/if} /><br />
       <span class="form_note">{$aLang.blog_create_url_notice}</span><br />
       <span class="form_note_red"></span>
       </p>
       
       <p>
       <label for="blog_type">{$aLang.blog_create_type}:</label>
     	<select name="blog_type" id="blog_type" style="width: 100%;" onChange="">
      	<option value="open">{$aLang.blog_create_type_open}</option>
      	</select><br />
      	<span class="form_note">{$aLang.blog_create_type_notice}</span><br />
      	<span class="form_note_red"></span>
      	</p>
     
     
        <label for="blog_description">{$aLang.blog_create_description}:</label>            
		<textarea style="width: 100%;" name="blog_description" id="blog_description" rows="10">{$_aRequest.blog_description}</textarea><br>            
		<span class="form_note">{$aLang.blog_create_description_notice}</span>
		<br />


		<p>
     	 <label for="blog_limit_rating_topic">{$aLang.blog_create_rating}:</label>
      	<input type="text" id="blog_limit_rating_topic" name="blog_limit_rating_topic" value="{$_aRequest.blog_limit_rating_topic}" style="width: 100%;" /><br />
        <span class="form_note">{$aLang.blog_create_rating_notice}</span>
    	</p>
    	
	{if $oBlogEdit and $oBlogEdit->getAvatar()}
		<img src="{$oBlogEdit->getAvatarPath(48)}" border="0">
		<img src="{$oBlogEdit->getAvatarPath(24)}" border="0">
		<input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"> &mdash; <label for="avatar_delete"><span class="form">{$aLang.blog_create_avatar_delete}</span></label><br /><br>
	{/if}
     <span class="form">{$aLang.blog_create_avatar}:</span><br /> <input type="file" name="avatar" ><br>

    <p class="l-bot">     
     <input type="submit" name="submit_blog_add" value="{$aLang.blog_create_submit}">&nbsp;    
    </p>

    <div class="form_note">{$aLang.blog_create_submit_notice}</div>

    
	<input type="hidden" name="blog_id" value="{$_aRequest.blog_id}">
    </form>
     </div>
 </div>
</div>




{include file='footer.tpl'}

