<div class="block" id="block_blogs">
	<header class="block-header">
		<h3>{$aLang.block_blogs}</h3>
		<div class="block-update js-block-blogs-update"></div>
	</header>
	
	
	<div class="block-content">
		<ul class="nav nav-pills js-block-blogs-nav">
			<li class="active js-block-blogs-item" data-type="top"><a href="#">{$aLang.block_blogs_top}</a></li>
			{if $oUserCurrent}
				<li class="js-block-blogs-item" data-type="join"><a href="#">{$aLang.block_blogs_join}</a></li>
				<li class="js-block-blogs-item" data-type="self"><a href="#">{$aLang.block_blogs_self}</a></li>
			{/if}
		</ul>
		
		
		<div class="js-block-blogs-content">
			{$sBlogsTop}
		</div>

		
		<footer>
			<a href="{router page='blogs'}">{$aLang.block_blogs_all}</a>
		</footer>
	</div>
</div>
