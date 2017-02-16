<?php

/* -------------------------------------------------------
 *
 *   LiveStreet Engine Social Networking
 *   Copyright © 2008 Mzhelskiy Maxim
 *
 * --------------------------------------------------------
 *
 *   Official site: www.livestreet.ru
 *   Contact e-mail: rus.engine@gmail.com
 *
 *   GNU General Public License, version 2:
 *   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
  ---------------------------------------------------------
 */

/**
 * English language file.
 * Pre alpha translation. Google translate would probably do it better.
 */
return array(
    /**
     * Голосование
     */
    'vote'          => array(
        'up'      => 'Like',
        'down'    => 'Dislike',
        'abstain' => 'Skip voting, check the rating',
        'count'   => 'Voted',
        'rating'  => 'Rating',
        // Всплывающие сообщения
        'notices' => array(
            'success'             => 'Thank you for your vote',
            'success_abstain'     => 'You have skipped the voting to check the rating',
            'error_time'          => 'Votes are not accepted anymore!',
            'error_already_voted' => 'You have already been voted!',
            'error_acl'           => 'Not enough rating to vote!',
            'error_auth'          => 'You have to be logged in to vote',
            'error_self'          => 'You can not vote for yourself',
        ),
    ),
    /**
     * Избранное
     */
    'favourite'     => array(
        'favourite' => 'Favourite',
        'add'       => 'Add to favourite',
        'remove'    => 'Delete from favourite',
        // Всплывающие сообщения
        'notices'   => array(
            'add_success'     => 'Added to favourites',
            'remove_success'  => 'Removed from favourites',
            'already_added'   => 'Already in favourites!',
            'already_removed' => 'Already removed from favourites!',
        ),
    ),
    /**
     * Поиск
     */
    'search'        => array(
        'search' => 'Search',
        'find'   => 'Find',
        'result' => array(
            'topics'   => 'Topics',
            'comments' => 'Comments',
        ),
        // Сообщения
        'alerts' => array(
            'empty'           => 'Nothing found',
            'query_incorrect' => 'Minimum search length is 3 symbols',
        ),
    ),
    /**
     * Сортировка
     */
    'sort'          => array(
        'label'                => 'Sort',
        'by_login'             => 'by login',
        'by_name'              => 'by name',
        'by_title'             => 'by title',
        'by_date'              => 'by date',
        'by_date_registration' => 'by reg date',
        'by_rating'            => 'by rating',
    ),
    /**
     * Заметка пользователя
     */
    'user_note'     => array(
        'add'     => 'Add note',
        // Всплывающие сообщения
        'notices' => array(
            'target_error' => 'Not able to add a note to the selected user', // TODO: Remove?
        ),
    ),
    /**
     * Блог
     */
    'blog'          => array(
        'blog'                 => 'Blog',
        'blogs'                => 'Blogs',
        'readers_declension'   => 'reader;reader;readers',
        'administrators'       => 'Administrators',
        'moderators'           => 'Moderators',
        'owner'                => 'Owner',
        'create_blog'          => 'Create a blog',
        'can_add'              => 'You can create a new blog!',
        'cant_add'             => 'Your rating must be %%rating%% in order to create a blog.',
        'private'              => 'Private blog',
        'personal_prefix'      => 'Blog belongs to...',
        'personal_description' => 'This is your personal blog.',
        'topics_total'         => 'Total topics',
        'date_created'         => 'Creation date',
        'rating_limit'         => 'Rating limit',
        'rss'                  => 'RSS',
        // Действия
        'actions'              => array(
            'write'  => 'Add to blog',
            'join'   => '___blog.join.join___',
            'leave'  => '___blog.join.leave___',
            'rss'    => 'Subscribe to RSS',
            'edit'   => '___common.edit___',
            'remove' => '___common.remove___',
        ),
        // Сообщения
        'alerts'               => array(
            'private' => 'You are not authorized to access this private blog.',
            'banned'  => 'You are banned in this blog',
            'empty'   => 'No blogs',
        ),
        /**
         * Поиск
         */
        'search'               => array(
            'placeholder'  => 'Search by name',
            'result_title' => '%%count%% blogs found;%%count%% blogs found;%%count%% blogs found',
            'form'         => array(
                'type'     => array(
                    'any'     => 'Any',
                    'public'  => 'Public',
                    'private' => 'Private'
                ),
                'relation' => array(
                    'all'    => 'All',
                    'my'     => 'My',
                    'joined' => 'Subscribed'
                )
            )
        ),
        /**
         * Приглашения
         */
        'invite'               => array(
            'invite_users' => 'Invite users',
            'repeat'       => 'Repeat',
            'empty'        => 'No invited users',
            // Письмо с приглашением
            'email'        => array(
                'title' => "Invitation to read '%%blog_title%%' blog",
                'text'  => "User %%login%% is inviting you to access a private blog '%%blog_title%%'.<br/><br/><a href='%%accept_path%%'>Accept</a> - <a href='%%reject_path%%'>Reject</a>"
            ),
            // Всплывающие сообщения
            'notices'      => array(
                'add'             => 'Invitation sent to %%login%%',
                'add_self'        => 'You can not invite yourself',
                'already_invited' => 'Ivitation was already sent to %%login%%',
                'already_joined'  => 'User %%login%% has already joined the blog',
                'remove'          => 'Invitation for user %%login%% removed',
                'reject'          => 'User %%login%% has rejected an invitation',
            ),
            // Сообщения
            'alerts'       => array(
                'already_joined' => 'You are already a member of this blog',
                'accepted'       => 'Invitation accepted',
                'rejected'       => 'Invitation rejected',
            )
        ),
        /**
         * Страница добавления/редактирования блога
         */
        'add'                  => array(
            'title'  => 'Create a new blog',
            // Поля
            'fields' => array(
                'title'       => array(
                    'label'        => 'Blog title',
                    'note'         => 'Blog title should make sense. It should be easy to understand what is the blog about.',
                    'error'        => 'Blog title must be withing 2 to 200 charachters long',
                    'error_unique' => 'Blog with this name already exists',
                ),
                'url'         => array(
                    'label'         => 'Blogs URL',
                    'note'          => 'Blogs URL may contain only english letters, digits and dash. All spaces will be replaced with underscore. Ideally URL should match the blogs title. This parameter is not editable after the blog is created.',
                    'error'         => 'URL must be 2 to 50 chars long. Only English letters, Digits, symbols "-" and "_" are allowed.',
                    'error_badword' => 'URL must be defferent from:',
                    'error_unique'  => 'This URL is already exists',
                ),
                'category'    => array(
                    'label'               => 'Blogs category',
                    'note'                => 'A category can be assigned to the blog. This helps to implement a structure to the web site.',
                    'error'               => 'Category not found',
                    'error_only_children' => 'Only child category is allowed (Category must not have a sub-category)',
                ),
                'type'        => array(
                    'label'       => 'Blogs type',
                    'note_open'   => 'Public - anyone can join, all topics are visible.',
                    'note_close'  => 'Private - must be invited by the blog administrators. Topics are only visible to approved users.',
                    'value_open'  => 'Public',
                    'value_close' => 'Private',
                    'error'       => 'Unknown blog type',
                ),
                'description' => array(
                    'label' => 'Blogs description',
                    'error' => 'Description must be from 10 to 3000 charachters long',
                ),
                'rating'      => array(
                    'label' => 'Rating limit',
                    'note'  => 'Required rating in order to write blog posts',
                    'error' => 'Rating limit must be a number',
                ),
                'avatar'      => array(
                    'label' => 'Avatar',
                    'error' => 'Was not able to load an avatar',
                ),
                'skip_index'  => array(
                    'label' => 'Do not post topics on a main page',
                    'note'  => 'No topics from this blog will be allowed on a main page',
                ),
            ),
            // Сообщения
            'alerts' => array(
                'acl' => 'You are not good enough yet to create a blog', // TODO: Remove?
            )
        ),
        /**
         * Удаление блога
         */
        'remove'               => array(
            'title'         => 'Delete blog',
            'remove_topics' => 'Delete topics',
            'move_to'       => 'Move topics to the blog',
            // Сообщения
            'alerts'        => array(
                'success'             => 'Blog was successfully removed',
                'not_empty'           => 'You are not allowed to delete the blog with posts. All posts must be removed first.',
                'move_error'          => 'Was not able to move topics',
                'move_personal_error' => 'Forbidden to move topics to the private blogs', // TODO: Remove?
            )
        ),
        /**
         * Управление блогом
         */
        'admin'                => array(
            'title'              => 'Blog edit',
            'role_administrator' => 'Administrator',
            'role_moderator'     => 'Moderator',
            'role_reader'        => 'Reader',
            'role_banned'        => 'Banned',
            // Навигация
            'nav'                => array(
                'profile' => 'Profile',
                'users'   => 'Users',
            ),
            // Сообщения
            'alerts'             => array(
                'empty'          => 'No blog readers', // TODO: Remove?
                'submit_success' => 'Permissions saved', // TODO: Remove?
            )
        ),
        /**
         * Голосование
         */
        'vote'                 => array(
            // Всплывающие сообщения
            'notices' => array(
                'error_close' => 'You are not able to vote for a private blog',
            ),
        ),
        /**
         * Вступить / покинуть блог
         */
        'join'                 => array(
            'join'    => 'Join',
            'leave'   => 'Leave',
            // Всплывающие сообщения
            'notices' => array(
                'join_success'  => 'You have joined the blog',
                'leave_success' => 'You have left the blog',
                'error_invite'  => 'You must be invited in order to join the private blog!', // Remove?
                'error_self'    => 'Can not join. You are already an owner!', // Remove?
            ),
        ),
        /**
         * Категории
         */
        'categories'           => array(
            'category'   => 'Category',
            'categories' => 'Categories',
            'empty'      => 'No blogs in this category',
        ),
        /**
         * Список пользователей
         */
        'users'                => array(
            'readers'       => 'Readers',
            'readers_all'   => 'All blog readers',
            'readers_total' => 'Total readers',
            'empty'         => 'No readers',
        ),
        /**
         * Сортировка
         */
        'sort'                 => array(
            'by_users'  => 'by readers amount',
            'by_topics' => 'by topics amount',
        ),
        /**
         * Меню со списокм топиков
         */
        'menu'                 => array(
            'all'            => 'All',
            'all_good'       => 'Most Popular',
            'all_discussed'  => 'Hot',
            'all_top'        => 'TOP',
            'all_new'        => 'New',
            'all_list'       => 'All Blogs',
            'top_period_1'   => '24 hrs',
            'top_period_7'   => '7 days',
            'top_period_30'  => '30 days',
            'top_period_all' => 'All the time',
        ),
        /**
         * Блоки
         */
        'blocks'               => array(
            'info'      => array(
                'title' => 'Blog description',
            ),
            'navigator' => array(
                'title'    => 'Blogs navigation',
                'submit'   => 'View',
                'category' => '___blog.categories.category___',
                'blog'     => '___blog.blog___',
                'empty'    => '___blog.categories.empty___',
            ),
            'blogs'     => array(
                'title'        => 'Blogs',
                'nav'          => array(
                    'top'    => 'Top',
                    'joined' => 'Joined',
                    'self'   => 'Mine',
                ),
                'item'         => array(
                    'rating'  => '___vote.rating___',
                    'private' => '___blog.private___',
                ),
                'joined_empty' => '___common.empty___', // TODO: Remove?
                'self_empty'   => '___common.empty___', // TODO: Remove?
            ),
            'search'    => array(
                'title'      => 'Blogs search',
                'categories' => array(
                    'title' => '___blog.categories.categories___',
                    'all'   => 'All',
                ),
                'type'       => array(
                    'title' => 'Blog type',
                ),
                'relation'   => array(
                    'title' => 'Ownership',
                ),
            ),
        ),
        'types'                => array(
            'personal' => 'Personal blogs',
            'open'     => 'Public blogs',
            'close'    => 'Private blogs',
        ),
    ),
    /**
     * Личные сообщения
     */
    'talk'          => array(
        'title'        => 'Messages',
        'participants' => '%%count%% user;%%count%% users;%%count%% users',
        'new_messages' => 'You have new messages',
        'send_message' => 'Send a message',
        // Меню
        'nav'          => array(
            'inbox'      => 'Messages',
            'new'        => 'Only new',
            'add'        => 'New message',
            'favourites' => 'Favourites',
            'blacklist'  => 'Add to blacklist'
        ),
        // Форма добавления
        'add'          => array(
            'title'          => 'New message',
            'choose_friends' => 'Select recipients from the friendlist',
            // Поля
            'fields'         => array(
                'users' => array(
                    'label' => 'To'
                ),
                'title' => array(
                    'label' => 'Title',
                ),
                'text'  => array(
                    'label' => 'Message',
                ),
            ),
            // Сообщения
            'notices'        => array(
                'users_error'           => 'At least one recepient must be selected',
                'users_error_not_found' => 'Recepient not found', // TODO: Move to common
                'users_error_many'      => 'Too many recepients',
                'title_error'           => 'Title must be within 2 to 200 charachters',
                'text_error'            => 'Message must be within 2 to 3000 charachters',
            )
        ),
        // Сообщение
        'message'      => array(
            // Сообщения
            'notices' => array(
                'error_text' => 'Message must be within 2 to 3000 charachters',
            )
        ),
        // Экшнбар
        'actionbar'    => array(
            'read'         => 'Read',
            'unread'       => 'Unread',
            'mark_as_read' => 'Mark as read',
        ),
        // Форма поиска
        'search'       => array(
            'title'   => 'Search by messages',
            // Поля
            'fields'  => array(
                'sender'       => array(
                    'label' => 'Sender',
                    'note'  => 'Confirm senders login'
                ),
                'receiver'     => array(
                    'label' => 'Recepient',
                    'note'  => 'Confirm recepients login'
                ),
                'keyword'      => array(
                    'label' => 'Search the title',
                ),
                'keyword_text' => array(
                    'label' => 'Search the text',
                ),
                'start'        => array(
                    'label'       => 'Date limit',
                    'placeholder' => 'From'
                ),
                'end'          => array(
                    'placeholder' => 'To'
                ),
                'favourite'    => array(
                    'label' => 'Search only in favourites'
                ),
            ),
            // Сообщения
            'notices' => array(
                'error'             => 'Search error',
                'error_date_format' => 'Wrong date format',
                'result_count'      => 'Found: %%count%% messages',
                'result_empty'      => 'No messages found'
            )
        ),
        // Черный список
        'blacklist'    => array(
            'title'   => 'Black list',
            'note'    => 'Users to stop receiveing messages from',
            // Сообщения
            'notices' => array(
                'blocked'        => 'User <b>%%login%%</b> does not accept messages from you',
                'user_not_found' => 'User <b>%%login%%</b> is not in your black list',
            ),
        ),
        // Список участников разговора
        'users'        => array(
            'title'    => 'Conference user list',
            'inactive' => 'User is inactive',
            // Сообщения
            'notices'  => array(
                'user_not_found' => 'User <b>%%login%%</b> is inactive',
                'deleted'        => 'User <b>%%login%%</b> has deleted this conversation',
            )
        ),
        // Сообщения
        'notices'      => array(
            'time_limit' => 'You are not allowed to send messages so often',
            'empty'      => 'No messages',
            'deleted'    => 'Sender has deleted the conversation',
            'not_found'  => 'Conversation not found'
        ),
    ),
    /**
     * Опросы
     */
    'poll'          => array(
        'polls'     => 'Polls',
        'vote'      => 'Vote',
        'abstain'   => 'Skip',
        'only_auth' => 'Only authorized users are allowed to vote',
        // Результат
        'result'    => array(
            'voted_total'     => 'Voted',
            'abstained_total' => 'Skipped',
            'sort'            => 'Sorting Enable/Disable',
        ),
        // Форма добавления
        'form'      => array(
            'title'         => array(
                'add'  => 'Add poll',
                'edit' => 'Edit poll',
            ),
            'answers_title' => 'Answers',
            // Поля
            'fields'        => array(
                'title'             => 'Question',
                'is_guest_allow'    => 'Guests allowed',
                'is_guest_check_ip' => 'Only one vote per IP is allowed',
                'type'              => array(
                    'label'      => 'User can select',
                    'label_one'  => 'Only one answer',
                    'label_many' => 'Several answers'
                ),
            ),
        ),
        // Всплывающие сообщения
        'notices'   => array(
            'error_answers_max'       => 'Maximum allowed answers %%count%%',
            'error_not_allow_vote'    => 'Not allowed to vote in this poll',
            'error_not_allow_remove'  => 'This poll may not be removed',
            'error_already_vote'      => 'You`ve already been voted',
            'error_no_answers'        => 'An option should be selected',
            'error_answers_max_wrong' => 'Maximum answers should be more than one',
            'error_answers_count'     => 'You must select more than one answer',
            'error_answer_remove'     => 'Unable to remove an option as someone already used it',
            'error_target_type'       => 'Wrong answer type',
            'error_target_tmp'        => 'Timestamp is already in use',
        ),
    ),
    /**
     * Комментарии
     */
    'comments'      => array(
        'comments_declension' => '%%count%% comment;%%count%% comments;%%count%% comments',
        'no_comments'         => 'No comments',
        'count_new'           => 'New comments',
        'update'              => 'Refresh comments',
        'title'               => 'Comments',
        'subscribe'           => 'Subscribe',
        'unsubscribe'         => 'Unsubscribe',
        // Комментарий
        'comment'             => array(
            'deleted'          => 'Comment was deleted',
            'restore'          => 'Restore',
            'reply'            => 'Reply',
            'scroll_to_parent' => 'Reply on',
            'scroll_to_child'  => 'Back to reply',
            'target_author'    => 'Author',
            'url'              => 'Comment link',
            'edit_info'        => 'Comment edited',
        ),
        // Сворачивание
        'folding'             => array(
            'fold'       => 'Collapse',
            'unfold'     => 'Expand',
            'fold_all'   => 'Collapse all',
            'unfold_all' => 'Expand all',
        ),
        // Форма добавления
        'form'                => array(
            'title' => 'Leave a comment',
        ),
        // Всплывающие сообщения
        'notices'             => array(
            'success_restore' => 'Comment restored',
        ),
        // Сообщения
        'alerts'              => array(
            'unregistered' => 'Only registered users are allowed to comment'
        ),
    ),
    /**
     * Пополняемый список пользователей
     */
    'user_list_add' => array(
        // Форма добавления
        'form'    => array(
            // Поля
            'fields' => array(
                'add' => array(
                    'label' => '___user.users___',
                ),
            ),
        ),
        // Всплывающие сообщения
        'notices' => array(
            'success_add'         => 'Successfuly added user %%login%%',
            'error_already_added' => 'User %%login%% is already in the list',
            'error_self'          => 'Not able to add yourself',
        ),
    ),
    /**
     * Мэйлы
     */
    'emails'        => array(
        'common'                => array(
            'comment_text' => 'Comment body',
            'regards'      => 'Sincerely, site admins',
        ),
        // Приглашение в закрытый блог
        'blog_invite_new'       => array(
            'subject' => 'You have been invited to join the blog',
            'text'    =>
                'User <a href="%%user_url%%">%%user_name%%</a>
				is inviting you to join the blog <a href="%%blog_url%%">%%blog_name%%</a>.
				<br><br>
				<a href="%%invite_url%%">View the invite</a>
				<br>
				Do not forget to authorize!',
        ),
        // Оповещение о новом комментарии в топике
        'comment_new'           => array(
            'subject'     => 'New comment',
            'text'        =>
                'User <a href="%%user_url%%">%%user_name%%</a>
				just added a new comment to the topic <b>%%topic_name%%</b>,
				follow <a href="%%comment_url%%">this link</a> to read it
				<br><br>
				%%comment_text%%
				%%unsubscribe%%',
            'unsubscribe' => '<a href="%%unsubscribe_url%%">STOP receiving new comments from this topic (Unsubscribe)</a>'
        ),
        // Оповещение об ответе на комментарий
        'comment_reply'         => array(
            'subject' => 'You have got a reply',
            'text'    =>
                'User <a href="%%user_url%%">%%user_name%%</a>
				just replied on your comment in the topic <b>%%topic_name%%</b>,
				follow <a href="%%comment_url%%">this link</a> to read it
				<br><br>
				%%comment_text%%'
        ),
        // Приглашение на сайт
        'invite'                => array(
            'subject' => 'You are invited',
            'text'    =>
                'User <a href="%%user_url%%">%%user_name%%</a>
				just sent you invite for registration on %%website_name%%
				<br><br>
				Go to <a href="%%ref_link%%">%%ref_link%%</a> link</a> to register.'
        ),
        // Повторная активация
        'reactivation'          => array(
            'subject' => 'Reactivation request',
            'text'    =>
                'You have requested another reactivation on <a href="%%website_url%%">%%website_name%%</a>
				<br><br>
				Account activation link:
				<br>
				<a href="%%activation_url%%">%%activation_url%%</a>'
        ),
        // Регистрация
        'registration'          => array(
            'subject' => 'Registration',
            'text'    =>
                'You have been registered on <a href="%%website_url%%">%%website_name%%</a>
				<br><br>
				Username: <b>%%user_name%%</b><br>'
        ),
        // Подтверждение регистрации
        'registration_activate' => array(
            'subject' => 'Registration confirmation',
            'text'    =>
                'You have been registered on <a href="%%website_url%%">%%website_name%%</a>
				<br><br>
				Your username: <b>%%user_name%%</b>
				<br><br>
				To complete registration you need to follow the activation link:<br>
				<a href="%%activation_url%%">%%activation_url%%</a>'
        ),
        // Смена пароля
        'reminder_code'         => array(
            'subject' => 'Password recovery',
            'text'    =>
                'If you would like to change your password on <a href="%%website_url%%">%%website_name%%</a>, please follow the link:<br>
				<a href="%%recover_url%%">%%recover_url%%</a>'
        ),
        // Новый пароль
        'reminder_password'     => array(
            'subject' => 'New password',
            'text'    =>
                'Your new password: <b>%%password%%</b>'
        ),
        // Оповещение о новом сообщении в диалоге
        'talk_comment_new'      => array(
            'subject' => 'You have got a new message comment',
            'text'    =>
                'User <a href="%%user_url%%">%%user_name%%</a>
				just replied on <b>%%talk_name%%</b>,
				follow <a href="%%message_url%%">this link</a> to read the message
				<br><br>
				%%message_text%%
				<br><br>
				Do not forget to authorize!'
        ),
        // Оповещение о новом сообщении
        'talk_new'              => array(
            'subject' => 'You`ve got a new message',
            'text'    =>
                'You`ve got a new message from <a href="%%user_url%%">%%user_name%%</a>,
				it may be read at <a href="%%talk_url%%">here</a>
				<br><br>
				Message title: <b>%%talk_name%%</b><br>
				%%talk_text%%
				<br><br>
				Do not forget to authorize!'
        ),
        // Оповещение о новом топике
        'topic_new'             => array(
            'subject' => 'New post in blog',
            'text'    =>
                'User <a href="%%user_url%%">%%user_name%%</a>
				sumitted a new post &mdash; <a href="%%topic_url%%">%%topic_name%%</a>
				in the blog <b>%%blog_name%%</b>'
        ),
        // Смена почты
        'user_changemail'       => array(
            'subject' => 'E-mail change confirmation',
            'text'    =>
                'You`ve sent an e-mail change request for the user <a href="%%user_url%%">%%user_name%%</a>
				on <a href="%%website_url%%">%%website_name%%</a>.
				<br><br>
				Old e-mail: <b>%%mail_old%%</b><br>
				New e-mail: <b>%%mail_new%%</b>
				<br><br>
				To confirm change, please follow the link :<br>
				<a href="%%change_url%%">%%change_url%%</a>'
        ),
        // Жалоба
        'user_complaint'        => array(
            'subject' => 'User complaint',
            'text'    =>
                'User <a href="%%user_url%%">%%user_name%%</a>
				complaint on <a href="%%user_target_url%%">%%user_target_url%%</a>.
				<br><br>
				<b>Reason:</b> %%complaint_title%%<br>
				%%complaint_text%%',
            'more'    => 'Details'
        ),
        // Заявка в друзья
        'user_friend_new'       => array(
            'subject' => 'You`ve been added as a frined',
            'text'    =>
                'User <a href="%%user_url%%">%%user_name%%</a>
				<br><br>
				<em>%%text%%</em>
				<br><br>
				<a href="%%url%%">Read a request</a>
				<br><br>
				Do not forget to authorize!'
        ),
        // Новое сообщение на стене
        'wall_new'              => array(
            'subject' => 'New message on your wall',
            'text'    =>
                'User <a href="%%user_url%%">%%user_name%%</a>
				had added a message on <a href="%%wall_url%%">your wall</a>
				<br><br>
				Message:<br>
				%%message_text%%'
        ),
        // Ответ на сообщение на стене
        'wall_reply'            => array(
            'subject' => 'Reply to your wall message',
            'text'    =>
                'User <a href="%%user_url%%">%%user_name%%</a>
				has replied <a href="%%wall_url%%">on the wall</a>
				<br><br>
				<b>Your message:</b><br>
				<em>%%message_parent_text%%</em>
				<br><br>
				Users reply:<br>
				<em>%%message_text%%</em>'
        )
    ),
    /**
     * Стена
     */
    'wall'          => array(
        'title'   => 'Wall',
        // Форма
        'form'    => array(
            // Поля
            'fields' => array(
                'text' => array(
                    'placeholder'       => 'Write on the wall',
                    'placeholder_reply' => 'Reply...',
                ),
            ),
        ),
        // Всплывающие сообщения
        'notices' => array(
            'error_add_pid'        => 'Impossible to reply to this message',
            'error_add_time_limit' => 'You`re not allowed to write so often'
        ),
        // Сообщения
        'alerts'  => array(
            'unregistered' => 'Only registered and authorized users are allowed to write on the wall'
        ),
    ),
    /**
     * Авторизация
     */
    'auth'          => array(
        'authorization' => 'Authorization',
        'logout'        => 'Logout',
        // Вход
        'login'         => array(
            'title'   => 'Login',
            'form'    => array(
                // Поля
                'fields' => array(
                    'login'    => array(
                        'label' => 'Username or E-mail'
                    ),
                    'remember' => array(
                        'label' => 'Remember me'
                    ),
                    'submit'   => array(
                        'text' => 'Login'
                    )
                )
            ),
            // Всплывающие сообщения
            'notices' => array(
                'error_login'         => 'Wrong username (e-mail) and/or passwod.',
                'error_not_activated' => 'Your account requires activation. <br/> <a href="%%reactivation_path%%">Re-send activation link</a>'
            ),
        ),
        // Повторный запрос активации
        'reactivation'  => array(
            'title'   => 'Re-send activation',
            'form'    => array(
                // Поля
                'fields' => array(
                    'mail'   => array(
                        'label' => 'Your e-mail'
                    ),
                    'submit' => array(
                        'text' => 'Get the activation link'
                    )
                )
            ),
            // Всплывающие сообщения
            'notices' => array(
                'success' => 'Activation link was sent to your e-mail address.',
            )
        ),
        // Сброс пароля
        'reset'         => array(
            'title'   => 'Password recovery',
            'form'    => array(
                // Поля
                'fields' => array(
                    'mail'   => array(
                        'label' => 'Your e-mail'
                    ),
                    'submit' => array(
                        'text' => 'Get the password change link'
                    )
                )
            ),
            // Всплывающие сообщения
            'notices' => array(
                'success_send_password' => 'New password was sent to your e-mail',
                'success_send_link'     => 'Password change link was sent to your e-mail',
            ),
            // Сообщения
            'alerts'  => array(
                'error_bad_code' => 'Wrong password recovery code.',
            )
        ),
        // Регистрация по приглашению
        'invite'        => array(
            'title'  => 'Registration by invitation',
            'form'   => array(
                // Поля
                'fields' => array(
                    'code'   => array(
                        'label' => 'Invitation code'
                    ),
                    'submit' => array(
                        'text' => 'Confirm code'
                    )
                ),
            ),
            // Сообщения
            'alerts' => array(
                'error_code' => 'Incorrect invitation code',
            )
        ),
        // Регистрация
        'registration'  => array(
            'title'   => 'Registration',
            'form'    => array(
                // Поля
                'fields' => array(
                    'password_confirm' => array(
                        'label' => 'Confirm password'
                    ),
                    'submit'           => array(
                        'text' => 'Register'
                    )
                )
            ),
            'confirm' => array(
                'title' => 'Account activation',
                'text'  => 'You`re almost done! An account has to be activated. Details were sent to the provided e-mail address.'
            ),
            // Сообщения
            'notices' => array(
                'already_registered'   => 'You`re already registered and the account is activated!',
                'success'              => 'Congrats with successful registration',
                'success_activate'     => 'Congrats! Your account is activated now.',
                'error_login'          => 'Unaccepted username. Must be 3 to 30 characters long.',
                'error_login_used'     => 'This username is already taken.',
                'error_mail_used'      => 'This e-mail is already in use.',
                'error_reactivate'     => 'Your account is already active',
                'error_code'           => 'Wrong activation code!',
                'error_password_equal' => 'Passwords do not match',
            ),
        ),
        // Общие лэйблы
        'labels'        => array(
            'login'         => 'Username',
            'password'      => 'Password',
            'captcha'       => 'Input captcha',
            'captcha_field' => 'Captcha',
        ),
        // Общие всплывающие сообщения
        'notices'       => array(
            'error_bad_email' => 'User with the provided e-mail is not found',
        ),
    ),
    /**
     * Активность
     */
    'activity'      => array(
        'title'        => 'Activity',
        // Навигация
        'nav'          => array(
            'all'      => 'All',
            'personal' => 'Personal'
        ),
        // Настройки
        'settings'     => array(
            'title'   => 'Event settings',
            'note'    => 'Select events to track',
            'options' => array(
                'add_wall'           => 'New wall message',
                'add_topic'          => 'New post',
                'add_comment'        => 'New comment',
                'add_blog'           => 'New blog',
                'vote_topic'         => 'Vote for a post',
                'vote_comment_topic' => 'Vote for a comment',
                'vote_blog'          => 'Vote for a blog',
                'vote_user'          => 'Vote for a user',
                'add_friend'         => 'Add to friends',
                'join_blog'          => 'Join the blog',
            )
        ),
        // Пользователи
        'users'        => array(
            'title' => 'Users',
            'note'  => 'Select users to track their activity',
        ),
        'events'       => array(
            'add_wall_male'             => 'added message to the %%user%%`s <a href="%%url%%">wall</a>',
            'add_wall_female'           => 'added message to the %%user%%`s <a href="%%url%%">wall</a>',
            'add_wall_self_male'        => 'added message on his <a href="%%url%%">wall</a>',
            'add_wall_self_female'      => 'added message on her <a href="%%url%%">wall</a>',
            'add_topic_male'            => 'added new post %%topic%%',
            'add_topic_female'          => 'added new post %%topic%%',
            'add_comment_male'          => 'commented in %%topic%%',
            'add_comment_female'        => 'commented in %%topic%%',
            'add_blog_male'             => 'added new blog %%blog%%',
            'add_blog_female'           => 'added new blog %%blog%%',
            'vote_topic_male'           => 'voted for post %%topic%%',
            'vote_topic_female'         => 'voted for post %%topic%%',
            'vote_comment_topic_male'   => 'voted for %%topic%% comment',
            'vote_comment_topic_female' => 'voted for %%topic%% comment',
            'vote_blog_male'            => 'voted for a blog %%blog%%',
            'vote_blog_female'          => 'voted for a blog %%blog%%',
            'vote_user_male'            => 'voted for a user %%user%%',
            'vote_user_female'          => 'voted for a user %%user%%',
            'join_blog_male'            => 'joined the blog %%blog%%',
            'join_blog_female'          => 'joined the blog %%blog%%',
            'add_friend_male'           => 'added user %%user%% to friends',
            'add_friend_female'         => 'added user %%user%% to friends'
        ),
        // Блок с последними событиями
        'block_recent' => array(
            'title'          => '___activity.title___',
            'topics'         => 'Posts',
            'topics_empty'   => '___common.empty___',
            'comments'       => 'Comments',
            'comments_empty' => '___common.empty___',
            'feed'           => 'RSS',
        ),
        // Сообщения
        'notices'      => array(
            'error_already_subscribed' => 'You`re already subscribed on this user',
        )
    ),
    /**
     * Лента
     */
    'feed'          => array(
        'title' => 'News feed',
        // Блоги
        'blogs' => array(
            'title' => 'Blogs',
            'note'  => 'Select blogs you would like to follow',
            'empty' => 'You didn`t select any blogs to follow'
        ),
        // Пользователи
        'users' => array(
            'title' => 'Users',
            'note'  => 'Add users you would like to follow'
        )
    ),
    /**
     * Топик
     */
    'topic'         => array(
        'topics'       => 'Posts',
        'topic_plural' => 'post;posts;posts',
        'drafts'       => 'Draft',
        'read_more'    => 'Read further',
        'author'       => 'Author',
        'tags'         => '___tags.tags___',
        'share'        => 'Share',
        'is_draft'     => 'Post is in drafts',
        // Навигация
        'nav'          => array(
            'drafts'    => 'Drafts', // TODO: Remove duplication
            'published' => 'Published'
        ),
        'content_type' => array(
            'states'  => array(
                'active'     => 'Active',
                'not_active' => 'Inactive',
                'wrong'      => 'Unknown status',
            ),
            'notices' => array(
                'error_code' => 'Type with the same code already exists',
            ),
        ),
        // Форма добавления
        'add'          => array(
            'title'   => array(
                'add'  => 'Add new post',
                'edit' => 'Edit post',
            ),
            // Поля
            'fields'  => array(
                'blog'            => array(
                    'label'           => 'Select the blogs',
                    'placeholder'     => 'Select the blogs',
                    'note'            => 'You have to join the blog in order to submit new posts in it.',
                    'option_personal' => 'My personal blog',
                ),
                'title'           => array(
                    'label' => 'Title'
                ),
                'slug'            => array(
                    'label' => 'URL',
                    'note'  => 'Optional. May be automaticaly created or you can choose one.'
                ),
                'text'            => array(
                    'label' => 'Content'
                ),
                'tags'            => array(
                    'label' => '___tags.tags___',
                    'note'  => 'Tags must be comma-separated: tiger, maine coon, google'
                ),
                'forbid_comments' => array(
                    'label' => 'Block comments',
                    'note'  => 'If selected, no comments will be allowed in this post'
                ),
                'publish_index'   => array(
                    'label' => 'Force to main page',
                    'note'  => 'If selected, post will be forced to the main page (available for admins only)'
                ),
                'skip_index'      => array(
                    'label' => 'Force to SKIP the main page',
                    'note'  => 'If selected, post will be forced to SKIP the main page (available for admins only)'
                ),
            ),
            // Кнопки
            'button'  => array(
                'publish'       => 'Submit',
                'update'        => 'Save changes',
                'save_as_draft' => 'Save as a draft',
                'mark_as_draft' => 'Move to drafts',
            ),
            // Сообщения
            'notices' => array(
                'error_blog_not_found'   => 'Selected blog does not exist',
                'error_blog_max_count'   => 'Maximum amount of blogs reached: %%count%%',
                'error_blog_not_allowed' => 'You`re not allowed to post in this blog',
                'error_text_unique'      => 'You`ve already created a post with the same content',
                'error_type'             => 'Wrong post type', // TODO: Remove?
                'error_slug'             => 'Post URL must be provided',
                'error_favourite_draft'  => 'Drafts are not allowed to favourites',
                'time_limit'             => 'You`re not allowed to create posts so often',
                'rating_limit'           => 'Not enough rating to create a post',
                'update_complete'        => 'Updated successfully',
                'create_complete'        => 'Created successfully',
            )
        ),
        // Комментарии
        'comments'     => array(
            // Сообщения
            'notices' => array(
                'error_text'  => 'Comment must be 2 to 3000 characters long, all tags should be valid',
                'acl'         => 'You don`t have enough rating to submit a comment',
                'limit'       => 'You`re not allowed to submit comments so often',
                'not_allowed' => 'No comments are allowed by the author',
                'spam'        => 'Stop! Spam!',
            )
        ),
        // Блоки
        'blocks'       => array(
            'tip' => array(
                'title' => 'Advice',
                'text'  => '<strong>Tag &lt;cut&gt; is used to shorten long posts</strong>, by hiding a part of the content .',
            )
        )
    ),
    /**
     * Пользователь
     * !user
     */
    'user'          => array(
        'user'              => 'User',
        'users'             => 'Users',
        'rating'            => '___vote.rating___',
        'date_last_session' => 'Last visit',
        'date_registration' => 'Registration date',
        // Действия
        'actions'           => array(
            'send_message' => '___talk.send_message___',
            'follow'       => 'Subscribe',
            'unfollow'     => 'Unsubscribe',
            'report'       => '___report.report___',
        ),
        // Действия
        'choose'            => array(
            'label'  => '___user.users___',
            'choose' => 'Select from friend list',
        ),
        // Пол
        'gender'            => array(
            'gender' => 'Gender',
            'male'   => 'Male',
            'female' => 'Female',
            'men'    => 'Men',
            'women'  => 'Women',
            'none'   => 'Not provided'
        ),
        // Статус
        'status'            => array(
            'online'            => 'Online',
            'offline'           => 'Offline',
            'was_online_male'   => 'Was online %%date%%',
            'was_online_female' => 'Was online %%date%%'
        ),
        // Жалоба
        'report'            => array(
            'types' => array(
                'spam'    => 'Spam',
                'obscene' => 'Obscene',
                'other'   => 'Other'
            )
        ),
        // Друзья
        'friends'           => array(
            'title'    => 'Friends',
            'add'      => 'Add to friends',
            'remove'   => 'Remove from friends',
            'rejected' => 'Request rejected',
            'sent'     => 'Request sent',
            // Статусы
            'status'   => array(
                'notfriends' => '___user.friends.add___',
                'added'      => '___user.friends.remove___',
                'pending'    => '___user.friends.status.notfriends___',
                'rejected'   => '___user.friends.rejected___',
                'sent'       => '___user.friends.sent___',
                'linked'     => '___user.friends.status.notfriends___',
            ),
            // Форма добавления в друзья
            'form'     => array(
                'title'  => '___user.friends.add___',
                'fields' => array(
                    'text'   => array(
                        'label' => 'Name',
                    ),
                    'submit' => array(
                        'text' => '___common.send___',
                    )
                ),
            ),
            // Сообщения
            'messages' => array(
                'offer'   => array(
                    'title' => 'User %%login%% is inviting you to be friends',
                    'text'  => "User %%login%% is inviting you to be friends.<br/><br/>%%user_text%%<br/><br/><a href='%%accept_path%%'>Accept</a> - <a href='%%reject_path%%'>Reject</a>",
                ),
                'accept'  => array(
                    'title' => 'Your request is accepted',
                    'text'  => 'User %%login%% has accepted you as a friend',
                ),
                'reject'  => array(
                    'title' => 'Your request is rejected',
                    'text'  => 'User %%login%% has rejected your friendship request',
                ),
                'deleted' => array(
                    'title' => 'You`ve been removed from friends',
                    'text'  => '%%login%% is not your friend anymore',
                ),
            ),
            'notices'  => array(
                'add_success'        => 'You`ve got a new friends',
                'remove_success'     => 'Not your friend anymore',
                'not_found'          => 'Friend not found!', // TODO: Remove?
                'already_exist'      => 'This user is already your friend',
                'rejected'           => 'This user has rejected your friendship',
                'time_limit'         => 'Too many requests, please try again later',
                'offer_not_found'    => 'Request not found', // TODO: Remove?
                'offer_already_done' => 'Request already processed',
            )
        ),
        // Поиск
        'search'            => array(
            'title'        => 'Search by users',
            'placeholder'  => 'Search by username',
            'result_title' => 'Found %%count%% user;Found %%count%% users;Found %%count%% users',
            'form'         => array(
                'is_online' => 'Users online',
                'gender'    => array(
                    'any'    => 'Any',
                    'male'   => 'Male',
                    'female' => 'Female'
                )
            )
        ),
        // Публикации
        'publications'      => array(
            'title' => 'Posts',
            // Меню
            'nav'   => array(
                'topics'   => '___topic.topics___',
                'comments' => '___comments.title___',
                'notes'    => 'Notes'
            ),
        ),
        // Избранное
        'favourites'        => array(
            'title' => '___favourite.favourite___',
            // Меню
            'nav'   => array(
                'topics'   => '___topic.topics___',
                'comments' => '___comments.title___'
            ),
        ),
        // Профиль
        'profile'           => array(
            'title'           => 'Profile',
            'social_networks' => 'Social networks',
            'contact'         => 'Contacts',
            // Меню
            'nav'             => array(
                'info'         => '___user.profile.title___',
                'wall'         => '___wall.title___',
                'publications' => '___user.publications.title___',
                'favourite'    => '___favourite.favourite___',
                'friends'      => '___user.friends.title___',
                'activity'     => '___activity.title___',
                'messages'     => '___talk.title___',
                'settings'     => 'Settings',
            ),
            'about'           => array(
                'title' => 'About'
            ),
            'personal'        => array(
                'title'         => 'Personal',
                'birthday'      => 'Birthday',
                'place'         => 'Location',
                'gender'        => '___user.gender.gender___',
                'gender_male'   => '___user.gender.male___',
                'gender_female' => '___user.gender.female___',
            ),
            'activity'        => array(
                'title'         => '___activity.title___',
                'blogs_joined'  => 'Joined blogs',
                'blogs_created' => 'Created blogs',
                'blogs_admin'   => 'Is an admin of',
                'blogs_mod'     => 'Is a moderator of',
                'invited_by'    => 'Invited by',
                'invited'       => 'Invited',
            )
        ),
        // Статистика
        'stats'             => array(
            'title'      => 'Stats',
            'all'        => 'Total users',
            'active'     => 'Active',
            'not_active' => 'Not active',
            'men'        => '___user.gender.men___',
            'women'      => '___user.gender.women___',
            'none'       => '___user.gender.none___'
        ),
        // Настройки
        'settings'          => array(
            'title'   => 'Settings',
            // Меню
            'nav'     => array(
                'profile' => '___user.profile.title___',
                'account' => 'Account',
                'tuning'  => 'Site settings',
                'invites' => 'Invites',
            ),
            // Настройки профиля
            'profile' => array(
                'generic' => 'Basic information',
                'contact' => '___user.profile.contact___',
                'fields'  => array(
                    'name'     => array(
                        'label' => 'Name',
                    ),
                    'sex'      => array(
                        'label' => '___user.gender.gender___',
                    ),
                    'birthday' => array(
                        'label' => '___user.profile.personal.birthday___',
                    ),
                    'place'    => array(
                        'label' => '___user.profile.personal.place___',
                    ),
                    'about'    => array(
                        'label' => '___user.profile.about.title___',
                    ),
                ),
                'notices' => array(
                    'error_max_userfields' => 'You can`t add over %%count%% contact details'
                ),
            ),
            // Настройки аккаунта
            'account' => array(
                'account'       => 'Account settings',
                'password'      => 'Password',
                'password_note' => 'Leave fields blank if you are not going to change the passwords.',
                'fields'        => array(
                    'email'            => array(
                        'note'    => 'Your real e-mail. Activation code will be sent there',
                        'notices' => array(
                            'error_used'         => 'This email is already in use',
                            'change_from_notice' => 'A confirmation was sent to your OLD email address',
                            'change_to_notice'   => 'Thank you! <br/> A confirmation request was sent to your NEW email address .',
                            'change_ok'          => 'Your email is changed to <b>%%mail%%</b>',
                        )
                    ),
                    'password'         => array(
                        'label'   => '___auth.labels.password___',
                        'notices' => array(
                            'error' => 'Wrong password',
                        )
                    ),
                    'password_new'     => array(
                        'label'   => 'New password',
                        'notices' => array(
                            'error' => 'Password must be at least 5 characters long',
                        )
                    ),
                    'password_confirm' => array(
                        'label'   => '___auth.registration.form.fields.password_confirm.label___',
                        'notices' => array(
                            'error' => 'Passwords do not match',
                        )
                    ),
                ),
            ),
            // Настройки сайта
            'tuning'  => array(
                'email_notices' => 'E-mail notices',
                'general'       => 'General settings',
                'fields'        => array(
                    'new_topic'     => 'New blog post',
                    'new_comment'   => 'New post comment',
                    'new_talk'      => 'New private message',
                    'reply_comment' => 'New comment reply',
                    'new_friend'    => 'New friendship request',
                    'timezone'      => array(
                        'label' => 'Time zone'
                    ),
                )
            ),
            // Инвайты
            'invites' => array(
                'note'          => 'You may invite your friends to join the community. To do this, just input their e-mails and submit.',
                'available'     => 'Invites available',
                'available_no'  => 'You don`t have any invites available',
                'used'          => 'Users invited',
                'used_empty'    => 'no',
                'referral_link' => 'Your personal ref URL',
                'many'          => 'a lot',
                'fields'        => array(
                    'email'  => array(
                        'label' => 'Invite user',
                        'note'  => 'This e-mail will be used to send invitation to',
                    ),
                    'submit' => array(
                        'text' => 'Send invitation',
                    ),
                ),
                'notices'       => array(
                    'success' => 'Invitation sent'
                )
            ),
        ),
        'photo'             => array(
            'crop_avatar' => array(
                'title' => 'Avatar selection',
                'desc'  => 'Select square area for avatar.',
            ),
            'crop_photo'  => array(
                'title'  => 'Your photo',
                'desc'   => 'Square zone should be selected to display in your profile.',
                'submit' => 'Save and continue',
            ),
            'actions'     => array(
                'change_photo'  => 'Change photo',
                'upload_photo'  => 'Upload photo',
                'change_avatar' => 'Change avatar',
                'remove'        => '___common.remove___'
            )
        ),
        // Блоки
        'blocks'            => array(
            'cities'    => array(
                'title' => 'Cities'
            ),
            'countries' => array(
                'title' => 'Countries'
            )
        ),
        // Сообщения
        'notices'           => array(
            'empty'           => '___common.empty___',
            'not_found'       => 'User <b>%%login%%</b> is not found',
            'not_found_by_id' => 'User <b>#%%id%%</b> not found'
        ),
    ),
    /**
     * Поля
     */
    'field'         => array(
        'email'       => array(
            'label'   => 'E-mail',
            'notices' => array(
                'error' => 'Invalid e-mail',
            ),
        ),
        'geo'         => array(
            'select_country' => 'Select country',
            'select_region'  => 'Select region',
            'select_city'    => 'Select city',
        ),
        'upload_area' => array(
            'label' => 'Drag files here or click to pick the files',
        ),
        'category'    => array(
            'label' => 'Category'
        ),
    ),
    /**
     * Категории
     */
    'category'      => array(
        'notices' => array(
            'validate_require'   => 'Must pick a category',
            'validate_count'     => 'Amount of categories must be within %%min%% to %%max%%',
            'validate_children'  => 'Only child categories are allowed for selection',
            'validate_recursion' => 'Category can not be recursive',
            'validate_parent'    => 'Wrong parent category',
            'validate_wrong'     => 'Wrong category',
        ),
    ),
    /**
     * Кастомные поля
     */
    'property'      => array(
        'video'   => array(
            'preview' => 'Video preview',
            'watch'   => 'Watch'
        ),
        'image'   => array(
            'empty' => 'No image'
        ),
        'file'    => array(
            'forbidden' => 'You must be authorized to access the file',
            'downloads' => 'Downloads',
            'empty'     => 'No file'
        ),
        'notices' => array(
            'validate_type'                   => 'Wrong type',
            'validate_code'                   => 'Code must be unique',
            'validate_value_date_future'      => 'date may not be from the future',
            'validate_value_date_past'        => 'date may not be from the past',
            'validate_value_file_empty'       => 'Pick a file',
            'validate_value_file_upload'      => 'An error occured during file upload',
            'validate_value_file_size_max'    => 'Max file size (%%size%% Kb) limit reached',
            'validate_value_file_type'        => 'Unaccepted file type. Accepted types are %%types%%',
            'validate_value_image_wrong'      => 'File is not an image',
            'validate_value_image_width_max'  => 'Accepted Max image width is %%size%%px',
            'validate_value_image_height_max' => 'Accepted Max image height is %%size%%px',
            'validate_value_select_max'       => 'You can select up to %%count%% elements',
            'validate_value_select_min'       => 'You should select more than %%count%% elements',
            'validate_value_select_wrong'     => 'Check the right elements are selected',
            'validate_value_select_only_one'  => 'Only one element is permitted',
            'validate_value_video_wrong'      => 'Fix the video link: YouTube, Vimeo',
            'validate_value_wrong'            => 'Form "%%field%%": ',
            'validate_value_wrong_base'       => 'wrong value',
            'create_error'                    => 'An error occured while adding new form field',
        ),
    ),
    /**
     * Админка
     */
    'admin'         => array(
        'title'                => 'Admin panel',
        'items'                => array(
            'plugins' => '___admin.plugins.title___',
        ),
        'install_plugin_admin' => 'Install advanced admin-panel',
        // Страница администрирования плагинов
        'plugins'              => array(
            'title'   => 'Manage plugins',
            'plugin'  => array(
                'author'       => 'Author',
                'version'      => 'Version',
                'url'          => 'Web-site',
                'activate'     => 'Activate',
                'deactivate'   => 'Deactivate',
                'settings'     => 'Settings',
                'remove'       => '___common.remove___',
                'apply_update' => 'Apply update',
            ),
            // Сообщения
            'notices' => array(
                'unknown_action'              => 'Unknown action selected',
                'action_ok'                   => 'Successfully complete',
                'activation_overlap'          => 'Plugins conflict. Resource %%resource%% is reassigned to %%delegate%% by %%plugin%%.',
                'activation_overlap_inherit'  => 'Plugins conflict. Resource %%resource%% is used as a child for %%plugin%%.',
                'activation_file_not_found'   => 'Plugin file not found',
                'activation_file_write_error' => 'Missing written permissions for the plugin',
                'activation_version_error'    => 'LiveStreet core %%version%%+ is required for the plugin',
                'activation_requires_error'   => 'Plugin dependency is required: <b>%%plugin%%</b>',
                'activation_already_error'    => 'Plugin is already activated',
                'deactivation_already_error'  => 'Plugin is not activated',
                'deactivation_requires_error' => 'Another plugin depends on this one, first disable it: <b>%%plugin%%</b>',
            )
        ),
    ),
    /**
     * Жалобы
     */
    'report'        => array(
        'report'  => 'Report',
        'form'    => array(
            'title'  => '___report.report___',
            'fields' => array(
                'type' => array(
                    'label' => 'Reason'
                ),
                'text' => array(
                    'label' => 'Description'
                )
            ),
            'submit' => '___common.send___'
        ),
        'notices' => array(
            'target_error' => 'Wrong object id', // TODO: Remove?
            'error_type'   => 'Wrong report type', // TODO: Remove?
            'success'      => 'Report is sent',
        )
    ),
    /**
     * Загрузка изображений
     */
    'media'         => array(
        'title'       => 'Media files upload',
        'error'       => array(
            'upload'            => 'Was not able to upload the file',
            'not_image'         => 'File is not an image',
            'too_large'         => 'Exceeded maximum file size limit: %%size%%Kb',
            'incorrect_type'    => 'Wrong file type',
            'max_count_files'   => 'Maximum amount of files reached',
            'need_choose_items' => 'Need to choose elements',
        ),
        'nav'         => array(
            'insert'   => 'Upload',
            'photoset' => 'Make a photoset',
            'url'      => 'Image URL',
            'preview'  => 'Preview',
        ),
        'image_align' => array(
            'title'  => 'Align',
            'no'     => 'none',
            'left'   => 'Left',
            'right'  => 'Right',
            'center' => 'Center',
        ),
        'insert'      => array(
            'submit'   => 'Paste',
            'settings' => array(
                'title'  => 'Paste options',
                'fields' => array(
                    'size' => array(
                        'label'    => 'Size',
                        'original' => 'Original'
                    ),
                )
            ),
        ),
        'photoset'    => array(
            'submit'   => 'Create a photoset',
            'settings' => array(
                'title'  => 'Photoset options',
                'fields' => array(
                    'use_thumbs'   => array(
                        'label' => 'Show preview feed'
                    ),
                    'show_caption' => array(
                        'label' => 'Show photo descriptions'
                    )
                )
            ),
        ),
        'url'         => array(
            'fields'        => array(
                'url'   => array(
                    'label' => 'Link',
                ),
                'title' => array(
                    'label' => 'Description',
                ),
            ),
            'submit_insert' => 'Paste a URL',
            'submit_upload' => 'Upload and paste'
        ),
    ),
    /**
     * Теги
     */
    'tags'          => array(
        'tags'       => 'Tags',
        'tag'        => 'Tag',
        'search'     => array(
            'title' => 'Tags search',
            'label' => '___tags.search.title___',
        ),
        'block_tags' => array(
            'nav'   => array(
                'all'       => 'All tags',
                // Теги избранных топиков
                'favourite' => 'My tags',
            ),
            'title' => '___tags.tags___',
            'empty' => '___common.empty___',
        ),
    ),
    /**
     * Персональные теги
     */
    'tags_personal' => array(
        'title' => 'Favourites tags',
        'edit'  => 'change own tags',
    ),
    /**
     * Toolbar
     */
    'toolbar'       => array(
        'scrollup'  => array(
            'title' => 'Up',
        ),
        'topic_nav' => array(
            'next' => 'Next post',
            'prev' => 'Previous post',
        )
    ),
    /**
     * Создание
     */
    'modal_create'  => array(
        'title' => 'Create',
        'items' => array(
            'blog' => 'Blog',
            'talk' => 'Message',
        )
    ),
    /**
     * Обрезка изображения
     */
    'crop'          => array(
        'title' => 'Image crop'
    ),
    /**
     * Экшнбар
     */
    'actionbar'     => array(
        'select' => array(
            'title' => 'Select',
            'menu'  => array(
                'all'      => 'All',
                'deselect' => 'Deselect',
                'invert'   => 'Invert selection',
            ),
        ),
    ),
    /**
     * Управление правами (RBAC)
     */
    'rbac'          => array(
        'permission' => array(
            'create_blog'              => array(
                'title' => 'Blog creation',
                'error' => 'You have no permission to create a blog',
            ),
            'vote_blog'                => array(
                'title' => 'Blog vote',
                'error' => 'You have no permission to vote for a blog',
            ),
            'create_comment_favourite' => array(
                'title' => 'Add to favourites',
                'error' => 'You have no permissions to add comment to favourites',
            ),
            'vote_comment'             => array(
                'title' => 'Comments vote',
                'error' => 'You have no permissions to vote for a comment',
            ),
            'create_invite'            => array(
                'title' => 'Send an invite',
                'error' => 'You have no permissions to send an invite',
            ),
            'create_talk'              => array(
                'title' => 'Send a private message',
                'error' => 'You have no permissions to send a private message',
            ),
            'create_talk_comment'      => array(
                'title' => 'Comment private message',
                'error' => 'Not allowed to comment a private message',
            ),
            'vote_user'                => array(
                'title' => 'Vote for a user',
                'error' => 'You`re not allowed to vote for a user',
            ),
            'create_topic'             => array(
                'title' => 'Create post',
                'error' => 'Not enough permissions to create a post',
            ),
            'create_topic_comment'     => array(
                'title' => 'Post comments',
                'error' => 'Not enough permissions to comment a post'
            ),
            'remove_topic'             => array(
                'title' => 'Delete topic',
                'error' => 'Not enough permissions to delete a post',
            ),
            'vote_topic'               => array(
                'title' => 'Post vote',
                'error' => 'Not enough permissions to vote for a post',
            ),
        ),
        'notices'    => array(
            'validate_group_code'      => 'Code must be unique',
            'validate_group_wrong'     => 'Wrong group',
            'validate_permission_code' => 'Code must be unique',
            'validate_role_code'       => 'Code must be unique',
            'validate_role_recursive'  => 'Recursive roles are forbidden today',
            'validate_role_wrong'      => 'Wrong role',
            'error_not_allow'          => 'You`re not allowed to "%%permission%%"',
        ),
    ),
);