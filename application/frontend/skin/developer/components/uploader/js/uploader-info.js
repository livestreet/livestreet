/**
 * Отображает информацию о выбранном файле
 *
 * @module ls/uploader/info
 *
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

(function($) {
	"use strict";

	$.widget( "livestreet.lsUploaderInfo", {
		/**
		 * Дефолтные опции
		 */
		options: {
			// Основной блок загрузчика
			uploader: $(),

			// Ссылки
			urls: {
				// Обновление св-ва
				update_property: aRouter['ajax'] + 'media/save-data-file/'
			},

			// Селекторы
			selectors: {
				// Группа с информацией уникальной для каждого типа
				group: '.js-uploader-info-group',
				// Свойство
				property: '.js-uploader-info-property',
				// Кнопка удаления
				remove: '.js-uploader-info-remove',
			},
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
			var _this = this;

			this.elements = {
				groups: this.element.find( this.option( 'selectors.group' ) ),
				info:   this.element.find( this.option( 'selectors.info' ) ),
				properties: {
					image:    this.element.find( this.option( 'selectors.property' ) + '[data-name=image]' ),
					name:     this.element.find( this.option( 'selectors.property' ) + '[data-name=name]' ),
					filesize: this.element.find( this.option( 'selectors.property' ) + '[data-name=size]' ),
					date:     this.element.find( this.option( 'selectors.property' ) + '[data-name=date]' ),
				}
			};

			// Текущий файл
			this.file = $();

			// Удаление файла
			this.element.on( 'click' + this.eventNamespace, this.option( 'selectors.remove' ), function () {
				this.file.lsUploaderFile( 'remove' );
			}.bind( this ));

			// Удаление файла
			this.element.on( 'blur' + this.eventNamespace, '.js-uploader-info-property[type=text]', function () {
				var input = $( this );

				_this._updateProperty( input.attr( 'name' ), input.val() );
			});
		},

		/**
		 * Устанавливает файл
		 */
		setFile: function( file ) {
			this.file = file;

			var group = this._getGroupByType( this.file.lsUploaderFile( 'getProperty', 'type' ) );

			// Устанавливаем общие для всех типов свойства
			this._setProperty( this.elements.properties.image,    this.file.lsUploaderFile( 'getProperty', 'preview' ) );
			this._setProperty( this.elements.properties.name,     this.file.lsUploaderFile( 'getProperty', 'file-name' ) );
			this._setProperty( this.elements.properties.filesize, Math.floor( this.file.lsUploaderFile( 'getProperty', 'file-size' ) / 1024 ) + ' KB' );
			this._setProperty( this.elements.properties.date,     this.file.lsUploaderFile( 'getProperty', 'date-add' ) );

			// Активируем группу свойств данного типа
			this._activateGroup( group );

			// Устанавливаем уникальные свойства для каждого типа файла
			this._getPropertiesByGroup( group ).each( function ( index, property ) {
				var property = $( property );

				this._setProperty( property, this.getFile().lsUploaderFile( 'getProperty', property.data( 'name' ) ) );
			}.bind( this ));
		},

		/**
		 * Получает текущий файл
		 */
		getFile: function() {
			return this.file;
		},

		/**
		 * Помечает блок как пустой
		 */
		empty: function() {
			this.file = $();
		},

		/**
		 * Обновляет информацию о файле
		 */
		update: function() {
			this.setFile( this.file );
		},

		/**
		 * Устанавливает значение св-ва
		 *
		 * @param {jQuery} element Св-во
		 * @param {String} value   Значение
		 *
		 * @private
		 */
		_setProperty: function( element, value ) {
			switch ( element.prop('tagName').toLowerCase() ) {
				case 'img':
					element.attr( 'src', value );
					break;
				case 'input':
				case 'textarea':
					element.val( value );
					break;
				default:
					element.text( value );
			}
		},

		/**
		 * Обновляет текстовое св-во
		 *
		 * @param {String} name  Название св-ва
		 * @param {String} value Значение св-ва
		 *
		 * @private
		 */
		_updateProperty: function( name, value ) {
			// Кэшируем файл, т.к. он может измениться к концу ajax запроса
			var file = this.getFile();

			ls.ajax.load( this.option( 'urls.update_property' ), {
				name:  name,
				value: value,
				id:    file.lsUploaderFile( 'getProperty', 'id' )
			}, function( response ) {
				if ( response.bStateError ) {
					ls.msg.error( response.sMsgTitle, response.sMsg );
				} else {
					file.lsUploaderFile( 'setProperty', name, value );
				}
			}.bind( this ));
		},

		/**
		 * @private
		 */
		_activateGroup: function( group ) {
			this.elements.groups.hide();
			group.show();
		},

		/**
		 * @private
		 */
		_getGroupByType: function( type ) {
			return this.elements.groups.filter( '[data-type=' + type + ']' );
		},

		/**
		 * @private
		 */
		_getPropertiesByGroup: function( group ) {
			return group.find( this.option( 'selectors.property' ) );
		},
	});
})(jQuery);