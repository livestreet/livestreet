/**
 * Markup editor
 *
 * @module ls/editor/markup
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

$.widget( "livestreet.lsEditorMarkup", {
	/**
	 * Дефолтные опции
	 */
	options: {
		media: null,
		sets: {
			default: {
				onShiftEnter: { keepDefault:false, replaceWith: '<br />\n' },
				onCtrlEnter: { keepDefault:false, openWith: '\n<p>', closeWith: '</p>' },
				onTab: { keepDefault:false, replaceWith: '    ' },
				markupSet:  [
					{ name: 'H4', className: 'editor-h4', openWith: '<h4>', closeWith: '</h4>' },
					{ name: 'H5', className: 'editor-h5', openWith: '<h5>', closeWith: '</h5>' },
					{ name: 'H6', className: 'editor-h6', openWith: '<h6>', closeWith: '</h6>' },
					{ separator: '---------------' },
					{ name: ls.lang.get('panel_b'), className: 'editor-bold', key: 'B', openWith: '(!(<strong>|!|<b>)!)', closeWith: '(!(</strong>|!|</b>)!)' },
					{ name: ls.lang.get('panel_i'), className: 'editor-italic', key: 'I', openWith: '(!(<em>|!|<i>)!)', closeWith: '(!(</em>|!|</i>)!)'  },
					{ name: ls.lang.get('panel_s'), className: 'editor-stroke', key: 'S', openWith: '<s>', closeWith: '</s>' },
					{ name: ls.lang.get('panel_u'), className: 'editor-underline', key: 'U', openWith: '<u>', closeWith: '</u>' },
					{ name: ls.lang.get('panel_quote'), className: 'editor-quote', key: 'Q', replaceWith: function( m ) { if ( m.selectionOuter ) return '<blockquote>' + m.selectionOuter + '</blockquote>'; else if (m.selection) return '<blockquote>' + m.selection + '</blockquote>'; else return '<blockquote></blockquote>' }},
					{ name: ls.lang.get('panel_code'), className: 'editor-code', openWith: '<(!(code|!|codeline)!)>', closeWith: '</(!(code|!|codeline)!)>' },
					{ separator: '---------------' },
					{ name: ls.lang.get('panel_list'), className: 'editor-ul', openWith: '    <li>', closeWith: '</li>', multiline: true, openBlockWith: '<ul>\n', closeBlockWith: '\n</ul>' },
					{ name: ls.lang.get('panel_list'), className: 'editor-ol', openWith: '    <li>', closeWith: '</li>', multiline: true, openBlockWith: '<ol>\n', closeBlockWith: '\n</ol>' },
					{ name: ls.lang.get('panel_list_li'), className: 'editor-li', openWith: '<li>', closeWith: '</li>' },
					{ separator: '---------------' },
					{ name: ls.lang.get('panel_image'), className: 'editor-picture', key: 'P', beforeInsert: function ( markitup ) { $( markitup.textarea ).lsEditorMarkup( 'showMedia' ); } },
					{ name: ls.lang.get('panel_video'), className: 'editor-video', replaceWith: '<video>[![' + ls.lang.get('panel_video_promt') + ':!:http://]!]</video>' },
					{ name: ls.lang.get('panel_url'), className: 'editor-link', key: 'L', openWith: '<a href="[![' + ls.lang.get('panel_url_promt') + ':!:http://]!]"(!( title="[![Title]!]")!)>', closeWith: '</a>', placeHolder: 'Your text to link...' },
					{ name: ls.lang.get('panel_user'), className: 'editor-user', replaceWith: '<ls user="[![' + ls.lang.get('panel_user_promt') + ']!]" />' },
					{ separator: '---------------' },
					{ name: ls.lang.get('panel_clear_tags'), className: 'editor-clean', replaceWith: function( markitup ) { return markitup.selection.replace(/<(.*?)>/g, ""); }},
					{ name: ls.lang.get('panel_cut'), className: 'editor-cut', replaceWith: function( markitup ) { if ( markitup.selection ) return '<cut name="' + markitup.selection + '">'; else return '<cut>' }}
				]
			},
			light: {
				onShiftEnter: { keepDefault: false, replaceWith: '<br />\n' },
				onTab: { keepDefault: false, replaceWith: '    ' },
				markupSet:  [
					{ name: ls.lang.get('panel_b'), className: 'editor-bold', key: 'B', openWith: '(!(<strong>|!|<b>)!)', closeWith: '(!(</strong>|!|</b>)!)' },
					{ name: ls.lang.get('panel_i'), className: 'editor-italic', key: 'I', openWith: '(!(<em>|!|<i>)!)', closeWith: '(!(</em>|!|</i>)!)'  },
					{ name: ls.lang.get('panel_s'), className: 'editor-stroke', key: 'S', openWith: '<s>', closeWith: '</s>' },
					{ name: ls.lang.get('panel_u'), className: 'editor-underline', key: 'U', openWith: '<u>', closeWith: '</u>' },
					{ separator: '---------------' },
					{ name: ls.lang.get('panel_quote'), className: 'editor-quote', key: 'Q', replaceWith: function(m) { if (m.selectionOuter) return '<blockquote>' + m.selectionOuter + '</blockquote>'; else if (m.selection) return '<blockquote>' + m.selection + '</blockquote>'; else return '<blockquote></blockquote>' } },
					{ name: ls.lang.get('panel_code'), className: 'editor-code', openWith: '<(!(code|!|codeline)!)>', closeWith: '</(!(code|!|codeline)!)>' },
					{ name: ls.lang.get('panel_image'), className: 'editor-picture', key: 'P', beforeInsert: function ( markitup ) { $( markitup.textarea ).lsEditorMarkup( 'showMedia' ); } },
					{ name: ls.lang.get('panel_url'), className: 'editor-link', key: 'L', openWith: '<a href="[![' + ls.lang.get('panel_url_promt') + ':!:http://]!]"(!( title="[![Title]!]")!)>', closeWith: '</a>', placeHolder: 'Your text to link...' },
					{ name: ls.lang.get('panel_user'), className: 'editor-user', replaceWith: '<ls user="[![' + ls.lang.get('panel_user_promt') + ']!]" />' },
					{ separator: '---------------' },
					{ name: ls.lang.get('panel_clear_tags'), className: 'editor-clean', replaceWith: function( markitup ) { return markitup.selection.replace(/<(.*?)>/g, "") } }
				]
			}
		}
	},

	/**
	 * Конструктор
	 *
	 * @constructor
	 * @private
	 */
	_create: function () {
		var _this = this;

		this.element.markItUp( this.option( 'sets.' + this.option( 'set' ) ) );

		// Помощь
		var help = $( '.js-editor-help[data-form-id=' + this.element.attr( 'id' ) + ']' ),
			toggle = help.find( '.js-editor-help-toggle' ),
			content = help.find( '.js-editor-help-body' );

		toggle.on( 'click' + this.eventNamespace, function ( event ) {
			content.toggle();
			event.preventDefault();
		});

		$( '.js-tags-help-link' ).click(function() {
			var tag = $( this );

			_this.insert( tag.data( 'insert' ) || tag.text() );

			return false;
		});
	},

	/**
	 * Вставка текста
	 *
	 * @param {String} text Текст для вставки
	 */
	insert: function ( text ) {
		$.markItUp({ target: this.element, replaceWith: text });
	},

	/**
	 * 
	 */
	getText: function () {
		return this.element.val();
	},

	/**
	 * 
	 */
	setText: function ( text ) {
		return this.element.val( text );
	},

	/**
	 * 
	 */
	focus: function () {
		this.element.focus();
	},

	/**
	 * 
	 */
	showMedia: function ( text ) {
		this.option( 'media' ).lsMedia( 'show' );
	}
});