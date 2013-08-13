/**
 * Вспомгательные функции для текстового редактора
 */

var ls = ls || {};

ls.editor = (function($) {
	"use strict";

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		// Селекторы
		selectors: {
			insertImageButton:  '.js-insert-image-button',
			uploadImageButton:  '.js-upload-image-button',
			previewImageLoader: '.js-topic-preview-loader'
		}
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var self = this;

		this.options = $.extend({}, defaults, options);

		// Вставка ссылки
		$(this.options.selectors.insertImageButton).on('click', function (e) {
			var sUrl   = $('#img_url').val(),
				sAlign = $('#form-image-url-align').val(),
				sTitle = $('#form-image-url-title').val();

			self.insertImageUrlToEditor(sUrl, sAlign, sTitle);
		});

		// Вставка ссылки
		$(this.options.selectors.uploadImageButton).on('click', function (e) {
			var sFormId   = $(this).data('form-id');

			self.ajaxUploadImg(sFormId);
		});

		// Справка по разметке редактора
		$('.js-editor-help').each(function () {
			var oEditorHelp = $(this),
				oTargetForm = $('#' + oEditorHelp.data('form-id'));

			oEditorHelp.find('.js-tags-help-link').on('click', function (e) {
				if ($(this).data('insert')) {
					var sTag = $(this).data('insert');
				} else {
					var sTag = $(this).text();
				}

				$.markItUp({
					target: oTargetForm, 
					replaceWith: sTag
				});

				e.preventDefault();
			});
		});
	};

	/**
	 * Вставка ссылки загруженного изображения в редактор
	 * 
	 * @param  {String} sUrl   Ссылка
	 * @param  {String} sAlign Выравнивание
	 * @param  {String} sTitle Описание
	 */
	this.insertImageUrlToEditor = function(sUrl, sAlign, sTitle) {
		sAlign = sAlign == 'center' ? 'class="image-center"' : 'align="' + sAlign + '"';

		$.markItUp({
			replaceWith: '<img src="' + sUrl + '" title="' + sTitle + '" ' + sAlign + ' />'
		});

		this.hideUploadImageModal();
	};

	/**
	 * Загрузка изображения
	 *
	 * @param  {String} sFormId ID формы
	 */
	this.ajaxUploadImg = function(sFormId) {
		var self = this;

		ls.hook.marker('ajaxUploadImgBefore');

		ls.ajaxSubmit('upload/image/', sFormId, function (data){
			if (data.bStateError) {
				ls.msg.error(data.sMsgTitle,data.sMsg);
			} else {
				$.markItUp({
					replaceWith: data.sText
				});

				self.hideUploadImageModal();

				ls.hook.marker('ajaxUploadImgAfter');
			}
		});
	};

	/**
	 * Закрытие окна загрузки изображения
	 */
	this.hideUploadImageModal = function() {
		var oModal = $('#modal-image-upload');

		oModal.find('input[type="text"], input[type="file"]').val('');
		oModal.modal('hide');
	};

	return this;
}).call(ls.editor || {},jQuery);