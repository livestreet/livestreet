/**
 * Комментарии
 *
 * @module ls/comments
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsComments", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Ссылки
			urls: {
				// Добавление комментария
				add: null,
				// Подгрузка новых комментариев
				load: null,
				// Показать/скрыть комментарий
				hide: aRouter['ajax'] + 'comment/delete/',
				// Обновление текста комментария
				text: aRouter['ajax'] + 'comment/load/',
				// Обновление комментария
				update: aRouter['ajax'] + 'comment/update/'
			},

			// Селекторы
			selectors: {
				// Блок с превью текста
				preview: '.js-comment-preview',
				// Кнопка свернуть/развернуть все
				fold_all_toggle: '.js-comments-fold-all-toggle',
				// Заголовок
				title: '.js-comments-title',
				// Кнопка "Оставить комментарий"
				reply_root: '.js-comment-reply-root',
				// Блок с комментариями
				comment_list: '.js-comment-list',
				// Подписаться на новые комментарии
				subscribe: '.js-comments-subscribe',

				// Комментарий
				comment: {
					comment:          '.js-comment',
					wrapper:          '.js-comment-wrapper',
					reply:            '.js-comment-reply',
					fold:             '.js-comment-fold',
					remove:           '.js-comment-remove',
					update:           '.js-comment-update',
					update_timer:     '.js-comment-update-timer',
					scroll_to_child:  '.js-comment-scroll-to-child',
					scroll_to_parent: '.js-comment-scroll-to-parent'
				},

				// Форма добавления
				form: {
					form:          '.js-comment-form',
					text:          '.js-comment-form-text',
					submit:        '.js-comment-form-submit',
					preview:       '.js-comment-form-preview',
					update_submit: '.js-comment-form-update-submit',
					update_cancel: '.js-comment-form-update-cancel',
					comment_id:    '#form_comment_reply'
				}
			},

			// Классы
			classes: {
				states: {
					current: 'comment--current',
					new:     'comment--new',
					deleted: 'comment--deleted',
					self:    'comment--self'
				}
			},

			// Использовать визуальный редактор или нет
			wysiwyg: null,
			// Включить/выключить функцию сворачивания
			folding: true,
			// Показать/скрыть форму по умолчанию
			show_form: false
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			var _this = this;

			// Получаем элементы
			this.form = this.element.find(this.options.selectors.form.form);

			this.elements = {
				title:           this.element.find(this.options.selectors.title),
				reply_root:      this.element.find(this.options.selectors.reply_root),
				fold_all_toggle: this.element.find(this.options.selectors.fold_all_toggle),
				comment_list:    this.element.find(this.options.selectors.comment_list),
				subscribe:       this.element.find(this.options.selectors.subscribe),
				form: {
					text:          this.form.find(this.options.selectors.form.text),
					submit:        this.form.find(this.options.selectors.form.submit),
					preview:       this.form.find(this.options.selectors.form.preview),
					update_cancel: this.form.find(this.options.selectors.form.update_cancel),
					update_submit: this.form.find(this.options.selectors.form.update_submit),
					comment_id:    this.form.find(this.options.selectors.form.comment_id)
				}
			};

			// ID комментария помеченного как текущий
			this.currentCommentId = null;

			// Список комментариев на странице
			this.comments = $(this.options.selectors.comment.comment);

			// ID комментария после которого отображается форма
			this.formTargetId = 0;

			// Получаем ID объекта к которому оставлен комментарий
			this.targetId = this.element.data('target-id');

			// Получаем тип объекта
			this.targetType = this.element.data('target-type');

			// ID последнего добавленного комментария
			this.commentLastId = this.element.data('comment-last-id');

			// Показываем кнопку свернуть у комментариев с дочерними комментариями
			this.checkFolding();

			// Обновление таймеров у кнопок редактирования
			this.initUpdateTimers();

			// Показываем/скрываем форму по умолчанию
			! this.options.show_form && this.formToggle(0);

			//
			// События
			//

			// Навигация по комментариям
			this.document.on('click' + this.eventNamespace, this.options.selectors.comment.scroll_to_parent, function (e) {
				var element = $(this);

				_this.scrollToParentComment(element.data('id'), element.data('parent-id'));
			});

			// Показывает / скрывает форму комментирования
			this.document.on('click' + this.eventNamespace, this.options.selectors.comment.reply, function (e) {
				_this.formToggle($(this).data('id'), true, false, false);
				e.preventDefault();
			});

			// Превью текста
			this._on( this.elements.form.preview, { 'click': this.previewShow } );

			// Отправка формы
			this._on( this.form, { 'submit': function (e) {
				this.add( this.form, this.form.data('target-id'), this.form.data('target-type') );
				e.preventDefault();
			}});

			this.elements.form.text.bind( 'keydown' + this.eventNamespace, 'ctrl+return', function() { _this.form.submit() } );

			// Удаление
			this.element.on('click' + this.eventNamespace, this.options.selectors.comment.remove, function(e) {
				var element = $(this),
					commentId = element.data('id');

				_this.toggle(element, commentId);

				e.preventDefault();
			});

			// Редактирование
			this.element.on('click' + this.eventNamespace, this.options.selectors.comment.update, function(e) {
				var element = $(this),
					commentId = element.data('id');

				_this.formToggle(commentId, false, true);
				e.preventDefault();
			});

			// Отмена редактирования
			this.elements.form.update_cancel.on('click' + this.eventNamespace, function (e) {
				this.formToggle(this.formTargetId, false, true);
				e.preventDefault();
			}.bind(this));

			// Сохранение после редактирования
			this.elements.form.update_submit.on('click' + this.eventNamespace, function (e) {
				this.submitCommentUpdate(this.formTargetId);
				e.preventDefault();
			}.bind(this));

			// Подписаться/отписаться от новых комментариев
			this.elements.subscribe.on('click' + this.eventNamespace, function (e) {
				var element = $(this),
					isActive = element.hasClass('active');

				ls.subscribe.toggle(element.data('type') + '_new_comment', element.data('target-id'), '', ! isActive);

				if ( isActive ) {
					element.removeClass('active').text( ls.lang.get('comments.subscribe') );
				} else {
					element.addClass('active').text( ls.lang.get('comments.unsubscribe') );
				}

				e.preventDefault();
			});

			// Сворачивание
			if ( this.options.folding ) {
				// Свернуть/развернуть все
				this.elements.fold_all_toggle.on('click' + this.eventNamespace, function (e) {
					var element = $(this);

					if ( ! element.hasClass('active') ) {
						_this.foldAll();
						element.addClass('active').text( ls.lang.get('comments.folding.unfold_all') );
					} else {
						_this.unfoldAll();
						element.removeClass('active').text( ls.lang.get('comments.folding.fold_all') );
					}

					e.preventDefault();
				});

				// Свернуть/развернуть
				this.element.on('click' + this.eventNamespace, this.options.selectors.comment.fold, function(e) {
					var element = $(this),
						comment = _this.getCommentById(element.data('id'));

					_this[ element.hasClass(ls.options.classes.states.open) ? 'fold' : 'unfold' ](comment);

					e.preventDefault();
				});
			}

			ls.hook.run('ls_comments_init_after',[],this);
		},

		/**
		 * Добавляет комментарий
		 */
		add: function() {
			// Получаем данные формы до ее блокировки
			var data = this.form.serializeJSON();

			ls.utils.formLock(this.form);
			this.previewHide();

			ls.ajax.load(this.options.urls.add, data, function(response) {
				if (response.bStateError) {
					ls.msg.error(null, response.sMsg);
				} else {
					this.elements.form.text.val('');
					this.load(response.sCommentId, true);

					ls.hook.run('ls_comments_add_after', [this.form, this.targetId, this.targetType, response]);
				}

				ls.utils.formUnlock(this.form);
			}.bind(this));
		},

		/**
		 * Скрыть/восстановить комментарий
		 *
		 * @param {jQuery} toggle    Кнопка скрыть/восстановить комментарий
		 * @param {Number} commentId ID добавляемого комментария
		 */
		toggle: function(toggle, commentId) {
			var url = this.options.urls.hide,
				params = { idComment: commentId };

			ls.hook.marker('toggleBefore');

			ls.ajax.load(url, params, function(response) {
				if (response.bStateError) {
					ls.msg.error(null, response.sMsg);
				} else {
					ls.msg.notice(null, response.sMsg);

					this.getCommentById(commentId).removeClass(
						this.options.classes.states.self + ' ' +
						this.options.classes.states.new + ' ' +
						this.options.classes.states.deleted + ' ' +
						this.options.classes.states.current
					);

					if (response.bState) {
						this.getCommentById(commentId).addClass(this.options.classes.states.deleted);
					}

					toggle.text(response.sTextToggle);

					ls.hook.run('ls_comments_toggle_after',[toggle, commentId, response]);
				}
			}.bind(this));
		},

		/**
		 * Подгружает новые комментарии
		 *
		 * @param {Number}  сommentSelfId (undefined) ID добавляемого комментария
		 * @param {Boolean} flush         (true) Удалять подсветку у текущих новых комментариев или нет
		 */
		load: function(сommentSelfId, flush, callbacks) {
			flush = typeof flush === 'undefined' ? true : flush;

			var params = {
				idCommentLast: this.commentLastId,
				idTarget: this.targetId,
				typeTarget: this.targetType,
				// TODO: Fix
				// bUsePaging: this.elements.toolbar.use_paging.val() ? 1 : 0
			};

			params.selfIdComment = сommentSelfId || undefined;

			ls.ajax.load(this.options.urls.load, params, function(response) {
				if (response.bStateError) {
					ls.msg.error(null, response.sMsg);
				} else {
					var сommentsLoaded = response.aComments,
						сountLoaded = сommentsLoaded.length;

					// Убираем подсветку у текущего комментария
					this.getCommentCurrent().removeClass(this.options.classes.states.current);
					this.currentCommentId = null;

					// Убираем подсветку у новых комментариев
					if ( flush ) this.getCommentsNew().removeClass(this.options.classes.states.new);

					// Если комментарии подгружаются после сабмита формы текущим пользователем
					if ( сommentSelfId ) this.formToggle(this.formTargetId, true);

					// Вставляем новые комментарии
					$.each(сommentsLoaded, function(index, item) {
						var сomment = $( $.trim(item.html) );

						this.comments = this.comments.add(сomment);
						this.insert(сomment, item.id, item.idParent);
					}.bind(this));

					// Обновляем данные
					if ( сountLoaded && response.iMaxIdComment ) {
						this.commentLastId = response.iMaxIdComment;

						// Обновляем кол-во комментариев в заголовке
						this.elements.title.text( ls.i18n.pluralize(this.comments.length, 'comments.comments_declension') );

						// Обновляем блок активности
						// TODO: Fix
						$('#js-stream-update').click();
					}

					// Проверяем сворачивание
					if ( this.options.folding ) {
						this.checkFolding();

						// Разворачиваем все ветки если идет просто подгрузка комментариев
						// или если при добавления комментария текущим пользователем
						// помимо этого комментария подгружаются еще и ранее добавленные комментарии
						if ( ( ! сommentSelfId && сountLoaded ) || ( сommentSelfId && сountLoaded - 1 > 0 ) ) this.unfoldAll();
					}

					// Прокручиваем к комментарию который оставил текущий пользователь
					if ( сommentSelfId ) this.scrollToComment( this.getCommentById(сommentSelfId) );

					// Обновляем таймеры
					this.initUpdateTimers();

					callbacks && $.proxy( callbacks.success, this )();
					ls.hook.run('ls_comments_load_after', [ this.targetId, this.targetType, сommentSelfId, flush, response ]);
				}

				callbacks && $.proxy( callbacks.done, this )();
			}.bind(this));
		},

		/**
		 * Вставка комментария
		 *
		 * @param {jQuery} сomment         Комментарий
		 * @param {Number} commentId       ID добавляемого комментария
		 * @param {Number} commentParentId (optional) ID родительского комментария
		 */
		insert: function(comment, commentId, commentParentId) {
			var commentWrapper = $('<div class="comment-wrapper js-comment-wrapper" data-id="' + commentId + '"></div>').append(comment);

			if (commentParentId) {
				// Получаем обертку родительского комментария
				var wrapper = $(this.options.selectors.comment.wrapper + '[data-id=' + commentParentId + ']');

				// Проверяем чтобы уровень вложенности комментариев была не больше значения заданного в конфиге
				if (wrapper.parentsUntil(this.elements.comment_list).length == ls.registry.get('comment_max_tree')) {
					wrapper = wrapper.parent(this.options.selectors.comment.wrapper);
				}

				wrapper.append(commentWrapper);
			} else {
				this.elements.comment_list.append(commentWrapper);
			}

			ls.hook.run('ls_comment_insert_after', arguments, commentWrapper);
		},

		/**
		 * Предпросмотр комментария
		 */
		previewShow: function() {
			if ( ! this.elements.form.text.val() ) return;

			var preview = $('<div class="comment-preview text js-comment-preview" data-id="' + this.formTargetId +'"></div>');

			this.previewHide();
			this.form.before(preview);

			ls.utils.textPreview(this.elements.form.text, preview, false);
		},

		/**
		 * Предпросмотр комментария
		 */
		previewHide: function() {
			this.element.find(this.options.selectors.preview).remove();
		},

		/**
		 * Показывает/скрывает форму комментирования
		 *
		 * @param {Number}  commentId ID комментария после которого нужно показать форму
		 * @param {Boolean} focus     Переводить фокус на инпут с текстом или нет
		 */
		formToggle: function(commentId, focus, update, reset) {
			update = update || false,
			focus = typeof focus === 'undefined' ? true : focus;
			reset = typeof reset === 'undefined' ? true : reset;

			this.previewHide();

			if ( ( ! update && this.form.data('update') ) || reset ) this.elements.form.text.val('');

			this.form.data('update', false);

			if ( this.formTargetId == commentId && this.form.is(':visible') ) {
				this.form.hide();
				this.formTargetId = 0;
				return;
			}

			this.form.insertAfter(commentId ? this.getCommentById(commentId) : this.elements.reply_root).show();
			this.elements.form.comment_id.val(commentId);

			// Показываем необходимые кнопки
			if (update) {
				this.form.data('update', true);
				this.elements.form.update_cancel.show();
				this.elements.form.update_submit.show();
				this.elements.form.submit.hide();

				// Загружаем исходный текст комментария
				this.loadCommentUpdate(commentId);
			} else {
				this.elements.form.update_cancel.hide();
				this.elements.form.update_submit.hide();
				this.elements.form.submit.show();
			}

			this.formTargetId = commentId;

			if ( focus ) this.elements.form.text.focus();
		},

		/**
		 * Обновление текста комментария
		 *
		 * @param  {Number} commentId ID комментария
		 */
		loadCommentUpdate: function(commentId) {
			var url = this.options.urls.text,
				params = { idComment: commentId };

			ls.utils.formLock(this.form);
			ls.hook.marker('loadBefore');

			ls.ajax.load(url, params, function(response) {
				if (response.bStateError) {
					ls.msg.error(null, response.sMsg);
				} else {
					this.elements.form.text.val(response.sText);
				}

				ls.hook.run('ls_comments_load_comment_update_after', [commentId, response]);

				ls.utils.formUnlock(this.form);
			}.bind(this));
		},

		/**
		 * Редактирование комментария
		 *
		 * @param  {Number} commentId ID комментария
		 */
		submitCommentUpdate: function(commentId) {
			var data = this.form.serializeJSON(),
				url = this.options.urls.update;

			ls.utils.formLock(this.form);
			this.previewHide();

			data.comment_id = commentId;

			ls.ajax.load(url, data, function(response) {
				if (response.bStateError) {
					ls.msg.error(null, response.sMsg);
				} else {
					var comment = this.getCommentById(commentId),
						commentNew = $( $.trim(response.sHtml) );

					this.removeCommentById(commentId);
					this.comments = this.comments.add(commentNew);

					comment.replaceWith( commentNew );
					comment.find(this.options.selectors.comment.update_timer).stopTime();

					this.formToggle(commentId, true);
					this.initUpdateTimers();
					this.checkFolding(commentNew);
					this.scrollToComment(commentNew);
				}

				ls.hook.run('ls_comments_submit_comment_update_after', [this.form, commentId, response]);

				ls.utils.formUnlock(this.form);
			}.bind(this));
		},

		/**
		 * Иниц-ия таймеров
		 * TODO: Fix
		 */
		initUpdateTimers: function() {
			var _this=this;
			$(this.options.selectors.comment.update_timer).each(function(k,el){
				el=$(el);
				if (!el.data('isInit')) {
					el.data('isInit',true);
					el.everyTime(1000,function(){
						var seconds=parseInt(el.data('seconds'));
						seconds--;
						el.data('seconds',seconds);
						if (seconds>0) {
							el.text(ls.utils.timeRemaining(seconds));
						} else {
							el.parents(_this.options.selectors.comment.comment)
								.find(_this.options.selectors.comment.update).hide();
							el.stopTime();
						}
					});
				}
			});
		},

		/**
		 * Показывает кнопку сворачивания у комментариев с дочерними комментариями
		 * и скрывает у комментариев без них
		 *
		 * @param {jQuery} сomment (optional) Комментарий у которого нужно проверить наличие дочерних комментарев, если не указан то проверяется у всех
		 */
		checkFolding: function(comment) {
			if ( ! this.options.folding ) return;

			var comments = comment ? comment : this.getComments();

			comments.each(function (index, сomment) {
				var сomment = $(сomment);

				сomment.find(this.options.selectors.comment.fold)[ сomment.nextAll(this.options.selectors.comment.wrapper).length ? 'show' : 'hide' ]();
			}.bind(this));
		},

		/**
		 * Сворачивает ветку комментариев
		 *
		 * @param {jQuery} сomment Комментарий у которого нужно скрыть дочернии комментарии
		 */
		fold: function(сomment) {
			сomment.removeClass(ls.options.classes.states.open).nextAll(this.options.selectors.comment.wrapper).hide();
			сomment.find(this.options.selectors.comment.fold).removeClass(ls.options.classes.states.open);

			this.onFold(сomment);
		},

		/**
		 * Разворачивает ветку комментариев
		 *
		 * @param {jQuery} comment Комментарий у которого нужно показать дочернии комментарии
		 */
		unfold: function(comment) {
			comment.addClass(ls.options.classes.states.open).nextAll(this.options.selectors.comment.wrapper).show();
			comment.find(this.options.selectors.comment.fold).addClass(ls.options.classes.states.open);

			this.onUnfold(comment);
		},

		/**
		 * Коллбэк вызываемый после сворачивания ветки комментариев
		 *
		 * @param {jQuery} comment Комментарий
		 */
		onFold: function(comment) {
			comment.find(this.options.selectors.comment.fold).find('a').text(ls.lang.get('comments.folding.unfold'));
		},

		/**
		 * Коллбэк вызываемый после разворачивания ветки комментариев
		 *
		 * @param {jQuery} comment Комментарий
		 */
		onUnfold: function(comment) {
			comment.find(this.options.selectors.comment.fold).find('a').text(ls.lang.get('comments.folding.fold'));
		},

		/**
		 * Сворачивает все ветки комментариев
		 */
		foldAll: function() {
			this.getComments().filter('.' + ls.options.classes.states.open).each(function (index, comment) {
				this.fold( $(comment) );
			}.bind(this));
		},

		/**
		 * Разворачивает все ветки комментариев
		 */
		unfoldAll: function() {
			this.getComments().not('.' + ls.options.classes.states.open).each(function (index, comment) {
				this.unfold( $(comment) );
			}.bind(this));
		},

		/**
		 * Прокрутка к комментарию
		 *
		 * @param  {jQuery} comment Комментарий
		 */
		scrollToComment: function(comment) {
			this.setCommentCurrent(comment);

			$.scrollTo(comment, 1000, { offset: -250 });
		},

		/**
		 * Прокрутка к родительскому комментарию
		 *
		 * @param  {Number} commentId       ID комментария
		 * @param  {Number} commentParentId ID родительского комментария
		 */
		scrollToParentComment: function(commentId, commentParentId) {
			var child = this.getCommentById(commentId),
				parent = this.getCommentById(commentParentId),
				scroll = parent.find(this.options.selectors.comment.scroll_to_child);

			// Скрываем кнопку прокрутки к дочернему комментарию у текущего комментария
			this.getCommentCurrent().find(this.options.selectors.comment.scroll_to_child).off().hide();

			// Прокрутка к родительскому комментарию
			this.scrollToComment(parent);

			// Прокрутка обратно к дочернему комментарию
			scroll.show().off().one('click', function() {
				scroll.hide();
				this.scrollToComment(child);
			}.bind(this));
		},

		/**
		 * Получает комментарий по его ID
		 *
		 * @param  {Number} commentId ID комментария
		 * @return {jQuery}           Комментарий
		 */
		getCommentById: function(commentId) {
			return this.getComments().filter('[data-id=' + commentId + ']');
		},

		/**
		 * Удаляет комментарий по его ID
		 *
		 * @param  {Number} commentId ID комментария
		 */
		removeCommentById: function(commentId) {
			this.comments = this.getComments().not('[data-id=' + commentId + ']');
		},

		/**
		 * Устанавливает текущий комментарий
		 *
		 * @param {Object} comment
		 */
		setCommentCurrent: function(comment) {
			this.getCommentCurrent().removeClass(this.options.classes.states.current);
			comment.addClass(this.options.classes.states.current);
			this.currentCommentId = comment.data('id');
		},

		/**
		 * Получает текущий комментарий
		 *
		 * @return {jQuery} Текущий комментарий
		 */
		getCommentCurrent: function() {
			return this.getCommentById(this.currentCommentId);
		},

		/**
		 * Получает новые комментарии
		 *
		 * @return {Array} Массив с новыми комментариями
		 */
		getCommentsNew: function() {
			return this.getComments().filter('.' + this.options.classes.states.new);
		},

		/**
		 * Получает комментарии
		 *
		 * @return {Array} Массив с комментариями
		 */
		getComments: function() {
			return this.comments;
		}
	});
})(jQuery);