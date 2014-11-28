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

			this._on({ click: this.onClick.bind( this ) });
		},

		/**
		 * Коллбэк вызываемый при клике по файлу
		 */
		onClick: function( event ) {
			var multiselect      = this._getComponent( 'list' ).lsUploaderFileList( 'option', 'multiselect' ),
				multiselect_ctrl = this._getComponent( 'list' ).lsUploaderFileList( 'option', 'multiselect_ctrl' );

			this.toggleActive( ! multiselect || ( multiselect && multiselect_ctrl && ! ( event.ctrlKey || event.metaKey ) ) );
		},

		/**
		 * Изменение состояния файла активен/не активен
		 */
		toggleActive: function( clearSelected ) {
			this[ this.getState( 'active' ) ? 'unselect' : 'activate' ]( clearSelected );
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
		 * Устанавливает свойство файла
		 */
		setProperty: function( name, value ) {
			this.info[ name ] = value;
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
					this.removeDom();
				}
			}.bind( this ));
		},

		/**
		 * Удаляет файл
		 */
		removeDom: function() {
			this.element.fadeOut( 500, this.onRemoveDom.bind( this ) );
		},

		/**
		 * Коллбэк вызываемый после удаления
		 */
		onRemoveDom: function() {
			this.destroy();
			this.element.remove();
			this.element = null;

			this.option( 'uploader' ).lsUploader( 'checkEmpty' );
		},

		/**
		 * Помечает файл как активный
		 *
		 * @param {Boolean} clearSelected Убрать выделение со всех файлов
		 */
		activate: function( clearSelected ) {
			this._trigger( 'beforeactivate', null, this );

			// Не активируем незагруженный файл
			if ( this.getState( 'error' ) || this.getState( 'uploading' ) ) return;

			if ( clearSelected ) {
				this._getComponent( 'list' ).lsUploaderFileList( 'clearSelected' );
			}

			this.select();

			this._getComponent( 'list' ).lsUploaderFileList( 'getActiveFile' ).lsUploaderFile( 'deactivate' );

			this.setState( 'active', true );
			this.element.addClass( this.option( 'classes.active' ) );

			this.option( 'uploader' ).lsUploader( 'showBlocks' );
			this._getComponent( 'info' ).lsUploaderInfo( 'setFile', this.element );
			this._getComponent( 'list' ).lsUploaderFileList( 'resizeHeight' );

			this._trigger( 'afteractivate', null, this );
		},

		/**
		 * Помечает файл как неактивный
		 */
		deactivate: function() {
			this._trigger( 'beforeadectivate', null, this );

			this.setState( 'active', false );
			this.element.removeClass( this.option( 'classes.active' ) );

			this.option( 'uploader' ).lsUploader( 'hideBlocks' );
			this._getComponent( 'info' ).lsUploaderInfo( 'empty' );
			this._getComponent( 'list' ).lsUploaderFileList( 'resizeHeight' );

			this._trigger( 'afterdeactivate', null, this );
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
		 *
		 * @param {Number} percent Процент загрузки
		 */
		setProgress: function( percent ) {
			this.element.find( this.option( 'selectors.progress.value' ) ).height( percent + '%' );
			this.element.find( this.option( 'selectors.progress.label' ) ).text( percent + '%' );
		},

		/**
		 * Получает состяние
		 *
		 * @param {String} state Название состояния
		 */
		getState: function( state ) {
			return this.states[ state ];
		},

		/**
		 * Устанавливает состяние
		 *
		 * @param {String}  state Название состояния
		 * @param {Boolean} value Значение состояния
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