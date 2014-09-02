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
			// Основной блок загрузчика
			uploader: $(),

			// Ссылки
			urls: {
				// Удаление
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
				// Файл активен
				active: 'active',
				// Произошла ошибка при загрузке
				error: 'is-error',
				// Файл загружается
				uploading: 'is-uploading',
				// Файл выделен
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
			// Информация о файле
			this.info = this.getInfo();

			// Состояния файла
			this.states = {
				active: false,
				selected: false,
				uploading: false,
				error: false
			};

			this._on({ click: this.toggle.bind( this ) });
		},

		/**
		 * Изменение состояния файла активен/не активен
		 */
		toggle: function() {
			this[ this.getState( 'active' ) ? 'unselect' : 'activate' ]();
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
			this.element = null;

			if ( ! this._getComponent( 'list' ).lsUploaderFileList( 'getFiles' ).length ) {
				this.option( 'uploader' ).lsUploader( 'markAsEmpty' );
			}
		},

		/**
		 * Помечает файл как активный
		 */
		activate: function() {
			// Не активируем незагруженный файл
			if ( this.getState( 'error' ) || this.getState( 'uploading' ) ) return;

			if ( ! this._getComponent( 'list' ).lsUploaderFileList( 'option', 'multiselect' ) ) {
				this._getComponent( 'list' ).lsUploaderFileList( 'clearSelected' );
			}

			this.select();

			this._getComponent( 'list' ).lsUploaderFileList( 'getActiveFile' ).lsUploaderFile( 'deactivate' );

			this.setState( 'active', true );
			this.element.addClass( this.option( 'classes.active' ) );

			this.option( 'uploader' ).lsUploader( 'showBlocks' );
			this._getComponent( 'info' ).lsUploaderInfo( 'setFile', this.element );
		},

		/**
		 * Помечает файл как неактивный
		 */
		deactivate: function() {
			this.setState( 'active', false );
			this.element.removeClass( this.option( 'classes.active' ) );

			this.option( 'uploader' ).lsUploader( 'hideBlocks' );
			this._getComponent( 'info' ).lsUploaderInfo( 'empty' );
		},

		/**
		 * Выделяет файл
		 */
		select: function() {
			this.setState( 'selected', true );
			this.element.addClass( this.option( 'classes.selected' ) );
		},

		/**
		 * Убирает выделение с файла
		 */
		unselect: function() {
			this.setState( 'selected', false );
			this.element.removeClass( this.option( 'classes.selected' ) );

			if ( this.getState( 'active' ) ) {
				this.deactivate();
				this._getComponent( 'list' ).lsUploaderFileList( 'activateNextFile' );
			}
		},

		/**
		 * Помечает файл как незагруженный
		 */
		error: function() {
			this.setState( 'error', true );
			this.element.addClass( this.option( 'classes.error' ) );

			this.element.find( this.option( 'selectors.progress.value' ) ).height( 0 );
			this.element.find( this.option( 'selectors.progress.label' ) ).text( 'ERROR' );
		},

		/**
		 * Помечает файл как незагруженный
		 */
		uploading: function() {
			this.setState( 'uploading', true );
			this.element.addClass( this.option( 'classes.uploading' ) );
		},

		/**
		 * Помечает файл как загруженный
		 */
		uploaded: function() {
			this.setState( 'uploading', false );
			this.element.removeClass( this.option( 'classes.uploading' ) );
		},

		/**
		 * Устанавливает процент загрузки
		 */
		setProgress: function( percent ) {
			this.element.find( this.option( 'selectors.progress.value' ) ).height( percent + '%' );
			this.element.find( this.option( 'selectors.progress.label' ) ).text( percent + '%' );
		},

		/**
		 * Получает состяние
		 */
		getState: function( state ) {
			return this.states[ state ];
		},

		/**
		 * Устанавливает состяние
		 */
		setState: function( state, value ) {
			this.states[ state ] = value;
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