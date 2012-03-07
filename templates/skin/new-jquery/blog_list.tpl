<table class="table blog-list-table table-people">
	<thead>
		<tr>
			<td class="blog-title">{$aLang.blogs_title}</td>
			{if $oUserCurrent}<td class="blog-join-leave">{$aLang.blog_join_leave}</td>{/if}
			<td class="blog-readers-count">{$aLang.blogs_readers}</td>
			<td class="blog-rating">{$aLang.blogs_rating}</td>
		</tr>
	</thead>

	<tbody>
		{foreach from=$aBlogs item=oBlog}
			{assign var="oUserOwner" value=$oBlog->getOwner()}
			<tr>
				<td class="blog-title">
					<a href="{router page='blog'}{$oBlog->getUrl()}/"><img src="{$oBlog->getAvatarPath(24)}" alt="" class="avatar" /></a>
					<a href="{router page='blog'}{$oBlog->getUrl()}/" class="title">{$oBlog->getTitle()|escape:'html'}</a>
					{if $oBlog->getType()=='close'}<img src="{cfg name='path.static.skin'}/images/lock.png" alt="[x]" title="{$aLang.blog_closed}" class="private" />{/if}
					<p>{$aLang.blogs_owner}: <a href="{$oUserOwner->getUserWebPath()}" class="user">{$oUserOwner->getLogin()}</a></p>
				</td>
				{if $oUserCurrent}
					<td class="blog-join-leave">
						{if $oUserCurrent->getId()!=$oBlog->getOwnerId() and $oBlog->getType()=='open'}
							<div onclick="ls.blog.toggleJoin(this, {$oBlog->getId()}); return false;" class="join {if $oBlog->getUserIsJoin()}active{/if}"></div>
						{else}
							&mdash;
						{/if}
					</td>
				{/if}
				<td class="blog-readers-count date" id="blog_user_count_{$oBlog->getId()}">{$oBlog->getCountUser()}</td>
				<td class="blog-rating rating"><strong>{$oBlog->getRating()}</strong></td>
			</tr>
		{/foreach}
	</tbody>
</table>