Function.prototype.bind = function(context) {
	var fn = this;
	return function() {
		return fn.apply(context, arguments);
	};
};

$(document).ready(function(){
	// Всплывающие окна
	$('#login_form').jqm({trigger: '#login_form_show'});
	$('#blog_delete_form').jqm({trigger: '#blog_delete_show'});
	$('#add_friend_form').jqm({trigger: '#add_friend_show'});
	$('#form_upload_img').jqm();
	
	
	// Подключаем редактор
    $('#topic_text').markItUp(mySettings);
	
	
	// Datepicker
	$('.date-picker').datepicker({ 
		dateFormat: 'dd.mm.yy',
		dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
		monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		firstDay: 1
	});
	
	
	// Поиск по тегам
	$('#tag_search_form').submit(function(){
		window.location = DIR_WEB_ROOT+'/tag/'+$('#tag_search').val()+'/';
		return false;
	});
	
	
	// Автокомплит
	autocompleteAdd($(".autocomplete-tags-sep"), aRouter['ajax']+'autocompleter/tag/', true);
	autocompleteAdd($(".autocomplete-users"), aRouter['ajax']+'autocompleter/user/', true);
	autocompleteAdd($(".autocomplete-city"), aRouter['ajax']+'autocompleter/city/', false);
	autocompleteAdd($(".autocomplete-country"), aRouter['ajax']+'autocompleter/country/', false);

	
	// Скролл
	$(window)._scrollable();
});



// ===================
// Разное
// ===================

// Отмечает все чекбоксы
function checkAll(obj, checkbox) {
	$('.'+obj).each(function(index, item){
		if ($(checkbox).attr("checked")) {$(item).attr('checked', true);} else {$(item).attr('checked', false);}
	});	
}


// Предпросмотр
function ajaxTextPreview(textId, save, divPreview) {
	var text = $('#'+textId).val();	
	save = save ? 1 : 0;
	$.post(aRouter['ajax']+'preview/text/', { text: text, save: save, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result){
		if (!result) {
			$.notifier.error('Error','Please try again later');           
		}
		if (result.bStateError) {
			$.notifier.error('Error','Please try again later');
		} else {    	
			if (!divPreview) {
				divPreview = 'text_preview';
			}            	
			if ($('#'+divPreview).length) {
				$('#'+divPreview).html(result.sText);
			}
		}
	});
}


// Загрузка изображения
function ajaxUploadImg(value, sToLoad) {	
	var req = new JsHttpRequest();
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if (req.responseJS.bStateError) {
				$.notifier.error(req.responseJS.sMsgTitle,req.responseJS.sMsg);				
			} else {
				$.markItUp({ replaceWith: req.responseJS.sText} );
				$('#form_upload_img').jqmHide();
			}
		}
	}
	req.open(null, aRouter['ajax']+'upload/image/', true);
	req.send( { value: value, security_ls_key: LIVESTREET_SECURITY_KEY } );
}
