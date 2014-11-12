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

	$('html').removeClass('no-js');

	/**
	 * Иниц-ия модулей ядра
	 */
	ls.init({
		production: false
	});

	ls.dev.init();


	/**
	 * Actionbar
	 */
	$('.js-user-list-modal-actionbar').livequery(function () {
		$( this ).lsActionbarItemSelect({
			selectors: {
				target_item: '.js-user-list-select .js-user-list-small-item'
			}
		});
	});


	/**
	 * Modals
	 */
	$('.js-modal-default').lsModal();


	/**
	 * Accordion
	 */
	$('.js-accordion-default').accordion({
		collapsible: true
	});


	/**
	 * Dropdowns
	 */
	$('.js-dropdown-default').livequery(function () {
		$(this).lsDropdown();
	});


	/**
	 * Tabs
	 */
	$( '.js-tabs-auth, .js-tabs-block' ).lsTabs();


	/**
	 * Fields
	 */
	ls.geo.initSelect();

	$('.js-date-picker').datepicker();

	$('[data-type=captcha]').livequery(function () {
		$(this).captcha();
	});


	/**
	 * Alerts
	 */
	$('.js-alert').lsAlert();


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
	$( '.autocomplete-tags' ).lsAutocomplete({
		multiple: false,
		urls: {
			load: aRouter.ajax + 'autocompleter/tag/'
		}
	});

	$( '.autocomplete-tags-sep' ).lsAutocomplete({
		multiple: true,
		urls: {
			load: aRouter.ajax + 'autocompleter/tag/'
		}
	});

	$( '.autocomplete-users' ).lsAutocomplete({
		multiple: false,
		urls: {
			load: aRouter.ajax + 'autocompleter/user/'
		}
	});

	$( '.autocomplete-users-sep' ).lsAutocomplete({
		multiple: true,
		urls: {
			load: aRouter.ajax + 'autocompleter/user/'
		}
	});


	/**
	 * Code highlight
	 */
	prettyPrint();


	/**
	 * Blocks
	 */
	$( '.js-block-default' ).lsBlock();


	/**
	 * Активность
	 */
	$('.js-activity--all').lsActivity({ urls: { more: aRouter.stream + 'get_more_all' } });
	$('.js-activity--user').lsActivity({ urls: { more: aRouter.stream + 'get_more_user' } });
	$('.js-activity--personal').lsActivity({
		urls: {
			more: aRouter.stream + 'get_more_personal'
		},
		create: function() {
			// Настройки активности
			$('.js-activity-settings').lsActivitySettings({
				urls: {
					toggle_type: aRouter.stream + 'switchEventType'
				}
			});

			// Добавление пользователей в персональную активность
			$('.js-activity-users').user_list_add({
				urls: {
					add: aRouter.stream + 'ajaxadduser',
					remove: aRouter.stream + 'ajaxremoveuser'
				}
			});
		}
	});


	/**
	 * Лента
	 */
	$('.js-feed').lsFeed({
		urls: {
			more: aRouter.feed + 'get_more'
		},
		create: function() {
			// Блоги
			$('.js-feed-blogs').lsFeedBlogs({
				urls: {
					subscribe: aRouter.feed + 'subscribe',
					unsubscribe: aRouter.feed + 'unsubscribe'
				}
			});

			// Добавление пользователей в свою ленту
			$('.js-feed-users').user_list_add({
				urls: {
					add: aRouter.feed + 'ajaxadduser',
					remove: aRouter.feed + 'unsubscribe'
				}
			});
		}
	});


	/**
	 * User
	 */
	ls.user.init();

	// Голосование за пользователя
	$('.js-vote-user').vote({
		urls: {
			vote: aRouter['ajax'] + 'vote/user/'
		}
	});

	// Поиск
	$( '.js-search-ajax-users' ).lsSearchAjax({
		urls: {
			search: aRouter.people + 'ajax-search/'
		},
		filters : [
			{
				type: 'text',
				name: 'sText',
				selector: '.js-search-text-main',
				alphanumericFilterSelector: '.js-search-alphabet'
			},
			{
				type: 'alphanumeric',
				name: 'sText',
				selector: '.js-search-alphabet .js-search-alphabet-item',
				textFilterSelector: '.js-search-text-main'
			},
			{
				type: 'radio',
				name: 'sex',
				selector: '.js-search-ajax-user-sex'
			},
			{
				type: 'checkbox',
				name: 'is_online',
				selector: '.js-search-ajax-user-online'
			},
			{
				type: 'sort',
				name: 'sort_by',
				selector: '.js-search-sort-menu li'
			}
		]
	});

	// Добавление пользователя в свою активность
	$('.js-user-follow').lsUserFollow({
		urls: {
			follow:   aRouter['stream'] + 'ajaxadduser/',
			unfollow: aRouter['stream'] + 'ajaxremoveuser/'
		}
	});

	// Добавление пользователя в друзья
	$('.js-user-friend').lsUserFriend({
		urls: {
			add:    aRouter.profile + 'ajaxfriendadd/',
			remove: aRouter.profile + 'ajaxfrienddelete/',
			accept: aRouter.profile + 'ajaxfriendaccept/',
			modal:  aRouter.profile + 'ajax-modal-add-friend'
		}
	});

	// Жалоба
	$('.js-user-report').lsReport({
		urls: {
			modal: aRouter.profile + 'ajax-modal-complaint',
			add: aRouter.profile + 'ajax-complaint-add',
		}
	});

	// Управление кастомными полями
	$( '.js-user-fields' ).lsUserFields();

	// Фото пользователя
	$( '.js-user-photo' ).lsUserPhoto({
		urls: {
			upload: aRouter.settings + 'ajax-upload-photo',
			remove: aRouter.settings + 'ajax-remove-photo',
			crop_photo: aRouter.ajax + 'modal/image-crop/',
			crop_avatar: aRouter.ajax + 'modal/image-crop/',
			save_photo: aRouter.settings + 'ajax-crop-photo',
			save_avatar: aRouter.settings + 'ajax-change-avatar',
			cancel_photo: aRouter.settings + 'ajax-crop-cancel-photo',
		}
	});


	/**
	 * Talk
	 */
	ls.talk.init();

	// Форма поиска
	$('.js-talk-search-form').accordion({
		collapsible: true,
		active: false
	});

	// Добавление диалога в избранное
	$('.js-favourite-talk').lsFavourite({
		urls: {
			toggle: aRouter['ajax'] + 'favourite/talk/'
		}
	});

	// Комментарии
	$('.js-comments-talk').lsComments({
		urls: {
			add:  aRouter['talk'] + 'ajaxaddcomment/',
			load: aRouter['talk'] + 'ajaxresponsecomment/'
		}
	});

	// Экшнбар
	$('.js-talk-actionbar-select').lsActionbarItemSelect({
		selectors: {
			target_item: '.js-message-list-item'
		}
	});

	// Добавление участников личного сообщения
	$('.js-message-users').message_users();

	// Черный список
	$('.js-user-list-add-blacklist').user_list_add({
		urls: {
			add: aRouter['talk'] + 'ajaxaddtoblacklist/',
			remove: aRouter['talk'] + 'ajaxdeletefromblacklist/'
		}
	});


	/**
	 * Poll
	 */
	$('.js-poll').lsPoll();
	$('.js-poll-manage').lsPollManage();


	/**
	 * User Note
	 */
	$('.js-user-note').livequery(function () {
		$(this).lsNote({
			urls: {
				save:   aRouter['profile'] + 'ajax-note-save/',
				remove: aRouter['profile'] + 'ajax-note-remove/'
			}
		});
	});


	/**
	 * Editor
	 */
	$( '.js-editor-default' ).lsEditor();


	/**
	 * Blog
	 */
	ls.blog.init();

	// Голосование за блог
	$('.js-vote-blog').vote({
		urls: {
			vote: aRouter['ajax'] + 'vote/blog/'
		}
	});

	// Приглашение пользователей в блог
	$('.js-user-list-add-blog-invite').lsBlogInvites();


	/**
	 * Topic
	 */
	$( '.js-topic' ).lsTopic();

	// Добавление/редактирование
	ls.topic.init();

	// Пагинация
	$('.js-pagination-topics').lsPagination({
		hash: {
			next: 'goTopic=first',
			prev: 'goTopic=last'
		}
	});

	// Комментарии
	$('.js-comments-topic').lsComments({
		urls: {
			add:  aRouter['blog'] + 'ajaxaddcomment/',
			load: aRouter['blog'] + 'ajaxresponsecomment/'
		}
	});


	/**
	 * Теги
	 */
	ls.tags.init();

	// Облако тегов избранного
	$('.js-tags-favourite-accordion').accordion({
		collapsible: true,
		active: false
	});

	// Поиск по тегам
	$('.js-tag-search-form').submit(function() {
		var val = $(this).find('.js-tag-search').val();

		if ( val ) {
			window.location = aRouter['tag'] + encodeURIComponent( val ) + '/';
		}

		return false;
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
	 * Стена
	 */
	$('.js-wall-default').lsWall({
		urls: {
			add:           aRouter.ajax + 'wall/add/',
			remove:        aRouter.ajax + 'wall/remove/',
			load:          aRouter.ajax + 'wall/load/',
			load_comments: aRouter.ajax + 'wall/load-comments/'
		}
	});


	/**
	 * Лайтбокс
	 */
	$('a.js-lbx').colorbox({ width:"100%", height:"100%" });


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
	 * Fotorama
	 */
	$( '.fotorama' ).livequery(function() {
		$( this ).fotorama();
	});


	/**
	 * Фикс бага с z-index у встроенных видео
	 */
	$( 'iframe' ).each(function() {
		var iframe = $( this ),
			src = iframe.attr( 'src' );

		if ( src ) {
			iframe.attr( 'src', src + ( ~ src.indexOf( '?' ) ? '?' : '&' ) + 'wmode=opaque' );
		}
	});

	// Хук конца инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_end',[],window);
});