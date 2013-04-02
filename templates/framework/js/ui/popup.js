/**
 * Popups
 *
 * Base plugin for dropdowns, tooltips and popovers
 *
 * @version 1.0
 * @author Denis Shakhov <denis.shakhov@gmail.com>
 */

var ls = ls || {};

(function($) {
    "use strict";

    /**
     * Constructs popup objects
     * @constructor
     * @class Popup
     * @param {Object} toggle  Toggle element
     * @param {Object} options Options
     */
    var Popup = ls.popup = function (toggle, options) {
        this.init('popup', toggle, options);
    };

    /**
     * Hide all dropdowns
     */
    Popup.hideAll = function (type) {
        $($.fn[type].settings.toggleSelector + '.' + $.fn[type].settings.openClass).each(function () {
            $(this).data('object').hide();
        });
    };

    /**
     * Init plugin
     */
    Popup.initPlugin = function (type, elements, options, variable, value) {
        var returnValue = false;

        // Hide when click anywhere but target
        $('body').on('click', function (e) {
            var el = $($.fn[type].settings.toggleSelector + ', ' + $.fn[type].settings.targetSelector);

            if (el.length && ! el.is(e.target) && el.has(e.target).length === 0) {
                Popup.hideAll(type);
            }
        });

        // Resize
        $(window).resize(function () {
            $($.fn[type].settings.toggleSelector + '.' + $.fn[type].settings.openClass).each(function(event) {
                $(this).data('object').position();
            });
        });

        elements.each(function () {
            var element = $(this),
                object = element.data('object');

            if ( ! object ) { 
                element.data('object', (object = new $.fn[type].Constructor(this, $.extend({}, options, ls.tools.getDataOptions(element)))));
                object.options.params = ls.tools.getDataOptions(element, 'param');
            }
            if (typeof options === 'string') {
                if (options === "option") {
                    if (value) object.options[variable] = value; else returnValue = object.options[variable];
                } else {
                    object[options]();
                }
            }
        });

        return returnValue;
    };

    Popup.prototype = {
        constructor: Popup,

        /**
         * Hooks
         * @type {Object}
         */
        hooks : {
            onInitTarget: false
        },

        /**
         * Init
         * @param  {String} type    Type of control
         * @param  {Object} toggle  Toggle element
         * @param  {Object} options Options
         */
        init: function (type, toggle, options) {
            this.type = type;
            this.timeout = false;
            this.open = false;

            this.togglePosition = {};
            this.targetPosition = {};

            // Default options
            this.options = $.extend({}, $.fn[this.type].defaults, options);

            // Toggle
            this.$toggle = $(toggle);

            // Events
            this.$toggle.on(this.options.event + '.' + this.type, this.options.selector, $.proxy(this.toggle, this));

            // Init target
            ! this.options.template && this.initTarget(this);
        },

        /**
         * Init target
         */
        initTarget: function () {
            var self = this;

            this.$target = this.options.template && ! this.options.target ? $(this.options.template) : $('#' + this.options.target);
            this.$target.hide();

            // Align classes
            this.$target.addClass($.fn[this.type].settings.menuAlignPrefixClass + 'x-' + this.options.alignX);
            this.$target.addClass($.fn[this.type].settings.menuAlignPrefixClass + 'y-' + this.options.alignY);

            // Append to body
            if (this.options.appendToBody) this.$target.appendTo('body');

            // Target's content
            this.options.content && this.setContent(this.options.content);

            // Add classes
            this.options.class && this.$target.addClass(this.options.class);

            // Hide
            this.$target.find('[data-type=dropdown-hide]').on('click', this.hide);

            // Hook
            if (this.hooks.onInitTarget) $.proxy(this.hooks.onInitTarget, this)();
        },

        /**
         * Mouse enter
         */
        enter: function () {
            var self = this;

            if ( ! this.options.delay ) {
                this.show();
            } else {
                this.timeout = setTimeout(function() { self.show(); }, this.options.delay);
            }
        },

        /**
         * Mouse leave
         */
        leave: function () {
            if ( ! this.options.delay ) {
                this.hide();
            } else {
                this.open && this.hide();
                clearTimeout(this.timeout);
                this.timeout = false;
            }
        },

        /**
         * Toggle dropdown
         */
        toggle: function (e) {
            if ( ! this.options.delay) {
                ! this.open ? this.enter() : this.leave();
            } else {
                this.open || (this.timeout && ! this.open) ? this.leave() : this.enter();
            }
            this.options.preventDefault && e.preventDefault();
        },

        /**
         * Show dropdown
         */
        show: function () {
            var self = this;

            typeof this.options.onShow === 'function' && $.proxy(this.options.onShow, this)();

            this.options.template && this.initTarget();
            Popup.hideAll(this.type);
            this.position();
            this.$toggle.addClass($.fn[this.type].settings.openClass);
            this.$target[this.options.effect == 'show' ? 'show' : (this.options.effect == 'fade' ? 'fadeIn' : 'slideDown')](this.options.duration);
            this.open = true;

            // Ajax
            if (this.options.url) {
                this.setContent('');
                this.$target.addClass('loading');
                this.position();

                ls.ajax(this.options.url, this.options.params, function (result) {
                    if (result.bStateError) {
                        self.hide();
                        ls.msg.error('Error', result.sMsg);
                    } else {
                        self.$target.removeClass('loading');
                        self.setContent(result[self.options.ajaxVar]);
                        self.position();
                    }
                }, {
                    error: function () {
                        self.hide();
                        ls.msg.error('Error', 'Please try again later');
                    }
                });
            }
        },

        /**
         * Hide dropdown
         */
        hide: function () {
            var self = this;

            typeof this.options.onHide === 'function' && $.proxy(this.options.onHide, this)();

            this.$toggle.removeClass($.fn[this.type].settings.openClass);
            this.$target[this.options.effect == 'show' ? 'hide' : (this.options.effect == 'fade' ? 'fadeOut' : 'slideUp')](this.options.duration, function () {
                if (self.options.template && ! self.options.target) {
                    self.$target.remove();
                    self.$target = false;
                }
                self.open = false;
            });
        },

        /**
         * Set content
         */
        setContent: function (content) {
            var contentElement = this.$target.find('[data-type=' + this.type + '-content]');
            if ( ! contentElement.length ) contentElement = this.$target;

            contentElement.html(content);
        },

        /**
         * Position menu
         */
        position: function () {
            var toggleWidth  = this.$toggle.outerWidth(),
                toggleHeight = this.$toggle.outerHeight(),
                targetWidth  = this.$target.outerWidth(),
                targetHeight = this.$target.outerHeight();

            this.togglePosition = this.$toggle.offset();

            if ( ! this.options.appendToBody ) {
                this.togglePosition.top = this.togglePosition.left = 0;
            }
            
            switch(this.options.alignY) {
                case 'top':
                    this.targetPosition.top = this.togglePosition.top - targetHeight - this.options.offsetY;
                    break;
                case 'center':
                    this.targetPosition.top = this.togglePosition.top + (toggleHeight - targetHeight) / 2;
                    break;
                case 'bottom':
                    this.targetPosition.top = this.togglePosition.top + toggleHeight + this.options.offsetY;
                    break;
                default:
                    this.targetPosition.top = 0;
            }

            switch(this.options.alignX) {
                case 'left':
                    this.targetPosition.left = this.options.alignY == 'center' ? this.togglePosition.left - targetWidth - this.options.offsetY : this.togglePosition.left + this.options.offsetX;
                    break;
                case 'center':
                    this.targetPosition.left = this.togglePosition.left + (toggleWidth - targetWidth) / 2;
                    break;
                case 'right':
                    this.targetPosition.left = this.options.alignY == 'center' ? this.togglePosition.left + toggleWidth + this.options.offsetY : this.togglePosition.left + toggleWidth - targetWidth - this.options.offsetX;
                    break;
                default:
                    this.targetPosition.left = 0;
            }

            this.$target.css({
                'top'     : this.targetPosition.top,
                'left'    : this.targetPosition.left,
                'bottom'  : 'auto',
                'right'   : 'auto'
            });
        }
    };

    /**
     * Plugin defenition
     */
    $.fn.popup = function (options, variable, value) {
        //Popup.initPlugin('popup', this, options);
    };

    $.fn.popup.Constructor = Popup;

    /**
     * Default options
     * @type {Object}
     */
    $.fn.popup.defaults = {
        target         : false,      // Target ID
        selector       : false,      // Target ID

        alignX         : 'left',     // left, center, right
        alignY         : 'bottom',   // top, center, bottom
        alignTo        : false,

        offsetX        : 0,          // Shift along toggle
        offsetY        : 3,          // Distance from toggle

        appendToBody   : true,       // Append toggle to body
        changeText     : false,
        activateItems  : false,
        
        event          : 'click',
        effect         : 'fade',     // slide, fade, show
        delay          : 0,
        duration       : 200,

        url            : false,
        params         : {},
        class          : false,
        title          : false,
        content        : false,
        template       : false,
        ajaxVar        : 'sText',

        onShow         : false,
        onHide         : false,
        preventDefault : true
    };

    // Global settings
    $.fn.popup.settings = {
        // Selectors
        toggleSelector: '[data-type=popup-toggle]',
        targetSelector: '[data-type=popup-target]',

        // Classes
        openClass:            'open',
        menuAlignPrefixClass: 'align'
    };
})(jQuery);