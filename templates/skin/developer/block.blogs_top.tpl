<ul class="list">
	{foreach from=$aBlogs item=oBlog}
		<li><div class="total">{$oBlog->getRating()}</div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a></li>						
	{/foreach}
</ul>				