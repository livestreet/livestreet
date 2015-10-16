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
                modal: null,
                autocomplete: aRouter.ajax + 'autocompleter/user/'
            },
            // Селекторы
            selectors: {
                // Список пользователей
                users: '.js-user-field-choose-users',
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

            this.elements.users.lsFieldAutocomplete({
                urls: {
                    load: this.option( 'urls.autocomplete' )
                },
                params: {
                    extended: true
                }
            });
        },

        /**
         * Получает список выбранных пользователей
         *
         * @return {Array} Массив с выбранными пользователями
         */
        getUsers: function () {
            return this.elements.users.val();
        },

        /**
         * Очищает поле со списком пользователей
         */
        empty: function () {
            this.elements.users.empty().trigger('chosen:updated');
        },

        /**
         * Коллбэк вызываемый при отправке формы в мод. окне
         *
         * @param {Array} users Список выбранных пользователей
         */
        onModalListAdd: function (users) {
            var currentUsers = this.elements.users.val();

            $.each(users, function (index, user) {
                if ($.inArray(user.id + "", currentUsers) !== -1) return;

                $('<option />')
                    .attr('value', user.id)
                    .prop('selected', true)
                    .html(user.login)
                    .appendTo(this.elements.users);
            }.bind(this));

            this.elements.users.trigger('chosen:updated');
        },
    });
})(jQuery);