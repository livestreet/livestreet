/**
 * Комментарии
 *
 * @module ls/comments
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.comments = (function ($) {
	"use strict";

	/**
	 * Дефолтные опции
	 *
	 * @private
	 */
	var _defaults = {
		type: {
			topic: {
				url_add: 		aRouter.blog + 'ajaxaddcomment/',
				url_response: 	aRouter.blog + 'ajaxresponsecomment/'
			},
			talk: {
				url_add: 		aRouter.talk + 'ajaxaddcomment/',
				url_response: 	aRouter.talk + 'ajaxresponsecomment/'
			}
		},
		// Селекторы
		selectors: {
			container:  '#comments',
			preview:    '.js-comment-preview',
			wrapper:    '.js-comment-wrapper',
			fold_all:   '.js-comments-fold-all',
			unfold_all: '.js-comments-unfold-all',
			title:      '.js-comments-title',
			reply_root: '.js-comment-reply-root',
			comment: {
				comment:          '.js-comment',
				reply:            '.js-comment-reply',
				fold:             '.js-comment-fold',
				remove:           '.js-comment-remove',
				scroll_to_child:  '.js-comment-scroll-to-child',
				scroll_to_parent: '.js-comment-scroll-to-parent'
			},
			form: {
				form:       '.js-comment-form',
				text:       '#form_comment_text',
				submit:     '.js-comment-form-submit',
				preview:    '.js-comment-form-preview',
				comment_id: '#form_comment_reply'
			},
			toolbar: {
				update:      '#update-comments',
				new_counter: '#new_comments_counter',
				last_id:     '#comment_last_id',
				use_paging:  '#comment_use_paging'
			}
		},
		classes: {
			states: {
				current: 'comment-current',
				new:     'comment-new',
				deleted: 'comment-deleted',
				self:    'comment-self'
			}
		},
		wysiwyg: null,
		folding: true,
		show_form: false
	};

	this.aComments         = $();
	this.aCommentsNew      = $();
	this.iCurrentCommentId = null;
	this.iFormTargetId     = 0;

	/**
	 * Инициализация
	 *
	 * @param {Object} options Опции
	 */
	this.init = function(options) {
		var _this = this,
			oDocument = $(document);

		this.options = $.extend({}, _defaults, options);

		this.elements = {
			container:    $(this.options.selectors.container),
			title:        $(this.options.selectors.title),
			reply_root:   $(this.options.selectors.reply_root),
			fold_all:     $(this.options.selectors.fold_all),
			unfold_all:   $(this.options.selectors.unfold_all),
			form: {
				form:       $(this.options.selectors.form.form),
				text:       $(this.options.selectors.form.text),
				submit:     $(this.options.selectors.form.submit),
				preview:    $(this.options.selectors.form.preview),
				comment_id: $(this.options.selectors.form.comment_id)
			},
			toolbar: {
				update:      $(this.options.selectors.toolbar.update),
				new_counter: $(this.options.selectors.toolbar.new_counter),
				last_id:     $(this.options.selectors.toolbar.last_id),
				use_paging:  $(this.options.selectors.toolbar.use_paging)
			}
		}

		this.aComments = $(this.options.selectors.comment.comment);

		this.calcNewComments();
		this.checkFolding();
		! this.options.show_form && this.formToggle(this.iFormTargetId);

		// Навигация по комментариям
		oDocument.on('click', this.options.selectors.comment.scroll_to_parent, function (e) {
			var oElement = $(this);

			_this.scrollToParentComment(oElement.data('id'), oElement.data('parent-id'));
		});

		// Показывает / скрывает форму комментирования
		oDocument.on('click', this.options.selectors.comment.reply, function (e) {
			_this.formToggle($(this).data('id'));
			e.preventDefault();
		});

		// Подгрузка новых комментариев
		this.elements.toolbar.update.on('click', function (e) {
			var oButton = $(this);

			_this.load(oButton.data('target-id'), oButton.data('target-type'));
			e.preventDefault();
		});

		// Переход к следующему новому комментарию
		this.elements.toolbar.new_counter.on('click', function (e) {
			_this.scrollToNextNewComment();
			e.preventDefault();
		});

		// Превью текста
		this.elements.form.preview.on('click', function (e) {
			_this.previewShow();
		});

		// Добавление
		this.elements.form.form.on('submit', function (e) {
			var oForm = $(this);

			_this.add(oForm, oForm.data('target-id'), oForm.data('target-type'));
			e.preventDefault();
		});

		this.elements.form.text.bind('keyup', function(e) {
			if (e.ctrlKey && (e.keyCode || e.which) == 13) {
				_this.elements.form.form.submit();
			}
		});

		// Удаление
		this.elements.container.on('click', this.options.selectors.comment.remove, function(e) {
			var oElement = $(this),
				iCommentId = oElement.data('id');

			_this.toggle(oElement, iCommentId);

			e.preventDefault();
		});

		// Сворачивание
		if (this.options.folding) {
			// Свернуть все
			this.elements.fold_all.on('click', function (e) {
				_this.foldAll();
				e.preventDefault();
			});

			// Развернуть все
			this.elements.unfold_all.on('click', function (e) {
				_this.unfoldAll();
				e.preventDefault();
			});

			// Свернуть/развернуть
			this.elements.container.on('click', this.options.selectors.comment.fold, function(e) {
				var oElement = $(this),
					oComment = _this.getCommentById(oElement.data('id'));

				_this[ oElement.hasClass(ls.options.classes.states.open) ? 'fold' : 'unfold' ](oComment);

				e.preventDefault();
			});
		}

		ls.hook.run('ls_comments_init_after',[],this);
	};

	/**
	 * Добавляет комментарий
	 */
	this.add = function(oForm, iTargetId, sTargetType) {
		this.formLock();
		this.previewHide();

		ls.ajax.load(this.options.type[sTargetType].url_add, oForm.serializeJSON(), function(oResponse) {
			if (oResponse.bStateError) {
				ls.msg.error(null, oResponse.sMsg);
			} else {
				this.elements.form.text.val('');
				this.load(iTargetId, sTargetType, oResponse.sCommentId, true);

				ls.hook.run('ls_comments_add_after', [oForm, iTargetId, sTargetType, oResponse]);
			}

			this.formUnlock();
		}.bind(this));
	};

	/**
	 * Удалить/восстановить комментарий
	 */
	this.toggle = function(oElement, iCommentId) {
		var sUrl = aRouter['ajax'] + 'comment/delete/',
			oParams = { idComment: iCommentId };

		ls.hook.marker('toggleBefore');

		ls.ajax.load(sUrl, oParams, function(oResponse) {
			if (oResponse.bStateError) {
				ls.msg.error(null, oResponse.sMsg);
			} else {
				ls.msg.notice(null, oResponse.sMsg);

				this.getCommentById(iCommentId).removeClass(this.options.classes.states.self + ' ' + this.options.classes.states.new + ' ' + this.options.classes.states.deleted + ' ' + this.options.classes.states.current);

				if (oResponse.bState) {
					this.getCommentById(iCommentId).addClass(this.options.classes.states.deleted);
				}

				oElement.text(oResponse.sTextToggle);

				ls.hook.run('ls_comments_toggle_after',[oElement, iCommentId, oResponse]);
			}
		}.bind(this));
	};

	/**
	 * Подгружает новые комментарии
	 */
	this.load = function(iTargetId, sTargetType, iCommentSelfId, bNotFlushNew) {
		var idCommentLast = this.elements.toolbar.last_id.val(),
			oUpdate = this.elements.toolbar.update;

		oUpdate.addClass(ls.options.classes.states.active);

		var oParams = {
			idCommentLast: idCommentLast,
			idTarget: iTargetId,
			typeTarget: sTargetType
		};

		oParams.selfIdComment = iCommentSelfId || undefined;
		oParams.bUsePaging = this.elements.toolbar.use_paging.val() ? 1 : 0;

		ls.ajax.load(this.options.type[sTargetType].url_response, oParams, function(oResponse) {
			oUpdate.removeClass(ls.options.classes.states.active);

			if (oResponse.bStateError) {
				ls.msg.error(null, oResponse.sMsg);
			} else {
				var aCommentsLoaded = oResponse.aComments,
					iCountOld = this.aCommentsNew.length;

				// Убираем подсветку новых и текущего комментариев
				this.aComments.removeClass(this.options.classes.states.current);

				if ( ! bNotFlushNew ) {
					this.aCommentsNew.removeClass(this.options.classes.states.new);
					this.aCommentsNew = $();
					iCountOld = 0;
				}

				// Если комментарии подгружаются после сабмита формы текущим пользователем
				if (iCommentSelfId) {
					this.formToggle(this.iFormTargetId, true);
					iCountOld--;
				}

				this.setCountNewComment(aCommentsLoaded.length + iCountOld);

				// Вставляем новые комментарии
				$.each(aCommentsLoaded, function(iIndex, oItem) {
					var oComment = $( $.trim(oItem.html) );

					if ( ! (iCommentSelfId && iCommentSelfId == oItem.id) ) {
						this.aCommentsNew = this.aCommentsNew.add(oComment);
					}

					this.aComments = this.aComments.add(oComment);

					this.inject(oItem.idParent, oItem.id, oComment);
				}.bind(this));

				// Обновляем данные
				if (aCommentsLoaded.length && oResponse.iMaxIdComment) {
					this.elements.toolbar.last_id.val(oResponse.iMaxIdComment);

					// Обновляем счетчик комментариев
					this.elements.title.text( ls.i18n.pluralize(this.aComments.length, 'comments.comments_declension') );

					// Обновляем блок активности
					// TODO: Fix
					$('#js-stream-update').click();
				}

				// Проверяем сворачивание
				if (this.options.folding) {
					this.checkFolding();
					if ( ( ! iCommentSelfId && aCommentsLoaded.length ) || ( iCommentSelfId && aCommentsLoaded.length - 1 > 0 ) ) this.unfoldAll();
				}

				// Прокручиваем к комментарию который оставил текущий пользователь
				if (iCommentSelfId) {
					this.scrollToComment(this.getCommentById(iCommentSelfId));
				}

				ls.hook.run('ls_comments_load_after', [iTargetId, sTargetType, iCommentSelfId, bNotFlushNew, oResponse]);
			}
		}.bind(this));
	};

	/**
	 * Вставка комментария
	 */
	this.inject = function(iCommentParentId, iCommentId, oComment) {
		var oCommentNew = $('<div class="comment-wrapper js-comment-wrapper" data-id="' + iCommentId + '"></div>').append(oComment);

		if (iCommentParentId) {
			var oWrapper = $(this.options.selectors.wrapper + '[data-id=' + iCommentParentId + ']');

			if (oWrapper.parentsUntil(this.elements.container).length == ls.registry.get('comment_max_tree')) {
				oWrapper = oWrapper.parent(this.options.selectors.wrapper);
			}

			oWrapper.append(oCommentNew);
		} else {
			this.elements.container.append(oCommentNew);
		}

		ls.hook.run('ls_comment_inject_after', arguments, oCommentNew);
	};

	/**
	 * Предпросмотр комментария
	 */
	this.previewShow = function() {
		if ( ! this.elements.form.text.val() ) return;

		var oPreview = $('<div class="comment-preview text js-comment-preview" data-id="' + this.iFormTargetId +'"></div>');

		this.previewHide();
		this.elements.form.form.before(oPreview);

		ls.utils.textPreview(this.elements.form.text, oPreview, false);
	};

	/**
	 * Предпросмотр комментария
	 */
	this.previewHide = function() {
		this.elements.container.find(this.options.selectors.preview).remove();
	};

	/**
	 * Разблокировывает форму
	 */
	this.formLock = function() {
		this.elements.form.text.prop('readonly', true);
		this.elements.form.submit.prop('disabled', true);
	};

	/**
	 * Блокирует форму
	 */
	this.formUnlock = function() {
		this.elements.form.text.prop('readonly', false);
		this.elements.form.submit.prop('disabled', false);
	};

	/**
	 * Показывает/скрывает форму комментирования
	 *
	 * @param {Number}  iCommentId ID комментария
	 * @param {Boolean} bNoFocus   Переводить фокус на инпут с текстом или нет
	 */
	this.formToggle = function(iCommentId, bNoFocus) {
		this.previewHide();

		if (this.iFormTargetId == iCommentId && this.elements.form.form.is(':visible')) {
			this.elements.form.form.detach();
			this.iFormTargetId = null;
			return;
		}

		this.elements.form.form.insertAfter(iCommentId ? this.getCommentById(iCommentId) : this.elements.reply_root).show();
		this.elements.form.text.val('');
		this.elements.form.comment_id.val(iCommentId);

		this.iFormTargetId = iCommentId;

		if ( ! bNoFocus ) this.elements.form.text.focus();
	};

	/**
	 * Скрывает кнопку сворачивания у комментариев без дочерних комментариев
	 */
	this.checkFolding = function() {
		if ( ! this.options.folding ) return false;

		this.getComments().each(function (iIndex, oComment) {
			var oComment = $(oComment);

			oComment.find(this.options.selectors.comment.fold)[ oComment.next(this.options.selectors.wrapper).length ? 'show' : 'hide' ]();
		}.bind(this));
	};

	/**
	 * Сворачивает ветку комментариев
	 *
	 * @param {Object} oComment Комментарий
	 */
	this.fold = function(oComment) {
		oComment.removeClass(ls.options.classes.states.open).nextAll(this.options.selectors.wrapper).hide();
		oComment.find(this.options.selectors.comment.fold).removeClass(ls.options.classes.states.open);

		this.onFold(oComment);
	};

	/**
	 * Разворачивает ветку комментариев
	 *
	 * @param {Object} oComment Комментарий
	 */
	this.unfold = function(oComment) {
		oComment.addClass(ls.options.classes.states.open).nextAll(this.options.selectors.wrapper).show();
		oComment.find(this.options.selectors.comment.fold).addClass(ls.options.classes.states.open);

		this.onUnfold(oComment);
	};

	/**
	 * Сворачивает ветку комментариев
	 *
	 * @param {Object} oComment Комментарий
	 */
	this.onFold = function(oComment) {
		oComment.find(this.options.selectors.comment.fold).find('a').text(ls.lang.get('comments.folding.unfold'));
	};

	/**
	 * Сворачивает ветку комментариев
	 *
	 * @param {Object} oComment Комментарий
	 */
	this.onUnfold = function(oComment) {
		oComment.find(this.options.selectors.comment.fold).find('a').text(ls.lang.get('comments.folding.fold'));
	};

	/**
	 * Сворачивает все ветки комментариев
	 */
	this.foldAll = function() {
		this.getComments().filter('.' + ls.options.classes.states.open).each(function (iIndex, oComment) {
			this.fold($(oComment));
		}.bind(this));
	};

	/**
	 * Разворачивает все ветки комментариев
	 */
	this.unfoldAll = function() {
		this.getComments().filter(':not(.' + ls.options.classes.states.open + ')').each(function (iIndex, oComment) {
			this.unfold($(oComment));
		}.bind(this));
	};

	/**
	 * Устанавливает число новых комментариев
	 *
	 * @param  {Number} iCount Кол-во новых комментариев
	 */
	this.setCountNewComment = function(iCount) {
		this.elements.toolbar.new_counter[ iCount > 0 ? 'show' : 'hide' ]().text(iCount);
	};

	/**
	 * Вычисляет кол-во новых комментариев
	 */
	this.calcNewComments = function() {
		this.aCommentsNew = this.getCommentsNew();
		this.setCountNewComment(this.aCommentsNew.length);
	};

	/**
	 * Прокрутка к следующему новому комментарию
	 */
	this.scrollToNextNewComment = function() {
		if ( ! this.aCommentsNew.length ) return false;

		var oCommentNew = this.aCommentsNew.eq(0);

		oCommentNew.removeClass(this.options.classes.states.new);
		this.scrollToComment(oCommentNew);

		this.aCommentsNew = this.aCommentsNew.filter(':not(:eq(0))');
		this.setCountNewComment(this.aCommentsNew.length);
	};

	/**
	 * Прокрутка к родительскому комментарию
	 *
	 * @param  {Number} iCommentId       ID комментария
	 * @param  {Number} iCommentParentId ID родительского комментария
	 */
	this.scrollToParentComment = function(iCommentId, iCommentParentId) {
		var _this = this,
			oCommentChild = this.getCommentById(iCommentId),
			oCommentParent = this.getCommentById(iCommentParentId),
			oScrollToChild = oCommentParent.find(this.options.selectors.comment.scroll_to_child);

		this.getComments().find(this.options.selectors.comment.scroll_to_child).hide();
		this.scrollToComment(oCommentParent);

		// Прокрутка обратно к дочернему комментарию
		oScrollToChild.show().off().one('click', function() {
			oScrollToChild.hide();
			_this.scrollToComment(oCommentChild);
		});
	};

	/**
	 * Прокрутка к комментарию
	 *
	 * @param  {Number} oComment Комментарий
	 */
	this.scrollToComment = function(oComment) {
		this.getCommentCurrent().removeClass(this.options.classes.states.current);
		oComment.addClass(this.options.classes.states.current);
		this.iCurrentCommentId = oComment.data('id');

		$.scrollTo(oComment, 1000, { offset: -250 });
	};

	/**
	 * Получает комментарий по его ID
	 *
	 * @param  {Number} iCommentId ID комментария
	 * @return {Object}            jQuery объект комментария
	 */
	this.getCommentById = function(iCommentId) {
		return this.getComments().filter('[data-id=' + iCommentId + ']');
	};

	/**
	 * Получает текущий комментарий
	 *
	 * @return {Object} Текущий комментарий
	 */
	this.getCommentCurrent = function() {
		return this.getComments().filter('.' + this.options.classes.states.current);
	};

	/**
	 * Получает новые комментарии
	 *
	 * @return {Array} Массив с новыми комментариями
	 */
	this.getCommentsNew = function() {
		return this.getComments().filter('.' + this.options.classes.states.new);
	};

	/**
	 * Получает комментарии
	 *
	 * @return {Array} Массив с комментариями
	 */
	this.getComments = function() {
		return this.aComments;
	};

	return this;
}).call(ls.comments || {},jQuery);
