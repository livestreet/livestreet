<ul class="block-blog-list">
	{foreach from=$aBlogs item=oBlog}
		<li>
			{strip}
				<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>
				{if $oBlog->getType()=='close'}<i title="{$aLang.blog_closed}" class="icon-synio-topic-private"></i>{/if}
			{/strip}
			
			<strong>{$oBlog->getRating()}</strong>
		</li>
	{/foreach}
</ul>				