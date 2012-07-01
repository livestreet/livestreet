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
			stream_comment: {
				url: aRouter['ajax']+'stream/comment/'
			},
			stream_topic: {
				url: aRouter['ajax']+'stream/topic/'
			},
			blogs_top: {
				url: aRouter['ajax']+'blogs/top/'
			},
			blogs_join: {
				url: aRouter['ajax']+'blogs/join/'
			},
			blogs_self: {
				url: aRouter['ajax']+'blogs/self/'
			}
		}
	};

	/**
	* Метод загрузки содержимого блока
	*/
	this.load = function(obj, block, params){
		var type = $(obj).data('type');
		ls.hook.marker('loadBefore');
		
		if(!type) return;
		type=block+'_'+type;
		
		params=$.extend(true,{},this.options.type[type].params || {},params || {});
		
		var content = $('.js-block-'+block+'-content');
		this.showProgress(content);

		$('.js-block-'+block+'-item').removeClass(this.options.active);
		$(obj).addClass(this.options.active);

		ls.ajax(this.options.type[type].url, params, function(result){
			var args = [content,result];
			ls.hook.marker('onLoadBefore');
			this.onLoad.apply(this,args);
		}.bind(this));
	};

	/**
	 * Переключает вкладки в блоке, без использования Ajax
	 * @param obj
	 * @param block
	 */
	this.switchTab = function(obj, block) {
		/**
		 * Если вкладку передаем как строчку - значение data-type
		 */
		if (typeof(obj)=='string') {
			$('.js-block-'+block+'-item').each(function(k,v){
				if ($(v).data('type')==obj) {
					obj=v;
					return;
				}
			});
		}
		/**
		 * Если не нашли такой вкладки
		 */
		if (typeof(obj)=='string') {
			return false;
		}

		$('.js-block-'+block+'-item').removeClass(this.options.active);
		$(obj).addClass(this.options.active);

		$('.js-block-'+block+'-content').hide();
		$('.js-block-'+block+'-content').each(function(k,v){
			if ($(v).data('type')==$(obj).data('type')) {
				$(v).show();
			}
		});
		ls.hook.run('ls_blocks_switch_tab_after',[obj, block],this);
		return true;
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
	this.onLoad = function(content,result) {
		$(this).trigger('loadSuccessful',arguments);
		content.empty();
		if (result.bStateError) {
			ls.msg.error(null, result.sMsg);
		} else {
			content.html(result.sText);
			ls.hook.run('ls_block_onload_html_after',arguments,this);
		}
	};

	this.getCurrentItem = function(block) {
		if ($('.js-block-'+block+'-nav').is(':visible')) {
			return $('.js-block-'+block+'-nav').find('.js-block-'+block+'-item.'+this.options.active);
		} else {
			return $('.js-block-'+block+'-dropdown-items').find('.js-block-'+block+'-item.'+this.options.active);
		}
	};

	this.initSwitch = function(block) {
		$('.js-block-'+block+'-item').click(function(){
			ls.blocks.switchTab(this, block);
			return false;
		});
	};

	this.init = function(block,params) {
		params=params || {};
		$('.js-block-'+block+'-item').click(function(){
			ls.blocks.load(this, block);
			return false;
		});
		if (params.group_items) {
			this.initNavigation(block,params.group_min);
		}

		var $this=this;
		$('.js-block-'+block+'-update').click(function(){
			$(this).addClass('active');
			ls.blocks.load($this.getCurrentItem(block), block);
			setTimeout( function() { $(this).removeClass('active'); }.bind(this), 600 );
		});
	};

	this.initNavigation = function(block,count) {
		count=count || 3;
		if ($('.js-block-'+block+'-nav').find('li').length >= count) {
			$('.js-block-'+block+'-nav').hide();
			$('.js-block-'+block+'-dropdown').show();
			// Dropdown
			var trigger = $('.js-block-'+block+'-dropdown-trigger');
			var menu 	= $('.js-block-'+block+'-dropdown-items');
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
			$('body').on('click', '.js-block-'+block+'-dropdown-trigger, .js-block-'+block+'-dropdown-items', function(e) {
				e.stopPropagation();
			});
		
			$(window).resize(function(){
				menu.css({ 'left': $('.js-block-'+block+'-dropdown-trigger').offset().left });
			});
		} else {
			// Transform nav to dropdown
			$('.js-block-'+block+'-nav').show();
			$('.js-block-'+block+'-dropdown').hide();
		}
		ls.hook.run('ls_blocks_init_navigation_after',[block,count],this);
	};

	return this;
}).call(ls.blocks || {},jQuery);