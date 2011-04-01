var blocks = {
	//==================
	// Опции
	//==================
	
	options: {
		active:  	'active',
		url: {
			block_stream_item_comment: 	aRouter['ajax']+'stream/comment/',
			block_stream_item_topic: 	aRouter['ajax']+'stream/topic/',
			block_blogs_item_top: 		aRouter['ajax']+'blogs/top/',
			block_blogs_item_join: 		aRouter['ajax']+'blogs/join/',
			block_blogs_item_self: 		aRouter['ajax']+'blogs/self/'
		}
	},
	

	//==================
	// Функции
	//==================
	
	load: function(obj, block_id){
		thisObj = this;
		objId = $(obj).attr('id');
		content = $('#'+block_id+'_content');
		
		content.html($('<div />').css('text-align','center').append($('<img>', {src: IMG_PATH_LOADER})));
		
		$('[id^="'+block_id+'_item"]').removeClass(this.options.active);
		$(obj).addClass(this.options.active);
		
		$.getJSON(this.options.url[objId], {security_ls_key: LIVESTREET_SECURITY_KEY}, function(result){
			content.empty();
			
			if (result.bStateError) {
				$.notifier.error(null, result.sMsg);
			} else {
				content.html(result.sText);
			}
		});
	}
}



$(document).ready(function(){
	$('[id^="block_stream_item"]').click(function(){
		blocks.load(this, 'block_stream');
		return false;
	});
	
	$('[id^="block_blogs_item"]').click(function(){
		blocks.load(this, 'block_blogs');
		return false;
	});
});







