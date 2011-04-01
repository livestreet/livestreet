<div class="block stream" id="block_stream">
	<h2>{$aLang.block_stream}</h2>
	
	
	<ul class="switcher-block">						
		<li id="block_stream_item_comment" class="active">{$aLang.block_stream_comments}</li>
		<li id="block_stream_item_topic">{$aLang.block_stream_topics}</li>
		
		{hook run='block_stream_nav_item'}
	</ul>					
	
	
	<div class="block-content" id="block_stream_content">
		{$sStreamComments}
	</div>
</div>

