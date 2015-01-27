/**
 * Comment form
 *
 * @module ls/comment/form
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsCommentForm", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            comments: $(),

            // Ссылки
            urls: {
                text: null,
                add: null,
                update: null
            },

            // Селекторы
            selectors: {
                text:          '.js-comment-form-text',
                submit:        '.js-comment-form-submit',
                show_preview:  '.js-comment-form-preview',
                update_submit: '.js-comment-form-update-submit',
                cancel:        '.js-comment-form-update-cancel',
                comment_id:    '.js-comment-form-id'
            },

            // Классы
            classes : {
                locked: 'comment-form--locked'
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

            // ID комментария к которому прикреплена форма
            this._targetId = 0;

            // Заблокирована форма или нет
            this._locked = false;

            this.setModeAdd();

            // Иниц-ия редактора
            this.elements.text.lsEditor();

            //
            // СОБЫТИЯ
            //

            // Отправка формы
            this._on({ submit: 'submit' });

            // Скрытие формы
            this._on( this.elements.cancel, { click: 'hide' } );

            // Превью текста
            this._on( this.elements.show_preview, { click: 'previewShow' } );
        },

        /**
         * Отправляет форму
         */
        submit: function( event ) {
            event.preventDefault();

            // Получаем данные формы до ее блокировки
            var data = this.element.serializeJSON();

            this.lock();
            this[ this.getMode() === this.MODE.ADD ? 'add' : 'update' ]( data );
        },

        /**
         * Добавляет комментарий
         */
        add: function( data ) {
            this._load( 'add', data, 'onAdd' );
        },

        /**
         * Обновляет комментарий
         */
        update: function( data ) {
            this.emptyText();
            this._load( 'update', data, 'onUpdate' );
        },

        /**
         * Коллбэк вызываемый после успешного добавления комментария
         */
        onAdd: function( response ) {
            this.emptyText();
            this.unlock();
            this.option( 'comments' ).lsComments( 'load', response.sCommentId, false );
        },

        /**
         * Коллбэк вызываемый после успешного обновления комментария
         */
        onUpdate: function( response ) {
            var comment = this.option( 'comments' ).lsComments( 'getCommentById', this.getTargetId() ),
                commentNew = this.option( 'comments' ).lsComments( 'initComments', $( $.trim( response.html ) ) );

            this.option( 'comments' )
                .lsComments( 'removeCommentById', this.getTargetId() )
                .lsComments( 'addComments', commentNew );

            comment.replaceWith( commentNew );

            this.hide();
            this.unlock();
            this.emptyText();

            this.option( 'comments' ).lsComments( 'scrollToComment', commentNew );
        },

        /**
         * Подгружает текст комментария
         */
        loadCommentText: function() {
            this._load( 'text', { comment_id: this.getTargetId() }, function( response ) {
                this.setText( response.text );
                this.unlock();
                this.elements.text.lsEditor( 'focus' );
            });
        },

        /**
         * Очищает текстовое поле
         */
        emptyText: function() {
            this.setText( '' );
        },

        /**
         * Получает текст из текстового поля
         */
        getText: function() {
            return this.elements.text.lsEditor( 'getText' );
        },

        /**
         * Устанавливает текст
         */
        setText: function( text ) {
            this.elements.text.lsEditor( 'setText', text );
        },

        /**
         * Показывает/скрывает форму
         */
        toggle: function( commentId, edit, focus ) {
            if ( this.getTargetId() === commentId && this.element.is( ':visible' ) ) {
                if ( ( edit && this.getMode() === this.MODE.ADD ) || ( ! edit && this.getMode() === this.MODE.EDIT ) ) {
                    this[ edit ? 'setModeEdit' : 'setModeAdd' ]();
                } else if ( ! this.isLocked() ) {
                    this.hide();
                }
            } else {
                this.show( commentId, edit, focus );
            }
        },

        /**
         * Показывает форму
         */
        show: function( commentId, edit, focus ) {
            this.setTargetId( commentId );

            this[ edit ? 'setModeEdit' : 'setModeAdd' ]();

            var element = commentId
                ? this.option( 'comments' ).lsComments( 'getCommentById', commentId )
                : this.option( 'comments' ).lsComments( 'getElement', 'reply_root' );

            this.element.insertAfter( element ).show();
            if ( focus ) this.elements.text.lsEditor( 'focus' );
        },

        /**
         * Скрывает форму
         */
        hide: function() {
            if ( this.getMode() === this.MODE.EDIT ) {
                this.emptyText();
            }

            this.element.hide();
            this.previewHide();
        },

        /**
         * Блокирует форму
         */
        lock: function() {
            this._locked = true;
            this._addClass( 'locked' );
            ls.utils.formLock( this.element );
        },

        /**
         * Разблокировывает форму
         */
        unlock: function() {
            this._locked = false;
            this._removeClass( 'locked' );
            ls.utils.formUnlock( this.element );
        },

        /**
         * Проверяет заблокирована форма или нет
         */
        isLocked: function() {
            return this._locked;
        },

        /**
         * Предпросмотр комментария
         */
        previewShow: function() {
            if ( ! this.elements.text.val() ) return;

            this.previewHide();

            this._preview = $( '<div class="comment-preview text"></div>' );

            this.element.before( this._preview );
            ls.utils.textPreview( this.elements.text, this._preview, false);
        },

        /**
         * Предпросмотр комментария
         */
        previewHide: function() {
            if ( ! this._preview ) return;

            this._preview.remove();
            this._preview = null;
        },

        /**
         * Устанавливает режим в "Добавление"
         */
        setModeAdd: function() {
            if ( this.getMode() === this.MODE.EDIT ) this.emptyText();

            this.setMode( this.MODE.ADD );

            this.elements.update_submit.hide();
            this.elements.submit.show();
        },

        /**
         * Устанавливает режим в "Редактирование"
         */
        setModeEdit: function() {
            this.setMode( this.MODE.EDIT );

            this.elements.update_submit.show();
            this.elements.submit.hide();

            this.lock();
            this.loadCommentText();
        },

        /**
         * Получает режим формы
         */
        getMode: function() {
            return this._mode;
        },

        /**
         * Устанавливает режим
         */
        setMode: function( mode ) {
            this._mode = mode;
        },

        /**
         * Получает ID комментария к которому прикреплена форма
         */
        getTargetId: function() {
            return this._targetId;
        },

        /**
         * Устанавливает ID комментария к которому прикреплена форма
         */
        setTargetId: function( id ) {
            this.elements.comment_id.val( id );
            this._targetId = id;
        },

        /**
         * Режимы
         *
         * @readonly
         * @enum {String}
         */
        MODE: {
            EDIT: 'EDIT',
            ADD: 'ADD'
        }
    });
})(jQuery);