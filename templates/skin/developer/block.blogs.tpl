<div class="block blogs">
	<h2>{$aLang.block_blogs}</h2>
	
	<ul class="switcher">
		<li class="active"><a href="#" id="block_blogs_top" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">{$aLang.block_blogs_top}</a></li>
		{if $oUserCurrent}
			<li><a href="#" id="block_blogs_join" onclick="lsBlockBlogs.toggle(this,'blogs_join'); return false;">{$aLang.block_blogs_join}</a></li>
			<li><a href="#" id="block_blogs_self" onclick="lsBlockBlogs.toggle(this,'blogs_self'); return false;">{$aLang.block_blogs_self}</a></li>
		{/if}
	</ul>
	
	<div class="block-content">
		{literal}
			<script language="JavaScript" type="text/javascript">
			var lsBlockBlogs;
			window.addEvent('domready', function() {       
				lsBlockBlogs=new lsBlockLoaderClass();
			});
			</script>
		{/literal}
		{$sBlogsTop}
	</div>

	<div class="bottom">
		<a href="{router page='blogs'}">{$aLang.block_blogs_all}</a>
	</div>
</div>
