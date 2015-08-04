/**
 * Выбор пользователей
 *
 * @module ls/user/field-choose
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsUserFieldChoose", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                modal: null
            },
            // Селекторы
            selectors: {
                // Текствое поле
                text: '.js-user-field-choose-text',
                // Выбор пользователей
                button: '.js-user-field-choose-button'
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

            // Показывает модальное окно со списком пользователей
            // и принимает от него список выбранных пользователей
            this.elements.button.on( 'click', function (e) {
                ls.userModalList.show( this.option( 'urls.modal' ), true, this.onModalListAdd.bind(this) );

                e.preventDefault();
            }.bind(this));
        },

        /**
         * Получает список выбранных пользователей
         *
         * @return {Array} Массив с выбранными пользователями
         */
        getUsers: function () {
            return $.map( this.elements.text.val().split( ',' ), function( item ) {
                return $.trim( item ) || null;
            });
        },

        /**
         * Очищает поле со списком пользователей
         */
        empty: function () {
            this.elements.text.val('');
        },

        /**
         * Коллбэк вызываемый при отправке формы в мод. окне
         *
         * @param {Array} users Список выбранных пользователей
         */
        onModalListAdd: function (users) {
            // Получаем логины для добавления
            var loginsNew = $.map(users, function(user) {
                return user.login;
            });

            // Получаем логины которые уже прописаны
            var loginsOld = $.map(this.elements.text.val().split(','), function(login) {
                return $.trim(login) || null;
            });

            // Мержим логины
            var logins = $.merge(loginsOld, loginsNew);

            // Убираем дубликаты
            logins = $.grep(logins, function(value, key) {
                return $.inArray(value, logins) === key;
            });

            this.elements.text.val( logins.join(', ') );
        },
    });
})(jQuery);