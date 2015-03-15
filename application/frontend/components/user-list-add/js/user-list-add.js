/**
 * Пополняемый список пользователей
 *
 * @module ls/user-list-add
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsUserListAdd", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            urls: {
                add: null,
                remove: null
            },
            // Селекторы
            selectors: {
                // Блок со списком объектов
                list: '.js-user-list-add-users',
                // Объект
                item: '.js-user-list-small-item',
                // Кнопка удаления объекта
                item_remove: '.js-user-list-add-user-remove',
                // Сообщение о пустом списке
                empty: '.js-user-list-small-empty',
                // Форма добавления
                form: '.js-user-list-add-form',
                // Форма добавления
                form_text: '.js-user-list-add-form input[type=text]'
            },
            // Анимация при скрытии объекта
            hide: {
                effect: 'slide',
                duration: 200,
                direction: 'left'
            },
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

            // Удаление пользователя из списка
            this.elements.list.on('click' + this.eventNamespace, this.options.selectors.item_remove, function (e) {
                _this.remove( $(this).data('user-id') );
                e.preventDefault();
            });

            // Добавление пользователя в список
            this.elements.form.on('submit' + this.eventNamespace, function (e) {
                var items = _this.getItems();

                if ( items.length ) {
                    ls.utils.formLock( _this.elements.form );
                    _this.add( items );
                }

                e.preventDefault();
            });
        },

        /**
         * Получает объекты для добавления
         *
         * @return {Array} Массив с объектами
         */
        getItems: function () {
            return $.map( this.elements.form_text.val().split( ',' ), function( item ) {
                return $.trim( item ) || null;
            });
        },

        /**
         * Добавление объекта
         */
        add: function( users ) {
            if ( ! users ) return;

            this._load( 'add', { 'users': users }, '_onAdd' );
        },

        /**
         * Коллбэк вызываемый при добавлении объекта
         */
        _onAdd: function ( response ) {
            var users = this._getUsersAll();

            // Составляем список добавляемых объектов
            var itemsHtml = $.map( response.users, function ( item ) {
                if ( item.bStateError ) {
                    ls.msg.error( null, item.sMsg );
                } else {
                    ls.msg.notice( null, ls.lang.get( 'common.success.add' ) );

                    this._trigger( "afteruseradd", null, { context: this, item: item, response: response } );

                    this._onUserAdd( item );

                    // Не добавляем юзера если он уже есть в списке
                    return users.filter( '[data-user-id=' + item.user_id + ']' ).length ? null : item.html;
                }
            }.bind(this)).join('');

            if ( itemsHtml ) {
                this.elements.empty.hide();
                this.elements.list.show().prepend( itemsHtml );
            }

            ls.utils.formUnlock( this.elements.form );
            this.elements.form_text.focus().val('');

            this._trigger( "afteradd", null, { context: this, response: response } );
        },

        _onUserAdd: function (item) {
            return;
        },

        /**
         * Удаление объекта
         */
        remove: function( userId ) {
            if ( ! this.options.urls.remove ) return;

            var _this = this;

            this._load( 'remove', { user_id: userId }, function( response ) {
                this._hide( this._getUserById( userId ), this.options.hide, function () {
                    $( this ).remove();

                    // Скрываем список если объектов в нем больше нет
                    if ( ! _this.elements.list.find( _this.options.selectors.item ).length ) {
                        _this.elements.list.hide();
                        _this.elements.empty.show();
                    }
                });

                this._trigger( "afterremove", null, { context: this, response: response } );
            });
        },

        /**
         * Получает пользователя по ID
         *
         * @private
         * @param  {Number} userId , ID объекта
         * @return {jQuery}           Объект
         */
        _getUserById: function( userId ) {
            return this.elements.list.find( this.options.selectors.item + '[data-user-id=' + userId + ']' );
        },

        /**
         * Получает всех пользователей
         */
        _getUsersAll: function() {
            return this.elements.list.find( this.options.selectors.item );
        }
    });
})(jQuery);