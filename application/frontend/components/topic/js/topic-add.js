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
            // Максимальное кол-во блогов которое можно выбрать
            max_blog_count: 3,

            // Ссылки
            urls: {
                add: aRouter[ 'content' ] + 'ajax/add/',
                edit: aRouter[ 'content' ] + 'ajax/edit/',
                preview: aRouter[ 'content' ] + 'ajax/preview/'
            },

            // Селекторы
            selectors: {
                preview: '#topic-text-preview',
                preview_content: '#topic-text-preview .js-topic-preview-content',
                image_preview: '.js-topic-add-field-image-preview',
                blogs: '.js-topic-add-blogs',
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
                preview_content: $( this.option( 'selectors.preview_content' ) ),
                image_preview: this.element.find( this.option( 'selectors.image_preview' ) ),
                blogs: this.element.find( this.option( 'selectors.blogs' ) ),
                buttons: {
                    preview: this.element.find( this.option( 'selectors.buttons.preview' ) ),
                    preview_hide: $( this.option( 'selectors.buttons.preview_hide' ) ),
                    draft: this.element.find( this.option( 'selectors.buttons.draft' ) ),
                    submit: this.element.find( this.option( 'selectors.buttons.submit' ) )
                }
            };

            // Иниц-ия формы
            this.element.lsContent({
                urls: {
                    add: this.option( 'urls.add' ),
                    edit: this.option( 'urls.edit' )
                },
                callbacks: {
                    beforeSubmit: function() {
                        _this.prepareParams(this);
                    }
                }
            });

            // Выбор блогов
            this.elements.blogs.chosen({
                max_selected_options: this.option( 'max_blog_count' ),
                width: '100%'
            });

            // Установка правильной сортировки блогов
            var chosenOrder = this.elements.blogs.data('chosenOrder');
            if (chosenOrder && chosenOrder.length) {
                this.elements.blogs.setSelectionOrder(chosenOrder);
            }

            // Превью (изображение)
            this.elements.image_preview.lsFieldImageAjax({
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
            this.element.lsContent( 'submit', { is_draft: 1 } );
        },

        /**
         * Превью текста
         */
        previewShow: function() {
            ls.ajax.submit( this.option( 'urls.preview' ), this.element, function( response ) {
                if ( response.bStateError ) {
                    ls.msg.error( null, response.sMsg );
                } else {
                    this.elements.preview.show();
                    this.elements.preview_content.html( response.sText );
                }
            }.bind( this ), {
                submitButton: this.elements.buttons.preview
            });
        },

        /**
         * Закрытие превью текста
         */
        previewHide: function() {
            this.elements.preview.hide();
            this.elements.preview_content.empty();
        },

        /**
         * Дополнительная обработка параметров перед отправкой формы
         * @param formContent
         */
        prepareParams: function(formContent) {
            /**
             * Корректируем сортировку выбранных блогов
             */
            if (this.elements.blogs.length) {
                $.extend(formContent.option('params'), {
                    'topic[blogs_id_raw]': this.elements.blogs.getSelectionOrder()
                });
            }
        }
    });
})(jQuery);