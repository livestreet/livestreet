<div class="block blogs" id="block_blogs">
	<h2>{$aLang.block_blogs}</h2>
	
	
	<ul class="switcher-block">
		<li id="block_blogs_item_top" class="active">{$aLang.block_blogs_top}</li>
		{if $oUserCurrent}
			<li id="block_blogs_item_join">{$aLang.block_blogs_join}</li>
			<li id="block_blogs_item_self">{$aLang.block_blogs_self}</li>
		{/if}
	</ul>
	
	
	<div class="block-content" id="block_blogs_content">
		{$sBlogsTop}
	</div>

	
	<div class="bottom">
		<a href="{router page='blogs'}">{$aLang.block_blogs_all}</a>
	</div>
</div>
