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
     * Form validate
     * Валидатор нужно иниц-ть до иниц-ии аякс форм, чтобы избежать валидации аякс-полей после сабмита формы
     */
    $('.js-form-validate').parsley();


    /**
     * Подтверждение удаления
     */
    $('.js-confirm-remove-default').livequery(function () {
        $(this).lsConfirm({
            message: ls.lang.get('common.remove_confirm')
        });
    });


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
    $('.js-modal-toggle-default').lsModalToggle();


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
        $(this).lsFieldDate({
            language: LANGUAGE
        });
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

    $( '.autocomplete-users-sep' ).lsAutocomplete({
        multiple: true,
        urls: {
            load: aRouter.ajax + 'autocompleter/user/'
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
                    remove: aRouter.stream + 'ajaxremoveuser',
                    list: aRouter.ajax + 'modal-friend-list'
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
            remove: aRouter.feed + 'unsubscribe',
            list: aRouter.ajax + 'modal-friend-list'
        }
    });


    /**
     * Auth
     */
    ls.auth.init();

    // Поиск
    $( '.js-search-ajax-users' ).lsSearchAjax({
        urls: {
            search: aRouter.people + 'ajax-search/'
        },
        i18n: {
            title: ls.lang.get( 'user.search.result_title' )
        },
        selectors: {
            list: '.js-more-users-container',
            more: '.js-more-search',
            title: '@.js-user-list-search-title'
        },
        filters : [
            {
                type: 'text',
                name: 'sText',
                selector: '.js-search-text-main'
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
        ],
        afterupdate: function ( event, data ) {
            data.context.getElement( 'more' ).lsMore( 'option', 'params.next_page', 2 );
        }
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
    
    $('.js-talk-list').lsTalkList();

    // Выбор получателей в форме добавления
    $('.js-talk-add-user-choose').lsUserFieldChoose({
        urls: {
            modal: aRouter.ajax + 'modal-friend-list'
        }
    });

    // Форма поиска
    $('.js-talk-search-form').lsDetails();

    // Добавление диалога в избранное
    $('.js-favourite-talk').lsFavourite({
        urls: {
            toggle: aRouter['ajax'] + 'favourite/talk/'
        }
    });

    // Добавление в избранное на странице диалога
    $('.js-talk-message-root-favourite').on('click', function (event) {
        if (event.target == event.currentTarget) {
            $(this).find('.js-favourite-talk').lsFavourite('toggle');
        }
    });

    // Комментарии
    $('.js-comments-talk').lsComments({
        urls: {
            add:  aRouter['talk'] + 'ajaxaddcomment/',
            load: aRouter['talk'] + 'ajaxresponsecomment/'
        }
    });

    // Управление участниками личного сообщения
    $('.js-message-users').lsTalkUsers();

    // Черный список
    $('.js-user-list-add-blacklist').lsUserListAdd({
        urls: {
            add: aRouter['talk'] + 'ajaxaddtoblacklist/',
            remove: aRouter['talk'] + 'ajaxdeletefromblacklist/',
            list: aRouter.ajax + 'modal-friend-list'
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

    // Вступить/покинуть блог (список блогов)
    $( '.js-blog-join' ).livequery(function() {
        $( this ).lsBlogJoin({
            urls: {
                toggle: aRouter.blog + 'ajaxblogjoin'
            },
            classes: {
                loading: ls.options.classes.states.loading
            }
        });
    });

    // Вступить/покинуть блог (страница блога)
    $( '.js-blog-profile-join' ).lsBlogJoin({
        urls: {
            toggle: aRouter.blog + 'ajaxblogjoin'
        },
        selectors: {
            text: 'a'
        },
        classes: {
            active: 'active'
        }
    });

    // Поиск
    $( '.js-search-ajax-blog' ).lsSearchAjax({
        urls: {
            search: aRouter.blogs + 'ajax-search/'
        },
        i18n: {
            title: ls.lang.get( 'blog.search.result_title' )
        },
        selectors: {
            list: '.js-more-blogs-container',
            more: '.js-more-search',
            title: '@.js-blog-list-search-title'
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
        ],
        afterupdate: function ( event, data ) {
            data.context.getElement( 'more' ).lsMore( 'option', 'params.next_page', 2 );
        }
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
        target: '.layout-wrapper',
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