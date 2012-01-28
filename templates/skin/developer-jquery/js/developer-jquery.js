/****************
 * MAIN
 */

jQuery(document).ready(function($){
	// Хук начала инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_start',[],window);
	 
	// Всплывающие окна
	$('#login_form').jqm({trigger: '#login_form_show'});
	$('#blog_delete_form').jqm({trigger: '#blog_delete_show'});
	$('#add_friend_form').jqm({trigger: '#add_friend_show'});
	$('#form_upload_img').jqm();
	$('#userfield_form').jqm();
	
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
	$('#tag_search_form').submit(function(){
		window.location = aRouter['tag']+encodeURIComponent($('#tag_search').val())+'/';
		return false;
	});
	
	
	// Автокомплит
	ls.autocomplete.add($(".autocomplete-tags-sep"), aRouter['ajax']+'autocompleter/tag/', true);
	ls.autocomplete.add($(".autocomplete-users"), aRouter['ajax']+'autocompleter/user/', true);
	ls.autocomplete.add($(".autocomplete-city"), aRouter['ajax']+'autocompleter/city/', false);
	ls.autocomplete.add($(".autocomplete-country"), aRouter['ajax']+'autocompleter/country/', false);

	
	// Скролл
	$(window)._scrollable();
	
	// Хук конца инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_end',[],window);
});



/****************
 * COMMENTS
 */

jQuery(document).ready(function(){
	ls.comments.init();
});



/*****************
 * FAVOURITE
 */


ls.hook.add('ls_favourite_toggle_after',function(idTarget,objFavourite,type,params,result){
	$('#fav_count_'+type+'_'+idTarget).text((result.iCount>0) ? result.iCount : '');
});

/****************
 * TALK
 */




jQuery(document).ready(function($){
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
});

