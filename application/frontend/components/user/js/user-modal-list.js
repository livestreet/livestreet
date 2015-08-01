/**
 * Модальное окно со списком пользователей
 *
 * @module ls/user/modal-list
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.userModalList = (function ($) {
    "use strict";

    /**
     * Инициализация
     */
    var init = function(event, modal) {
        modal.element.on('click', '.js-user-list-select-add', function (e) {
            var checkboxes = $('.js-user-list-select').find('.js-user-list-small-checkbox:checked');

            // Получаем логины для добавления
            var users = $.map(checkboxes, function(element) {
                return {
                    id: $(element).data('user-id'),
                    login: $(element).data('user-login')
                }
            });

            if ( $.isFunction(modal.options.add) ) {
                modal.options.add(users);
            }

            modal.hide();
        });
    };

    /**
     * Показывает окно
     *
     * @param  {String}  url            
     * @param  {Boolean} isSelectable   
     * @param  {Object}  options        
     * @param  {Object}  params         
     */
    this.show = function( url, isSelectable, onAdd, options, params ) {
        ls.modal.load( url, {
            selectable: isSelectable
        }, {
            aftershow: init.bind(this),
            add: onAdd
        });
    }

    return this;
}).call(ls.user || {}, jQuery);