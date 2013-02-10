/*
 * Modal
 *
 * Author: Denis Shakhov
 * Version: 1.0
 *
 */

(function ($) {
    /**
     * Constructs modal objects
     * @constructor
     * @class Modal
     * @param {Object} options Options
     */
    var Modal = function (element, options) {
        var $this = this;

        this.options = options || {};

        this.$element = $(element);

        // TODO: Fix data options
        this.center = this.$element.data('center') == undefined ? true : (this.$element.data('center') === "false" ? false : true);

        this.$element.appendTo(Modal.settings._overlay);

        this.$element.on('click.modal', function (e) {
            e.stopPropagation();
        });

        this.$element.find(Modal.settings.closeSelector).on('click.modal', function () {
            Modal.settings._hideOverlay();
            if ($this.options.isAjax) $this.$element.remove();
        });
    };

    /**
     * Static methods and vars
     * @type {Object}
     */
    Modal.settings = {
            modalClass:     'modal',
            overlayClass:   'modal-overlay',
            loaderClass:    'modal-loader',

            modalSelector:  '[data-type=modal]',
            toggleSelector: '[data-type=modal-toggle]',
            closeSelector:  '[data-type=modal-close]',

            closeOnEsc:     true,

            _windowWidth:   null,
            _windowHeight:  null,
            _scrollTop:     0,

            _overlay:       null,
            _loader:        null,

            _resize:        function () {
                                Modal.settings._windowWidth = $(window).width();
                                Modal.settings._windowHeight = $(window).height();
                                Modal.settings._overlay.height(Modal.settings._windowHeight);
                            },
            /**
             * Hide overlay, loader and all modals
             */
            _hideOverlay:   function (callback) {
                                if (!Modal.settings._overlay.is(':visible')) return false;

                                Modal.settings._overlay.fadeOut(300, function () {
                                    if ($('html').hasClass('ie7')) {
                                        $('html').css('overflow', 'auto');
                                    } else {
                                        $('html').css('overflow', 'visible');
                                    }
                                    $('body').css({'margin-right': 0});
                                    Modal.settings._overlay.find(Modal.settings.modalSelector).hide();
                                    Modal.settings._loader.hide();
                                    $(window).scrollTop(Modal.settings._scrollTop);

                                    callback && callback();
                                });
                            },
            /**
             * Show overlay
             */
            _showOverlay:    function () {
                                if (Modal.settings._overlay.is(':visible')) {
                                    Modal.settings._overlay.find(Modal.settings.modalSelector).hide();
                                    Modal.settings._loader.hide();
                                    return false;
                                }

                                Modal.settings._scrollTop = $(window).scrollTop();

                                $('html').css({'overflow': 'hidden'});

                                // Prevent content from shifting
                                $('body').css({'margin-right': $(window).width() - Modal.settings._windowWidth});
                                $(window).scrollTop(Modal.settings._scrollTop);

                                // Show overlay
                                Modal.settings._overlay.fadeIn(300);
                             }
    };


    Modal.prototype = {
        /**
         * Show modal
         */
        show: function () {
            Modal.settings._showOverlay();
            this.$element.show();
            Modal.settings._overlay.scrollTop(0);

            // Center
            if (this.center && Modal.settings._windowHeight > this.$element.outerHeight()) { 
                this.$element.css({'margin-top': (Modal.settings._windowHeight - this.$element.height()) / 2});
            }
        }
    };


    $(document).ready(function($) {
        // Hide scrollbar in IE7
        if ($('html').hasClass('ie7')) {
            $('body').attr('scroll', 'no');
            $('html').css('overflow', 'auto');
        }

        Modal.settings._overlay = $('<div class="' + Modal.settings.overlayClass + '" />').height(Modal.settings._windowHeight).appendTo('body');
        Modal.settings._loader = $('<div class="' + Modal.settings.loaderClass + '" />').height(Modal.settings._windowHeight).appendTo(Modal.settings._overlay);
        Modal.settings._resize();

        Modal.settings._overlay.on('click.modal', function () {
            Modal.settings._hideOverlay();
        });

        $(window).on('resize.modal', function () {
            Modal.settings._resize();
        });

        // Close on esc
        if (Modal.settings.closeOnEsc) {
            $(document).on('keyup.modal', function (e) {
                e.keyCode === 27 && Modal.settings._hideOverlay();
            });
        }

        // Init modals
        $(Modal.settings.modalSelector).each(function () {
            var 
                modal  = $(this),
                object = modal.data('object');

            if (!object) modal.data('object', (object = new Modal(this)));
        });

        // Init toggles
        $(Modal.settings.toggleSelector).each(function () {
            var
                toggle = $(this),
                url    = toggle.data('modal-url'),
                modal  = $('#' + toggle.data('modal-target')).data('object');


            toggle.on('click.modal', function () {
                if (url) {
                    Modal.settings._loader.show();
                    Modal.settings._showOverlay();

                    ls.ajax(url, null, function () {
                        var modal = $(this.result.sModal);
                        Modal.settings._loader.hide();
                        modal.data('object', (object = new Modal(modal, { isAjax: true })));
                        modal.data('object').show();
                    }, {
                        error: function () {
                            Modal.settings._hideOverlay(function () {
                                // TODO: Move text to lang file
                                ls.msg.error('Error', 'Please try again later');
                            });
                        }
                    });
                } else {
                    modal.show();
                }
                return false;
            });
        });
    });


    /**
     * Plugin defention
     */
    $.fn.jqmShow = $.fn.modalShow = function () {
        $(this).data('object').show();
    };

    $.fn.jqmHide = $.fn.modalHide = function () {
        Modal.settings._hideOverlay();
    };
})(jQuery);