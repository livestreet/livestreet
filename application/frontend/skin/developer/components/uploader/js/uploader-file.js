/**
 * Uploader File
 *
 * @module ls/uploader/file
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsUploaderFile", {
		/**
		 * Дефолтные опции
		 */
		options: {
			uploader: $(),

			// Ссылки
			urls: {
				remove: aRouter['ajax'] + 'media/remove-file/'
			},

			// Селекторы
			selectors: {
				progress: {
					value: '.js-uploader-file-progress-value',
					label: '.js-uploader-file-progress-label'
				}
			},

			// Классы
			classes : {
				active: 'active',
				selected: 'is-selected'
			}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			this.elements = {};

			// Информация о файле
			this.info = this.getInfo();
			// Файл активен
			this.active = false;
			// Файл выделен
			this.selected = false;

			this._on({ click: this.toggle.bind( this ) });
		},

		/**
		 * Method
		 */
		toggle: function() {
			this[ this.isActive() ? 'unselect' : 'activate' ]();
		},

		/**
		 * Получает информацию о файле
		 *
		 * TODO: Refactor
		 */
		getInfo: function() {
			var result = {};

			$.each( this.element[ 0 ].attributes, function( index, attr ) {
                if ( ~ attr.name.indexOf( 'data-media' ) ) {
                	result[ attr.name.slice( 11 ) ] = attr.value;
                }
            });

            return result;
		},

		/**
		 * Получает свойство файла
		 */
		getProperty: function( name ) {
			return this.info[ name ];
		},

		/**
		 * Удаляет файл
		 */
		remove: function() {
			this.unselect();

			ls.ajax.load( this.option( 'urls.remove' ), {
				id: this.info.id
			}, function( response ) {
				if ( response.bStateError ) {
					ls.msg.error( null, response.sMsg );
				} else {
					this.element.fadeOut( 500, this.onRemove.bind( this, response ) );
				}
			}.bind( this ));
		},

		/**
		 * Коллбэк вызываемый после удаление
		 */
		onRemove: function( response ) {
			this.destroy();
			this.element.remove();

			if ( ! this._getComponent( 'list' ).lsUploaderFileList( 'getFiles' ).length ) {
				this.option( 'uploader' ).lsUploader( 'markAsEmpty' );
			}
		},

		/**
		 * Помечает файл как активный
		 *
		 * TODO: Activate ERROR file
		 */
		activate: function() {
			if ( ! this._getComponent( 'list' ).lsUploaderFileList( 'option', 'multiselect' ) ) {
				this._getComponent( 'list' ).lsUploaderFileList( 'clearSelected' );
			}

			this.select();

			this._getComponent( 'list' ).lsUploaderFileList( 'getActiveFile' ).lsUploaderFile( 'deactivate' );

			this.active = true;
			this.element.addClass( this.option( 'classes.active' ) );

			this._getComponent( 'info' ).lsUploaderInfo( 'setFile', this.element );
		},

		/**
		 * Помечает файл как неактивный
		 */
		deactivate: function() {
			this.active = false;
			this.element.removeClass( this.option( 'classes.active' ) );

			this._getComponent( 'info' ).lsUploaderInfo( 'empty' );
		},

		/**
		 * Выделяет файл
		 */
		select: function() {
			this.selected = true;
			this.element.addClass( this.option( 'classes.selected' ) );
		},

		/**
		 * Убирает выделение с файла
		 */
		unselect: function() {
			this.selected = false;
			this.element.removeClass( this.option( 'classes.selected' ) );

			if ( this.isActive() ) {
				this.deactivate();
				this._getComponent( 'list' ).lsUploaderFileList( 'activateNextFile' );
			}
		},

		/**
		 * Проверяет активен файл или нет
		 */
		isActive: function() {
			return this.active;
		},

		/**
		 * Проверяет выделен файл или нет
		 */
		isSelected: function() {
			return this.selected;
		},

		/**
		 * Вспомогательный метод
		 *
		 * @private
		 */
		_getComponent: function( name ) {
			return this.option( 'uploader' ).lsUploader( 'getElement', name );
		},
	});
})(jQuery);