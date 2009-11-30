{include file='header.tpl' menu='blog'}
{assign var="oUserOwner" value=$oBlog->getOwner()}
{assign var="oVote" value=$oBlog->getVote()}

{literal}
<script language="JavaScript" type="text/javascript">
function toggleBlogInfo(id,link) {
	link=$(link);
	var obj=$(id);	
	var slideObj = new Fx.Slide(obj);
	if (obj.getStyle('display')=='none') {
		slideObj.hide();
		obj.setStyle('display','block');		
	}	
	link.toggleClass('inactive');
	slideObj.toggle();
}

function toggleBlogDeleteForm(id,link) {
	link=$(link);
	var obj=$(id);	
	var slideObj = new Fx.Slide(obj);
	if (obj.getStyle('display')=='none') {
		slideObj.hide();
		obj.setStyle('display','block');
	}	
	link.toggleClass('inactive');
	slideObj.toggle();
	
}
</script>
{/literal}

			<div class="profile-blog">							
				<div class="voting {if $oBlog->getRating()>=0}positive{else}negative{/if} {if !$oUserCurrent || $oBlog->getOwnerId()==$oUserCurrent->getId()}guest{/if} {if $oVote} voted {if $oVote->getDirection()>0}plus{elseif $oVote->getDirection()<0}minus{/if}{/if}">
					<div class="clear">{$aLang.blog_rating}</div>
					
					<a href="#" class="plus" onclick="lsVote.vote({$oBlog->getId()},this,1,'blog'); return false;"></a>
					<div class="total">{if $oBlog->getRating()>0}+{/if}{$oBlog->getRating()}</div>
					<a href="#" class="minus" onclick="lsVote.vote({$oBlog->getId()},this,-1,'blog'); return false;"></a>
					
					<div class="clear"></div>
					<div class="text">{$aLang.blog_vote_count}:</div><div class="count">{$oBlog->getCountVote()}</div>
				</div>

				<img src="{$oBlog->getAvatarPath(24)}" alt="avatar" class="avatar" />
				<h1 class="title"><a href="#" class="title-link" onclick="toggleBlogInfo('blog_about_{$oBlog->getId()}',this); return false;"><span>{$oBlog->getTitle()|escape:'html'}</span><strong>&nbsp;&nbsp;</strong></a></h1>
				<ul class="action">
					<li class="rss"><a href="{router page='rss'}blog/{$oBlog->getUrl()}/"></a></li>					
					{if $oUserCurrent and $oUserCurrent->getId()!=$oBlog->getOwnerId()}
						<li class="join {if $oBlog->getUserIsJoin()}active{/if}">
							<a href="#" onclick="ajaxJoinLeaveBlog(this,{$oBlog->getId()}); return false;"></a>
						</li>
					{/if}
					{if $oUserCurrent and ($oUserCurrent->getId()==$oBlog->getOwnerId() or $oUserCurrent->isAdministrator() or $oBlog->getUserIsAdministrator() )}
  						<li class="edit"><a href="{router page='blog'}edit/{$oBlog->getId()}/" title="{$aLang.blog_edit}">{$aLang.blog_edit}</a></li>
 						{if $oUserCurrent->isAdministrator()}
							<li class="delete">
								<a href="#" title="{$aLang.blog_delete}" onclick="toggleBlogDeleteForm('blog_delete_form',this); return false;">{$aLang.blog_delete}</a> 
								<form id="blog_delete_form" class="hidden" action="{router page='blog'}delete/{$oBlog->getId()}/" method="POST">
									<input type="hidden" value="{$LIVESTREET_SECURITY_KEY}" name="security_ls_key" /> 
									{$aLang.blog_admin_delete_move}:<br /> 
									<select name="topic_move_to">
										<option value="-1">{$aLang.blog_delete_clear}</option>
										{if $aBlogs} 
											<option disabled="disabled">-------------</option>
											{foreach from=$aBlogs item=oBlogDelete}
												<option value="{$oBlogDelete->getId()}">{$oBlogDelete->getTitle()}</option>											
											{/foreach}
										{/if}
									</select>
									<input type="submit" value="{$aLang.blog_delete}" />
								</form></li>						
						{else} 						
  							<li class="delete"><a href="{router page='blog'}delete/{$oBlog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" title="{$aLang.blog_delete}" onclick="return confirm('{$aLang.blog_admin_delete_confirm}');" >{$aLang.blog_delete}</a></li>
  						{/if}
  					{/if}
				</ul>
				<div class="about" id="blog_about_{$oBlog->getId()}" style="display: none;" >
					<div class="tl"><div class="tr"></div></div>

					<div class="content">
					
						<h1>{$aLang.blog_about}</h1>
						<p>
						{$oBlog->getDescription()|nl2br}
						</p>					
						
						<div class="line"></div>
						
						<div class="admins">
							<h1>{$aLang.blog_user_administrators} ({$iCountBlogAdministrators})</h1>							
							
							<ul class="admin-list">				
								<li>
									<dl>
										<dt>
											<a href="{$oUserOwner->getUserWebPath()}"><img src="{$oUserOwner->getProfileAvatarPath(48)}" alt=""  title="{$oUserOwner->getLogin()}"/></a>
										</dt>
										<dd>
											<a href="{$oUserOwner->getUserWebPath()}">{$oUserOwner->getLogin()}</a>
										</dd>
									</dl>
								</li>
								{if $aBlogAdministrators}			
 								{foreach from=$aBlogAdministrators item=oBlogUser}
 								{assign var="oUser" value=$oBlogUser->getUser()}  									
								<li>
									<dl>
										<dt>
											<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(48)}" alt=""  title="{$oUser->getLogin()}"/></a>
										</dt>
										<dd>
											<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
										</dd>
									</dl>
								</li>
								{/foreach}	
								{/if}						
							</ul>
							
						</div>

						
						<div class="moderators">
							<h1>{$aLang.blog_user_moderators} ({$iCountBlogModerators})</h1>
							{if $aBlogModerators}
							<ul class="admin-list">							
 								{foreach from=$aBlogModerators item=oBlogUser}  
 								{assign var="oUser" value=$oBlogUser->getUser()}									
								<li>
									<dl>
										<dt>
											<a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(48)}" alt="" title="{$oUser->getLogin()}" /></a>
										</dt>
										<dd>
											<a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
										</dd>
									</dl>
								</li>
								{/foreach}							
							</ul>
							{else}
   	 							{$aLang.blog_user_moderators_empty}
							{/if}
						</div>
						
						<h1 class="readers">{$aLang.blog_user_readers} ({$iCountBlogUsers})</h1>
						{if $aBlogUsers}
						<ul class="reader-list">
							{foreach from=$aBlogUsers item=oBlogUser}
							{assign var="oUser" value=$oBlogUser->getUser()}
								<li><a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
							{/foreach}
						</ul>
						{else}
   	 						{$aLang.blog_user_readers_empty}
    					{/if}
					</div>
					<div class="bl"><div class="br"></div></div>
				</div>				
			</div>


{if $bCloseBlog}
	<div class="topic">
		{$aLang.blog_close_show}
	</div>
{else}
	{include file='topic_list.tpl'}
{/if}

{include file='footer.tpl'}

