<ul class="list">
	{foreach from=$aBlogs item=oBlog}
		<li>
			<a href="{router page='blog'}{$oBlog->getUrl()}/">{$oBlog->getTitle()|escape:'html'}</a> 
			{if $oBlog->getType()=='close'}<img src="{cfg name='path.static.skin'}/images/lock.png" alt="[x]" title="{$aLang.clean_blog_closed}" />{/if}
			<span class="rating">{$oBlog->getRating()}</span>
		</li>
	{/foreach}
</ul>				