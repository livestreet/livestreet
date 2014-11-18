/**
 * Форма добавления топика
 *
 * @module ls/topic/add
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
    "use strict";

    $.widget( "livestreet.lsTopicAdd", {
        /**
         * Дефолтные опции
         */
        options: {
            // Ссылки
            urls: {
                add: aRouter[ 'content' ] + 'ajax/add/',
                edit: aRouter[ 'content' ] + 'ajax/edit/',
                preview: aRouter[ 'content' ] + 'ajax/preview/'
            },

            // Селекторы
            selectors: {
                preview: '#topic-text-preview',
                buttons: {
                    preview: '.js-topic-preview-text-button',
                    preview_hide: '.js-topic-preview-text-hide-button',
                    draft: '.js-topic-draft-button'
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
            var _this = this;

            this.elements = {
                preview: $( this.option( 'selectors.preview' ) ),
                buttons: {
                    preview: this.element.find( this.option( 'selectors.buttons.preview' ) ),
                    preview_hide: $( this.option( 'selectors.buttons.preview_hide' ) ),
                    draft: this.element.find( this.option( 'selectors.buttons.draft' ) ),
                    submit: this.element.find( this.option( 'selectors.buttons.submit' ) ),
                }
            };

            // Иниц-ия формы
            this.element.lsContent({
                urls: {
                    add: this.option( 'urls.add' ),
                    edit: this.option( 'urls.edit' )
                }
            });

            // Добавление в черновик
            this.elements.buttons.draft.on( 'click' + this.eventNamespace, this.saveAsDraft.bind( this ) );

            // Превью текста
            this.elements.buttons.preview.on( 'click' + this.eventNamespace, this.previewShow.bind( this ) );

            // Закрытие превью текста
            this.elements.buttons.preview_hide.on( 'click' + this.eventNamespace, this.previewHide.bind( this ) );
        },

        /**
         * Добавление в черновик
         */
        saveAsDraft: function() {
            this.element.lsContent( 'submit', { is_draft: 1 });
        },

        /**
         * Превью текста
         */
        previewShow: function() {
            ls.ajax.submit( this.option( 'urls.preview' ), this.element, function( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( null, response.sMsg );
                } else {
                    this.elements.preview.show().html( response.sText );
                }
            }.bind( this ), {
                submitButton: this.elements.buttons.preview
            });
        },

        /**
         * Закрытие превью текста
         */
        previewHide: function() {
            this.elements.preview.hide().empty();
        }
    });
})(jQuery);