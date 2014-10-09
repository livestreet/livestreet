/**
 * Выпадающее меню
 *
 * @module dropdown
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

$.widget( "livestreet.lsDropdown", {
    /**
     * Дефолтные опции
     */
    options: {
        // Позиционирование
        // Для позиционирования используется модуль position библиотеки jQuery UI
        position: {
            my: "left top+5",
            at: "left bottom",
            collision: "flipfit flip"
        },
        // Анимация при показе
        show: {
            effect: 'slideDown',
            duration: 200
        },
        // Анимация при скрытии
        hide: {
            effect: 'slideUp',
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
        this.options = $.extend({}, this.options, ls.utils.getDataOptions(this.element, 'dropdown'));

        this._menu = $( '#' + this.element.data('dropdown-target') );

        // Вынос меню в тег body
        if ( this.options.body ) this._menu.appendTo('body');

        // Пункты меню
        var items = this._menu.find('li:not(.dropdown-separator)');

        // Присваиваем текст активного пункта меню переключателю
        if ( this.options.selectable ) {
            var text = items.filter('.active').eq(0).find('a').text();
            if ( text ) this.element.text( text );
        }

        // Объект относительно которого позиционируется меню
        this.options.position.of = this.options.position.of || this.element;

        this.options.position.using = this.options.position.using || function ( position, feedback ) {
            ls.utils.removeClassByPrefix( this._menu, 'position-' );

            this._menu
                    .addClass( 'position-y-' + feedback.vertical + ' ' +  'position-x-' + feedback.horizontal )
                    .css( position );
        }.bind(this);


        // События
        // ----------

        // Клик по переключателю
        this._on({
            click : function (event) {
                this.toggle();
                event.preventDefault();
            }
        });

        // Обработка кликов по пунктам меню
        this._on( items.find('a'), {
            click: function (event) {
                if ( this.options.selectable ) {
                    var itemLink = $(event.currentTarget);

                    items.removeClass('active');
                    itemLink.closest('li').addClass('active');
                    this.element.text( itemLink.text() );
                }

                this.hide();
            }
        });

        // Reposition menu on window scroll or resize
        this.window.on('resize'  + this.eventNamespace + 'scroll' + this.eventNamespace, this._reposition.bind(this));

        // Hide when click anywhere but menu or toggle
        this.document.on('click' + this.eventNamespace, function (event) {
            if ( ! this._menu.is(event.target) && this._menu.has(event.target).length === 0 && ! this.element.is(event.target) && this.element.has(event.target).length === 0 ) this.hide();
        }.bind(this));
    },

    /**
     * Показавает/скрывает меню
     */
    toggle: function () {
        if ( this._menu.is(':visible') ) {
            this.hide();
        } else {
            this.show();
        }
    },

    /**
     * Показывает меню
     */
    show: function () {
        this._trigger("beforeshow", null, this);

        this._show(this._menu, this.options.show, function () {
            this._trigger("aftershow", null, this);
        }.bind(this));

        this._reposition();
        this.element.addClass('open');
    },

    /**
     * Скрывает меню
     */
    hide: function () {
        if ( ! this._menu.is(':visible') || this.element.data('dropdown-state-hide') === true ) return false;

        this._trigger("beforehide", null, this);

        this.element.data('dropdown-state-hide', true);

        this._hide(this._menu, this.options.hide, function () {
            this.element.removeClass('open').removeData('dropdown-state-hide');
            this._trigger("afterhide", null, this);
        }.bind(this));
    },

    /**
     *
     */
    getMenu: function () {
        return this._menu;
    },

    /**
     * Изменение положения меню
     */
    _reposition: function () {
        if ( ! this._menu.is(':visible') ) return false;

        this._menu.position(this.options.position);
        this._trigger("reposition", null, this);
    }
});