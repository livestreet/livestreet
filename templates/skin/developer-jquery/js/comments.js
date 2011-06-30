var comments = {
	//==================
	// Опции
	//==================
	
	typeComment: {
		topic: {
			url_add: 		aRouter.blog+'ajaxaddcomment/',			
			url_response: 	aRouter.blog+'ajaxresponsecomment/'		
		},
		talk: {
			url_add: 		aRouter.talk+'ajaxaddcomment/',
			url_response: 	aRouter.talk+'ajaxresponsecomment/'
		}
	},
    
    
        iCurrentShowFormComment:0,
	
	
	//==================
	// Функции
	//==================
	
	// Добавляет комментарий
	add: function(formObj, targetId, targetType) {
		var thisObj = this;		
		formObj = $('#'+formObj);

		var params = formObj.serialize() + '&security_ls_key=' + LIVESTREET_SECURITY_KEY;
		
      	$('#form_comment_text').addClass('loader').attr('readonly',true);
                  if (BLOG_USE_TINYMCE) {
                    $('#form_comment input[name=submit_comment]').attr('disabled', 'disabled');
                  }
		$.post(thisObj.typeComment[targetType].url_add, params, function(result){
			if (!result) {
				thisObj.enableFormComment();
				$.notifier.error('Error','Please try again later');  
				return;         
			}      
			if (result.bStateError) {        			
				thisObj.enableFormComment();        			
				$.notifier.error(null,result.sMsg);
			} else {
				thisObj.enableFormComment(); 
				$('#form_comment_text').val('');
				
				// Load new comments
				thisObj.load(targetId, targetType, result.sCommentId, true);        			   								
			}
                            if (BLOG_USE_TINYMCE) {
                                $('#form_comment input[name=submit_comment]').attr('disabled', '');
                            }
		});
	},
	
	
	// Активирует форму
	enableFormComment: function() {
		$('#form_comment_text').removeClass('loader').attr('readonly',false);
	},	
	
	
	// Показывает/скрывает форму комментирования
	toggleCommentForm: function(idComment, bNoFocus) {
		$('#form_comment').appendTo("#reply_"+idComment);
		$('#form_comment_text').val('');
		if (!bNoFocus) $('#form_comment_text').focus();
		$('#form_comment_reply').val(idComment);
                  $("#reply_"+idComment).css('display','block');
	},
	
	
	// Подгружает новые комментарии
	load: function(idTarget, typeTarget, selfIdComment, bNotFlushNew) {
		var thisObj = this;
		var idCommentLast = $("#comment_last_id").val();
		
		// Удаляем подсветку у комментариев
		if (!bNotFlushNew) { $('.comment').each(function(index, item){ $(item).removeClass('new current');}); }
		
		objImg = $('#update-comments');
		objImg.attr('src', DIR_STATIC_SKIN+'/images/update_act.gif');	
		
		var params = { idCommentLast: idCommentLast, idTarget: idTarget, typeTarget: typeTarget, security_ls_key: LIVESTREET_SECURITY_KEY  };
		if (selfIdComment) { params.selfIdComment = selfIdComment; }
		if ($('#comment_use_paging').val()) { params.bUsePaging = 1; }
	
		$.post(thisObj.typeComment[typeTarget].url_response, params, function(result) {  
			objImg.attr('src', DIR_STATIC_SKIN+'/images/update.gif'); 
			
			if (!result) { $.notifier.error('Error','Please try again later'); }      
			if (result.bStateError) {
				$.notifier.error(null,result.sMsg);
			} else {   
				var aCmt = result.aComments;      			
				if (aCmt.length > 0 && result.iMaxIdComment) { $("#comment_last_id").val(result.iMaxIdComment); } 
				if (selfIdComment) { thisObj.toggleCommentForm(0, true); } else { thisObj.setCountNewComment(aCmt.length); }
				
				$.each(aCmt, function(index, item) { thisObj.inject(item.idParent, item.id, item.html); }); 
				
				if (selfIdComment && $('#comment_id_'+selfIdComment).length) { thisObj.scrollToComment(selfIdComment); }
			}
		});
	},
	
	
	// Вставка комментария
	inject: function(idCommentParent, idComment, sHtml) {
		var newComment = $('<div>', {'class': 'comment-wrapper', id: 'comment_wrapper_id_'+idComment}).html(sHtml);		
		if (idCommentParent) {	
			$('#comment_wrapper_id_'+idCommentParent).append(newComment);
		} else {
			$('#comments').append(newComment);
		}	
	},
	
	
	// Удалить/восстановить комментарий
	toggle: function(obj, commentId) {
		var thisObj = this;
		
		$.post(aRouter['ajax']+'comment/delete/', { idComment: commentId, security_ls_key: LIVESTREET_SECURITY_KEY }, function(result){
			if (!result) {
				$.notifier.error('Error','Please try again later');           
			}      
			if (result.bStateError) {        			
				$.notifier.error(null,result.sMsg);
			} else {   
				$.notifier.notice(null,result.sMsg); 
				
				$('#comment_id_'+commentId).removeClass('self new deleted current');
				if (result.bState) {
					$('#comment_id_'+commentId).addClass('deleted');
				}
				$(obj).text(result.sTextToggle);        			        								
			}
		});
	},
	
	
	// Предпросмотр комментария
	preview: function() {
		$("#comment_preview").remove();
		if ($("#form_comment_text").val() == '') return;
		$(".reply").before($("<div>", {id: "comment_preview", 'class': "comment-preview"}));
		ls.tools.textPreview('form_comment_text', false, 'comment_preview');
	},
	
	
	// Устанавливает число новых комментариев
	setCountNewComment: function(count) {
        if (count > 0) {
        	$('#new_comments_counter').css('display','block').text(count);        	
        } else {
			$('#new_comments_counter').text(0).hide();
		}
	},
	
	
	// Вычисляет кол-во новых комментариев
	calcNewComments: function() {
        this.setCountNewComment($(".comment.new").length);        	
	},
	
	
	// Переход к следующему комментарию
	goToNextComment: function() {
		var aCommentsNew = $(".comment.new");
		
		$.scrollTo($(aCommentsNew[0]), 1000, {offset: -250});
		$('[id^=comment_id_]').removeClass("current");
		$(aCommentsNew[0]).removeClass("new").addClass("current");
		
		this.setCountNewComment(aCommentsNew.length - 1);
	},
	
	
	// Прокрутка к комментарию
	scrollToComment: function(idComment) {
		$.scrollTo('#comment_id_'+idComment, 1000, {offset: -250});
		$('[id^=comment_id_]').removeClass("current");
		$('#comment_id_'+idComment).addClass("current");
	},

	
	// Прокрутка к родительскому комментарию
	goToParentComment: function(id, pid) {
		thisObj = this;
		$('.goto-comment-child').hide().find('a').unbind();
		
		$("#comment_id_"+pid).find('.goto-comment-child').show().find("a").bind("click", function(){
			$(this).parent('.goto-comment-child').hide();
			thisObj.scrollToComment(id);
			return false;
		});
		this.scrollToComment(pid);
		return false;
	}
}


