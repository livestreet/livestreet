<ul class="list">
	{foreach from=$aBlogs item=oBlog}
		<li><div class="total">{$oBlog->getRating()}</div><a href="{router page='blog'}{$oBlog->getUrl()}/" {if $oBlog->getType()=='close'}class="close"{/if}>{$oBlog->getTitle()|escape:'html'}</a></li>						
	{/foreach}
</ul>				