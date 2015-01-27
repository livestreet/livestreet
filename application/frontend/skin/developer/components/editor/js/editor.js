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
		media: null,
		media_options: {},
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
		this.option( 'media', $( '#' + this.element.data('editor-media') || this.option( 'media' ) ) );

		// Иниц-ия компонента media (вставка медиа-файлов)
		this.options.media_options.editor = this.element;
		this.option( 'media' ).lsMedia( this.option( 'media_options' ) );

		// Иниц-ия редактора определенного типа
		this.element[ this.strategy ]( this.options );
	},

	/**
	 * Вставка текста
	 *
	 * @param {String} text Текст для вставки
	 */
	insert: function ( text ) {
		this.element[ this.strategy ]( 'insert', text );
	},

	/**
	 * 
	 */
	getText: function () {
		return this.element[ this.strategy ]( 'getText' );
	},

	/**
	 * 
	 */
	setText: function ( text ) {
		this.element[ this.strategy ]( 'setText', text );
	},

	/**
	 * 
	 */
	focus: function () {
		this.element[ this.strategy ]( 'focus' );
	}
});