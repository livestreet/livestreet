/**
 * User fields
 *
 * @module ls/user/fields
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsUserFields", {
        /**
         * Дефолтные опции
         */
        options: {
            // Селекторы
            selectors: {
                template: '#user-field-template',
                list: '.js-user-field-list',
                field: '.js-user-field-item',
                field_remove: '.js-user-field-item-remove',
                empty: '.js-user-fields-empty',
                submit: '.js-user-fields-submit'
            },
            max: 3
        },

        /**
         * Конструктор
         *
         * @constructor
         * @private
         */
        _create: function () {
            var _this = this;

            this.elements = {
                template: $( this.option( 'selectors.template' ) ),
                empty: this.element.find( this.option( 'selectors.empty' ) ),
                list: this.element.find( this.option( 'selectors.list' ) ),
                submit: this.element.find( this.option( 'selectors.submit' ) )
            };

            this.elements.submit.on( 'click' + this.eventNamespace, this.add.bind( this ) );
            this.element.on( 'click' + this.eventNamespace, this.option( 'selectors.field_remove' ), this.remove.bind( this ) );
            this.element.on( 'change' + this.eventNamespace, 'select', this.change.bind( this ) );
        },

        /**
         * Добавление контакта
         */
        add: function( event ) {
            var typeId, template = this.getTemplate();

            template.find( 'option' ).each(function ( key, value ) {
                var id = $( value ).val();

                if ( this.getCountByTypeId( id ) < this.option( 'max' ) ) {
                    typeId = id;
                    return false;
                }
            }.bind( this ));

            if ( typeId ) {
                template.find( 'select' ).val( typeId );
                this.elements.list.append( template );
            } else {
                template = null;
                ls.msg.error( null, ls.lang.get( 'user.settings.profile.notices.error_max_userfields', { count: this.option( 'max' ) } ) );
            }

            this.elements.empty.hide();
        },

        /**
         * Удаление контакта
         */
        remove: function( event ) {
            if ( ! confirm( ls.lang.get( 'common.remove_confirm' ) ) ) return;

            $( event.target )
                .off()
                .closest( this.option( 'selectors.field' ) )
                .remove();

            if ( this.getCount() === 0 ) {
                this.elements.empty.show();
            }
        },

        /**
         * Изменение типа
         */
        change: function( event ) {
            if ( this.getCountByTypeId( $( event.target ).val() ) > this.option( 'max' ) ) {
                ls.msg.error( null, ls.lang.get( 'user.settings.profile.notices.error_max_userfields', { count: this.option( 'max' ) } ) );
            }
        },

        /**
         * Получает шаблон для вставки
         */
        getTemplate: function() {
            return this.elements.template.clone().show();
        },

        /**
         * Получает кол-во контактов
         */
        getCount: function() {
            return this.elements.list.find( this.option( 'selectors.field' ) ).length;
        },

        /**
         * Получает кол-во контактов определенного типа
         */
        getCountByTypeId: function( id ) {
            return this.elements.list.find( 'select' ).filter(function () {
                return $( this ).val() == id;
            }).length;
        }
    });
})(jQuery);