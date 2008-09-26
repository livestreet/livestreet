{include file='header.tpl'}

{include file='menu.blog.tpl'}






<DIV class=blog_profile>    

	<div id="karmadiv">
		<div class="userinfo_karma_section_3">
			<p style="margin-top: 0px;" align="center">
			<span class="userinfo_karma">
				<nobr>
				
						<span id="blog_vote_self_{$oBlog->getId()}" style="display: none;" >
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="нравится" title="нельзя голосовать за свой блог" />
     						<span id="blog_rating_self_{$oBlog->getId()}" style="color: {if $oBlog->getRating()<0}#d00000{else}#008000{/if};">{$oBlog->getRating()}</span>
     						<img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="не нравится" title="нельзя голосовать за свой блог" />
     					</span>
     					<span id="blog_vote_anonim_{$oBlog->getId()}" style="display: none;" >
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="нравится" title="для голосования необходимо авторизоваться" />
     						<span id="blog_rating_anonim_{$oBlog->getId()}" style="color: {if $oBlog->getRating()<0}#d00000{else}#008000{/if};">{$oBlog->getRating()}</span>
     						<img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="не нравится" title="для голосования необходимо авторизоваться" />
     					</span>
     					<span id="blog_vote_is_vote_down_{$oBlog->getId()}" style="display: none;" >
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up_gray.gif" width="16" height="16" alt="нравится" title="вы уже голосовали за этот блог" />
     						<span id="blog_rating_is_vote_down_{$oBlog->getId()}" style="color: {if $oBlog->getRating()<0}#d00000{else}#008000{/if};">{$oBlog->getRating()}</span>
     						<img src="{$DIR_STATIC_SKIN}/img/vote_down.gif" width="16" height="16" alt="не нравится" title="вы уже голосовали за этот блог" />
     					</span>
     					<span id="blog_vote_is_vote_up_{$oBlog->getId()}" style="display: none;" >
     						<img src="{$DIR_STATIC_SKIN}/img/vote_up.gif" width="16" height="16" alt="нравится" title="вы уже голосовали за этот блог" />
     						<span id="blog_rating_is_vote_up_{$oBlog->getId()}" style="color: {if $oBlog->getRating()<0}#d00000{else}#008000{/if};">{$oBlog->getRating()}</span>
     						<img src="{$DIR_STATIC_SKIN}/img/vote_down_gray.gif" width="16" height="16" alt="не нравится" title="вы уже голосовали за этот блог" />
     					</span>
     					<span id="blog_vote_ok_{$oBlog->getId()}" style="display: none;" >
     						<a href="#" onclick="ajaxVoteBlog({$oBlog->getId()},1); return false;"><img src="{$DIR_STATIC_SKIN}/img/vote_up.gif" width="16" height="16" alt="нравится" title="нравится" /></a>
     						<span id="blog_rating_ok_{$oBlog->getId()}" style="color: {if $oBlog->getRating()<0}#d00000{else}#008000{/if};">{$oBlog->getRating()}</span>
     						<a href="#" onclick="ajaxVoteBlog({$oBlog->getId()},-1); return false;"><img src="{$DIR_STATIC_SKIN}/img/vote_down.gif" width="16" height="16" alt="не нравится" title="не нравится" /></a>
     					</span>
     					
     					{if $oUserCurrent}
     						{if $oBlog->getOwnerId()==$oUserCurrent->getId()}
   								<script>showBlogVote('blog_vote_self',{$oBlog->getId()});</script>
   							{else}
   								{if $oBlog->getUserIsVote()}
   									{if $oBlog->getUserVoteDelta()>0}
   										<script>showBlogVote('blog_vote_is_vote_up',{$oBlog->getId()});</script>
   									{else}
   										<script>showBlogVote('blog_vote_is_vote_down',{$oBlog->getId()});</script>
   									{/if}
   								{else}
   									<script>showBlogVote('blog_vote_ok',{$oBlog->getId()});</script>
   								{/if}
   							{/if}     						
     					{else}
     						<script>showBlogVote('blog_vote_anonim',{$oBlog->getId()});</script>
     					{/if}
     					
				</nobr>
			</span>
			</p>
			<p style="margin-top: 7px;" align="center">
			<span class="userinfo_karma_text">проголосовало<br><span id="blog_count_vote_{$oBlog->getId()}">{$oBlog->getCountVote()}</span> пользователей<br /></span>
			</p>
		</div>
	</div>
	<div class="blog_page">
		<img class="blog_avatar"   src="{$DIR_STATIC_SKIN}/img/stub-user-middle.gif" width="48" height="48" alt="" title="{$oBlog->getTitle()|escape:'html'}" border="0"> 
		<a href="{$DIR_WEB_ROOT}/blog/{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a> 		
		(<a id="groupuserscnt" href="{$DIR_WEB_ROOT}/blog/{$oBlog->getUrl()}/profile/" title="подписчиков"><span  id="blog_user_count">{$oBlog->getCountUser()}</span></a>) 
		
		{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}			
			<span id="blog_action_join" {if !$bNeedJoin}style="display: none;"{/if}>
				<a href="#" onclick="ajaxJoinLeaveBlog({$oBlog->getId()},'join'); return false;" title="вступить в блог"><img src="{$DIR_STATIC_SKIN}/img/blog_join.gif" border="0" alt="вступить"></a>
			</span>			
			<span id="blog_action_leave" {if $bNeedJoin}style="display: none;"{/if}>
					<a href="#" onclick="ajaxJoinLeaveBlog({$oBlog->getId()},'leave'); return false;" title="покинуть блог"><img src="{$DIR_STATIC_SKIN}/img/blog_leave.gif" border="0" alt="покинуть"></a>
			</span>			
		{/if}	
		<a href="{$DIR_WEB_ROOT}/rss/blog/{$oBlog->getUrl()}/" title="RSS лента"><IMG  height=12 src="{$DIR_STATIC_SKIN}/img/rss_small.gif" width=12></a>		
		{if $oUserCurrent and $oUserCurrent->getId()==$oBlog->getOwnerId()}
  					<a href="{$DIR_WEB_ROOT}/blog/edit/{$oBlog->getId()}/" title="отредактировать блог"><img src="{$DIR_STATIC_SKIN}/img/blog_edit.gif" border="0" title="отредактировать блог"></a>
  		{/if}	
	</div>
	
