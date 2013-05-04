/**
 * Блоки
 */

var ls = ls || {};

ls.blocks = (function ($) {
	this.init = function() {
		// Заменяет навигацию на выпадающий список если пунктов больше
		// определенного значения
		// TODO: Вынести в отдельный функционал
		var tabs = $('#js-stream-tabs'),
			dropdown = $('#js-stream-dropdown');

		if ($('#js-stream-tabs li').length >= 3) {
			tabs.hide();
			dropdown.show();
		}

		// Кнопка обновления блока
		$('#js-stream-update').on('click', function () {
			((tabs.is(':visible')) ? tabs : $('#js-dropdown-menu-stream')).find('li.active').tab('activate');
			$(this).addClass('active');
			setTimeout( function() { $(this).removeClass('active'); }.bind(this), 600 );
		});

		// Сохраняем высоту блока при переключении табов
		$('.js-block-nav ' + $.fn.tab.settings.tabSelector).tab('option', {
			onActivate: function () {
				this.$pane.css('height', this.$pane.height());
			},
			onActivated: function () {
				this.$pane.css('height', 'auto');
			}
		});
	};

	return this;
}).call(ls.blocks || {},jQuery);