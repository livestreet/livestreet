<div class="block stream">
	<h2>{$aLang.block_stream}</h2>
	
	<ul class="switcher">						
		<li class="active"><a href="#" id="block_stream_comment" onclick="lsBlockStream.toggle(this,'comment_stream'); return false;">{$aLang.block_stream_comments}</a></li>
		<li><a href="#" id="block_stream_topic" onclick="lsBlockStream.toggle(this,'topic_stream'); return false;">{$aLang.block_stream_topics}</a></li>
		{hook run='block_stream_nav_item'}
	</ul>					
	
	<div class="block-content">
		{literal}
			<script language="JavaScript" type="text/javascript">
			var lsBlockStream;
			window.addEvent('domready', function() { 
				lsBlockStream=new lsBlockLoaderClass();
			});
			</script>
		{/literal}					
		
		{$sStreamComments}
	</div>
</div>

