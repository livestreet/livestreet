				<table>
					<thead>
						<tr>
							<td class="user">Название и смотритель</td>
							{if $oUserCurrent}
							<td class="join-head"><img src="{$DIR_STATIC_SKIN}/images/join-head.gif" alt="" /></td>
							{/if}
							<td class="readers">Читателей</td>														
							<td class="rating">Рейтинг</td>
						</tr>
					</thead>
					
					<tbody>
						{foreach from=$aBlogs item=oBlog}
						<tr>
							<td class="name">
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/{$oBlog->getUrl()}/"><img src="{$oBlog->getAvatarPath(24)}" alt="" /></a>
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/{$oBlog->getUrl()}/" class="title">{$oBlog->getTitle()|escape:'html'}</a><br />
								Смотритель: <a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oBlog->getUserLogin()}/" class="author">{$oBlog->getUserLogin()}</a>
							</td>
							{if $oUserCurrent}
							<td class="join {if $oBlog->getCurrentUserIsJoin()}active{/if}">
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