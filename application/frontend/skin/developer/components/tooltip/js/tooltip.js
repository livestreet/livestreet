/**
 * Всплывающие подсказки
 *
 * @module tooltip
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

$.widget( "livestreet.tooltip", {
    /**
     * Дефолтные опции
     */
    options: {
        //
        target: null,
        // Позиционирование
        // Для позиционирования используется модуль position библиотеки jQuery UI
        position: {
            my: "center bottom-15",
            at: "center top",
            collision: "flipfit flip"
        },
        // Анимация при показе
        show: {
            effect: 'fadeIn',
            duration: 200
        },
        // Анимация при скрытии
        hide: {
            effect: 'fadeOut',
            duration: 200
        },
        // Событие при котором показывается тултип
        // click, hover
        trigger: 'hover',
        //
        classes: null,
        //
        template: '<div class="tooltip">' +
                      '<div class="tooltip-title js-tooltip-title"></div>' +
                      '<div class="tooltip-content js-tooltip-content"></div>' +
                  '</div>',
        // Коллбэк вызываемый при изменении положения меню
        reposition: null,
        //
        selectors: {
            title: '.js-tooltip-title',
            content: '.js-tooltip-content'
        },
        //
        useAttrTitle: true,
        // Ajax
        ajax: {
            url: null,
            result: 'sText',
            params: null
        }
    },

    /**
     * Конструктор
     *
     * @constructor
     * @private
     */
    _create: function() {
        this.options.target = this.element.data('tooltip-target') || this.options.target;

        this._target = this.options.target ? $( this.options.target ) : $( this.options.template );
        this._targetTitle = this._target.find( this.options.selectors.title );
        this._targetContent = this._target.find( this.options.selectors.content );

        // Идет загрузка данных через аякс или нет
        this.loading = false;

        // Состояние тултипа
        this.state = this._state.HIDDEN;

        // Ajax
        this.options.ajax.url = this.options.ajax.url || this.element.data('tooltip-url');

        // Добавляем кастомные классы
        if ( this.options.classes ) this._target.addClass(this.options.classes);

        // Устанавливаем заголовок и контент
        if ( this._targetTitle.length ) {
            this.setTitle( this.element.data('tooltip-title') );
        }

        var attrTitle = this.element.attr('title');

        if ( attrTitle && this.options.useAttrTitle ) {
            this.setContent(attrTitle);
            this.element.removeAttr('title').data('tooltip-content', attrTitle);
        } else {
            this.setContent( this.element.data('tooltip-content') );
        }

        // Объект относительно которого позиционируется тултип
        this.options.position.of = this.options.position.of || this.element;

        this.options.position.using = this.options.position.using || function ( position, feedback ) {
            ls.utils.removeClassByPrefix( this._target, 'position-' );

            this._target
                    .addClass( 'position-y-' + feedback.vertical + ' ' +  'position-x-' + feedback.horizontal )
                    .css( position );
        }.bind(this);


        // События
        // ----------

        // Клик по переключателю
        this._on( this.options.trigger == 'click' ? {
            click : function (event) {
                this.toggle();
                event.preventDefault();
            }
        } : {
            mouseenter : 'show',
            mouseleave : 'hide'
        });

        // Скролл/ресайз
        this.window.on('resize scroll', function () {
            this._reposition( false );
        }.bind(this));

        // При клике вне тултипа скрываем его
        if (this.options.trigger == 'click') {
            this.document.on('click' + this.eventNamespace, function (event) {
                if ( ! this._target.is(event.target) && this._target.has(event.target).length === 0 && ! this.element.is(event.target) && this.element.has(event.target).length === 0 ) this.hide();
            }.bind(this));
        }
    },

    /**
     * Показавает/скрывает тултип
     */
    toggle: function () {
        this[ this._target.is(':visible') ? 'hide' : 'show' ]();
    },

    /**
     * Показывает тултип
     */
    show: function () {
        if ( this.state == this._state.OPEN || this.state == this._state.OPENING || this.state == this._state.HIDING ) return false;

        this._target.appendTo('body');

        if ( this.options.ajax.url && ! this.loading ) this._targetContent.empty().addClass( ls.options.classes.states.loading );

        this.element.addClass( ls.options.classes.states.open );
        this.state = this._state.OPENING;

        this._reposition();
        this._show(this._target, this.options.show, function () {
            if ( this.state == this._state.OPENING ) this.state = this._state.OPEN;
        }.bind(this));

        // Ajax
        if ( this.options.ajax.url && ! this.loading ) {
            this._load();
        }

        this._trigger("show", null, this);
    },

    /**
     * Скрывает тултип
     */
    hide: function () {
        if ( this.state == this._state.HIDDEN || this.state == this._state.HIDING ) return false;

        if ( this.state == this._state.OPENING ) {
            this._target.stop();

            if ( this.options.show.delay ) {
                this._target.hide();
                this._onHide();

                return true;
            }
        }

        this.state = this._state.HIDING;

        this._hide(this._target, this.options.hide, function () {
            this._onHide();
        }.bind(this));
    },

    /**
     * Скрывает тултип
     */
    _onHide: function () {
        this._target.detach();
        this.state = this._state.HIDDEN;

        this._trigger("hide", null, this);
    },

    /**
     * Устанавливает заголовок
     */
    setTitle: function ( title ) {
        if ( ! title ) {
            this._targetTitle.hide();
            return false;
        }

        this._targetTitle.html( title );
    },

    /**
     * Устанавливает содержимое тултипа
     */
    setContent: function ( content ) {
        this._target.find( this.options.selectors.content ).html( content );
    },

    /**
     * Изменение положения тултипа
     *
     * @param {Boolean} hidden Изменять положение скрытых тултипов или нет
     */
    _reposition: function ( hidden ) {
        hidden = typeof hidden === 'undefined' ? true : hidden;

        if ( this.state == this._state.HIDDEN && ! hidden ) return;

        var isVisible = this._target.is(':visible');

        this._target
                .css( ! isVisible ? { 'display' : 'block', 'visibility' : 'hidden' } : {} )
                .position(this.options.position)
                .css( ! isVisible ? { 'display' : 'none', 'visibility' : 'visible' } : {} );

        this._trigger("reposition", null, this);
    },

    /**
     * Загрузка содержимого тултипа через аякс
     */
    _load: function () {
        var params = $.extend( {}, this.options.ajax.params, ls.utils.getDataOptions(this.element, 'param') ) || {};

        this._targetContent.empty().addClass( ls.options.classes.states.loading );

        ls.ajax.load(this.options.ajax.url, params, function (data) {
            this._targetContent
                .removeClass( ls.options.classes.states.loading )
                .html( data.bStateError ? 'Error' : data['sText'] );
            this._reposition();
            this.loading = false;
        }.bind(this), {
            error: function (data) {
                this._targetContent
                    .removeClass( ls.options.classes.states.loading )
                    .html( 'Error' );
                this._reposition();
                this.loading = false;
            }.bind(this)
        });
    },

    /**
     * Состояния тултипа
     */
    _state: {
        OPEN:    'OPEN',
        OPENING: 'OPENING',
        HIDDEN:  'HIDDEN',
        HIDING:  'HIDING'
    }
});