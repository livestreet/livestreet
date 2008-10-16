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
       
      	<table width="100%" bgcolor="White" border="0" cellspacing="0" cellpadding="0" class="inbox">
			<tr>
				<td width="200px"></td>
				<td align="center">администратор</td>				
				<td align="center">модератор</td>
				<td align="center">читатель</td>
			</tr>	
			{foreach from=$aBlogUsers item=oBlogUser}
			<tr>
				<td><a href="{$DIR_WEB_ROOT}/profile/{$oBlogUser->getUserLogin()}/">{$oBlogUser->getUserLogin()}</a></td>
				<td align="center">
					<input type="radio" name="user_rank[{$oBlogUser->getUserId()}]" value="administrator" {if $oBlogUser->getIsAdministrator()}checked{/if}>
				</td>				
				<td align="center">
					<input type="radio" name="user_rank[{$oBlogUser->getUserId()}]" value="moderator" {if $oBlogUser->getIsModerator()}checked{/if}>
				</td>
				<td  align="center">
					<input type="radio" name="user_rank[{$oBlogUser->getUserId()}]" value="reader" {if !$oBlogUser->getIsAdministrator() and !$oBlogUser->getIsModerator()}checked{/if}>
				</td>
			</tr>
			{/foreach}		
					
		</table><br>

     

    <p class="l-bot">     
     <input type="submit" name="submit_blog_admin" value="сохранить">&nbsp;    
    </p>

    <div class="form_note">После нажатия на кнопку &laquo;Сохранить&raquo;, права пользователей будут сохранены</div>

    <p>Может быть, перейти на <a href="{$DIR_WEB_ROOT}/topic/add/">страницу создания топиков</a>?</p>
	<input type="hidden" name="blog_id" value="{$_aRequest.blog_id}">
    </form>
     </div>
 </div>
</div>




{include file='footer.tpl'}

