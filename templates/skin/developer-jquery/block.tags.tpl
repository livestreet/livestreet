<section class="block">
	<header class="block-header">
		<h3>{$aLang.block_tags}</h3>
	</header>
	
	
	<div class="block-content">
		{if $aTags}
			<ul class="tag-cloud">						
				{foreach from=$aTags item=oTag}
					<li><a class="tag-size-{$oTag->getSize()}" href="{router page='tag'}{$oTag->getText()|escape:'url'}/">{$oTag->getText()|escape:'html'}</a></li>	
				{/foreach}
			</ul>
		{else}	
			<div class="notice-empty">{$aLang.block_empty_no_tags}</div>
		{/if}
	</div>
</section>