/**
 * Стена пользователя
 *
 * @module ls/wall
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.wall = (function ($) {
	"use strict";

	/**
	 * Дефолтные опции
	 *
	 * @private
	 */
	var _defaults = {
		// Селекторы
		selectors: {
			entry: {
				self:   '.js-wall-comment',
				remove: '.js-comment-remove',
				reply:  '.js-comment-reply'
			},
			form: {
				self:   '.js-wall-form',
				text:   '.js-wall-form-text',
				submit: '.js-wall-form-submit'
			},
			get_more: {
				self:  '.js-wall-get-more',
				count: '.js-wall-get-more-count'
			},
			comment_wrapper:   '.js-wall-comment-wrapper',
			entry_container:   '.js-wall-entry-container',
			empty:             '#wall-empty'
		},
		// Роуты
		routers: {
			add:           aRouter['profile'] + USER_PROFILE_LOGIN + '/wall/add/',
			remove:        aRouter['profile'] + USER_PROFILE_LOGIN + '/wall/remove/',
			load:          aRouter['profile'] + USER_PROFILE_LOGIN + '/wall/load/',
			load_comments: aRouter['profile'] + USER_PROFILE_LOGIN + '/wall/load-reply/'
		}
	};

	/**
	 * Инициализация
	 *
	 * @param {Object} options Опции
	 */
	this.init = function(options) {
		// Иниц-ем модуль только на странице профиля юзера
		if ( ! USER_PROFILE_LOGIN ) return;

		var _this = this;

		this.options = $.extend({}, _defaults, options);

		this.elements = {
			document: $(document),
			empty: $(this.options.selectors.empty)
		}

		// Добавление
		this.elements.document.on('submit', this.options.selectors.form.self, function(e) {
			_this.add( $(this) );
			e.preventDefault();
		});

		this.elements.document
			.on('keyup', this.options.selectors.form.text, function(e) {
				if (e.ctrlKey && (e.keyCode || e.which) == 13) {
					$(this).closest(_this.options.selectors.form.self).submit();
				}
			})
			.on('click', this.options.selectors.form.text, function(e) {
				// TODO: IE8 support
				if (e.which == 1) {
					_this.form.open($(this).closest(_this.options.selectors.form.self));
				}
			});

		// Показать/скрыть форму добавления комментария
		this.elements.document.on('click', this.options.selectors.entry.reply, function(e) {
			_this.form.toggle( $(_this.options.selectors.form.self + '[data-id=' + $(this).data('id') + ']') );
			e.preventDefault();
		});

		// Удаление записи
		this.elements.document.on('click', this.options.selectors.entry.remove, function(e) {
			_this.remove( $(this).data('id') );
			e.preventDefault();
		});

		// Сворачиваем открытые формы
		this.elements.document.on('click', function(e) {
			// TODO: IE8 support
			if (e.which == 1) {
				$(_this.options.selectors.form.self + '.' + ls.options.classes.states.open).each(function(key, value) {
					var oForm  = $(value),
						iId    = oForm.data('id'),
						oReply = $(_this.options.selectors.entry.reply + '[data-id=' + iId + ']');

					if ( ! oForm.is(e.target) &&
						 oForm.has(e.target).length === 0 &&
						 ! oReply.is(e.target) &&
						 ! oForm.find(_this.options.selectors.form.text).val() ) {
						if ( $(_this.options.selectors.entry_container + '[data-id=' + iId + ']' ).find(_this.options.selectors.entry.self).length || iId === 0 ) {
							_this.form.close(oForm);
						} else {
							_this.form.toggle(oForm);
						}
					}
				});
			}
		});

		$('.js-more-wall').more({
			url: aRouter['profile'] + USER_PROFILE_LOGIN + '/wall/load/'
		});

		$('.js-more-wall-comments').livequery(function () {
			$(this).more({
				url: aRouter['profile'] + USER_PROFILE_LOGIN + '/wall/load-reply/'
			});
		});
	};

	/**
	 * Добавляет комментарий к записи
	 */
	this.add = function(oForm) {
		var oTextarea = oForm.find(this.options.selectors.form.text),
			oButton   = oForm.find(this.options.selectors.form.submit),
			iId       = oForm.data('id'),
			sText     = oTextarea.val();

		ls.hook.marker('addBefore');

		ls.utils.formLock(oForm);

		ls.ajax.load(this.options.routers.add, { sText: sText, iPid: iId }, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				if (iId === 0) this.elements.empty.hide();
				oTextarea.val('');
				this.loadNew(iId);

				ls.hook.run('ls_wall_add_after', [sText, iId, result]);
			}

			ls.utils.formUnlock(oForm);
		}.bind(this));
	};

	/**
	 * Удаление записи/комментария
	 *
	 * @param  {Number} iId ID записи
	 */
	this.remove = function(iId) {
		var _this = this;

		ls.hook.marker('removeBefore');

		ls.ajax.load(this.options.routers.remove, { iId: iId }, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				var entry = $(_this.options.selectors.entry.self + '[data-id=' + iId + ']');

				entry.fadeOut('slow', function() {
					if ( ! $(_this.options.selectors.entry.self).length ) _this.elements.empty.show();

					ls.hook.run('ls_wall_remove_item_fade', [iId, result], this);
				});
				entry.next(_this.options.selectors.comment_wrapper).fadeOut('slow');

				ls.hook.run('ls_wall_remove_after', [iId, result]);
			}
		});
	};

	/**
	 * Подгрузка новых записей
	 */
	this.loadNew = function(iPid) {
		var oContainer = $(this.options.selectors.entry_container + '[data-id=' + iPid + ']'),
			iFirstId   = oContainer.find(' > ' + this.options.selectors.entry.self + ':' + (iPid === 0 ? 'first' : 'last')).data('id') || -1,
			oParams    = { iFirstId: iFirstId, iTargetId: iPid };

		ls.ajax.load(iPid === 0 ? this.options.routers.load : this.options.routers.load_comments, oParams, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				if (result.iCountLoaded) {
					oContainer[iPid === 0 ? 'prepend' : 'append'](result.sHtml);
				}

				this.form.close( $(this.options.selectors.form.self + '[data-id=' + iPid + ']') );

				ls.hook.run('ls_wall_loadnew_after', [iPid, iFirstId, result]);
			}
		}.bind(this));
	};

	/**
	 * Форма
	 */
	this.form = function(_this) {
		/**
		 * Разворачивает форму
		 */
		this.open = function(oForm) {
			oForm.addClass(ls.options.classes.states.open);
		};

		/**
		 * Сворачивает форму
		 */
		this.close = function(oForm) {
			oForm.removeClass(ls.options.classes.states.open);
		};

		/**
		 * Сворачивает/разворачивает форму
		 */
		this.expandToggle = function(oForm) {
			this.form[ oForm.hasClass(ls.options.classes.states.open) ? 'close' : 'open' ](oForm);
		}.bind(_this);

		/**
		 * Показывает/скрывает форму комментирования
		 */
		this.toggle = function(oForm) {
			oForm.toggle().find(this.options.selectors.form.text).focus();
			this.form.expandToggle(oForm);
		}.bind(_this);

		return this;
	}.call({}, this);

	return this;
}).call(ls.wall || {}, jQuery);