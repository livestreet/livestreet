<div class="block blogs">	
	<h3>{$aLang.block_blogs}</h3>
	
	<ul class="block-nav">
		<li class="active"><a href="#" id="block_blogs_top" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">{$aLang.block_blogs_top}</a></li>
		{if $oUserCurrent}
			<li><a href="#" id="block_blogs_join" onclick="lsBlockBlogs.toggle(this,'blogs_join'); return false;">{$aLang.block_blogs_join}</a></li>
			<li><a href="#" id="block_blogs_self" onclick="lsBlockBlogs.toggle(this,'blogs_self'); return false;">{$aLang.block_blogs_self}</a></li>
		{/if}
	</ul>
	
	<div class="block-content">
		{literal}
			<script>
			var lsBlockBlogs;
			window.addEvent('domready', function() {       
				lsBlockBlogs=new lsBlockLoaderClass();
			});
			</script>
		{/literal}
		{$sBlogsTop}
	</div>
	
	<div class="right"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOGS}/">{$aLang.block_blogs_all}</a></div>
</div>
