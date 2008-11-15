{include file='header.tpl'}


{if $sEvent=='add'}
	<h1>{$aLang.blog_create}</h1>
{else}
	<h1>{$aLang.blog_admin}: <a href="{$DIR_WEB_ROOT}/blog/{$oBlogEdit->getUrl()}/"  class="blog_headline_group">{$oBlogEdit->getTitle()}</a></h1>
	{include file='menu.blog_edit.tpl'}
{/if}

{include file='system_message.tpl'}

<div class="backoffice">

{if $aBlogUsers}
   
       <form action="" method="POST" id="thisform" enctype="multipart/form-data">
       
      	<table width="100%" bgcolor="White" border="0" cellspacing="0" cellpadding="0" class="inbox">
			<tr>
				<td width="200px"></td>
				<td align="center">{$aLang.blog_admin_users_administrator}</td>				
				<td align="center">{$aLang.blog_admin_users_moderator}</td>
				<td align="center">{$aLang.blog_admin_users_reader}</td>
			</tr>	
			{foreach from=$aBlogUsers item=oBlogUser}
			<tr>
				<td><a href="{$DIR_WEB_ROOT}/profile/{$oBlogUser->getUserLogin()}/">{$oBlogUser->getUserLogin()}</a></td>
				{if $oBlogUser->getUserId()==$oUserCurrent->getId()}
				<td colspan="3" align="center">{$aLang.blog_admin_users_current_administrator}</td>
				{else}
				<td align="center">
					<input type="radio" name="user_rank[{$oBlogUser->getUserId()}]" value="administrator" {if $oBlogUser->getIsAdministrator()}checked{/if}>
				</td>				
				<td align="center">
					<input type="radio" name="user_rank[{$oBlogUser->getUserId()}]" value="moderator" {if $oBlogUser->getIsModerator()}checked{/if}>
				</td>
				<td  align="center">
					<input type="radio" name="user_rank[{$oBlogUser->getUserId()}]" value="reader" {if !$oBlogUser->getIsAdministrator() and !$oBlogUser->getIsModerator()}checked{/if}>
				</td>
				{/if}
			</tr>
			{/foreach}		
					
		</table><br>

     

    <p class="l-bot">     
     <input type="submit" name="submit_blog_admin" value="{$aLang.blog_admin_users_submit}">&nbsp;    
    </p>

    <div class="form_note">{$aLang.blog_admin_users_submit_notice}</div>
{else}
	{$aLang.blog_admin_users_empty} 
{/if}  
        
	<input type="hidden" name="blog_id" value="{$_aRequest.blog_id}">
    </form>
     </div>
 </div>
</div>




{include file='footer.tpl'}

