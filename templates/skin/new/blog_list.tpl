				<table>
					<thead>
						<tr>
							<td class="user">{$aLang.blogs_title}</td>
							{if $oUserCurrent}
							<td class="join-head"><img src="{$DIR_STATIC_SKIN}/images/join-head.gif" alt="" /></td>
							{/if}
							<td class="readers">{$aLang.blogs_readers}</td>														
							<td class="rating">{$aLang.blogs_rating}</td>
						</tr>
					</thead>
					
					<tbody>
						{foreach from=$aBlogs item=oBlog}
						{assign var="oUserOwner" value=$oBlog->getOwner()}
						<tr>
							<td class="name">
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/{$oBlog->getUrl()}/"><img src="{$oBlog->getAvatarPath(24)}" alt="" /></a>
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/{$oBlog->getUrl()}/" class="title">{$oBlog->getTitle()|escape:'html'}</a><br />
								{$aLang.blogs_owner}: <a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oUserOwner->getLogin()}/" class="author">{$oUserOwner->getLogin()}</a>
							</td>
							{if $oUserCurrent}
							<td class="join {if $oBlog->getUserIsJoin()}active{/if}">
								{if $oUserCurrent->getId()!=$oBlog->getOwnerId()}
									<a href="#" onclick="ajaxJoinLeaveBlog(this,{$oBlog->getId()}); return false;"></a>
								{/if}
							</td>
							{/if}
							<td id="blog_user_count_{$oBlog->getId()}" class="readers">{$oBlog->getCountUser()}</td>													
							<td class="rating"><strong>{$oBlog->getRating()}</strong></td>
						</tr>
						{/foreach}
					</tbody>
				</table>