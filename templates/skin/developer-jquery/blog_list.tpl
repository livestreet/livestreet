<table class="table table-blogs">
	<thead>
		<tr>
			<th class="table-blogs-cell-name">{$aLang.blogs_title}</th>
			
			{if $oUserCurrent}
				<th class="table-blogs-cell-join">{$aLang.blog_join_leave}</th>
			{/if}
			
			<th class="table-blogs-cell-readers">{$aLang.blogs_readers}</th>														
			<th class="table-blogs-cell-rating align-center">{$aLang.blogs_rating}</th>
		</tr>
	</thead>
	
	
	<tbody>
		{foreach from=$aBlogs item=oBlog}
			{assign var="oUserOwner" value=$oBlog->getOwner()}
			
			<tr>
				<td class="table-blogs-cell-name">
					<a href="{router page='blog'}{$oBlog->getUrl()}/">
						<img src="{$oBlog->getAvatarPath(48)}" width="48" height="48" alt="avatar" class="avatar" />
					</a>
				
					<a href="{router page='blog'}{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a>
					
					{if $oBlog->getType() == 'close'}
						<i title="{$aLang.blog_closed}" class="icon-lock"></i>
					{/if}
				</td>
				
				{if $oUserCurrent}
					<td class="table-blogs-cell-join">
						{if $oUserCurrent->getId() != $oBlog->getOwnerId() and $oBlog->getType() == 'open'}
							<a href="#" onclick="ls.blog.toggleJoin(this, {$oBlog->getId()}); return false;">
								{if $oBlog->getUserIsJoin()}
									{$aLang.blog_leave}
								{else}
									{$aLang.blog_join}
								{/if}
							</a>
						{else}
							&mdash;
						{/if}
					</td>
				{/if}
				
				<td class="table-blogs-cell-readers" id="blog_user_count_{$oBlog->getId()}">{$oBlog->getCountUser()}</td>													
				<td class="table-blogs-cell-rating align-center">{$oBlog->getRating()}</td>
			</tr>
		{/foreach}
	</tbody>
</table>