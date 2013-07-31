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
			preview: aRouter['ajax'] + 'preview/topic/'
		},

		// Селекторы
		selectors: {
			previewImage:               '.js-topic-preview-image',
			previewImageLoader:         '.js-topic-preview-loader',
			previewTopicTextButton:     '.js-topic-preview-text-button',
			previewTopicTextHideButton: '.js-topic-preview-text-hide-button',
			addTopicTitle:              '.js-topic-add-title'
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

		// Подгрузка избражений-превью
		$(this.options.selectors.previewImage).each(function () {
			$(this).imagesLoaded(function () {
				var $this = $(this),
					$preview = $this.closest(self.options.selectors.previewImageLoader).removeClass('loading');

				$this.height() < $preview.height() && $this.css('top', ($preview.height() - $this.height()) / 2 );
			});
		});

		// Превью текста
		$(this.options.selectors.previewTopicTextButton).on('click', function (e) {
			self.showPreviewText('form-topic-add', 'topic-text-preview');
		});

		// Закрытие превью текста
		$(document).on('click', this.options.selectors.previewTopicTextHideButton, function (e) {
			self.hidePreviewText();
		});

		// Подгрузка информации о выбранном блоге при создании топика
		$(this.options.selectors.addTopicTitle).on('change', function (e) {
			ls.blog.loadInfo($(this).val());
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