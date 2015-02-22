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

    $.widget( "livestreet.lsComments", $.livestreet.lsComponent, {
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
                hide: aRouter.ajax + 'comment/delete/',
                // Обновление текста комментария
                text: aRouter.ajax + 'comment/load/',
                // Обновление комментария
                update: aRouter.ajax + 'comment/update/'
            },

            // Селекторы
            selectors: {
                comment: '.js-comment',
                comment_wrapper: '.js-comment-wrapper',
                form: '.js-comment-form',
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
                // Сообщение о пустом списке
                empty: '.js-comments-empty'
            },

            // Использовать визуальный редактор или нет
            wysiwyg: null,
            // Включить/выключить функцию сворачивания
            folding: true,
            // Показать/скрыть форму по умолчанию
            show_form: false,
            // Ajax параметры
            params: {}
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            var _this = this;

            this._super();

            this.initComments( this.getComments() );

            this.getForm().lsCommentForm({
                urls: {
                    text: this.option( 'urls.text' ),
                    add: this.option( 'urls.add' ),
                    update: this.option( 'urls.update' )
                },
                comments: this.element
            });

            this._currentComment = $();

            // Получаем ID объекта к которому оставлен комментарий
            this._targetId = this.element.data( 'target-id' );

            // Получаем тип объекта
            this._targetType = this.element.data( 'target-type' );

            // ID последнего добавленного комментария
            this.setLastCommentId( this.element.data('comment-last-id') );


            this.elements.reply_root.on( 'click' + this.eventNamespace, function ( event ) {
                event.preventDefault();
                _this.getForm().lsCommentForm( 'show', 0 );
            });

            if ( ! this.option( 'show_form' ) ) this.getForm().hide();

            //
            // ЭКШНБАР
            //

            // Сворачивание
            if ( this.options.folding ) {
                this.elements.fold_all_toggle.on( 'click' + this.eventNamespace, this.foldAllToggle.bind( this ) );
            }

            // Подписаться/отписаться от новых комментариев
            this.elements.subscribe.on( 'click' + this.eventNamespace, function () {
                var element = $(this),
                    isActive = element.hasClass('active');

                ls.subscribe.toggle( _this._targetType + '_new_comment', _this._targetId, '', ! isActive );

                if ( isActive ) {
                    element.removeClass('active').text( ls.lang.get('comments.subscribe') );
                } else {
                    element.addClass('active').text( ls.lang.get('comments.unsubscribe') );
                }
            });
        },

        /**
         * Подписаться/отписаться от комментариев
         */
        subscribeToggle: function() {
            var isActive = this.elements.subscribe.hasClass('active');

            ls.subscribe.toggle( _this._targetType + '_new_comment', _this._targetId, '', ! isActive );

            if ( isActive ) {
                this.elements.subscribe.removeClass( 'active' ).text( ls.lang.get('comments.subscribe') );
            } else {
                this.elements.subscribe.addClass( 'active' ).text( ls.lang.get('comments.unsubscribe') );
            }
        },

        /**
         * Свернуть/развернуть все ветки комментариев
         */
        foldAllToggle: function() {
            this[ this.elements.fold_all_toggle.hasClass( 'active' ) ? 'unfoldAll' : 'foldAll' ]();
        },

        /**
         * Сворачивает все ветки комментариев
         */
        foldAll: function() {
            this.getComments().lsComment( 'fold' );
            this.elements.fold_all_toggle.addClass( 'active' ).text( ls.lang.get('comments.folding.unfold_all') );
        },

        /**
         * Разворачивает все ветки комментариев
         */
        unfoldAll: function() {
            this.getComments().lsComment( 'unfold' );
            this.elements.fold_all_toggle.removeClass( 'active' ).text( ls.lang.get('comments.folding.fold_all') );
        },

        /**
         * Подгрузка новых комментариев
         */
        load: function( commentSelfId, flush, callback ) {
            flush = typeof flush === 'undefined' ? true : flush;

            var params = {
                target_id: this._targetId,
                target_type: this._targetType,
                last_comment_id: this.getLastCommentId(),
                self_comment_id: commentSelfId || undefined,
                use_paging: false
            };

            this._load( 'load', params, function( response ) {
                var commentsLoaded = response.comments,
                    сountLoaded = commentsLoaded.length;

                // Убираем подсветку у новых комментариев
                if ( flush ) this.getCommentsNew().lsComment( 'notNew' );

                // Скрываем сообщение о пустом списке
                if ( ~ this.getComments().length && сountLoaded ) this.elements.empty.hide();

                // Вставляем новые комментарии
                $.each( commentsLoaded, function( index, item ) {
                    var comment = this.initComments( $( $.trim( item.html ) ) );

                    this.elements.comment = this.elements.comment.add( comment );
                    this.insert( comment, item.id, item.parent_id );
                }.bind( this ));

                // Обновляем данные
                if ( сountLoaded && response.last_comment_id ) {
                    this.setLastCommentId( response.last_comment_id );

                    // Обновляем кол-во комментариев в заголовке
                    this.elements.title.text( ls.i18n.pluralize(this.getComments().length, 'comments.comments_declension') );
                }

                // Разворачиваем все ветки если идет просто подгрузка комментариев
                // или если при добавления комментария текущим пользователем
                // помимо этого комментария подгружаются еще и ранее добавленные комментарии
                if ( this.options.folding && ( ( ! commentSelfId && сountLoaded ) || ( commentSelfId && сountLoaded - 1 > 0 ) ) ) {
                    this.unfoldAll();
                }

                // Прокручиваем к комментарию который оставил текущий пользователь
                if ( commentSelfId ) {
                    this.getForm().lsCommentForm( 'hide' );
                    this.scrollToComment( this.getCommentById( commentSelfId ) );
                }

                if ( $.isFunction( callback ) ) callback.call( this );
            });
        },

        /**
         * Вставка комментария
         *
         * @param {jQuery} comment         Комментарий
         * @param {Number} commentId       ID добавляемого комментария
         * @param {Number} commentParentId (optional) ID родительского комментария
         */
        insert: function( comment, commentId, commentParentId ) {
            var commentWrapper = $( '<div class="comment-wrapper js-comment-wrapper" data-id="' + commentId + '"></div>' ).append( comment );

            if ( commentParentId ) {
                // Получаем обертку родительского комментария
                var wrapper = $( this.options.selectors.comment_wrapper + '[data-id=' + commentParentId + ']');

                // Проверяем чтобы уровень вложенности комментариев был не больше значения заданного в конфиге
                if (wrapper.parentsUntil(this.elements.comment_list).length == ls.registry.get('comment_max_tree')) {
                    wrapper = wrapper.parent(this.options.selectors.comment_wrapper);
                }

                wrapper.append( commentWrapper );
            } else {
                this.elements.comment_list.append( commentWrapper );
            }
        },

        /**
         * Получает текущий комментарий
         *
         * @return {jQuery} Текущий комментарий
         */
        getCommentCurrent: function() {
            return this._currentComment;
        },

        /**
         * Устанавливает текущий комментарий
         *
         * @param {Object} comment
         */
        setCommentCurrent: function( comment ) {
            if ( this.getCommentCurrent().is( comment ) ) return;

            this.getCommentCurrent().lsComment( 'notCurrent' );
            comment.lsComment( 'markAsCurrent' );
            this._currentComment = comment;
        },

        /**
         * Прокрутка к комментарию
         *
         * @param  {jQuery} comment Комментарий
         */
        scrollToComment: function( comment ) {
            this.setCommentCurrent( comment );
            $.scrollTo( comment, 1000, { offset: -250 } );
        },

        /**
         * Получает форму комментирования
         *
         * @return {jQuery} Форма комментирования
         */
        getForm: function() {
            return this.elements.form;
        },

        /**
         * Получает комментарии
         *
         * @return {Array} Массив с комментариями
         */
        getComments: function() {
            return this.elements.comment;
        },

        /**
         * Добавляет комментарий в массив с другими
         *
         * @param {jQuery} comments Комментарии
         */
        addComments: function( comments ) {
            this.elements.comment = this.elements.comment.add( comments );
        },

        /**
         * Получает комментарий по его ID
         *
         * @param  {Number} commentId ID комментария
         * @return {jQuery}           Комментарий
         */
        getCommentById: function( commentId ) {
            if ( ! commentId ) return;

            for ( var i = 0, len = this.getComments().length; i < len; i++ ) {
                if ( $( this.getComments()[ i ] ).lsComment( 'getId' ) == commentId ) {
                    return $( this.getComments()[ i ] );
                }
            };

            return $();
        },

        /**
         * Удаляет комментарий по его ID
         *
         * @param  {Number} commentId ID комментария
         */
        removeCommentById: function( commentId ) {
            this.elements.comment = this.getComments().filter(function () {
                if ( $( this ).lsComment( 'getId' ) == commentId ) {
                    $( this ).lsComment( 'destroy' );
                    return false;
                }

                return true;
            });
        },

        /**
         * Получает новые комментарии
         *
         * @return {Array} Массив с новыми комментариями
         */
        getCommentsNew: function() {
            return this.getComments().filter(function () {
                return $( this ).lsComment( 'isNew' );
            });
        },

        /**
         * Иниц-ия комментариев
         *
         * @param {jQuery} comments Комментарии
         */
        initComments: function( comments ) {
            return comments.lsComment({
                comments: this.element,
                folding: this.options.folding
            });
        },

        /**
         * Получает ID последнего добавленного комментария
         */
        getLastCommentId: function() {
            return this._commentLastId;
        },

        /**
         * Устанавливает ID последнего добавленного комментария
         *
         * @param {Number} id ID комментария
         */
        setLastCommentId: function( id ) {
            this._commentLastId = id;
        },
    });
})(jQuery);