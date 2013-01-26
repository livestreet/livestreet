/*
 * Modal
 *
 * Author: Denis Shakhov
 * Version: 1.0
 *
 */

(function ($) {
    $.fn.modal = function (options) {
        $.modal.options      = $.extend($.modal.defaults, options);
        $.modal.windowWidth  = $(window).width();
        $.modal.windowHeight = $(window).height();
        $.modal.overlay      = $('<div class="' + $.modal.options.overlayClass + '" />').height($(window).height()).appendTo('body');

        /* IE7 */
        if ($('html').hasClass('ie7')) {
            $('body').attr('scroll', 'no');
            $('html').css('overflow', 'auto');
        }

        $.modal.overlay.click(function () {
            $.modal.hide($('.modal:visible'));
        });

        $(window).resize(function () {
            $.modal.windowWidth = $(window).width();
            $.modal.windowHeight = $(window).height();
            $.modal.overlay.height($.modal.windowHeight);
        });

        // Init modals
        $('.modal').each(function () {
            var modal = $(this);

            modal.appendTo($.modal.overlay);

            modal.click(function (e) {
                e.stopPropagation();
            });

            modal.find('[data-toggle=modal-close]').click(function () {
                $.modal.hide(modal);
            });
        });

        // Init toggles
        this.each(function () {
            var
                toggle = $(this),
                center = toggle.data('modal-center') || true,
                modal  = $('#' + toggle.data('modal-target'));

            toggle.click(function () {
                $.modal.show(modal, null, center);
                return false;
            });
        });
    };

    $.fn.modalShow = function (options) {
        var
            defaults = {
                content: '',
                center: true,
                onShow: false
            },
            options = $.extend(defaults, options);

        $.modal.show($(this), options.content, options.center);

        if (options.onShow) options.onShow();
    };

    $.fn.modalHide = function () {
        $.modal.hide($(this));
    };

    // Fallback
    $.fn.jqmShow = $.fn.modalShow;
    $.fn.jqmHide = $.fn.modalHide;

    $.modal = {
        defaults: {
            overlayClass: 'modal-overlay',
            closeClass: 'modal-close'
        },
        options: null,
        overlay: null,
        windowWidth: null,
        windowHeight: null,
        scrollTop: null,

        show: function (modal, content, center) {
            center = center == undefined ? true : center;
            this.scrollTop = $(window).scrollTop();

            $('.modal:visible').hide();
            console.log(this.scrollTop);
            $('html').css({'overflow': 'hidden'});
            if (content) modal.find('.modal-content').html(content);
            modal.show();

            // Prevent content from shifting
            $('body').css({'margin-right': $(window).width() - this.windowWidth});
            $(window).scrollTop(this.scrollTop);

            // Show overlay
            this.overlay.fadeIn(300);

            // Center
            if (center && this.windowHeight > modal.outerHeight()) modal.css({'margin-top': (this.windowHeight - modal.height()) / 2});
        },

        hide: function (modal) {
            this.overlay.fadeOut(300, function () {
                if ($('html').hasClass('ie7')) {
                    $('html').css('overflow', 'auto');
                } else {
                    $('html').css('overflow', 'visible');
                }
                $('body').css({'margin-right': 0});
                modal.hide();
                $(window).scrollTop($.modal.scrollTop);
            });
        }
    };
})(jQuery);