/**
 * Топик
 */

var ls = ls || {};

ls.topic = (function ($) {
	"use strict";

	/**
	 * Дефолтные опции
	 */
	var defaults = {
		// Роутеры
		oRouters: {
			preview: aRouter['ajax'] + 'preview/topic/',
		},

		// Селекторы
		sPreviewImageSelector:               '.js-topic-preview-image',
		sPreviewImageLoaderSelector:         '.js-topic-preview-loader',
		sPreviewTopicTextButtonSelector:     '.js-topic-preview-text-button',
		sPreviewTopicTextHideButtonSelector: '.js-topic-preview-text-hide-button',
	};

	/**
	 * Инициализация
	 *
	 * @param  {Object} options Опции
	 */
	this.init = function(options) {
		var self = this;

		this.options = $.extend({}, defaults, options);

		// Подгрузка избражений-превью
		$(this.options.sPreviewImageSelector).each(function () {
			$(this).imagesLoaded(function () {
				var $this = $(this),
					$preview = $this.closest(self.options.sPreviewImageLoaderSelector).removeClass('loading');
					
				$this.height() < $preview.height() && $this.css('top', ($preview.height() - $this.height()) / 2 );
			});
		});

		// Превью текста
		$(this.options.sPreviewTopicTextButtonSelector).on('click', function (e) {
			self.showPreviewText('form-topic-add', 'topic-text-preview');
		});

		// Закрытие превью
		$(document).on('click', this.options.sPreviewTopicTextHideButtonSelector, function (e) {
			self.hidePreviewText();
		});
	};

	/**
	 * Превью текста
	 *
	 * @param  {String} sFormId ID формы
	 * @param  {String} sPreviewId ID блока превью
	 */
	this.showPreviewText = function(sFormId, sPreviewId) {
		var oForm = $('#' + sFormId);
		var oPreview = $('#' + sPreviewId);

		ls.hook.marker('previewBefore');

		ls.ajaxSubmit(this.options.oRouters.preview, oForm, function(result) {
			if (result.bStateError) {
				ls.msg.error(null, result.sMsg);
			} else {
				oPreview.show().html(result.sText);

				ls.hook.run('ls_topic_preview_after', [oForm, oPreview, result]);
			}
		});
	};

	/**
	 * Закрытие превью
	 */
	this.hidePreviewText = function() {
		$('#topic-text-preview').hide();
	};

	return this;
}).call(ls.topic || {}, jQuery);