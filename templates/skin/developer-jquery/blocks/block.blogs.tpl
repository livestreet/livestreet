<div class="block" id="block_blogs">
	<header class="block-header">
		<h3>{$aLang.block_blogs}</h3>
	</header>
	
	
	<div class="block-content">
		<ul class="nav nav-pills">
			<li id="block_blogs_item_top" class="active"><a href="#">{$aLang.block_blogs_top}</a></li>
			{if $oUserCurrent}
				<li id="block_blogs_item_join"><a href="#">{$aLang.block_blogs_join}</a></li>
				<li id="block_blogs_item_self"><a href="#">{$aLang.block_blogs_self}</a></li>
			{/if}
		</ul>
		
		
		<div id="block_blogs_content">
			{$sBlogsTop}
		</div>

		
		<footer>
			<a href="{router page='blogs'}">{$aLang.block_blogs_all}</a>
		</footer>
	</div>
</div>
