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
     * Hide all popups
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
        $('body').off('click.' + type).on('click.' + type, function (e) {
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

        // Events
        if (typeof options !== 'string') options = $.extend({}, $.fn[type].defaults, options);
        options.event = options.event == 'hover' ? 'mouseenter.' + type + ' mouseleave.' + type : options.event;

        if (options.selector) {
            $(elements).on(options.event, options.selector, function (e) {
                ! $(this).data('object') && Popup.initPluginElements(type, $(this), options, variable, value);
                $(this).data('object').toggle(e);
            });
        } else {
            Popup.initPluginElements(type, elements, options, variable, value);
        }

        return returnValue;
    };

    Popup.initPluginElements = function (type, elements, options, variable, value) {
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
    }

    Popup.prototype = {
        constructor: Popup,

        /**
         * Hooks
         * @type {Object}
         */
        hooks : {
            onInitTarget: false,
            onEnter: false,
            onLeave: false
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
            this.state = 'out';

            this.togglePosition = {};
            this.targetPosition = {};

            // Default options
            this.options = $.extend({}, $.fn[this.type].defaults, options);

            // Toggle
            this.$toggle = $(toggle);

            // Events
            ! this.options.selector && this.$toggle.on(this.options.event, $.proxy(this.toggle, this));

            // Init target
            ! this.options.template && this.initTarget(this);

            // onInit callback
            typeof this.options.onInit === 'function' && $.proxy(this.options.onInit, this)();
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
            this.options.classes && this.$target.addClass(this.options.classes);

            // Hide
            this.$target.find('[data-type=' + this.type + '-hide]').on('click', function () {
                self.hide();
            });

            // Hook
            if (this.hooks.onInitTarget) $.proxy(this.hooks.onInitTarget, this)();
        },

        /**
         * Mouse enter
         */
        enter: function () {
            var self = this;

            this.state = 'in';

            if (this.hooks.onEnter) $.proxy(this.hooks.onEnter, this)();

            if ( ! this.options.delay || this.open) {
                this.show();
            } else {
                this.timeout = setTimeout(function() { 
                    self.show();
                    self.timeout = false; 
                }, this.options.delay);
            }
        },

        /**
         * Mouse leave
         */
        leave: function () {
            this.state = 'out';

            if (this.hooks.onLeave) $.proxy(this.hooks.onLeave, this)();

            if ( ! this.options.delay || ! this.timeout ) {
                this.hide();
            } else {
                clearTimeout(this.timeout);
                this.timeout = false;
            }
        },

        /**
         * Toggle popup
         */
        toggle: function (e) {
            (this.options.event != 'click' && this.state === 'in') || (this.options.event == 'click' && this.open) ? this.leave() : this.enter();
            this.options.preventDefault && e.preventDefault();
        },

        /**
         * Show popup
         */
        show: function () {
            var self = this;

            Popup.hideAll(this.type);

            if (this.options.template && ! this.open) this.initTarget();

            this.$toggle.addClass($.fn[this.type].settings.openClass);

            this.$target.stop(true, true)[this.options.effect == 'show' ? 'show' : (this.options.effect == 'fade' ? 'fadeIn' : 'slideDown')](this.options.duration);

            typeof this.options.onShow === 'function' && $.proxy(this.options.onShow, this)();

            this.position();
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
                        if (self.$target) {
                            self.$target.removeClass('loading');
                            self.setContent(result[self.options.ajaxVar]);
                            self.position();
                        }
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
         * Hide popup
         */
        hide: function () {
            var self = this,
                duration = this.options.duration;

            if ( ! this.open ) return;

            typeof this.options.onHide === 'function' && $.proxy(this.options.onHide, this)();

            this.$toggle.removeClass($.fn[this.type].settings.openClass);

            this.$target.is(':animated') && (duration = 0);

            this.$target.stop(true, true)[this.options.effect == 'show' ? 'hide' : (this.options.effect == 'fade' ? 'fadeOut' : 'slideUp')](duration, function () {
                if (self.options.template && ! self.options.target && self.state === 'out') {
                    self.$target.remove();
                    self.$target = false;
                }
            });
            this.open = false;
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
                targetHeight = this.$target.outerHeight(),
                hasRelative = $('body').css('position') == 'relative' || $('html').css('position') == 'relative';

            this.targetPosition.top =
            this.targetPosition.bottom =
            this.targetPosition.left =
            this.targetPosition.right = 'auto';

            this.togglePosition = this.$toggle.offset();

            // TODO: Fix position when appenToBody is false
            // if ( ! this.options.appendToBody ) {
            //     this.togglePosition.top = this.togglePosition.left = 0;
            // }
            
            switch(this.options.alignY) {
                case 'top':
                    this.targetPosition.bottom = (hasRelative ? $(document).height() : $(window).height()) - this.togglePosition.top + this.options.offsetY;
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
                    if (this.options.alignY == 'center') {
                        this.targetPosition.right = $(window).width() - this.togglePosition.left + this.options.offsetY;
                    } else {
                        this.targetPosition.left = this.togglePosition.left + this.options.offsetX;
                    }
                    break;
                case 'center':
                    this.targetPosition.left = this.togglePosition.left + (toggleWidth - targetWidth) / 2;
                    break;
                case 'right':
                    if (this.options.alignY == 'center') {
                        this.targetPosition.left = this.togglePosition.left + toggleWidth + this.options.offsetY;
                    } else {
                        this.targetPosition.right = $(window).width() - this.togglePosition.left - toggleWidth + this.options.offsetX;
                    }
                    break;
                default:
                    this.targetPosition.left = 0;
            }

            this.$target.css({
                'top'     : this.targetPosition.top,
                'left'    : this.targetPosition.left,
                'bottom'  : this.targetPosition.bottom,
                'right'   : this.targetPosition.right
            });
        }
    };

    /**
     * Plugin defenition
     */
    $.fn.popup = function (options, variable, value) {
        Popup.initPlugin('popup', this, options);
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
        classes        : false,
        title          : false,
        content        : false,
        template       : false,
        ajaxVar        : 'sText',

        onShow         : false,
        onHide         : false,
        onInit         : false,
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