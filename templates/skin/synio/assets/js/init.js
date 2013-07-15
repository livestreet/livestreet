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
	ls.blocks.init();


	/**
	 * Activity
	 */
	ls.stream.init();


	/**
	 * Userfeed
	 */
	ls.userfeed.init();


	/**
	 * Comments
	 */
	ls.comments.init();


	/**
	 * User
	 */
	ls.user.init();


	/**
	 * Captcha
	 */
	ls.captcha.init();


	/**
	 * User Note
	 */
	ls.usernote.init();


	/**
	 * Poll
	 */
	ls.poll.init({
		sAddItemHtml: '<li class="poll-add-item js-poll-add-item">' +
					      '<input type="text" name="answer[]" class="poll-add-item-input js-poll-add-item-input">' +
					      '<i class="icon-synio-remove poll-add-item-remove js-poll-add-item-remove" title="' + ls.lang.get('delete') + '"></i>' +
					  '</li>',
	});


	/**
	 * Photoset
	 */
	$('.js-photoset-type-default-image').prettyPhoto({
		social_tools: '',
		show_title:   false,
		slideshow:    false,
		deeplinking:  false
	});


	/**
	 * Editor
	 */
	ls.editor.init();


	/**
	 * Topic
	 */
	ls.topic.init();


	/**
	 * Vote
	 */
	ls.vote.init();


	/**
	 * Pagination
	 */
	ls.pagination.init();
	

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

	// Инициализация строчки поиска
	(function(){
		var search_show = $('#search-header-show');
		if (!search_show.length) {
			return;
		}
		var search_form = $('#search-header-form');
		var write 		= $('#modal_write_show');

		search_show.click(function(){
			search_form.toggle().find('input[type=text]').focus();
			$(this).toggle();
			write.toggle();
			return false;
		});

		$(document).click(function(){
			if (search_form.find('input[type=text]').val() == '') {
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

	// TODO: load deprecated jQuery 1.9
	$(window).load(function () {
		navMainGroup();
	});

	$(window).resize(function () {
		navMainGroup();
	});
})(jQuery);