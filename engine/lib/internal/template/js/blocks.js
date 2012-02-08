var ls = ls || {};

/**
* Динамическая подгрузка блоков
*/
ls.blocks = (function ($) {
	/**
	* Опции
	*/
	this.options = {
		active: 'active',
		loader: DIR_STATIC_SKIN + '/images/loader.gif',
		type: {
			block_stream_item_comment: {
				url: aRouter['ajax']+'stream/comment/'
			},
			block_stream_item_topic: {
				url: aRouter['ajax']+'stream/topic/'
			},
			block_blogs_item_top: {
				url: aRouter['ajax']+'blogs/top/'
			},
			block_blogs_item_join: {
				url: aRouter['ajax']+'blogs/join/'
			},
			block_blogs_item_self: {
				url: aRouter['ajax']+'blogs/self/'
			}
		}
	};

	/**
	* Метод загрузки содержимого блока
	*/
	this.load = function(obj, block, params){
		var id = $(obj).attr('id');
		'*loadBefore*'; '*/loadBefore*';
		
		if(!id) return;
		
		params=$.extend(true,{},this.options.type[id].params || {},params || {});
		
		var content = $('#'+block+'_content');
		this.showProgress(content);

		$('[id^="'+block+'_item"]').removeClass(this.options.active);
		$(obj).addClass(this.options.active);

		ls.ajax(this.options.type[id].url, params, function(result){
			var args = [content,id,result];
			'*onLoadBefore*'; '*/onLoadBefore*';
			this.onLoad.apply(this,args);
		}.bind(this));
	};

	/**
	* Отображение процесса загрузки
	*/
	this.showProgress = function(content) {
		content.html($('<div />').css('text-align','center').append($('<img>', {src: this.options.loader})));
	};

	/**
	* Обработка результатов загрузки
	*/
	this.onLoad = function(content,id,result) {
		$(this).trigger('load',arguments);
		content.empty();
		if (result.bStateError) {
			ls.msg.error(null, result.sMsg);
		} else {
			content.html(result.sText);
			ls.hook.run('ls_block_onload_html_after',arguments,this);
		}
	};

	return this;
}).call(ls.blocks || {},jQuery);