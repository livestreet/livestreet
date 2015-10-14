/**
 * Персональные теги
 *
 * @module ls/tags-favourite
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsTagsFavourite", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                save: null
            },

            // Селекторы внешних элементов
            extSelectors: {
                // Общий блок для всех виджетов с формой редактирвоания
                editBlock: '#favourite-form-tags',
                // Форма редактирования
                form: '#js-favourite-form',
                // Кнопка отправки формы
                formSubmitButton: '.js-tags-form-submit',
                // Поле со списком тегов
                formTags: '.js-tags-form-input-list'
            },

            // Селекторы
            selectors: {
                // Блок с персональными тегами
                tags: '.js-tags-personal-tags',
                // Персональный тег
                tag: '.js-tags-personal-tag',
                // Кнопка редактирвоания
                edit: '.js-tags-personal-edit'
            },

            // Ajax параметры
            params : {
                target_type: null
            },

            // HTML
            html: {
                // Персональный тег
                tag: function ( tag ) {
                    return '<li class="ls-tags-item ls-tags-item--personal js-tags-personal-tag">' +
                        '<a rel="tag" href="' + tag.url + '">' + tag.tag + '</a></li>';
                }
            }
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            this._super();

            this.extElements = this._getElementsFromSelectors( this.options.extSelectors );

            this._on( this.elements.edit, { click: '_onEditClick' });
            this._on(  this.extElements.form, { submit: '_onFormSubmit' });
        },

        /**
         * Коллбэк вызываемый при клике по кнопке редактирования
         */
        _onEditClick: function( event ) {
            this.editShow();
            event.preventDefault();
        },

        /**
         * Коллбэк вызываемый при сабмите формы
         *
         * @param  {Object} event
         */
        _onFormSubmit: function( event ) {
            // Для всех виджетов используется одна общая форма редактирования,
            // поэтому убеждаемся что форма открыта именно для текущего виджета
            if ( this.extElements.form.data( 'target_id' ) != this.option( 'params.target_id' ) ) return;

            this._submit( 'save', this.extElements.form, '_onFormSubmitSuccess', {
                submitButton: this.extElements.formSubmitButton
            });

            event.preventDefault();
        },

        /**
         * Коллбэк вызываемый при успешной отправке формы
         *
         * @param  {Object} response
         */
        _onFormSubmitSuccess: function( response ) {
            this.editHide();
            this.setPersonalTags( response.tags );
        },

        /**
         * Получает персональные теги (jQuery элементы)
         */
        getPersonalTagsElements: function() {
            return this.element.find( this.option( 'selectors.tag' ) );
        },

        /**
         * Получает персональные теги
         */
        getPersonalTags: function() {
            return this.getPersonalTagsElements().map(function ( index, tag ) {
                return this.getTagInfo( $( tag ) );
            }.bind( this ));
        },

        /**
         * Получает информацию о теге (урл и имя)
         *
         * @param  {jQuery} tagElement Тег
         * @return {Object}            Тег
         */
        getTagInfo: function( tagElement ) {
            tagElement = tagElement.find( 'a' );

            return {
                tag: $.trim( tagElement.text() ),
                url: tagElement.attr( 'href' )
            };
        },

        /**
         * Устанавливает персональные теги
         *
         * @param {Array} tags Список персональных тегов
         */
        setPersonalTags: function( tags ) {
            this.removePersonalTags();
            this.elements.edit.before( $.map( tags, this.option( 'html.tag' ) ) );
        },

        /**
         * Удаляет персональные теги
         */
        removePersonalTags: function() {
            this.getPersonalTagsElements().remove();
        },

        /**
         * Отмечает виджет как (не)доступный для редактирования
         *
         * @param {Boolean} isEditable Доступен виджет для редактирования или нет
         */
        setEditable: function( isEditable ) {
            if ( isEditable ) {
                this.elements.edit.show();
            } else {
                this.removePersonalTags();
                this.elements.edit.hide();
            }
        },

        /**
         * Показывает блок редактирования
         */
        editShow: function() {
            this.extElements.form.data( 'target_id', this.option( 'params.target_id' ) );
            this.extElements.formTags.val( this._tagsToString() );
            this.extElements.editBlock.lsModal( 'show' );
        },

        /**
         * Скрывает блок редактирования
         */
        editHide: function() {
            this.extElements.editBlock.lsModal( 'hide' );
        },

        /**
         * Возвращает персональные теги в виде строки
         */
        _tagsToString: function() {
            return $.map( this.getPersonalTags(), function( tag ) { return tag.tag; } ).join( ', ' );
        },
    });
})(jQuery);