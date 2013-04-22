jQuery(document).ready(function($){
	// Хук начала инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_start',[],window);


	/**
	 * Modals
	 */
	$('.js-modal-default').modal();


	/**
	 * Datepicker
	 */
	$('.date-picker').datepicker();


	/**
	 * Dropdowns
	 */
	$('.js-dropdown-default').dropdown();

	/* User menu */
	$('.js-dropdown-usermenu').dropdown({
		alignX: 'right',
		offsetY: -1,
		onShow: function () {
			$('#user-menu').addClass('opened');
		},
		onHide: function () {
			$('#user-menu').removeClass('opened');
		}
	});

	/* Create */
	$('.js-dropdown-create').dropdown({
		offsetX: -18,
		offsetY: -41,
		effect: 'show',
		duration: 0,
		onInit: function () {
			var self = this;
			this.$target.find('li.active').prependTo(this.$target).find('a').on('click', function (e) {
				e.preventDefault();
				self.hide();
			});
		}
	});


	/**
	 * Popovers
	 */
	$(document).popover({ selector: '.js-popover-default' });

	$(document).popover({
		selector: '.js-popover-blog-info',
		alignX:   'left',
		alignY:   'bottom',
		classes:  'popover-blog-info',
		offsetX:  -20,
		offsetY:  10
	});


	/**
	 * Tooltips
	 */
	$(document).tooltip({
		selector: '.js-tooltip, .js-tooltip-vote-topic'
	});

	$('.js-title-talk').tooltip({
		alignX: 'left',
		alignY: 'center',
		classes: 'tooltip-yellow'
	});

	$('.js-tip-help').tooltip({
		alignX: 'right',
		alignY: 'center'
	});

	if (ls.registry.get('block_stream_show_tip')) {
		$(document).tooltip({
			selector: '.js-title-comment, .js-title-topic',
			alignX: 'left',
			alignY: 'center',
			classes: 'tooltip-yellow',
			delay: 1500
		});
	}


	/**
	 * Autocomplete
	 */
	ls.autocomplete.add($(".autocomplete-tags-sep"), aRouter['ajax']+'autocompleter/tag/', true);
	ls.autocomplete.add($(".autocomplete-tags"), aRouter['ajax']+'autocompleter/tag/', false);
	ls.autocomplete.add($(".autocomplete-users-sep"), aRouter['ajax']+'autocompleter/user/', true);
	ls.autocomplete.add($(".autocomplete-users"), aRouter['ajax']+'autocompleter/user/', false);


	/**
	 * Scroll
	 */
	$(window)._scrollable();


	/**
	 * Toolbar
	 */
	$('#toolbar').toolbar({
		alignTo: '#wrapper',
		align: 'right',
		offsetX: 7,
		offsetY: 0,
		onPosition: function () {
			if (this.$toolbar.find('section').length) {
				var $cont = $('#container');

				if ($(document).width() <= 1100) {
					! $cont.hasClass('no-resize') && $cont.addClass('toolbar-margin');
				} else {
					$cont.removeClass('toolbar-margin');
				}
			}
		}
	});

	ls.toolbar.topic.init(); // Тул-бар топиков
	ls.toolbar.up.init();    // Кнопка "UP"


	/**
	 * Code highlight
	 */
	prettyPrint();


	/**
	 * Blocks
	 */
	ls.blocks.init('stream',{group_items: true, group_min: 3});
	ls.blocks.init('blogs');
	ls.blocks.initSwitch('tags');
	ls.blocks.initSwitch('upload-img');
	ls.blocks.initSwitch('favourite-topic-tags');
	ls.blocks.initSwitch('popup-login');


	/**
	 * Auth modal
	 */
	$('.js-registration-form-show').click(function(){
		if ($('[data-option-target=tab-pane-registration]').length) {
			$('#modal-login').modal('option', 'onShow', function () { $('[data-option-target=tab-pane-registration]').tab('activate') });
			$('#modal-login').modal('show');
		} else {
			window.location=aRouter.registration;
		}
		return false;
	});

	$('.js-login-form-show').click(function(){
		if ($('[data-option-target=tab-pane-login]').length) {
			$('#modal-login').modal('option', 'onShow', function () { $('[data-option-target=tab-pane-login]').tab('activate') });
			$('#modal-login').modal('show');
		} else {
			window.location=aRouter.login;
		}
		return false;
	});


	/**
	 * Preview image
	 */
	$('.js-topic-preview-image').each(function () {
		$(this).imagesLoaded(function () {
			var $this = $(this),
				$preview = $this.closest('.js-topic-preview-loader').removeClass('loading');
				
			$this.height() < $preview.height() && $this.css('top', ($preview.height() - $this.height()) / 2 );
		});
	});


	// Поиск по тегам
	$('.js-tag-search-form').submit(function(){
		var val=$(this).find('.js-tag-search').val();
		if (val) {
			window.location = aRouter['tag']+encodeURIComponent(val)+'/';
		}
		return false;
	});



	// блоки
	ls.hook.add('ls_blocks_init_navigation_after',function(block,count){
		if ($('.js-block-'+block+'-nav').find('li').length >= count) {
			$('.js-block-'+block+'-dropdown-items').css({ 'top': $('.js-block-'+block+'-dropdown-trigger').offset().top + 25 });
		}
	});

	// комментарии
	ls.comments.init();

	// It will be deleted soon
	// TODO: Delete
	ls.blocks.initNavigation = function(block,count) {
		count=count || 3;
		if ($('.js-block-'+block+'-nav').find('li').length >= count) {
			$('.js-block-'+block+'-nav').hide();
			$('.js-block-'+block+'-dropdown').show();
			// Dropdown
			var trigger = $('.js-block-'+block+'-dropdown-trigger');
			var menu 	= $('.js-block-'+block+'-dropdown-items');

			menu.appendTo('body').css({'display': 'none'});
			trigger.click(function(){
				var pos = $(this).offset();
				menu.css({ 'left': pos.left, 'top': pos.top + 30, 'z-index': 2100 });
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

	// избранное
	ls.hook.add('ls_favourite_toggle_after',function(idTarget,objFavourite,type,params,result){
		var favCount = $('#fav_count_'+type+'_'+idTarget);
		favCount.text(result.iCount);
		result.iCount > 0 ? favCount.show() : favCount.hide();
	});

	// вступление в блог
	ls.hook.add('ls_blog_toggle_join_after',function(idBlog,result){
		if (!this.data('onlyText')) {
			this.html('<i class="icon-synio-join"></i><span>'+(result.bState ? ls.lang.get('blog_leave') : ls.lang.get('blog_join'))+'</span>');
			if (result.bState) {
				this.addClass('active');
			} else {
				this.removeClass('active');
			}
		} else {
			if (this.data('buttonAdditional') && $('#'+this.data('buttonAdditional')).length) {
				$('#'+this.data('buttonAdditional')).html(result.bState ? ls.lang.get('blog_leave') : ls.lang.get('blog_join'));
			}
		}
	});

	// лента активности
	ls.hook.add('ls_stream_append_user_after',function(length,data){
		if (length==0) {
			$('#strm_u_'+data.uid).parent().find('a').before('<a href="'+data.user_web_path+'"><img src="'+data.user_avatar_48+'" alt="avatar" class="avatar" /></a> ');
		}
	});

	// стена
	ls.hook.add('ls_wall_loadreplynew_after',function(iPid, idMore, result){
		if (result.iCountWall) {
			if ($('#wall-reply-container-'+iPid).length == 0) {
				$('#wall-item-'+iPid).find('.wall-item').after('<div class="wall-item-replies"><div id="wall-reply-container-'+iPid+'" class="wall-item-container"></div></div>');
				$('#wall-reply-container-'+iPid).append(result.sText);
			}
		}
	});
	ls.hook.add('ls_wall_remove_reply_item_fade',function(iId, result){
		var rpls = $(this).parent('.wall-item-container').parent();
		$(this).remove();
		if (rpls.children().find('.wall-item-reply').length == 0) {
			rpls.remove();
		}
	});
	ls.hook.add('ls_wall_remove_item_fade',function(iId, result){
		$(this).remove();
	});

	// опрос
	ls.hook.add('ls_pool_add_answer_after',function(removeAnchor){
		var removeAnchor = $('<a href="#" class="icon-synio-remove" />').attr('title', ls.lang.get('delete')).click(function(e){
			e.preventDefault();
			return this.removeAnswer(e.target);
		}.bind(ls.poll));
		$(this).find('a').remove();
		$(this).append(removeAnchor);
	});

	// регистрация
	ls.hook.add('ls_user_validate_registration_fields_after',function(aFields, sForm, result){
		$.each(aFields,function(i,aField){
			if (result.aErrors && result.aErrors[aField.field][0]) {
				sForm.find('.form-item-help-'+aField.field).removeClass('active');
			} else {
				sForm.find('.form-item-help-'+aField.field).addClass('active');
			}
		});
	});

	/****************
	 * TALK
	 */

		// Добавляем или удаляем друга из списка получателей
	$('#friends input:checkbox').change(function(){
		ls.talk.toggleRecipient($('#'+$(this).attr('id')+'_label').text(), $(this).attr('checked'));
	});

	// Добавляем всех друзей в список получателей
	$('#friend_check_all').click(function(){
		$('#friends input:checkbox').each(function(index, item){
			ls.talk.toggleRecipient($('#'+$(item).attr('id')+'_label').text(), true);
			$(item).attr('checked', true);
		});
		return false;
	});

	// Удаляем всех друзей из списка получателей
	$('#friend_uncheck_all').click(function(){
		$('#friends input:checkbox').each(function(index, item){
			ls.talk.toggleRecipient($('#'+$(item).attr('id')+'_label').text(), false);
			$(item).attr('checked', false);
		});
		return false;
	});

	// Удаляем пользователя из черного списка
	$("#black_list_block").delegate("a.delete", "click", function(){
		ls.talk.removeFromBlackList(this);
		return false;
	});

	// Удаляем пользователя из переписки
	$("#speaker_list_block").delegate("a.delete", "click", function(){
		ls.talk.removeFromTalk(this, $('#talk_id').val());
		return false;
	});


	// Help-tags link
	$('.js-tags-help-link').click(function(){
		var target=ls.registry.get('tags-help-target-id');
		if (!target || !$('#'+target).length) {
			return false;
		}
		target=$('#'+target);
		if ($(this).data('insert')) {
			var s=$(this).data('insert');
		} else {
			var s=$(this).text();
		}
		$.markItUp({target: target, replaceWith: s});
		return false;
	});

	// Инициализация строчки поиска
	(function(){
		var search_show = $('#search-header-show');
		if (!search_show.length) {
			return;
		}
		var search_form = $('#search-header-form');
		var write 		= $('#modal_write_show');

		search_show.click(function(){
			search_form.toggle().find('.input-text').focus();
			$(this).toggle();
			write.toggle();
			return false;
		});

		$(document).click(function(){
			if (search_form.find('.input-text').val() == '') {
				search_form.hide();
				search_show.show();
				write.show();
			}
		});

		$('body').on('click', '#search-header-form', function(e) {
			e.stopPropagation();
		});
	})();


	ls.talk.toggleSearchForm = function() {
		$('.talk-search').toggleClass('opened'); return false;
	};

	ls.blocks.options.loader = DIR_STATIC_SKIN + '/images/loader-circle.gif';

	ls.blog.toggleInfo = function() {
		if ($('#blog-mini').is(':visible')) {
			$('#blog-mini').hide();
			$('#blog').show();
		} else {
			$('#blog-mini').show();
			$('#blog').hide();
		}

		return false;
	};

	// Хук конца инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_end',[],window);
});



/**
 * Nav Main
 *
 * Группировка не влезающих пунктов в главном меню
 */

(function($) {
    "use strict";

	function navMainGroup() {
		var nav           = $('#nav-main'),
			ddm           = $('#dropdown-mainmenu-menu'),
			ddi           = nav.find('#nav-main-more'),
			currentWidth  = 0,
			isOutofbox    = false;

		ddi.hide().find('a').dropdown('hide');
		ddm.empty();

		nav.find('li').not(ddi).show().each(function() {
			var item = $(this);

			if ( ! isOutofbox ) {
				currentWidth += item.outerWidth(true);

				if ( nav.width() - currentWidth < ddi.outerWidth(true) ) {
					ddi.show();
					isOutofbox = true;
				}
			}

			if (isOutofbox) {
				item.hide().clone().show().appendTo(ddm);
			}
		});
	}

	$(window).load(function () {
		navMainGroup();
	});

	$(window).resize(function () {
		navMainGroup();
	});
})(jQuery);