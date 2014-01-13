/**
 * Инициализации модулей
 * 
 * @license   GNU General Public License, version 2
 * @copyright 2013 OOO "ЛС-СОФТ" {@link http://livestreetcms.com}
 * @author    Denis Shakhov <denis.shakhov@gmail.com>
 */

jQuery(document).ready(function($){
	// Хук начала инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_start',[],window);

	/**
	 * Main init
	 */
	ls.setOption('debug', true);


	/**
	 * Modals
	 */
	$('.js-modal-default').modal();


	/**
	 * Datepicker
	 */
	$('.date-picker').datepicker();


	/**
	 * Dropdowns
	 */
	$('.js-dropdown-default').dropdown();


	/**
	 * Tabs
	 */
	$('[data-type=tab]').tab();


	/**
	 * Alerts
	 */
	$('.js-alert').alert();


	/**
	 * Tooltips
	 */
	$('.js-tooltip').tooltip();

	$('.js-tooltip-vote-topic').livequery(function () {
		$(this).tooltip();
	});

	$('.js-popover-default').tooltip({
		useAttrTitle: false,
		trigger: 'click',
		classes: 'tooltip-light'
	});

	if (ls.registry.get('block_stream_show_tip')) {
		$('.js-title-comment, .js-title-topic').livequery(function () {
			$(this).tooltip({
				position: {
					my: "right center",
					at: "left left"
				},
				show: {
					delay: 1500
				}
			});
		});
	}


	/**
	 * Autocomplete
	 */
	ls.autocomplete.add($(".autocomplete-tags-sep"), aRouter['ajax']+'autocompleter/tag/', true);
	ls.autocomplete.add($(".autocomplete-tags"), aRouter['ajax']+'autocompleter/tag/', false);
	ls.autocomplete.add($(".autocomplete-users-sep"), aRouter['ajax']+'autocompleter/user/', true);
	ls.autocomplete.add($(".autocomplete-users"), aRouter['ajax']+'autocompleter/user/', false);


	/**
	 * Scroll
	 */
	$(window)._scrollable();


	/**
	 * Toolbar
	 */
	$('.js-toolbar').toolbar({
		target: '.grid-role-wrapper',
		offsetX: 20
	});

	ls.toolbar.topic.init(); // Тул-бар топиков
	ls.toolbar.up.init();    // Кнопка "UP"


	/**
	 * Code highlight
	 */
	prettyPrint();


	/**
	 * Blocks
	 */
	ls.blocks.init();


	/**
	 * Activity
	 */
	ls.stream.init();


	/**
	 * Userfeed
	 */
	ls.userfeed.init();


	/**
	 * Comments
	 */
	ls.comments.init({
		folding: false
	});


	/**
	 * User
	 */
	ls.user.init();


	/**
	 * Captcha
	 */
	ls.captcha.init();


	/**
	 * Talk
	 */
	ls.talk.init();


	/**
	 * Poll
	 */
	ls.poll.init();


	/**
	 * User Note
	 */
	ls.usernote.init();


	/**
	 * Editor
	 */
	ls.editor.init();


	/**
	 * Topic
	 */
	ls.topic.init();
	ls.content.init();


	/**
	 * Vote
	 */
	ls.vote.init();


	/**
	 * Pagination
	 */
	ls.pagination.init();


	/**
	 * Blog
	 */
	ls.blog.init();


	/**
	 * Избраноое
	 */
	ls.favourite.init();


	/**
	 * Теги
	 */
	ls.tags.init();


	/**
	 * Photoset
	 */
	$('.js-photoset-type-default-image').prettyPhoto({
		social_tools: '',
		show_title:   false,
		slideshow:    false,
		deeplinking:  false
	});


	/**
	 * Form validate
	 */
	$('.js-form-validate').parsley({
		validators: {
			rangetags: function (val, arrayRange) {
				var tag_count = val.replace(/ /g, "").match(/[^\s,]+(,|)/gi);
				return tag_count && tag_count.length >= arrayRange[0] && tag_count.length <= arrayRange[1];
			}
		},
		// TODO: Вынести в лок-ию
		messages: {
			rangetags: "Кол-во тегов должно быть от %s до %s"
		}
	});


	/**
	 * Медиа файлы
	 */
	//ls.media.init();


	/**
	 * Стена
	 */
	ls.wall.init();
	

	// Временный костыль для сабмита форм
	// TODO: Перенести в плагин button
	$('button[data-button-submit-form]').on('click', function () {
		$( '#' + $(this).data('button-submit-form') ).submit();
	});


	// Хук конца инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_end',[],window);
});