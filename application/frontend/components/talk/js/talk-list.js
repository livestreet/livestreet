/**
 * Управление списком личных сообщений
 *
 * @module ls/talk-list
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsTalkList", $.livestreet.lsComponent, {
        /**
         * Дефолтные опции
         */
        options: {
            // Селекторы
            selectors: {
                form: '#talk-form',
                formAction: '#talk-form-action',
                button: '.js-talk-form-button',
                buttonMarkAsRead: '.js-talk-form-button[data-action=mark_as_read]',
                buttonRemove: '.js-talk-form-button[data-action=remove]'
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

            // Экшнбар
            $('.js-talk-actionbar-select').lsActionbarItemSelect({
                selectors: {
                    target_item: '.js-talk-list-item'
                }
            });

            this.elements.buttonMarkAsRead.on('click', function (e) {
                this.setAction( $(this).data('action') );
            }.bind(this));

            this.elements.buttonRemove.lsConfirm({
                message: ls.lang.get('common.remove_confirm'),
                onconfirm: function () {
                    this.setAction( 'remove' );
                }.bind(this)
            })
        },

        /**
         * Устанавливает текущее действие
         *
         * @param {String} action Действие
         */
        setAction: function( action ) {
            if ( ! this.elements.form.find('input[type=checkbox]:checked').length ) return;

            this.elements.formAction.val( action );
            this.elements.form.submit();
        }
    });
})(jQuery);
