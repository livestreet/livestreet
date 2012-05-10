jQuery(document).ready(function($){
	// Хук начала инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_start',[],window);
	 
	// Всплывающие окна
	$('#window_login_form').jqm();
	$('#blog_delete_form').jqm({trigger: '#blog_delete_show'});
	$('#add_friend_form').jqm({trigger: '#add_friend_show'});
	$('#window_upload_img').jqm();
	$('#userfield_form').jqm();
	$('#favourite-form-tags').jqm();
	$('#modal_write').jqm({trigger: '#modal_write_show'});
	$('#foto-resize').jqm({modal: true});
	$('#avatar-resize').jqm({modal: true});

	$('.js-registration-form-show').click(function(){
		if (ls.blocks.switchTab('registration','popup-login')) {
			$('#window_login_form').jqmShow();
		} else {
			window.location=aRouter.registration;
		}
		return false;
	});

	$('.js-login-form-show').click(function(){
		if (ls.blocks.switchTab('login','popup-login')) {
			$('#window_login_form').jqmShow();
		} else {
			window.location=aRouter.login;
		}
		return false;
	});
	
	// Datepicker
	 /**
	  * TODO: навесить языки на datepicker
	  */
	$('.date-picker').datepicker({ 
		dateFormat: 'dd.mm.yy',
		dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
		monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		firstDay: 1
	});
	
	
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
	
	
	$('#toolbar').css({'left': $('#wrapper').offset().left + $('#wrapper').outerWidth() + 10, 'right': 'auto', 'display': 'block'});

	
	// Всплывающие сообщения
	$('.js-title-comment, .js-title-topic').poshytip({
		className: 'infobox-standart',
		alignTo: 'target',
		alignX: 'left',
		alignY: 'center',
		offsetX: 5,
		liveEvents: true,
		showTimeout: 1500
	});

	$('.js-infobox-vote-topic').poshytip({
		content: function() {
			var id = $(this).attr('id').replace('vote_total_topic_','vote-info-topic-');
			return $('#'+id).html();
		},
		className: 'infobox-standart',
		alignTo: 'target',
		alignX: 'center',
		alignY: 'top',
		offsetX: 2,
		liveEvents: true,
		showTimeout: 100
	});
	
	$('.js-tip-help').poshytip({
		className: 'infobox-standart',
		alignTo: 'target',
		alignX: 'right',
		alignY: 'center',
		offsetX: 5,
		liveEvents: true,
		showTimeout: 500
	});

	// подсветка кода
	prettyPrint();
	
	// эмуляция border-sizing в IE
	var inputs = $('input, textarea')
	
	if ($('html').hasClass('ie7')) {
		if (!tinyMCE) $('textarea.mce-editor').addClass('markItUpEditor');
		
		inputs.each(function(i){
			var obj = $(this);
			if (obj.css('box-sizing') == 'border-box') obj.width(2 * obj.width() - obj.outerWidth());
		});
	}
	
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

	
	// Хук конца инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_end',[],window);
});


ls.talk.toggleSearchForm = function() {
	$('.talk-search').toggleClass('opened'); return false;
}

ls.blocks.options.loader = DIR_STATIC_SKIN + '/images/loader-circle.gif';

ls.stream.appendUser = function() {
	var sLogin = $('#stream_users_complete').val();
	if (!sLogin) return;
	
	var url = aRouter['stream']+'subscribeByLogin/';
	var params = {'login':sLogin};
	
	ls.hook.marker('appendUserBefore');
	ls.ajax(url, params, function(data) {
		if (!data.bStateError) {
			$('#stream_no_subscribed_users').remove();
			var checkbox = $('#strm_u_'+data.uid);
			if (checkbox.length) {
				if (checkbox.attr('checked')) {
					ls.msg.error(ls.lang.get('error'),ls.lang.get('stream_subscribes_already_subscribed'));
				} else {
					checkbox.attr('checked', 'on');
					ls.msg.notice(data.sMsgTitle,data.sMsg);
				}
			} else {
				var liElement=$('<li><input type="checkbox" class="streamUserCheckbox input-checkbox" id="usf_u_'+data.uid+'" checked="checked" onClick="if ($(this).get(\'checked\')) {ls.stream.subscribe(\'users\','+data.uid+')} else {ls.stream.unsubscribe(\'users\','+data.uid+')}" /> <a href="'+data.user_web_path+'"><img src="'+data.user_avatar_48+'" alt="avatar" class="avatar" /></a> <a href="'+data.user_web_path+'">'+data.user_login+'</a></li>');
				$('#stream_block_users_list').append(liElement);
				ls.msg.notice(data.sMsgTitle,data.sMsg);
			}
		} else {
			ls.msg.error(data.sMsgTitle,data.sMsg);
		}
	});
};
				
ls.wall.loadReplyNew = function(iPid) {
	var divFirst=$('#wall-reply-container-'+iPid).find('.js-wall-reply-item::last');
	if (divFirst.length) {
		var idMore=divFirst.attr('id').replace('wall-reply-item-','');
	} else {
		var idMore=-1;
	}
	this.loadReply('',idMore,iPid,function(result) {
		if (result.bStateError) {
			ls.msg.error(null, result.sMsg);
		} else {
			if (result.iCountWall) {
				if ($('#wall-reply-container-'+iPid).length == 0) {
					$('#wall-item-'+iPid).find('.wall-item').after('<div class="wall-item-replies"><div id="wall-reply-container-'+iPid+'" class="wall-item-container"></div></div>')
				}
				$('#wall-reply-container-'+iPid).append(result.sText);
			}
			ls.hook.run('ls_wall_loadreplynew_after',[iPid, idMore, result]);
		}
	}.bind(this));
	return false;
};

ls.wall.remove = function(iId) {
	var url = aRouter['profile']+this.options.login+'/wall/remove/';
	var params = {iId: iId};
	ls.hook.marker('removeBefore');
	ls.ajax(url, params, function(result){
		if (result.bStateError) {
			ls.msg.error(null, result.sMsg);
		} else {
			$('#wall-item-'+iId).fadeOut('slow', function() { $(this).remove(); });
			$('#wall-reply-item-'+iId).fadeOut('slow', function() { 
				var rpls = $(this).parent('.wall-item-container').parent();
				
				$(this).remove(); 
				
				if (rpls.children().find('.wall-item-reply').length == 0) {
					rpls.remove();
				}
			});
			ls.hook.run('ls_wall_remove_after',[iId, result]);
		}
	});
	return false;
};