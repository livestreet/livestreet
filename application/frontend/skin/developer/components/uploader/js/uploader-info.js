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
			},

			// Селекторы
			selectors: {
				// Группа с информацией уникальной для каждого типа
				group: '.js-uploader-info-group',
				// Свойство
				property: '.js-uploader-info-property',
				// Кнопка удаления
				remove: '.js-uploader-info-remove',
			}
		},

		/**
		 * Конструктор
		 *
		 * @constructor
		 * @private
		 */
		_create: function () {
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
			this.element.on( 'click', this.option( 'selectors.remove' ), function () {
				this.file.lsUploaderFile( 'remove' );
			}.bind( this ));
		},

		/**
		 * Устанвливает файл
		 */
		setFile: function( file ) {
			var data = file.lsUploaderFile( 'getInfo' ),
				group = this._getGroupByType( data.type );

			this.file = file;

			// Устанавливаем общие для всех типов свойства
			this._setProperty( this.elements.properties.image,    data['preview'] );
			this._setProperty( this.elements.properties.name,     data['file-name'] );
			this._setProperty( this.elements.properties.filesize, data['file-size'] );
			this._setProperty( this.elements.properties.date,     data['date-add'] );

			// Активируем группу свойств данного типа
			this._activateGroup( group );

			// Устанавливаем уникальные свойства для каждого типа файла
			this._getPropertiesByGroup( group ).each( function ( index, property ) {
				var property = $( property );

				this._setProperty( property, data[ property.data( 'name' ) ] );
			}.bind( this ));
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
		 * @private
		 */
		_activateGroup: function( group ) {
			this.elements.groups.hide();
			group.show();
		},

		/**
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