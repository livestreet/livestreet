/**
 * Комментарии
 */

var ls = ls || {};

ls.comments = (function ($) {
	/**
	 * Опции
	 */
	this.defaults = {
		type: {
			topic: {
				url_add: 		aRouter.blog+'ajaxaddcomment/',
				url_response: 	aRouter.blog+'ajaxresponsecomment/'
			},
			talk: {
				url_add: 		aRouter.talk+'ajaxaddcomment/',
				url_response: 	aRouter.talk+'ajaxresponsecomment/'
			}
		},
		classes: {
			form_loader: 'loading',
			folding: 'comment-folding',
			comment_new: 'comment-new',
			comment_current: 'comment-current',
			comment_deleted: 'comment-deleted',
			comment_self: 'comment-self',
			comment: 'comment',
			comment_goto_parent: 'comment-goto-parent',
			comment_goto_child: 'comment-goto-child'
		},
		wysiwyg: null,
		folding: true
	};

	this.iCurrentShowFormComment=0;
	this.iCurrentViewComment=null;
	this.aCommentNew=[];
	
	/**
	 * Init
	 * @param  {Object} options Options
	 */
	this.init = function(options) {
		this.options = $.extend({}, this.defaults, options);

		this.initEvent();
		this.calcNewComments();
		this.checkFolding();
		this.toggleCommentForm(this.iCurrentShowFormComment);
		
		if (typeof(this.options.wysiwyg)!='number') {
			this.options.wysiwyg = Boolean(WYSIWYG && tinyMCE);
		}
		ls.hook.run('ls_comments_init_after',[],this);
	};
	
	this.initEvent = function() {
		$('#form_comment_text').bind('keyup', function(e) {
			key = e.keyCode || e.which;
			if(e.ctrlKey && (key == 13)) {
				$('#comment-button-submit').click();
				return false;
			}
		});
		
		if(this.options.folding){
			$('.' + this.options.classes.folding).click(function(e){
				if ($(e.target).hasClass("folded")) {
					this.expandComment(e.target);
				} else {
					this.collapseComment(e.target);
				}
			}.bind(this));
		}
	};

	// Добавляет комментарий
	this.add = function(formObj, targetId, targetType) {
		if (this.options.wysiwyg) {
			$('#'+formObj+' textarea').val(tinyMCE.activeEditor.getContent());
		}
		formObj = $('#'+formObj);

		$('#form_comment_text').addClass(this.options.classes.form_loader).attr('readonly',true);
		$('#comment-button-submit').attr('disabled', 'disabled');
		
		ls.ajax(this.options.type[targetType].url_add, formObj.serializeJSON(), function(result){
			$('#comment-button-submit').removeAttr('disabled');
			if (!result) {
				this.enableFormComment();
				ls.msg.error('Error','Please try again later');
				return;
			}
			if (result.bStateError) {
				this.enableFormComment();
				ls.msg.error(null,result.sMsg);
			} else {
				this.enableFormComment();
				$('#form_comment_text').val('');

				// Load new comments
				this.load(targetId, targetType, result.sCommentId, true);
				ls.hook.run('ls_comments_add_after',[formObj, targetId, targetType, result]);
			}
		}.bind(this));
	};


	// Активирует форму
	this.enableFormComment = function() {
		$('#form_comment_text').removeClass(this.options.classes.form_loader).attr('readonly',false);
	};


	// Показывает/скрывает форму комментирования
	this.toggleCommentForm = function(idComment, bNoFocus) {
		var reply=$('#reply');
		if(!reply.length){
			return;
		}
		$('#comment_preview_' + this.iCurrentShowFormComment).remove();

		if (this.iCurrentShowFormComment==idComment && reply.is(':visible')) {
			reply.hide();
			return;
		}
		if (this.options.wysiwyg) {
			tinyMCE.execCommand('mceRemoveControl',true,'form_comment_text');
		}
		reply.insertAfter('#comment_id_'+idComment).show();
		$('#form_comment_text').val('');
		$('#form_comment_reply').val(idComment);
		
		this.iCurrentShowFormComment = idComment;
		if (this.options.wysiwyg) {
			tinyMCE.execCommand('mceAddControl',true,'form_comment_text');
		}
		if (!bNoFocus) $('#form_comment_text').focus();
	};


	// Подгружает новые комментарии
	this.load = function(idTarget, typeTarget, selfIdComment, bNotFlushNew) {		
		var idCommentLast = $("#comment_last_id").val();

		// Удаляем подсветку у комментариев
		if (!bNotFlushNew) { 
			$('.comment').each(function(index, item){ 
				$(item).removeClass(this.options.classes.comment_new+' '+this.options.classes.comment_current);
			}.bind(this)); 
		}

		objImg = $('#update-comments');
		objImg.addClass('active');

		var params = { idCommentLast: idCommentLast, idTarget: idTarget, typeTarget: typeTarget };
		if (selfIdComment) { 
			params.selfIdComment = selfIdComment; 
		}
		if ($('#comment_use_paging').val()) { 
			params.bUsePaging = 1; 
		}

		ls.ajax(this.options.type[typeTarget].url_response, params, function(result) {
			objImg.removeClass('active');

			if (!result) { ls.msg.error('Error','Please try again later'); }
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				var aCmt = result.aComments;
				if (aCmt.length > 0 && result.iMaxIdComment) { 
					$("#comment_last_id").val(result.iMaxIdComment);
					$('#count-comments').text(parseInt($('#count-comments').text())+aCmt.length);

					var streamComments = $('#js-stream-tabs').is(':visible') ? $('#js-stream-tabs [data-name=block-stream-comments]') : $('#js-stream-dropdown [data-name=block-stream-comments]') ;
					(streamComments.length && streamComments.hasClass('active')) && streamComments.tab('activate');
				}
				var iCountOld=0;
				if (bNotFlushNew) {
					iCountOld=this.aCommentNew.length;
				} else {
					this.aCommentNew=[];
				}
				if (selfIdComment) { 
					this.toggleCommentForm(this.iCurrentShowFormComment, true); 
					this.setCountNewComment(aCmt.length-1+iCountOld);
				} else { 
					this.setCountNewComment(aCmt.length+iCountOld); 
				}

				$.each(aCmt, function(index, item) { 
					if (!(selfIdComment && selfIdComment==item.id)) {
						this.aCommentNew.push(item.id);
					}
					this.inject(item.idParent, item.id, item.html); 
				}.bind(this));

				if (selfIdComment && $('#comment_id_'+selfIdComment).length) { 
					this.scrollToComment(selfIdComment);
				}
				this.checkFolding();
				ls.hook.run('ls_comments_load_after',[idTarget, typeTarget, selfIdComment, bNotFlushNew, result]);
			}
		}.bind(this));
	};


	// Вставка комментария
	this.inject = function(idCommentParent, idComment, sHtml) {
		var newComment = $('<div>', {'class': 'comment-wrapper', id: 'comment_wrapper_id_'+idComment}).html(sHtml);
		if (idCommentParent) {
			// Уровень вложенности родителя
			var iCurrentTree = $('#comment_wrapper_id_'+idCommentParent).parentsUntil('#comments').length;
			if(iCurrentTree == ls.registry.get('comment_max_tree')) {
				// Определяем id предыдушего родителя
				var prevCommentParent = $('#comment_wrapper_id_'+idCommentParent).parent();
				idCommentParent = parseInt(prevCommentParent.attr('id').replace('comment_wrapper_id_',''));
			}
			$('#comment_wrapper_id_'+idCommentParent).append(newComment);
		} else {
			$('#comments').append(newComment);
		}
		ls.hook.run('ls_comment_inject_after',arguments,newComment);
	};


	// Удалить/восстановить комментарий
	this.toggle = function(obj, commentId) {
		var url = aRouter['ajax']+'comment/delete/';
		var params = { idComment: commentId };
		
		ls.hook.marker('toggleBefore');
		ls.ajax(url, params, function(result){
			if (!result) {
				ls.msg.error('Error','Please try again later');
			}
			if (result.bStateError) {
				ls.msg.error(null,result.sMsg);
			} else {
				ls.msg.notice(null,result.sMsg);

				$('#comment_id_'+commentId).removeClass(this.options.classes.comment_self+' '+this.options.classes.comment_new+' '+this.options.classes.comment_deleted+' '+this.options.classes.comment_current);
				if (result.bState) {
					$('#comment_id_'+commentId).addClass(this.options.classes.comment_deleted);
				}
				$(obj).text(result.sTextToggle);
				ls.hook.run('ls_comments_toggle_after',[obj,commentId,result]);
			}
		}.bind(this));
	};


	// Предпросмотр комментария
	this.preview = function(divPreview) {
		if (this.options.wysiwyg) {
			$("#form_comment_text").val(tinyMCE.activeEditor.getContent());
		}
		if ($("#form_comment_text").val() == '') return;
		$("#comment_preview_" + this.iCurrentShowFormComment).remove();
		$('#reply').before('<div id="comment_preview_' + this.iCurrentShowFormComment +'" class="comment-preview text"></div>');
		ls.tools.textPreview('form_comment_text', false, 'comment_preview_' + this.iCurrentShowFormComment);
	};


	// Устанавливает число новых комментариев
	this.setCountNewComment = function(count) {
		if (count > 0) {
			$('#new_comments_counter').show().text(count);
		} else {
			$('#new_comments_counter').text(0).hide();
		}
	};


	// Вычисляет кол-во новых комментариев
	this.calcNewComments = function() {
		var aCommentsNew = $('.'+this.options.classes.comment+'.'+this.options.classes.comment_new);
		this.setCountNewComment(aCommentsNew.length);
		$.each(aCommentsNew,function(k,v){
			this.aCommentNew.push(parseInt($(v).attr('id').replace('comment_id_','')));
		}.bind(this));
	};


	// Переход к следующему комментарию
	this.goToNextComment = function() {
		if (this.aCommentNew[0]) {
			if ($('#comment_id_'+this.aCommentNew[0]).length) {
				this.scrollToComment(this.aCommentNew[0]);
			}
			this.aCommentNew.shift();
		}
		this.setCountNewComment(this.aCommentNew.length);
	};


	// Прокрутка к комментарию
	this.scrollToComment = function(idComment) {
		$.scrollTo('#comment_id_'+idComment, 1000, {offset: -250});
						
		if (this.iCurrentViewComment) {
			$('#comment_id_'+this.iCurrentViewComment).removeClass(this.options.classes.comment_current);
		}				
		$('#comment_id_'+idComment).addClass(this.options.classes.comment_current);
		this.iCurrentViewComment=idComment;		
	};


	// Прокрутка к родительскому комментарию
	this.goToParentComment = function(id, pid) {
		thisObj = this;
		$('.'+this.options.classes.comment_goto_child).hide().find('a').unbind();

		$("#comment_id_"+pid).find('.'+this.options.classes.comment_goto_child).show().find("a").bind("click", function(){
			$(this).parent('.'+thisObj.options.classes.comment_goto_child).hide();
			thisObj.scrollToComment(id);
			return false;
		});
		this.scrollToComment(pid);
		return false;
	};


	// Сворачивание комментариев
	this.checkFolding = function() {
		if(!this.options.folding){
			return false;
		}
		$('.' + this.options.classes.folding).each(function(index, element){
			if ($(element).parent(".comment").next(".comment-wrapper").length == 0) {
				$(element).hide();
			} else {
				$(element).show();
			}
		});
		return false;
	};
	
	this.expandComment = function(folding) {
		$(folding).removeClass("folded").parent().nextAll(".comment-wrapper").show();
	};
	
	this.collapseComment = function(folding) {
		$(folding).addClass("folded").parent().nextAll(".comment-wrapper").hide();
	};

	this.expandCommentAll = function() {
		$.each($('.' + this.options.classes.folding),function(k,v){
			this.expandComment(v);
		}.bind(this));
	};
	
	this.collapseCommentAll = function() {
		$.each($('.' + this.options.classes.folding),function(k,v){
			this.collapseComment(v);
		}.bind(this));
	};

	return this;
}).call(ls.comments || {},jQuery);
