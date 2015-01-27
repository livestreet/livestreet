/**
 * Выпадающее меню
 *
 * @module ls/dropdown
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

$.widget( "livestreet.lsDropdown", $.livestreet.lsComponent, {
    /**
     * Дефолтные опции
     */
    options: {
        classes: {
            'open': 'active'
        },
        selectors: {
            toggle: '.js-dropdown-toggle',
            menu: '.js-dropdown-menu',
        },
        // Позиционирование
        // Для позиционирования используется модуль position библиотеки jQuery UI
        position: {
            my: "left top+4",
            at: "left bottom",
            collision: "flipfit flip"
        },
        // Анимация при показе
        show: {
            effect: 'show',
            duration: 200
        },
        // Анимация при скрытии
        hide: {
            effect: 'hide',
            duration: 200
        },
        // Поведение как у select'а
        selectable: false,
        // Выносить меню в тег body или нет
        body: false,

        // Коллбэки
        reposition: null,
        aftershow: null,
        afterhide: null,
        beforeshow: null,
        beforehide: null
    },

    /**
     * Конструктор
     *
     * @constructor
     * @private
     */
    _create: function() {
        var _this = this;

        this._super();

        // Вынос меню в тег body
        if ( this.options.body ) this.elements.menu.appendTo('body');

        // Пункты меню
        this._menuItems = this.elements.menu.find('li:not(.dropdown-separator)');
        this._menuLinks = this._menuItems.find('a');
        this._menuFocusedItem = null;

        // Присваиваем текст активного пункта меню переключателю
        if ( this.options.selectable ) {
            var text = this.getActiveItemText();
            if ( text ) this.setText( text );
        }

        // Объект относительно которого позиционируется меню
        this.options.position.of = this.options.position.of || this.element;

        this.options.position.using = this.options.position.using || function ( position, feedback ) {
            ls.utils.removeClassByPrefix( this.elements.menu, 'position-' );

            this.elements.menu
                .addClass( 'position-y-' + feedback.vertical + ' ' +  'position-x-' + feedback.horizontal )
                .css( position );
        }.bind( this );

        //
        // События
        //

        // Клик по переключателю
        this._on( this.elements.toggle, { click : 'toggle' });

        // Клавиатурная навигация
        this.elements.menu.bind( 'keydown' + this.eventNamespace, 'down', this.focusNextLink.bind( this ) );
        this.elements.menu.bind( 'keydown' + this.eventNamespace, 'up', this.focusPrevLink.bind( this ) );
        $().add( this.elements.menu ).add( this.element ).bind( 'keydown' + this.eventNamespace, 'esc', this.hide.bind( this, true ) );

        this._menuLinks.on( 'focus' + this.eventNamespace, function () {
            _this._menuFocusedItem = $( this ).closest( 'li' );
        })

        // Обработка кликов по пунктам меню
        this._on( this._menuLinks, { click: 'onItemClick' } );

        // Reposition menu on window scroll or resize
        this.window.on('resize'  + this.eventNamespace + 'scroll' + this.eventNamespace, this._reposition.bind(this));

        // Hide when click anywhere but menu or toggle
        this.document.on('click' + this.eventNamespace, function (event) {
            if ( ! this.elements.menu.is(event.target) && this.elements.menu.has(event.target).length === 0 && ! this.element.is(event.target) && this.element.has(event.target).length === 0 ) this.hide();
        }.bind(this));
    },

    /**
     * 
     */
    setText: function ( text ) {
        this.elements.toggle.text( text );
    },

    /**
     * Показавает/скрывает меню
     */
    toggle: function () {
        this[ this.elements.menu.is( ':visible' ) ? 'hide' : 'show' ]();
    },

    /**
     * 
     */
    getMenu: function () {
        return this.elements.menu;
    },

    /**
     * Показывает меню
     */
    show: function () {
        this._trigger( 'beforeshow', null, this );

        this.elements.toggle.attr( 'aria-expanded', true );

        this._show(this.elements.menu, this.options.show, function () {
            this.elements.menu.attr( 'aria-hidden', false );
            this._trigger('aftershow', null, this);
        }.bind(this));

        this._reposition();
        this._addClass( this.elements.toggle, 'open' );
    },

    /**
     * Скрывает меню
     */
    hide: function ( focus ) {
        if ( ! this.elements.menu.is(':visible') || this.elements.toggle.data('dropdown-state-hide') === true ) return false;

        this._trigger( 'beforehide', null, this );

        this.elements.toggle.attr('aria-expanded', false);
        this.elements.toggle.data('dropdown-state-hide', true);

        this._hide(this.elements.menu, this.options.hide, function () {
            // if ( focus ) this.elements.toggle.focus();
            this._menuFocusedItem = null;
            this.elements.menu.attr( 'aria-hidden', true );
            this._removeClass( this.elements.toggle, 'open' ).removeData('dropdown-state-hide');
            this._trigger('afterhide', null, this);
        }.bind(this));
    },

    /**
     * 
     */
    onItemClick: function ( event ) {
        if ( this.options.selectable ) {
            var itemLink = $(event.currentTarget);

            this._menuItems.removeClass('active');
            itemLink.closest('li').addClass('active');
            this.setText( itemLink.text() );
        }

        this.hide( true );
    },

    /**
     *
     */
    getItems: function () {
        return this._menuItems || ( this._menuItems = this.elements.menu.find('li:not(.dropdown-separator)') );
    },

    /**
     *
     */
    focusNextLink: function () {
        this._menuFocusedItem.next().find('a').focus();
    },

    /**
     *
     */
    focusPrevLink: function () {
        this._menuFocusedItem.prev().find('a').focus();
    },

    /**
     *
     */
    getActiveItem: function () {
        return this.getItems().filter('.active').eq(0);
    },

    /**
     *
     */
    getActiveItemText: function () {
        return this.getActiveItem().find('a').text();
    },

    /**
     * Изменение положения меню
     */
    _reposition: function () {
        if ( ! this.elements.menu.is(':visible') ) return false;

        this.elements.menu.position( this.options.position );
        this._trigger( 'reposition', null, this );
    }
});