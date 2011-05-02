<table class="table">
	<thead>
		<tr>
			<td>{$aLang.blogs_title}</td>
			{if $oUserCurrent}<td align="center">{$aLang.blog_join_leave}</td>{/if}
			<td align="center">{$aLang.blogs_readers}</td>														
			<td align="center">{$aLang.blogs_rating}</td>
		</tr>
	</thead>
	
	<tbody>
		{foreach from=$aBlogs item=oBlog}
			{assign var="oUserOwner" value=$oBlog->getOwner()}
			<tr>
				<td>
					<a href="{router page='blog'}{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a>
					{if $oBlog->getType()=='close'}<img src="{cfg name='path.static.skin'}/images/lock.png" alt="[x]" title="{$aLang.blog_closed}" />{/if}
				</td>
				{if $oUserCurrent}
					<td align="center">
						{if $oUserCurrent->getId()!=$oBlog->getOwnerId() and $oBlog->getType()=='open'}
							<a href="#" onclick="ls.blog.toggleJoin(this, {$oBlog->getId()}); return false;">
								{if $oBlog->getUserIsJoin()}{$aLang.blog_leave}{else}{$aLang.blog_join}{/if}
							</a>
						{else}
							&mdash;
						{/if}
					</td>
				{/if}
				<td align="center" id="blog_user_count_{$oBlog->getId()}">{$oBlog->getCountUser()}</td>													
				<td align="center"><strong>{$oBlog->getRating()}</strong></td>
			</tr>
		{/foreach}
	</tbody>
</table>