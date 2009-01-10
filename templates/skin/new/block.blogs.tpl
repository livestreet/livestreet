			<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>Блоги</h1>
					
					<ul class="block-nav">
						<li class="active"><strong></strong><a href="#" id="block_blogs_top" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">Топ</a></li>
						<li><a href="#" id="block_blogs_join" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">Подключенные</a></li>
						<li><a href="#" id="block_blogs_self" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">Мои</a><em></em></li>
					</ul>
					
					<div class="block-content">
					{literal}
						<script>
						var lsBlockBlogs;
						window.addEvent('domready', function() {       
							lsBlockBlogs=new lsBlockLoaderClass();	
      						lsBlockBlogs.toggle($('block_blogs_top'),'blogs_top');
						});
						</script>
					{/literal}
					</div>
					
					<div class="right"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOGS}/">Все блоги</a></div>

					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>
