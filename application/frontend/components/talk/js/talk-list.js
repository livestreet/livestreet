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
            },
            i18n: {
                remove_confirm: '@common.remove_confirm'
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
            var _this = this;

            // Экшнбар
            $('.js-talk-actionbar-select').lsActionbarItemSelect({
                selectors: {
                    target_item: '.js-talk-list-item'
                }
            });

            this.elements.buttonMarkAsRead.on('click', function (e) {
                _this.setAction( $(this).data('action') );
            });

            this.elements.buttonRemove.lsConfirm({
                message: this._i18n('remove_confirm'),
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
