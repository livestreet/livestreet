jQuery(document).ready(function($){
	// Хук начала инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_start',[],window);

	$('html').removeClass('no-js');

	// Определение браузера
	if ($.browser.opera) {
		$('body').addClass('opera opera' + parseInt($.browser.version));
	}
	if ($.browser.mozilla) {
		$('body').addClass('mozilla mozilla' + parseInt($.browser.version));
	}
	if ($.browser.webkit) {
		$('body').addClass('webkit webkit' + parseInt($.browser.version));
	}
	if ($.browser.msie) {
		$('body').addClass('ie');
		if (parseInt($.browser.version) > 8) {
			$('body').addClass('ie' + parseInt($.browser.version));
		}
	}


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

	// Datepicker
	$('.date-picker').datepicker();

	$('.js-popover-blog-info').popover();

	// Поиск по тегам
	$('.js-tag-search-form').submit(function(){
		window.location = aRouter['tag']+encodeURIComponent($(this).find('.js-tag-search').val())+'/';
		return false;
	});


	// Автокомплит
	ls.autocomplete.add($(".autocomplete-tags-sep"), aRouter['ajax']+'autocompleter/tag/', true);
	ls.autocomplete.add($(".autocomplete-tags"), aRouter['ajax']+'autocompleter/tag/', false);
	ls.autocomplete.add($(".autocomplete-users-sep"), aRouter['ajax']+'autocompleter/user/', true);
	ls.autocomplete.add($(".autocomplete-users"), aRouter['ajax']+'autocompleter/user/', false);


	// Скролл
	$(window)._scrollable();


	// Тул-бар топиков
	ls.toolbar.topic.init();
	// Кнопка "UP"
	ls.toolbar.up.init();


	/**
	 * Modals
	 */
	$('.js-modal-default').modal();


	/**
	 * Tooltips
	 */
	$('.js-tooltip').tooltip();

	$('.js-title-talk').tooltip({
		alignX: 'left',
		alignY: 'center'
	});

	$('.js-tip-help').tooltip({
		alignX: 'right',
		alignY: 'center'
	});

	if (ls.registry.get('block_stream_show_tip')) {
		$('.js-title-comment, .js-title-topic').tooltip({
			alignX: 'left',
			alignY: 'center'
		});
	}




	// подсветка кода
	prettyPrint();

	// эмуляция border-sizing в IE
	var inputs = $('input.input-text, textarea');
	ls.ie.bordersizing(inputs);

	// эмуляция placeholder'ов в IE
	inputs.placeholder();

	// инизиализация блоков
	ls.blocks.init('stream',{group_items: true, group_min: 3});
	ls.blocks.init('blogs');
	ls.blocks.initSwitch('tags');
	ls.blocks.initSwitch('upload-img');
	ls.blocks.initSwitch('favourite-topic-tags');
	ls.blocks.initSwitch('popup-login');

	// комментарии
	ls.comments.options.folding = false;
	ls.comments.init();

	// избранное
	ls.hook.add('ls_favourite_toggle_after',function(idTarget,objFavourite,type,params,result){
		$('#fav_count_'+type+'_'+idTarget).text((result.iCount>0) ? result.iCount : '');
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


	// Фикс бага с z-index у встроенных видео
	$("iframe").each(function(){
		var ifr_source = $(this).attr('src');

		if(ifr_source) {
			var wmode = "wmode=opaque";

			if (ifr_source.indexOf('?') != -1)
				$(this).attr('src',ifr_source+'&'+wmode);
			else
				$(this).attr('src',ifr_source+'?'+wmode);
		}
	});


	// Хук конца инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_end',[],window);
});