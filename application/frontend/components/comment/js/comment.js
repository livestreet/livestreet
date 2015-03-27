/**
 * Comment
 *
 * @module ls/comment
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsComment", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            comments: $(),
            form: $(),
            folding: true,

            // Ссылки
            urls: {
                vote: aRouter.ajax + 'vote/comment/',
                favourite: aRouter.ajax + 'favourite/comment/',
                // Показать/скрыть комментарий
                toggle: aRouter.ajax + 'comment/delete/'
            },

            // Селекторы
            selectors: {
                wrapper:          '.js-comment-wrapper',
                vote:             '.js-comment-vote',
                favourite:        '.js-comment-favourite',
                reply:            '.js-comment-reply',
                fold:             '.js-comment-fold',
                remove:           '.js-comment-remove',
                edit:             '.js-comment-update',
                update_timer:     '.js-comment-update-timer',
                scroll_to_child:  '.js-comment-scroll-to-child',
                scroll_to_parent: '.js-comment-scroll-to-parent'
            },

            // Классы
            classes : {
                folded:  'comment--folded',
                current: 'comment--current',
                new:     'comment--new',
                deleted: 'comment--deleted',
                self:    'comment--self'
            },
            params: {}
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();

            this._id = this.element.data( 'id' );
            this._parentId = this.element.data( 'parent-id' );
            this._parent = null;
            this._scrollChild = null;
            this._countdown = this.elements.update_timer.data( 'seconds' );

            // Голосование за комментарий
            this.elements.vote.lsVote({
                urls: {
                    vote: this.option( 'urls.vote' )
                }
            });

            // Избранное
            this.elements.favourite.lsFavourite({
                urls: {
                    toggle: this.option( 'urls.favourite' )
                }
            });

            // Сворчивание
            if ( this.options.folding ) {
                if ( this.hasChildren() ) this.elements.fold.show();

                this.elements.fold.on( 'click' + this.eventNamespace, this.foldToggle.bind( this ) );
            }

            // Навигация по комментариям
            this.elements.scroll_to_parent.on( 'click' + this.eventNamespace, this.scrollToParent.bind( this ) );

            // Ответить
            this.elements.reply.on( 'click' + this.eventNamespace, this.reply.bind( this ) );

            // Удалить
            this.elements.remove.on( 'click' + this.eventNamespace, this.toggle.bind( this ) );

            // Редактировать
            this.elements.edit.on( 'click' + this.eventNamespace, this.edit.bind( this ) );

            if ( this._countdown ) {
                this.updateTimer();
                this.elements.update_timer.everyTime( 1000, this.updateTimer.bind( this ) );
            }
        },

        /**
         * Прокрутка к родительскому комментарию
         */
        updateTimer: function() {
            if ( this._countdown-- ) {
                this.elements.update_timer.text( ls.utils.timeRemaining( this._countdown ) );
            } else {
                this.elements.update_timer.stopTime();
                this.elements.edit.remove();
            }
        },

        /**
         * Прокрутка к родительскому комментарию
         */
        scrollToParent: function() {
            this.getParent().lsComment( 'setScrollChild', this.element );
            this.option( 'comments' ).lsComments( 'scrollToComment', this.getParent() );
        },

        /**
         * Прокрутка обратно к дочернему комментарию
         */
        scrollToChild: function() {
            this.option( 'comments' ).lsComments( 'scrollToComment', this.getScrollChild() );
            this.setScrollChild( null );
        },

        /**
         * Редактировать
         */
        edit: function( event ) {
            event.preventDefault();

            this.option( 'comments' ).lsComments( 'getForm' ).lsCommentForm( 'toggle', this.getId(), true, true );
        },

        /**
         * Ответить
         */
        reply: function( event ) {
            event.preventDefault();

            this.option( 'comments' ).lsComments( 'getForm' ).lsCommentForm( 'toggle', this.getId(), false, true );
        },

        /**
         * Скрыть/восстановить комментарий
         */
        toggle: function( event ) {
            event.preventDefault();

            this._load( 'toggle', { comment_id: this.getId() }, function( response ) {
                this._removeClass( 'self new deleted current' );

                if ( response.state ) {
                    this._addClass( 'deleted' );
                }

                this.elements.remove.text( response.toggle_text );
            });
        },

        /**
         * Помечает комментарий как текущий
         */
        markAsCurrent: function() {
            this._addClass( 'current' );
        },

        /**
         * Убирает пометку о том, что комментарий текущий, если она есть
         */
        notCurrent: function() {
            this._removeClass( 'current' );
            this.setScrollChild( null );
        },

        /**
         * Проверяет комментарий текущий или нет
         *
         * @return {Boolean}
         */
        isCurrent: function() {
            return this._hasClass( 'current' );
        },

        /**
         * Помечает комментарий как новый
         */
        markAsNew: function() {
            this._addClass( 'new' );
        },

        /**
         * Убирает пометку о том, что комментарий новый, если она есть
         */
        notNew: function() {
            this._removeClass( 'new' );
        },

        /**
         * Проверяет комментарий новый или нет
         *
         * @return {Boolean}
         */
        isNew: function() {
            return this._hasClass( 'new' );
        },

        /**
         * Сворачивает/разворачивает ветку комментариев
         */
        foldToggle: function( event ) {
            event.preventDefault();

            this[ this._hasClass( 'folded' ) ? 'unfold' : 'fold' ]();
        },

        /**
         * Сворачивает ветку комментариев
         */
        fold: function() {
            this._addClass( 'folded' ).nextAll( this.options.selectors.wrapper ).hide();
            this.onFold();
        },

        /**
         * Разворачивает ветку комментариев
         */
        unfold: function() {
            this._removeClass( 'folded' ).nextAll( this.options.selectors.wrapper ).show();
            this.onUnfold();
        },

        /**
         * Коллбэк вызываемый после сворачивания ветки комментариев
         */
        onFold: function() {
            this.elements.fold.find('a').text(ls.lang.get('comments.folding.unfold'));
        },

        /**
         * Коллбэк вызываемый после разворачивания ветки комментариев
         */
        onUnfold: function() {
            this.elements.fold.find('a').text(ls.lang.get('comments.folding.fold'));
        },

        /**
         * Проверяет наличие дочерних комментариев
         *
         * @return {Boolean}
         */
        hasChildren: function() {
            return this.element.next( this.options.selectors.wrapper ).length;
        },

        /**
         * Получает ID комментария
         *
         * @return {Number} ID комментария
         */
        getId: function() {
            return this._id;
        },

        /**
         * Получает родительский комментарий
         *
         * @return {jQuery} Родительский комментарий
         */
        getParent: function() {
            return this._parent || ( this._parent = this.option( 'comments' ).lsComments( 'getCommentById', this._parentId ) );
        },

        /**
         * 
         *
         * @return {jQuery} 
         */
        getScrollChild: function() {
            return this._scrollChild;
        },

        /**
         * 
         */
        setScrollChild: function( comment ) {
            this._scrollChild = comment;

            this.elements.scroll_to_child.off();

            if ( comment ) {
                this.elements.scroll_to_child.show().one( 'click' + this.eventNamespace, this.scrollToChild.bind( this ) );
            } else {
                this.elements.scroll_to_child.hide();
            }
        },
    });
})(jQuery);