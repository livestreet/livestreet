			<div class="block stream">

				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.block_stream}</h1>
					
					<ul class="block-nav">						
						<li><strong></strong><a href="#" id="block_stream_topic" onclick="lsBlockStream.toggle(this,'topic_stream'); return false;">{$aLang.block_stream_topics}</a></li>
						<li class="active"><a href="#" id="block_stream_comment" onclick="lsBlockStream.toggle(this,'comment_stream'); return false;">{$aLang.block_stream_comments}</a><em></em></li>
					</ul>					
					
					<div class="block-content">
					{literal}
						<script>
						var lsBlockStream;
						window.addEvent('domready', function() { 
							lsBlockStream=new lsBlockLoaderClass();							     
      						lsBlockStream.toggle($('block_stream_comment'),'comment_stream');
						});
						</script>
					{/literal}
					</div>
					<div class="right"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_COMMENTS}/">{$aLang.block_stream_comments_all}</a> | <a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_RSS}/allcomments/">RSS</a></div>

					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>

