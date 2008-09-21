{include file='header.tpl'}

{include file='menu.blog.tpl'}

<BR><BR><BR>
<div class="habrablock">
     <table width="100%" border="0" cellpadding="5" cellspacing="1" class="story_text" style="width:100%!important;">
     <tr>
       <td width="2%" align="center"></td>
       <td width="63%" align="left" class="blog_page_tb_header">Блог</td>
       <td width="10%" align="center" class="blog_page_tb_header">Читатели</td>       
       <td width="15%" align="center" class="blog_page_tb_header">Рейтинг</td>
     </tr>
	{foreach from=$aBlogs item=oBlog}
     <tr>
       <td align="center"></td>
       <td align="left"><a href="{$DIR_WEB_ROOT}/blog/{$oBlog->getUrl()}/" class="blog_headline_pop">{$oBlog->getTitle()|escape:'html'}</a><br>
            <span class="sf_page_nickname">смотритель:</span>&nbsp;
      		<a href="{$DIR_WEB_ROOT}/profile/{$oBlog->getUserLogin()}/"><img src="{$DIR_STATIC_SKIN}/img/user.gif" width="11" height="11" border="0" alt="посмотреть профиль" title="посмотреть профиль"></a><a href="{$DIR_WEB_ROOT}/profile/{$oBlog->getUserLogin()}/" class="sf_page_nickname">{$oBlog->getUserLogin()}</a>              
       </td>
       <td nowrap align="center"><a href="{$DIR_WEB_ROOT}/blog/{$oBlog->getUrl()}/profile/" class="blog_popular_link">{$oBlog->getCountUser()}</a></td>       
       <td nowrap align="center"><span class="blog_rating">&nbsp;{$oBlog->getRating()}&nbsp;</span></td>
     </tr>
	{/foreach}

     </table>
</div>

{include file='footer.tpl'}

