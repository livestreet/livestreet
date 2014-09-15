/**
 * Editor
 *
 * @module editor
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

$.widget( "livestreet.lsEditor", {
	/**
	 * Дефолтные опции
	 */
	options: {
		type: null,
		set: 'default'
	},

	/**
	 * Конструктор
	 *
	 * @constructor
	 * @private
	 */
	_create: function() {
		this.strategy = this.element.data( 'editor-type' ) == 'visual' ? 'lsEditorVisual' : 'lsEditorMarkup';
		this.option( 'set', this.element.data('editor-set') || this.option( 'set' ) );

		this.element[ this.strategy ]( this.options );
	},

	/**
	 * Вставка текста
	 *
	 * @param {String} text Текст для вставки
	 */
	insert: function ( text ) {
		this.element[ this.strategy ]( 'insert', text );
	}
});