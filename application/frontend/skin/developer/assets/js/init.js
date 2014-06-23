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
	 * Иниц-ия модулей ядра
	 */
	ls.init({
		production: false
	});

	ls.dev.init();


	/**
	 * Modals
	 */
	$('.js-modal-default').modal();


	/**
	 * Datepicker
	 */
	$('.js-date-picker').datepicker();


	/**
	 * Dropdowns
	 */
	$('.js-dropdown-default').livequery(function () {
		$(this).dropdown();
	});


	/**
	 * Tabs
	 */
	$('[data-type=tab]').tab();

	/**
	 * Captcha
	 */
	$('[data-type=captcha]').captcha();


	/**
	 * Alerts
	 */
	$('.js-alert').alert();


	/**
	 * Tooltips
	 */
	$('.js-tooltip').tooltip();

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
	ls.activity.init();


	/**
	 * Userfeed
	 */
	ls.userfeed.init();


	/**
	 * Comments
	 */
	$('.js-comments-topic').lsComments({
		urls: {
			add:  aRouter['blog'] + 'ajaxaddcomment/',
			load: aRouter['blog'] + 'ajaxresponsecomment/'
		},
	});

	$('.js-comments-talk').lsComments({
		urls: {
			add:  aRouter['talk'] + 'ajaxaddcomment/',
			load: aRouter['talk'] + 'ajaxresponsecomment/'
		},
	});


	/**
	 * Toolbar
	 */
	$('.js-toolbar').toolbar({
		target: '.grid-role-wrapper',
		offsetX: 20
	});
	$('.js-toolbar-scrollup').lsToolbarScrollUp();
	$('.js-toolbar-comments').lsToolbarComments();
	$('.js-toolbar-topics').lsToolbarTopics();


	/**
	 * User
	 */
	ls.user.init();


	/**
	 * Talk
	 */
	ls.talk.init();


	/**
	 * Poll
	 */
	$('.js-poll').lsPoll();
	$('.js-poll-manage').lsPollManage();


	/**
	 * User Note
	 */
	$('.js-user-note').livequery(function () {
		$(this).usernote({
			urls: {
				save:   aRouter['profile'] + 'ajax-note-save/',
				remove: aRouter['profile'] + 'ajax-note-remove/'
			}
		});
	});


	/**
	 * Editor
	 */
	ls.editor.init('.js-editor');


	/**
	 * Blog
	 */
	ls.blog.init();


	/**
	 * Topic
	 */
	ls.topic.init();
	ls.content.init();


	/**
	 * Vote
	 */

	// Голосование за топик
	$('.js-vote-topic').vote({
		urls: {
			vote: aRouter['ajax'] + 'vote/topic/',
			info: aRouter['ajax'] + 'vote/get/info/topic'
		}
	});

	// Голосование за пользователя
	$('.js-vote-user').vote({
		urls: {
			vote: aRouter['ajax'] + 'vote/user/'
		}
	});

	// Голосование за блог
	$('.js-vote-blog').vote({
		urls: {
			vote: aRouter['ajax'] + 'vote/blog/'
		}
	});

	// Голосование за комментарий
	$('.js-vote-comment').livequery(function () {
		$(this).vote({
			urls: {
				vote: aRouter['ajax'] + 'vote/comment/'
			}
		});
	});


	/**
	 * Пагинация
	 */
	$('.js-pagination-topics').lsPagination({
		hash: {
			next: 'goTopic=first',
			prev: 'goTopic=last'
		}
	});


	/**
	 * Избранное
	 */

	// Топик
	$('.js-favourite-topic').lsFavouriteTopic();

	// Комментарий
	$('.js-favourite-comment').lsFavourite({
		urls: {
			toggle: aRouter['ajax'] + 'favourite/comment/'
		}
	});

	// Личное сообщение
	$('.js-favourite-talk').lsFavourite({
		urls: {
			toggle: aRouter['ajax'] + 'favourite/talk/'
		}
	});


	/**
	 * Теги
	 */
	ls.tags.init();


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


	/**
	 * Блоки добавления пользователей
	 */

	// Приглашение пользователей в блог
	$('.js-user-list-add-blog-invite').blog_invite_users();

	// Добавление участников личного сообщения
	$('.js-message-users').message_users();

	// Черный список
	$('.js-user-list-add-blacklist').user_list_add({
		urls: {
			add: aRouter['talk'] + 'ajaxaddtoblacklist/',
			remove: aRouter['talk'] + 'ajaxdeletefromblacklist/'
		}
	});

	// Добавление пользователей в свою активность
	$('.js-user-list-add-activity').user_list_add({
		urls: {
			add: aRouter['stream'] + 'ajaxadduser/',
			remove: aRouter['stream'] + 'ajaxremoveuser/'
		}
	});

	// Добавление пользователей в свою ленту
	$('.js-user-list-add-userfeed').user_list_add({
		urls: {
			add: aRouter['feed'] + 'ajaxadduser/',
			remove: aRouter['feed'] + 'unsubscribe/'
		}
	});

	/**
	 * Лайтбокс
	 */
	$('a.js-lbx').colorbox({ width:"100%", height:"100%" });


	/**
	 * Поиск
	 */
	ls.search.init();


	// Временный костыль для сабмита форм
	// TODO: Перенести в плагин button
	$(document).on('click', 'button[data-button-submit-form]', function () {
		$( $(this).data('button-submit-form') ).submit();
	});

	// Временный код экшнбара (кнопка выделения объектов)
	// TODO: Перенести в виджет
	$(document).on('click', 'li[data-select-item]', function (e) {
		var oElement       = $(this),
			sItemSelector  = $(this).data('select-item'),
			sItemFilter    = $(this).data('select-filter') || '*',
			aItems         = $( sItemSelector ),
			aItemsFiltered = aItems.filter( sItemFilter ),
			aCheckboxes    = aItems.find('input[type=checkbox]');

		aItems.removeClass('selected');
		aCheckboxes.prop('checked', false);
		aItemsFiltered.addClass('selected').find('input[type=checkbox]').prop('checked', true);
		e.preventDefault();
	});

	$(document).on('click', '.js-message-list-item input[type=checkbox]', function (e) {
		$(this).closest('.js-message-list-item').toggleClass('selected');
	});

	$(document).on('click', '.js-user-list-small-item input[type=checkbox]', function (e) {
		$(this).closest('.js-user-list-small-item').toggleClass('selected');
	});

	// Хук конца инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_end',[],window);
});