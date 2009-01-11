{include file='header.tpl' menu='blog'}


{literal}
<script>
function toggleBlogInfo(id,link) {
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
				<div class="voting {if $oBlog->getRating()>=0}positive{else}negative{/if} {if !$oUserCurrent || $oBlog->getOwnerId()==$oUserCurrent->getId()}guest{/if} {if $oBlog->getUserIsVote()} voted {if $oBlog->getUserVoteDelta()>0}plus{elseif $oBlog->getUserVoteDelta()<0}minus{/if}{/if}">
					<div class="clear">Рейтинг</div>
					
						<a href="#" class="plus" onclick="lsVote.vote({$oBlog->getId()},this,1,'blog'); return false;"></a>
						<div class="total">{if $oBlog->getRating()>0}+{/if}{$oBlog->getRating()}</div>
						<a href="#" class="minus" onclick="lsVote.vote({$oBlog->getId()},this,-1,'blog'); return false;"></a>
					
					<div class="clear">голосов: <span class="count">{$oBlog->getCountVote()}</span></div>
				</div>

				<img src="{$oBlog->getAvatarPath(24)}" alt="avatar" class="avatar" />
				<h1 class="title"><a href="#" class="title-link" onclick="toggleBlogInfo('blog_about_{$oBlog->getId()}',this); return false;">{$oBlog->getTitle()|escape:'html'}</a><a href="#"><img src="{$DIR_STATIC_SKIN}/images/profile-blog-info.gif" alt="" /></a></h1>
				<ul class="action">
					<li class="rss"><a href="#"></a></li>
					<li class="join"><a href="#"></a></li>
				</ul>
				<div class="about" id="blog_about_{$oBlog->getId()}" style="display: none;" >
					<div class="tl"><div class="tr"></div></div>

					<div class="content">
					
						<h1>О блоге</h1>
						<p>
						{$oBlog->getDescription()|nl2br}
						</p>					
						
						<div class="line"></div>
						
						<div class="admins">
							<h1>Администраторы ({$iCountBlogAdministrators})</h1>							
							
							<ul class="admin-list">				
								<li>
									<dl>
										<dt>
											<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlog->getUserLogin()}/"><img src="{$oBlog->getUserProfileAvatarPath(48)}" alt=""  title="{$oBlog->getUserLogin()}"/></a>
										</dt>
										<dd>
											<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlog->getUserLogin()}/">{$oBlog->getUserLogin()}</a>
										</dd>
									</dl>
								</li>
								{if $aBlogAdministrators}			
 								{foreach from=$aBlogAdministrators item=oBlogAdministrator}  									
								<li>
									<dl>
										<dt>
											<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogAdministrator->getUserLogin()}/"><img src="{$oBlogAdministrator->getUserProfileAvatarPath(48)}" alt=""  title="{$oBlogAdministrator->getUserLogin()}"/></a>
										</dt>
										<dd>
											<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogAdministrator->getUserLogin()}/">{$oBlogAdministrator->getUserLogin()}</a>
										</dd>
									</dl>
								</li>
								{/foreach}	
								{/if}						
							</ul>
							
						</div>

						
						<div class="moderators">
							<h1>Модераторы ({$iCountBlogModerators})</h1>
							{if $aBlogModerators}
							<ul class="admin-list">							
 								{foreach from=$aBlogModerators item=oBlogModerator}  									
								<li>
									<dl>
										<dt>
											<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogModerator->getUserLogin()}/"><img src="{$oBlogModerator->getUserProfileAvatarPath(48)}" alt=""  title="{$oBlogModerator->getUserLogin()}"/></a>
										</dt>
										<dd>
											<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogModerator->getUserLogin()}/">{$oBlogModerator->getUserLogin()}</a>
										</dd>
									</dl>
								</li>
								{/foreach}							
							</ul>
							{else}
   	 							Модераторов здесь не замеченно
							{/if}
						</div>
						
						<h1 class="readers">Читатели блога ({$iCountBlogUsers})</h1>
						{if $aBlogUsers}
						<ul class="reader-list">
							{foreach from=$aBlogUsers item=oBlogUser}
								<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlogUser->getUserLogin()}/">{$oBlogUser->getUserLogin()}</a></li>
							{/foreach}							
						</ul>
						{else}
   	 						Читателей здесь не замеченно
    					{/if}
					</div>
					<div class="bl"><div class="br"></div></div>

				</div>
			</div>



{include file='topic_list.tpl'}


{include file='footer.tpl'}

