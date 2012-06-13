<table class="table table-blogs" cellspacing="0">
	{if $bBlogsUseOrder}
		<thead>
			<tr>
				<th class="cell-info">&nbsp;</th>
				<th class="cell-name cell-tab">
					<div class="cell-tab-inner {if $sBlogOrder=='blog_title'}active{/if}"><a href="{$sBlogsRootPage}?order=blog_title&order_way={if $sBlogOrder=='blog_title'}{$sBlogOrderWayNext}{else}{$sBlogOrderWay}{/if}" {if $sBlogOrder=='blog_title'}class="{$sBlogOrderWay}"{/if}><span>{$aLang.blogs_title}</span></a></div>
				</th>

				{if $oUserCurrent}
					<th class="cell-join">&nbsp;</th>
				{/if}

				<th class="cell-readers cell-tab">
					<div class="cell-tab-inner {if $sBlogOrder=='blog_count_user'}active{/if}"><a href="{$sBlogsRootPage}?order=blog_count_user&order_way={if $sBlogOrder=='blog_count_user'}{$sBlogOrderWayNext}{else}{$sBlogOrderWay}{/if}" {if $sBlogOrder=='blog_count_user'}class="{$sBlogOrderWay}"{/if}><span>{$aLang.blogs_readers}</span></a></div>
				</th>
				<th class="cell-rating cell-tab align-center">
					<div class="cell-tab-inner {if $sBlogOrder=='blog_rating'}active{/if}"><a href="{$sBlogsRootPage}?order=blog_rating&order_way={if $sBlogOrder=='blog_rating'}{$sBlogOrderWayNext}{else}{$sBlogOrderWay}{/if}" {if $sBlogOrder=='blog_rating'}class="{$sBlogOrderWay}"{/if}><span>{$aLang.blogs_rating}</span></a></div>
				</th>
			</tr>
		</thead>
	{else}
		<thead>
			<tr>
				<th class="cell-info">&nbsp;</th>
				<th class="cell-name"><div class="cell-tab">{$aLang.blogs_title}</div></th>

				{if $oUserCurrent}
					<th class="cell-join">&nbsp;</th>
				{/if}

				<th class="cell-readers"><div class="cell-tab">{$aLang.blogs_readers}</div></th>
				<th class="cell-rating cell-tab align-center">
					<div class="cell-tab-inner active"><span>{$aLang.blogs_rating}</span></div>
				</th>
			</tr>
		</thead>
	{/if}
	
	
	<tbody>
		{if $aBlogs}
			{foreach from=$aBlogs item=oBlog}
				{assign var="oUserOwner" value=$oBlog->getOwner()}

				<tr>
					<td class="cell-info">
						<a href="#" onclick="return ls.infobox.showInfoBlog(this,{$oBlog->getId()});" class="blog-list-info"></a>
					</td>
					<td class="cell-name">
						<p>
							<a href="{$oBlog->getUrlFull()}" class="blog-name">{$oBlog->getTitle()|escape:'html'}</a>
							
							{if $oBlog->getType() == 'close'}
								<i title="{$aLang.blog_closed}" class="icon-synio-topic-private"></i>
							{/if}
						</p>
						
						<span class="user-avatar">
							<a href="{$oUserOwner->getUserWebPath()}"><img src="{$oUserOwner->getProfileAvatarPath(24)}" alt="avatar" /></a>
							<a href="{$oUserOwner->getUserWebPath()}">{$oUserOwner->getLogin()}</a>
						</span>
					</td>

					{if $oUserCurrent}
						<td class="cell-join">
							{if $oUserCurrent->getId() != $oBlog->getOwnerId() and $oBlog->getType() == 'open'}
								<button type="submit"  onclick="ls.blog.toggleJoin(this, {$oBlog->getId()}); return false;" class="button button-action button-action-join {if $oBlog->getUserIsJoin()}active{/if}">
									<i class="icon-synio-join"></i>
									<span>{if $oBlog->getUserIsJoin()}{$aLang.blog_leave}{else}{$aLang.blog_join}{/if}</span>
								</button>
							{else}
								&mdash;
							{/if}
						</td>
					{/if}

					<td class="cell-readers" id="blog_user_count_{$oBlog->getId()}">{$oBlog->getCountUser()}</td>
					<td class="cell-rating align-center {if $oBlog->getRating() < 0}negative{/if}">{$oBlog->getRating()}</td>
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