$(document).ready(function(){
	comments.calcNewComments();
});

if(BLOG_USE_TINYMCE) {
  comments._add = comments.add;
  
  comments.add = function(formObj,targetId,targetType) {
    $('#'+formObj+' textarea').val( tinyMCE.activeEditor.getContent());
    return this._add(formObj,targetId,targetType);
  };  
  
  comments.toggleCommentForm = function(idComment) {
    if (!$('#reply_'+this.iCurrentShowFormComment) || !$('#reply_'+idComment)) {
      return;
    } 
     tinyMCE.activeEditor.setContent('');
    divCurrentForm=$('#reply_'+this.iCurrentShowFormComment);
    divNextForm=$('#reply_'+idComment);
    //var slideCurrentForm = new Fx.Slide(divCurrentForm);
    //var slideNextForm = new Fx.Slide(divNextForm);
    
    tinyMCE.execCommand('mceRemoveControl',true,'form_comment_text');
    
    $('#comment_preview_'+this.iCurrentShowFormComment).html('').css('display','none');
    if (this.iCurrentShowFormComment==idComment) {
      tinyMCE.execCommand('mceAddControl',true,'form_comment_text');
      //slideCurrentForm.toggle();      
      //slideCurrentForm.addEvent('complete', function() {
        //tinyMCE.activeEditor.focus();
      //});
      
      return;
    }
    
    //slideCurrentForm.slideOut();
    divNextForm[0].innerHTML = divCurrentForm.html();
    divCurrentForm.html('');   
    //slideNextForm.hide();
    divNextForm.css('display','block');
    tinyMCE.execCommand('mceAddControl',true,'form_comment_text');
    //slideNextForm.slideIn();
    
    $('#form_comment_text').val('');
    $('#form_comment_reply').val(idComment);
    this.iCurrentShowFormComment=idComment;
    //slideNextForm.addEvent('complete', function() {
      //tinyMCE.activeEditor.focus();
    //});
  }
}