/*
 * Dropdowns
 *
 * Author: Denis Shakhov
 * Version: 1.0
 *
 * TODO: Add fixed menu option
 */

(function($) {
    /**
     * Constructs dropdown objects
     * @constructor
     * @class Dropdown
     * @param {Object} options Options
     */
    var Dropdown = function (element) {
        var self = this;

        this.$element = $(element);
        this.options = {
            menuId         : ls.tools.getOption(this.$element,'dropdown-menu'),
            align          : ls.tools.getOption(this.$element,'dropdown-align', 'left'),
            isAjax         : ls.tools.getOption(this.$element,'dropdown-ajax', false),
            isAppendToBody : ls.tools.getOption(this.$element,'dropdown-append-to-body', true),
            isChangeText   : ls.tools.getOption(this.$element,'dropdown-change-text', true),
            defaultText    : this.$element.text()
        };
        this.menu = $('#' + this.options.menuId);

        if (this.options.isAppendToBody) this.menu.appendTo('body');

        this.options.defaultText = this.options.defaultText || Dropdown.settings.defaultActiveText;

        if (this.options.isChangeText) {
            var activeText = this.menu.find('li.active').text();
            this.$element.text(this.menu.find('li.active').text() || this.options.defaultText);
        }

        // Resize
        $(window).resize(function () {
            self.position();
        });

        if (this.options.isAjax) {
            this.menu.find('li > a').on('click', function () {
                $this = $(this);
                if (self.options.isChangeText) self.$element.text($this.text());
                self.menu.find('li').removeClass('active');
                $this.parent('li').addClass('active');
                self.toggle();
            });
        }
    };

    // Common settings
    Dropdown.settings = {
        dropdownSelector: '[data-toggle=dropdown]',
        menuSelector: '.dropdown-menu',
        menuTopOffset: 2,
        defaultActiveText: '...'
    };

    // Static methods
    Dropdown.methods = {
        hideAll: function (currentDropdown) {
            $(Dropdown.settings.dropdownSelector).removeClass('open');
            $(Dropdown.settings.menuSelector).hide();
        }
    };

    Dropdown.prototype = {
        /**
         * Toggle dropdown
         */
        toggle: function () {
            if (!this.menu.is(':visible')) {
                Dropdown.methods.hideAll();
                this.position();
            }
            this.$element.toggleClass('open');
            this.menu.toggle();
        },

        /**
         * Position menu
         */
        position: function () {
            var
                pos    = this.$element.offset(),
                height = this.$element.outerHeight(),
                width  = this.$element.outerWidth();

            this.menu.css({
                'top': this.options.isAppendToBody ? pos.top + height + Dropdown.settings.menuTopOffset : height + Dropdown.settings.menuTopOffset,
                'left': this.options.isAppendToBody ? ( this.options.align == 'right' ? 'auto' : pos.left ) : ( this.options.align == 'right' ? 'auto' : 0 ),
                'right': this.options.isAppendToBody ? ( this.options.align == 'right' ? $(window).width() - pos.left - width : 'auto' ) : ( this.options.align == 'right' ? 0 : 'auto' )
            });
        }
    };

    // Init
    $(document).ready(function($) {
        $('body').on('click', function (e) {
            var $target = $(e.target);
            // TODO: Fix hide function
            if ($target.data('toggle') != 'dropdown' && !$target.hasClass('dropdown-menu')) Dropdown.methods.hideAll();
        });
    });

    $(document).on('click', Dropdown.settings.dropdownSelector, function () {
        var 
            dropdown = $(this),
            object = dropdown.data('object');

        if (!object) dropdown.data('object', (object = new Dropdown(this)));

        object.toggle();
        return false;
    });
})(jQuery);