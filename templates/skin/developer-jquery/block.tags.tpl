<div class="block">
	<ul class="cloud">						
		{foreach from=$aTags item=oTag}
			<li><a class="w{$oTag->getSize()}" rel="tag" href="{router page='tag'}{$oTag->getText()|escape:'html'}/">{$oTag->getText()|escape:'html'}</a></li>	
		{/foreach}
	</ul>
</div>