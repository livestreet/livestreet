/**
 * Highlighter
 *
 * @module ls/highlighter
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

$.widget( "livestreet.lsHighlighter", {
    /**
     * Дефолтные опции
     */
    options: {
        language: null
    },

    /**
     * Конструктор
     *
     * @constructor
     * @private
     */
    _create: function() {
        hljs.highlightBlock( this.element[0] );
    }
});