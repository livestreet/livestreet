<script type="text/javascript">
	jQuery(function($){
		if ($('#block-stream-nav').find('li').length > 2) {
			// Transform nav to dropdown
			$('#block-stream-nav').hide();
			$('#block-stream-nav-dropdown').show();
		
		
			// Dropdown
			var trigger = $('#dropdown-stream-trigger');
			var menu 	= $('#dropdown-stream-menu');
			var pos 	= trigger.offset();
			
			
			menu.appendTo('body').css({ 'left': pos.left, 'top': pos.top + 30, 'display': 'none' });
		
			trigger.click(function(){
				menu.slideToggle();
				$(this).toggleClass('opened');
				return false;
			});
			
			menu.find('a').click(function(){
				trigger.removeClass('opened').find('a').text( $(this).text() );
				menu.slideToggle();
			});
			
			
			// Hide menu
			$(document).click(function(){
				trigger.removeClass('opened');
				menu.slideUp();
			});
		
			$('body').on("click", "#dropdown-stream-trigger, #dropdown-stream-menu", function(e) {
				e.stopPropagation();
			});
		}
	});
</script>


<section class="block block-type-stream" id="block_stream">
	<header class="block-header">
		<h3><a href="{router page='comments'}" title="{$aLang.block_stream_comments_all}">{$aLang.block_stream}</a></h3>
	</header>
	
	
	<div class="block-content">
		<ul class="nav nav-pills" id="block-stream-nav">						
			<li id="block_stream_item_comment" class="active"><a href="#">{$aLang.block_stream_comments}</a></li>
			<li id="block_stream_item_topic"><a href="#">{$aLang.block_stream_topics}</a></li>
			{hook run='block_stream_nav_item'}
		</ul>
		
		<ul class="nav nav-pills" id="block-stream-nav-dropdown" style="display: none">
			<li class="dropdown active" id="dropdown-stream-trigger"><a href="#">{$aLang.block_stream_comments}</a> <i class="arrow"></i>
				<ul class="dropdown-menu" id="dropdown-stream-menu">
					<li id="block_stream_item_comment" class="active"><a href="#">{$aLang.block_stream_comments}</a></li>
					<li id="block_stream_item_topic"><a href="#">{$aLang.block_stream_topics}</a></li>
					{hook run='block_stream_nav_item'}
				</ul>
			</li>
		</ul>
		
		
		<div id="block_stream_content">
			{$sStreamComments}
		</div>
	</div>
</section>

