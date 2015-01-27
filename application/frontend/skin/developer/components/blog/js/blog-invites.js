/**
 * Приглашение пользователей в закрытый блог
 *
 * @module blog_invite_users
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsBlogInvites", $.livestreet.lsUserListAdd, {
        /**
         * Дефолтные опции
         */
        options: {
            urls: {
                add:      aRouter['blog'] + 'ajaxaddbloginvite/',
                remove:   aRouter['blog'] + 'ajaxremovebloginvite/',
                reinvite: aRouter['blog'] + 'ajaxrebloginvite/'
            },
            selectors: {
                // Кнопка повторного отправления инвайта
                item_reinvite: '.js-blog-invite-user-repeat'
            }
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

            // Повторная отправка инвайта
            this.elements.list.on('click' + this.eventNamespace, this.options.selectors.item_reinvite, function (e) {
                _this.reinvite( $(this).data('user-id') );
                e.preventDefault();
            });
        },

        /**
         * Отправляет инвайт заново
         */
        reinvite: function ( userId ) {
            this._load( 'reinvite', { user_id: userId }, function( response ) {
                this._trigger( "afterreinvite", null, { context: this, response: response } );
            });
        }
    });
})(jQuery);