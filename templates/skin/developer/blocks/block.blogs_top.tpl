<ul class="item-list">
	{foreach from=$aBlogs item=oBlog}
		<li>
			<a href="{$oBlog->getUrlFull()}"><img src="{$oBlog->getAvatarPath(48)}" alt="avatar" class="avatar" /></a>
			
			{if $oBlog->getType()=='close'}<i title="{$aLang.blog_closed}" class="icon icon-lock"></i>{/if}
			<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>
			
			<p>{$aLang.blog_rating}: <strong>{$oBlog->getRating()}</strong></p>
		</li>
	{/foreach}
</ul>				