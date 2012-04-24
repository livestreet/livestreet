<ul class="block-blog-list">
	{foreach from=$aBlogs item=oBlog}
		<li>
			{if $oBlog->getType()=='close'}<i title="{$aLang.blog_closed}" class="icon icon-lock"></i>{/if}
			<a href="{router page='blog'}{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a> 
			
			<strong>{$oBlog->getRating()}</strong>
		</li>
	{/foreach}
</ul>				