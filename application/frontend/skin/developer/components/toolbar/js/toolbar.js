/**
 * Toolbar
 *
 * @module ls/toolbar
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

$.widget( "livestreet.lsToolbar", {
    /**
     * Дефолтные опции
     */
    options: {
        // Селектор объекта относительно которого будет позиционироваться тулбар
        target: null,
        // Выравнивание по правой/левой стороне относителного целевого элемента
        // Возможные значения: 'left', 'right'
        align: 'right',
        // Смещение по оси X
        offsetX: 0,
        // Смещение по оси Y
        offsetY: 0,
        // Callback вызываемый при начале вычисления нового положения тулбара
        reposition: null
    },

    /**
     * Конструктор
     *
     * @constructor
     * @private
     */
    _create: function() {
        this.target = $(this.options.target);

        this.window._scrollable();
        this.window.on('ready resize scroll', this.reposition.bind(this));
    },

    /**
     * Вычисление нового положения тулбара
     */
    reposition: function () {
        this.targetPos = this.target.offset();

        this._trigger('reposition', null, this);

        this.element.css({
            'top': this.targetPos.top + this.options.offsetY,
            'left': (this.options.align == 'right' ? this.targetPos.left + this.target.outerWidth() + this.options.offsetX : this.targetPos.left - this.element.outerWidth() - this.options.offsetX) - this.document.scrollLeft()
        });
    }
});