</DIV>

<p class="about_company">{$oBlog->getDescription()|nl2br}</p>
    <table class="part_company"><tr><td>
   		<h1>Администраторы</h1>
      	<p class="groups_contacts_list"><a href="{$DIR_WEB_ROOT}/profile/{$oBlog->getUserLogin()}/" class="auth"><img  class="img_border" src="{$oBlog->getUserProfileAvatarPath(48)}" width="48" height="48" alt="" title="{$oBlog->getUserLogin()}" border="0"></a><br><a href="{$DIR_WEB_ROOT}/profile/{$oBlog->getUserLogin()}/" class="groups_auth">{$oBlog->getUserLogin()}</a></p>      	
      	{if $aBlogAdministrators}
 		{foreach from=$aBlogAdministrators item=oBlogAdministrator}
  			<p class="groups_contacts_list"><a href="{$DIR_WEB_ROOT}/profile/{$oBlogAdministrator->getUserLogin()}/" class="auth"><img  class="img_border" src="{$oBlogAdministrator->getUserProfileAvatarPath(48)}" width="48" height="48" alt="" title="{$oBlogAdministrator->getUserLogin()}" border="0"></a><br><a href="{$DIR_WEB_ROOT}/profile/{$oBlogAdministrator->getUserLogin()}/" class="groups_auth">{$oBlogAdministrator->getUserLogin()}</a></p>
   		{/foreach}     		
    	{/if}
     </td></tr></table>
  
    <table class="part_company"><tr><td>
   		<h1>Модераторы</h1>
      	{if $aBlogModerators}
 		{foreach from=$aBlogModerators item=oBlogModerator}
  			<p class="groups_contacts_list"><a href="{$DIR_WEB_ROOT}/profile/{$oBlogModerator->getUserLogin()}/" class="auth"><img  class="img_border" src="{$oBlogModerator->getUserProfileAvatarPath(48)}" width="48" height="48" alt="" title="{$oBlogModerator->getUserLogin()}" border="0"></a><br><a href="{$DIR_WEB_ROOT}/profile/{$oBlogModerator->getUserLogin()}/" class="groups_auth">{$oBlogModerator->getUserLogin()}</a></p>
   		{/foreach}  
   		{else}
   	 		Модераторов здесь не замеченно
    	{/if}
     </td></tr></table>  
  
    <div id="userslist">
    	<div class="company_part">
 	<h1>Пользователи ({$oBlog->getCountUser()})</h1>

 	<table class="part_company_01"><tr><td>
 	{if $aBlogUsers}
 	{foreach from=$aBlogUsers item=oBlogUser}
  		<p class="groups_contacts_list"><a href="{$DIR_WEB_ROOT}/profile/{$oBlogUser->getUserLogin()}/" class="auth"><img  class="img_border" src="{$oBlogUser->getUserProfileAvatarPath(48)}" width="48" height="48" alt="" title="{$oBlogUser->getUserLogin()}" border="0"></a><br><a href="{$DIR_WEB_ROOT}/profile/{$oBlogUser->getUserLogin()}/" class="groups_auth">{$oBlogUser->getUserLogin()}</a></p>
   	{/foreach}  
   	{else}
   	 	Пользователей здесь не замеченно
    {/if}
  </td></tr></table>

		</div>
	</div>
<br/>	
	



{include file='footer.tpl'}

