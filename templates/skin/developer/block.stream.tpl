<div class="block stream">
	<h3>{$aLang.block_stream}</h3>
	
	<ul class="block-nav">						
		<li><a href="#" id="block_stream_topic" onclick="lsBlockStream.toggle(this,'topic_stream'); return false;">{$aLang.block_stream_topics}</a></li>
		<li class="active"><a href="#" id="block_stream_comment" onclick="lsBlockStream.toggle(this,'comment_stream'); return false;">{$aLang.block_stream_comments}</a></li>
	</ul>					
	
	<div class="block-content">
		{literal}
			<script>
			var lsBlockStream;
			window.addEvent('domready', function() { 
				lsBlockStream=new lsBlockLoaderClass();      						
			});
			</script>
		{/literal}
		{$sStreamComments}
	</div>
	
	<div class="right"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_COMMENTS}/">{$aLang.block_stream_comments_all}</a> | <a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_RSS}/allcomments/">RSS</a></div>
</div>

