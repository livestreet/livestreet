{include file='header.tpl'}




<BR>
<div class="habrablock">
     <table width="100%" border="0" cellpadding="5" cellspacing="1" class="story_text" style="width:100%!important;">
     <tr>
       <td width="2%" align="center"></td>
       <td width="53%" align="left" class="blog_page_tb_header">Блог</td>
       {if $oUserCurrent}
       	<td width="10%" align="center" class="blog_page_tb_header">Вступить / Покинуть</td>
       {/if}
       <td width="10%" align="center" class="blog_page_tb_header">Читатели</td>       
       <td width="15%" align="center" class="blog_page_tb_header">Рейтинг</td>
     </tr>
	{foreach from=$aBlogs item=oBlog}
     <tr valign="top">
       <td align="center"><img src="{$oBlog->getAvatarPath(24)}" width="24" height="24" alt="" title="{$oBlog->getTitle()|escape:'html'}" border="0"></td>
       <td align="left"><a href="{$DIR_WEB_ROOT}/blog/{$oBlog->getUrl()}/" class="blog_headline_pop">{$oBlog->getTitle()|escape:'html'}</a><br>
            <span class="sf_page_nickname">смотритель:</span>&nbsp;
      		<a href="{$DIR_WEB_ROOT}/profile/{$oBlog->getUserLogin()}/"><img src="{$DIR_STATIC_SKIN}/img/user.gif" width="11" height="11" border="0" alt="посмотреть профиль" title="посмотреть профиль"></a><a href="{$DIR_WEB_ROOT}/profile/{$oBlog->getUserLogin()}/" class="sf_page_nickname">{$oBlog->getUserLogin()}</a>              
       </td>
       {if $oUserCurrent}
       <td nowrap align="center">  
            {if $oUserCurrent->getId()!=$oBlog->getOwnerId()}			
			<span id="blog_action_join_{$oBlog->getId()}" {if $oBlog->getCurrentUserIsJoin()}style="display: none;"{/if}>
				<a href="#" onclick="ajaxJoinLeaveBlog({$oBlog->getId()},'join'); return false;" title="вступить в блог"><img src="{$DIR_STATIC_SKIN}/img/blog_join.gif" border="0" alt="вступить"></a>
			</span>			
			<span id="blog_action_leave_{$oBlog->getId()}" {if !$oBlog->getCurrentUserIsJoin()}style="display: none;"{/if}>
					<a href="#" onclick="ajaxJoinLeaveBlog({$oBlog->getId()},'leave'); return false;" title="покинуть блог"><img src="{$DIR_STATIC_SKIN}/img/blog_leave.gif" border="0" alt="покинуть"></a>
			</span>		
			{/if}	
       </td>
       {/if}
       <td nowrap align="center"><a href="{$DIR_WEB_ROOT}/blog/{$oBlog->getUrl()}/profile/" class="blog_popular_link" id="blog_user_count_{$oBlog->getId()}">{$oBlog->getCountUser()}</a></td>       
       <td nowrap align="center"><span class="blog_rating">&nbsp;{$oBlog->getRating()}&nbsp;</span></td>
     </tr>
	{/foreach}

     </table>     
     
</div>

{include file='paging.tpl' aPaging=`$aPaging`}



{include file='footer.tpl'}

