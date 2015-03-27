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
	 * IE
	 */
	if ( $( 'html' ).hasClass( 'oldie' ) ) {
		// Эмуляция placeholder'ов в IE
		$( 'input[type=text], textarea' ).placeholder();
	}


	/**
     * Notification
     */
    ls.notification.init();


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
	 * Details
	 */
	$('.js-details-default').lsDetails();


	/**
	 * Dropdowns
	 */
	$('.js-dropdown-default').livequery(function () {
		$(this).lsDropdown();
	});


	/**
	 * Fields
	 */
	$('.js-field-geo-default').lsFieldGeo({
        urls: {
            regions: aRouter.ajax + 'geo/get/regions/',
            cities: aRouter.ajax + 'geo/get/cities/'
        }
    });

    $('.js-field-date-default').livequery(function () {
        $(this).lsFieldDate();
    });

    $('.js-field-datetime-default').livequery(function () {
        $(this).lsFieldDatetime();
    });

    $('.js-field-time-default').livequery(function () {
        $(this).lsFieldTime();
    });

	$('[data-type=captcha]').livequery(function () {
		$(this).lsCaptcha();
	});

	$('[data-type=recaptcha]').livequery(function () {
		$(this).lsReCaptcha({
			key: ls.registry.get('recaptcha.site_key')
		});
	});

	/**
	 * Alerts
	 */
	$('.js-alert').lsAlert();


	/**
	 * Tooltips
	 */
	$('.js-tooltip').lsTooltip();

	$('.js-popover-default').lsTooltip({
		useAttrTitle: false,
		trigger: 'click',
		classes: 'tooltip-light'
	});

	if (ls.registry.get('block_stream_show_tip')) {
		$('.js-title-comment, .js-title-topic').livequery(function () {
			$(this).lsTooltip({
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

	$( '.autocomplete-strict-users' ).lsAutocompleteStrict({
		multiple: false,
		urls: {
			load: aRouter.ajax + 'autocompleter/user/'
		},
		params: {
			extended: 1
		}
	});

	$( '.autocomplete-users-sep' ).lsAutocomplete({
		multiple: true,
		urls: {
			load: aRouter.ajax + 'autocompleter/user/'
		}
	});

	$( '.autocomplete-strict-users-sep' ).lsAutocompleteStrict({
		urls: {
			load: aRouter.ajax + 'autocompleter/user/'
		},
		params: {
			extended: 1
		}
	});

	$('.autocomplete-property-tags').each(function(k,v){
		$(v).lsAutocomplete({
			multiple: false,
			urls: {
				load: aRouter.ajax + 'property/tags/autocompleter/'
			},
			params: {
				property_id: $(v).data('propertyId')
			}
		});
	});

	$('.autocomplete-property-tags-sep').each(function(k,v){
		$(v).lsAutocomplete({
			multiple: true,
			urls: {
				load: aRouter.ajax + 'property/tags/autocompleter/'
			},
			params: {
				property_id: $(v).data('propertyId')
			}
		});
	});

	/**
	 * Code highlight
	 */
	$( 'pre code' ).lsHighlighter();


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
			$('.js-activity-users').lsUserListAdd({
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
	// Блоги
	$('.js-feed-blogs').lsFeedBlogs({
		urls: {
			subscribe: aRouter.feed + 'subscribe',
			unsubscribe: aRouter.feed + 'unsubscribe'
		}
	});

	// Добавление пользователей в свою ленту
	$('.js-feed-users').lsUserListAdd({
		urls: {
			add: aRouter.feed + 'ajaxadduser',
			remove: aRouter.feed + 'unsubscribe'
		}
	});


	/**
	 * User
	 */
	ls.user.init();

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
			},
			{
				type: 'select',
				name: 'country',
				selector: '.js-field-geo-country'
			},
			{
				type: 'select',
				name: 'region',
				selector: '.js-field-geo-region'
			},
			{
				type: 'select',
				name: 'city',
				selector: '.js-field-geo-city'
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
			add: aRouter.profile + 'ajax-complaint-add'
		}
	});

	// Управление кастомными полями
	$( '.js-user-fields' ).lsUserFields();

	// Фото пользователя
	$( '.js-user-photo' ).lsPhoto({
		urls: {
			upload: aRouter.settings + 'ajax-upload-photo',
			remove: aRouter.settings + 'ajax-remove-photo',
			crop_photo: aRouter.settings + 'ajax-modal-crop-photo',
			crop_avatar: aRouter.settings + 'ajax-modal-crop-avatar',
			save_photo: aRouter.settings + 'ajax-crop-photo',
			save_avatar: aRouter.settings + 'ajax-change-avatar',
			cancel_photo: aRouter.settings + 'ajax-crop-cancel-photo'
		},
		changeavatar: function ( event, _this, avatars ) {
			$( '.js-user-profile-avatar, .js-wall-entry[data-user-id=' + _this.option( 'params.user_id' ) + '] .comment-avatar img' ).attr( 'src', avatars[ '64crop' ] + '?' + Math.random() );
			$( '.nav-item--userbar-username img' ).attr( 'src', avatars[ '24crop' ] + '?' + Math.random() );
		}
	});

	/**
	 * Talk
	 */
	ls.talk.init();

	// Форма поиска
	$('.js-talk-search-form').lsDetails();

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

	// Управление участниками личного сообщения
	$('.js-message-users').lsTalkUsers();

	// Черный список
	$('.js-user-list-add-blacklist').lsUserListAdd({
		urls: {
			add: aRouter['talk'] + 'ajaxaddtoblacklist/',
			remove: aRouter['talk'] + 'ajaxdeletefromblacklist/'
		}
	});


	/**
	 * Poll
	 */
	$('.js-poll').lsPoll();
	$('.js-poll-manage').lsPollManage({
        max: ls.registry.get('poll_max_answers')
    });


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

	// Форма добавления блога
	$('.js-blog-add').lsBlogAdd();

	// Приглашение пользователей в блог
	$('.js-user-list-add-blog-invite').lsBlogInvites();

	// Вступить/покинуть блог
	$( '.js-blog-join' ).livequery(function() {
		$( this ).lsBlogJoin({
			urls: {
				toggle: aRouter.blog + 'ajaxblogjoin'
			}
		});
	});

	// Поиск
	$( '.js-search-ajax-blog' ).lsSearchAjax({
		urls: {
			search: aRouter.blogs + 'ajax-search/'
		},
		filters : [
			{
				type: 'text',
				name: 'sText',
				selector: '.js-search-text-main'
			},
			{
				type: 'radio',
				name: 'type',
				selector: '.js-search-ajax-blog-type'
			},
			{
				type: 'radio',
				name: 'relation',
				selector: '.js-search-ajax-blog-relation'
			},
			{
				type: 'list',
				name: 'category',
				selector: '#js-search-ajax-blog-category li'
			},
			{
				type: 'sort',
				name: 'sort_by',
				selector: '.js-search-sort-menu li'
			}
		]
	});

	// Аватар блога
	$( '.js-blog-avatar' ).lsPhoto({
		urls: {
			upload: aRouter.blog + 'ajax/upload-avatar',
			remove: aRouter.blog + 'ajax/remove-avatar',
			crop_photo: aRouter.blog + 'ajax/modal-crop-avatar',
			save_photo: aRouter.blog + 'ajax/crop-avatar',
			cancel_photo: aRouter.blog + 'ajax/crop-cancel-avatar'
		},
		use_avatar: false,
		crop_photo: {
			minSize: [ 100, 100 ],
			aspectRatio: 1,
			usePreview: true
		}
	});


	/**
	 * Topic
	 */
	$( '.js-topic' ).lsTopic();

	// Форма добавления
	$( '#topic-add-form' ).lsTopicAdd({
		max_blog_count: ls.registry.get('topic_max_blog_count')
	});

	// Пагинация
	$('.js-pagination-topics').lsPagination({
		hash: {
			next: 'goTopic=first',
			prev: 'goTopic=last'
		}
	});

	// Комментарии
	$('.js-topic-comments, .js-topic-comments-list').lsComments({
		urls: {
			add:  aRouter['blog'] + 'ajaxaddcomment/',
			load: aRouter['blog'] + 'ajaxresponsecomment/'
		},
		show_form: ls.registry.get('comment_show_form')
	});

	// Кнопка обновления комментариев
	// TODO: Fix init
	$('.js-comments-toolbar').lsCommentsToolbar({
		comments: $('.js-topic-comments, .js-comments-talk')
	});


	/**
	 * Теги
	 */
	ls.tags.init();

	// Облако тегов избранного
	$('.js-tags-favourite-cloud').lsDetails();

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
	$('.js-form-validate').parsley();


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
	$('a.js-lbx').lsLightbox({ width:"100%", height:"100%" });


	/**
	 * Toolbar
	 */
	$('.js-toolbar-default').lsToolbar({
		target: '.grid-role-wrapper',
		offsetX: 10
	});
	$('.js-toolbar-scrollup').lsToolbarScrollUp();
	$('.js-toolbar-topics').lsToolbarTopics();


	/**
	 * Fotorama
	 */
	$( '.fotorama' ).livequery(function() {
		$( this ).lsSlider();
	});

	// Хук конца инициализации javascript-составляющих шаблона
	ls.hook.run('ls_template_init_end',[],window);
});