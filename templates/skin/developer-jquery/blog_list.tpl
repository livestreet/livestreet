<table class="table table-blogs">
	{if $bBlogsUseOrder}
		<thead>
			<tr>
				<th class="cell-name"><a href="{$sBlogsRootPage}?order=blog_title&order_way={if $sBlogOrder=='blog_title'}{$sBlogOrderWayNext}{else}{$sBlogOrderWay}{/if}" {if $sBlogOrder=='blog_title'}class="{$sBlogOrderWay}"{/if}>{$aLang.blogs_title}</a></th>

				{if $oUserCurrent}
					<th class="cell-join">{$aLang.blog_join_leave}</th>
				{/if}

				<th class="cell-readers">
					<a href="{$sBlogsRootPage}?order=blog_count_user&order_way={if $sBlogOrder=='blog_count_user'}{$sBlogOrderWayNext}{else}{$sBlogOrderWay}{/if}" {if $sBlogOrder=='blog_count_user'}class="{$sBlogOrderWay}"{/if}>{$aLang.blogs_readers}</a>
				</th>
				<th class="cell-rating align-center"><a href="{$sBlogsRootPage}?order=blog_rating&order_way={if $sBlogOrder=='blog_rating'}{$sBlogOrderWayNext}{else}{$sBlogOrderWay}{/if}" {if $sBlogOrder=='blog_rating'}class="{$sBlogOrderWay}"{/if}>{$aLang.blogs_rating}</a></th>
			</tr>
		</thead>
	{else}
		<thead>
			<tr>
				<th class="cell-name">{$aLang.blogs_title}</th>

				{if $oUserCurrent}
					<th class="cell-join">{$aLang.blog_join_leave}</th>
				{/if}

				<th class="cell-readers">{$aLang.blogs_readers}</th>
				<th class="cell-rating align-center">{$aLang.blogs_rating}</th>
			</tr>
		</thead>
	{/if}
	
	
	<tbody>
		{if $aBlogs}
			{foreach from=$aBlogs item=oBlog}
				{assign var="oUserOwner" value=$oBlog->getOwner()}

				<tr>
					<td class="cell-name">
						<a href="{$oBlog->getUrlFull()}">
							<img src="{$oBlog->getAvatarPath(48)}" width="48" height="48" alt="avatar" class="avatar" />
						</a>
						
						<p>
							<a href="#" onclick="return ls.infobox.showInfoBlog(this,{$oBlog->getId()});" class="icon-question-sign"></a>

							{if $oBlog->getType() == 'close'}
								<i title="{$aLang.blog_closed}" class="icon-lock"></i>
							{/if}

							<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>
						</p>
					</td>

					{if $oUserCurrent}
						<td class="cell-join">
							{if $oUserCurrent->getId() != $oBlog->getOwnerId() and $oBlog->getType() == 'open'}
								<a href="#" onclick="ls.blog.toggleJoin(this, {$oBlog->getId()}); return false;" class="link-dotted">
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

					<td class="cell-readers" id="blog_user_count_{$oBlog->getId()}">{$oBlog->getCountUser()}</td>
					<td class="cell-rating align-center">{$oBlog->getRating()}</td>
				</tr>
			{/foreach}
		{else}
			<tr>
				<td colspan="3">
					{if $sBlogsEmptyList}
						{$sBlogsEmptyList}
					{else}

					{/if}
				</td>
			</tr>
		{/if}
	</tbody>
</table>