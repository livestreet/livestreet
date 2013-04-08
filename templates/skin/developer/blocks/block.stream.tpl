<section class="block block-type-stream">
	<header class="block-header">
		<h3><a href="{router page='comments'}" title="{$aLang.block_stream_comments_all}">{$aLang.block_stream}</a></h3>
		<div class="block-update js-block-stream-update"></div>
	</header>

	{hook run='block_stream_nav_item' assign="sItemsHook"}
	
	<div class="block-content">
		<ul class="nav nav-pills js-block-stream-nav" {if $sItemsHook}style="display: none;"{/if}>
			<li class="active js-block-stream-item" data-type="comment"><a href="#">{$aLang.block_stream_comments}</a></li>
			<li class="js-block-stream-item" data-type="topic"><a href="#">{$aLang.block_stream_topics}</a></li>
			{$sItemsHook}
		</ul>
		
		
		<div
			class="dropdown dropdown-toggle js-block-stream-dropdown js-dropdown-default" 
			data-type="dropdown-toggle" 
			data-option-target="js-dropdown-stream"
			data-option-change-text="true"
			{if !$sItemsHook}style="display: none;"{/if}>{$aLang.block_stream_comments}</div>
		
		<ul class="dropdown-menu js-block-stream-dropdown-items" id="js-dropdown-stream">
			<li class="active js-block-stream-item" data-type="comment"><a href="#">{$aLang.block_stream_comments}</a></li>
			<li class="js-block-stream-item" data-type="topic"><a href="#">{$aLang.block_stream_topics}</a></li>
			{$sItemsHook}
		</ul>
		
		
		<div class="js-block-stream-content">
			{$sStreamComments}
		</div>
	</div>
</section>

