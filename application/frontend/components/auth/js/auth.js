/**
 * Авторизация
 *
 * @module ls/auth
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

ls.auth = (function ($) {
    "use strict";

    /**
     * Инициализация
     */
    this.init = function() {
        /* Авторизация */
        $('.js-auth-login-form').on('submit', function (e) {
            ls.ajax.submit(aRouter.auth + 'ajax-login', $(this), function ( response ) {
                response.sUrlRedirect && (window.location = response.sUrlRedirect);
            });

            e.preventDefault();
        });

        /* Регистрация */
        $('.js-auth-registration-form').on('submit', function (e) {
            ls.ajax.submit(aRouter.auth + 'ajax-register', $(this), function ( response ) {
                response.sUrlRedirect && (window.location = response.sUrlRedirect);
            });

            e.preventDefault();
        });

        /* Восстановление пароля */
        $('.js-auth-reset-form').on('submit', function (e) {
            ls.ajax.submit(aRouter.auth + 'ajax-password-reset', $(this), function ( response ) {
                response.sUrlRedirect && (window.location = response.sUrlRedirect);
            });

            e.preventDefault();
        });

        /* Повторный запрос на ссылку активации */
        ls.ajax.form(aRouter.auth + 'ajax-reactivation', '.js-form-reactivation', function (result, status, xhr, form) {
            form.find('input').val('');
            ls.hook.run('ls_user_reactivation_after', [form, result]);
        });

        $('.js-modal-toggle-registration').on('click', function (e) {
            $('.js-auth-tab-reg').lsTab('activate');
            $('#modal-login').lsModal('show');
            e.preventDefault();
        });

        $('.js-modal-toggle-login').on('click', function (e) {
            $('.js-auth-tab-login').lsTab('activate');
            $('#modal-login').lsModal('show');
            e.preventDefault();
        });
    };

    return this;
}).call(ls.auth || {}, jQuery);