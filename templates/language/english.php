<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
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
 * 
 */
return array(
	/**
	 * Blogs
	 */
	'blogs' => 'Blogs',
	'blogs_title' => "Blog's Title and owner",
	'blogs_readers' => 'Reader',
	'blogs_rating' => 'Rating',
	'blogs_owner' => 'Owner',
	'blogs_personal_title' => "Blogger's name",
	'blogs_personal_description' => 'This is your personal blog.',
	
	'blog_no_topic' => 'No one posted here yet',
	'blog_rss' => 'RSS feed',
	'blog_rating' => 'Rating',
	'blog_vote_count' => 'Votes',
	'blog_about' => 'About this blog',
	/**
	 * Popular Blogs
	 */
	'blog_popular' => 'Popular Blogs',
	'blog_popular_rating' => 'Rating',
	'blog_popular_all' => 'All popular',
	/**
	 * Blog users
	 */
	'blog_user_count' => 'Bloggers',
	'blog_user_administrators' => 'Administrators',
	'blog_user_moderators' => 'Moderators',
	'blog_user_moderators_empty' => 'No moderators here',
	'blog_user_readers' => 'Readers',	
	'blog_user_readers_empty' => 'No readers here',	
	/**
	 * Blog votings
	 */
	'blog_vote_up' => 'Like',
	'blog_vote_down' => "Don't like",
	'blog_vote_count_text' => 'All votes:',
	'blog_vote_error_already' => "You've already voted for this blog!",
	'blog_vote_error_self' => "You can't vote for your own blog!",
	'blog_vote_error_acl' => "You don't have enough rating or power to vote!",
	'blog_vote_error_close' => "You can't vote for a [close blog]",
	'blog_vote_ok' => 'Your vote counted',
	/**
	 * Register/Unregister to the blog
	 */
	'blog_join' => 'Join the blog',	
	'blog_join_ok' => 'You joined this blog',	
	'blog_join_error_invite' => 'You can join this blog only by invitation!',	
	'blog_join_error_self' => "Why would you join this blog? You're already it's owner!",	
	'blog_leave' => 'Unjoin this blog',	
	'blog_leave_ok' => "You're unjoined this blog",	
	/**
	 * Blog menu
	 */
	'blog_menu_all' => 'All',
	'blog_menu_all_good' => 'Good',
	'blog_menu_all_new' => 'New',
	'blog_menu_all_list' => 'All blogs',
	'blog_menu_collective' => 'Groupe blogs',
	'blog_menu_collective_good' => 'Good',
	'blog_menu_collective_new' => 'New',
	'blog_menu_collective_bad' => 'Bad',
	'blog_menu_personal' => 'Personal',
	'blog_menu_personal_good' => 'Good',
	'blog_menu_personal_new' => 'New',
	'blog_menu_personal_bad' => 'Bad',
	'blog_menu_top' => 'TOP',
	'blog_menu_top_blog' => 'Blogs',
	'blog_menu_top_topic' => 'Topics',
	'blog_menu_top_comment' => 'Comments',
	'blog_menu_top_period_24h' => 'For the last 24 hours',
	'blog_menu_top_period_7d' => 'For the last 7 days',
	'blog_menu_top_period_30d' => 'For the last 30 daysй',
	'blog_menu_top_period_all' => 'All',	
	'blog_menu_create' => 'Create blog',
	/**
	 * Create/Edit Blog
	 */
	'blog_edit' => 'Edit',
	'blog_delete' => 'Delete',
	'blog_create' => 'Create new blog',
	'blog_create_acl' => "You don't have enough power to create a blog",
	'blog_create_title' => "Blog's title",
	'blog_create_title_notice' => "Blog's title should be meaningful.",
	'blog_create_title_error' => "Blog's title should be at least 2 and upto 200 characters",
	'blog_create_title_error_unique' => 'Blog with this name already exists',
	'blog_create_url' => "Blog's URL",
	'blog_create_url_notice' => "Blog's URL should consist of latin chars, numbers, hyphen; spaces would be replaced with \"_\". URL should reflect Blog's title. You won't be able to change URL later on.",
	'blog_create_url_error' => "Blog's URL should be at least 2 and upto 50 characters. It should consist of latin chars, numbers and \"-\", \"_\"",
	'blog_create_url_error_badword' => "Blog's URL should differ from:",
	'blog_create_url_error_unique' => 'Blog with this URL already exists',
	'blog_create_description' => "Blog's description",
	'blog_create_description_notice' => 'You can also use HTML markup',
	'blog_create_description_error' => "Blog's description should be at least 10 and upto 3000 characters.",
	'blog_create_type' => "Blog's type",
	'blog_create_type_open' => 'Open',
	'blog_create_type_close' => 'Close',
	'blog_create_type_open_notice' => 'Open — anyone can join this blog, topics viewable by everyone',
	'blog_create_type_close_notice' => "Close — you can join this blog only by invitation from Blog's administrators, topics viewable by joined members only",
	'blog_create_type_error' => "Unknown blog's type",
	'blog_create_rating' => 'Rating restrictions',
	'blog_create_rating_notice' => 'Rating, needed by user to post to this blog',
	'blog_create_rating_error' => 'Rating restrictions should be a numeric value',
	'blog_create_avatar' => 'Avatar',
	'blog_create_avatar_error' => "Can't create avatar",
	'blog_create_avatar_delete' => 'Remove',
	'blog_create_submit' => 'Save',
	'blog_create_submit_notice' => 'Blog will be created after pushing "Save" button',
	/**
	 * Blog Administration
	 */
	'blog_admin' => 'Blog administration',
	'blog_admin_not_authorization' => 'You need to login first.',
	'blog_admin_profile' => 'Profile',
	'blog_admin_users' => 'Users',
	'blog_admin_users_administrator' => 'Administrator',
	'blog_admin_users_moderator' => 'Moderator',
	'blog_admin_users_reader' => 'Reader',
	'blog_admin_users_bun' => 'Banned',
	'blog_admin_users_current_administrator' => 'Current administrator is you!',
	'blog_admin_users_empty' => 'No members in this blog',
	'blog_admin_users_submit' => 'Save',
	'blog_admin_users_submit_notice' => "After pushing \"Save\" button, user's permissions will be saved",
	'blog_admin_users_submit_ok' => 'Permissions saved',
	'blog_admin_users_submit_error' => "Hmm... Something's wrong",
	
	'blog_admin_delete_confirm' => 'Are you sure you want to create blog?',
	'blog_admin_delete_move' => 'Move topics to the blog',
	'blog_delete_clear' => 'Delete topics',
	'blog_admin_delete_success' => 'Blog deleted successfully',
	'blog_admin_delete_not_empty' => "You can't delete blog containing records. Please delete all records first.",
	'blog_admin_delete_move_error' => 'Unable to move topics from the blog which is being deleted',
	'blog_admin_delete_move_personal' => "Can't move topics to personal blog",
	
	'blog_admin_user_add_label' => 'Invite users:',
	'blog_admin_user_invited' => 'List of invitees:',
	'blog_close_show' => "It's a 'close blog', you don't have enought rights to see it's content",
	'blog_user_invite_add_self' => "Can't send invitation to yourself",
	'blog_user_invite_add_ok' => 'Invitation sent to user %%login%%',
	'blog_user_already_invited' => 'Invitation has been sent already to user %%login%%',
	'blog_user_already_exists' => 'User %%login%% already member of this blog',
	'blog_user_already_reject' => 'User %%login%% rejected invitation',
	'blog_user_invite_title' => "Invitation to become a reader of '%%blog_title%% blog'",
	'blog_user_invite_text' => "User %%login%% invites you to become a reader of a 'close' '%%blog_title%% blog'.<br/><br/><a href='%%accept_path%%'>Accept</a> - <a href='%%reject_path%%'>Reject</a>",
	'blog_user_invite_already_done' => 'You already a member of this blog',
	'blog_user_invite_accept' => 'Invitation accepted',
	'blog_user_invite_reject' => 'Invitation rejected',
	'blog_user_invite_readd' => 'Re-add',
	
	/**
	 * Topics
	 */
	'topic_title' => 'Topics',
	'topic_read_more' => 'Read more',
	'topic_date' => 'Date',
	'topic_user' => "Author's  text",
	'topic_time_limit' => "Can't create topics in such a frequent rate",
	'topic_comment_read' => 'Read comments',
	'topic_comment_add' => 'Add comment',
	'topic_comment_add_title' => 'Add comment',
	'topic_comment_add_text_error' => 'Comments should consist of 2 upto 3000 chars of decent content',
	'topic_comment_acl' => "Your rating is too low, you can't add comments yet",
	'topic_comment_limit' => "Can't create comments in such a frequent rate",
	'topic_comment_notallow' => 'Topic\'s owner forbade adding comments',
	'topic_comment_spam' => 'Stop! Spam!',
	'topic_unpublish' => 'Topic is in drafts and unpublished',
	'topic_favourite_add' => 'Add to favourites',
	'topic_favourite_add_ok' => 'Topic added to favourites',
	'topic_favourite_add_no' => 'This topic is not in your favourites',
	'topic_favourite_add_already' => 'This topic is already in your favourites',
	'topic_favourite_del' => 'Remove from favourites',
	'topic_favourite_del_ok' => 'Topic removed from favourites',
	
	'block_stream_comments_all' => 'All comment block',
	'block_stream_topics_all' => 'All topics block',
	'comments_all' => 'All comments',
	/**
	 * Topic menus
	 */
	'topic_menu_add' => 'New',
	'topic_menu_add_topic' => 'Add Topic',
	'topic_menu_add_question' => 'Add question',
	'topic_menu_add_link' => 'Add link',
	'topic_menu_saved' => 'Drafts',
	'topic_menu_published' => 'Published',
	/**
	 * Topic Creation
	 */
	'topic_topic_create' => 'Create topic',
	'topic_topic_edit' => 'Edit topic',
	'topic_create' => 'Write',
	'topic_create_blog' => 'What blog are we publishing in?',
	'topic_create_blog_personal' => 'my personal blog',
	'topic_create_blog_error_unknown' => 'Are you trying to post to unknown blog?',
	'topic_create_blog_error_nojoin' => "You're not a member of this blog!",
	'topic_create_blog_error_noacl' => "You don't have enough power to post to this blog",
	'topic_create_blog_error_noallow' => "You can't write to this blog",
	'topic_create_title' => 'Title',
	'topic_create_title_notice' => 'Title should be meaningful to reflect the meaning of the topic.',
	'topic_create_title_error' => "Topic's title should consist of 2 upto 200 chars",
	'topic_create_text' => 'Text',
	'topic_create_text_notice' => 'Html tags available',
	'topic_create_text_error' => "Topic's text should consist of 2 upto 15000 chars",
	'topic_create_text_error_unique' => "You've already created topic with the same text",
	'topic_create_tags' => 'Tags',
	'topic_create_tags_notice' => 'Tags should be comma separated. i.e: facebook clone, blogs, rating, google, block.',
	'topic_create_tags_error_bad' => 'Check that tags syntax is right',
	'topic_create_tags_error' => "Topic's tags should consist of 2 upto 50 chars with total sum of no more that 500 chars.",
	'topic_create_forbid_comment' => 'forbid comments',
	'topic_create_forbid_comment_notice' => "If you check this option you'll disable commenting in this topic",
	'topic_create_publish_index' => 'force exit to the main page',
	'topic_create_publish_index_notice' => "If you check this option you'll post this topic directly to the main page (option availiable to administrators only)",
	'topic_create_submit_publish' => 'Publish',
	'topic_create_submit_save' => 'Save to drafts',
	'topic_create_submit_preview' => 'Preview',
	'topic_create_submit_notice' => 'Pushing «save to drafts» will save topic to the drafts and will be viewable by you only, showing lock alongside. Push «publish» to make it viewable by everyone.',
	'topic_create_notice' => "Note: <cut> tag shortens long articles, hiding them totally or partially under the link («Read more»). Hidden part is not visible in the blog but will be available on the full Topic's page.",
	'topic_create_error' => "During topic creation we've experienced some technical difficulties. Please try again later.",
	
	'topic_edit' => 'Edit',
	'topic_delete' => 'Delete',
	'topic_delete_confirm' => 'Do you really want to delete this topic?',
	/**
	 * Link-topic
	 */
	'topic_link' => 'Link-topic',
	'topic_link_title' => 'Links',
	'topic_link_title_edit' => 'Edit link',
	'topic_link_title_create' => 'Add link',
	'topic_link_create' => 'Create link-topic',
	'topic_link_edit' => 'Edit link-topic',
	'topic_link_count_jump' => 'Go to link:',
	'topic_link_create_url' => "Link's URL",
	'topic_link_create_url_notice' => 'E.g., http://livestreet.ru/blog/dev_livestreet/113.html',
	'topic_link_create_url_error' => 'Link should consist of 2 upto 200 chars',
	'topic_link_create_text' => 'Short description (500 chars at max., HTML tags forbidden)',
	'topic_link_create_text_notice' => 'HTML tags forbidden',
	'topic_link_create_text_error' => 'Link description should consist of 10 upto 500 chars',
	/**
	 * Poll-topic
	 */
	'topic_question_title' => 'Polls',
	'topic_question_title_edit' => 'Edit poll',
	'topic_question_title_create' => 'Add poll',
	'topic_question_vote' => 'Vote',
	'topic_question_vote_ok' => 'Your vote counted.',
	'topic_question_vote_already' => 'Your vote is been counted already!',
	'topic_question_vote_result' => 'Votes',
	'topic_question_abstain' => 'Abstain',
	'topic_question_abstain_result' => 'Abstained',
	'topic_question_create' => 'Create poll-topic',
	'topic_question_edit' => 'Edit poll-topic',
	'topic_question_create_title' => 'Question',
	'topic_question_create_title_notice' => 'Question should be meaningful to reflect the meaning of the poll.',
	'topic_question_create_title_error' => 'Question should consist of 2 upto 200 chars',
	'topic_question_create_answers' => "Answer variants",	
	'topic_question_create_answers_error' => 'Answer should consist of 1 upto 100 chars',	
	'topic_question_create_answers_error_min' => 'There should be at least 2 answers',	
	'topic_question_create_answers_error_max' => 'Maximium available answers should is 20',	
	'topic_question_create_text' => 'Short description (mx. 500 chars, HTML tags forbidden)',
	'topic_question_create_text_notice' => 'HTML tags forbidden',
	'topic_question_create_text_error' => 'Poll description should be 500 chars or less',
	/**
	 * Topic voting
	 */
	'topic_vote_up' => 'I like this',
	'topic_vote_down' => "Don't like",	
	'topic_vote_error_already' => "You've already voteed for this topic!",
	'topic_vote_error_self' => "You can't vote for your own topic!",
	'topic_vote_error_guest' => 'In order to vote you need to login',
	'topic_vote_error_time' => 'Voting period for this topic is expired!',
	'topic_vote_error_acl' => "You don't have enough rating or power to vote!",
	'topic_vote_no' => 'No one voted yet',
	'topic_vote_ok' => 'Your vote counted',
	'topic_vote_ok_abstain' => 'You\'ve abstained in order to view topic raiting',
	'topic_vote_count' => 'All votes',
	
	/**
	 * Comments
	 */
	'comment_title' => 'Comments',
	'comment_collapse' => 'Collapse comments',
	'comment_expand' => 'Expand comments',
	'comment_goto_parent' => 'Reply to',
	'comment_goto_child' => 'Back to reply',
	'comment_bad_open' => 'Open comment',
	'comment_answer' => 'Reply',
	'comment_delete' => 'Delete',
	'comment_delete_ok' => 'Comment deleted',
	'comment_repair' => 'Restore',
	'comment_repair_ok' => 'Comment restored',
	'comment_was_delete' => 'comment deleted',
	'comment_add' => 'Add',
	'comment_preview' => 'Preview',
	'comment_unregistered' => 'Only registered users can comment.',
	/**
	 * Comment votes
	 */
	'comment_vote_error' => 'Please try to vote later',
	'comment_vote_error_value' => 'You can only vote with +1 or -1!',
	'comment_vote_error_acl' => "You don't have enough rating and power to vote!",
	'comment_vote_error_already' => "You've already voted for this comment!",
	'comment_vote_error_time' => "Voting period for this comment has been expired!",
	'comment_vote_error_self' => "You can't vote for your own comment!",
	'comment_vote_error_noexists' => "You're voting for comment wich doesn't exist!",
	'comment_vote_ok' => 'Your vote counted',

	'comment_favourite_add' => 'Add to favoutites',
	'comment_favourite_add_ok' => 'Comment added to favourites',
	'comment_favourite_add_no' => 'This comment is not in your favourites',
	'comment_favourite_add_already' => 'This comment is already in your favourites',
	'comment_favourite_del' => 'Delete from favourites',
	'comment_favourite_del_ok' => 'Comment deleted from favourites',

	
	/**
	 * People
	 */
	'people' => 'People',
	
	
	/**
	 * User
	 */
	'user' => 'User',
	'user_list' => 'Users list',
	'user_list_new' => 'New users',
	'user_list_online_last' => 'Latest logged in users',
	'user_good' => 'Positive',
	'user_bad' => 'Negative',
	'user_privat_messages' => 'Personal messages',
	'user_privat_messages_new' => "You've got new messages",
	'user_settings' => 'Settings',
	'user_settings_profile' => 'Profile',
	'user_settings_tuning' => 'Site',
	'user_login' => 'Login or e-mail',
	'user_login_submit' => 'Login',
	'user_login_remember' => 'Remember me',
	'user_login_bad' => 'Something is wrong! Wrong login (e-mail) or password?.',
	'user_password' => 'Password',
	'user_password_reminder' => 'Password reminder',
	'user_exit_notice' => 'Please come again.',
	'user_authorization' => 'Authorisation',
	'user_registration' => 'Registration',
	'user_write_prvmsg' => 'Write private message',

	'user_friend_add' => 'Add to friends',
	'user_friend_add_ok' => 'You\'ve got a new friend',
	'user_friend_add_self' => 'Your friend is - yourself!',
	'user_friend_del' => 'Remove from friends list',
	'user_friend_del_ok' => 'You\'re no longer friends with this user',
	'user_friend_del_no' => 'Friend not found!',
	'user_friend_offer_reject' => 'Friendship request rejected',
	'user_friend_offer_send' => 'Friendship request sent',	
	'user_friend_already_exist' => 'This user is already your friend',
	'user_friend_offer_title' => 'User %%login%% wants to be your friend',
	'user_friend_offer_text' => "User %%login%% wants to add you to friends list.<br/><br/>%%user_text%%<br/><br/><a href='%%accept_path%%'>Accept</a> - <a href='%%reject_path%%'>Reject</a>",
	'user_friend_add_deleted' => 'This user rejected your friendship offer',
	'user_friend_add_text_label' => 'Introduce yourself :',
	'user_friend_add_submit' => 'Submit',
	'user_friend_add_cansel' => 'Cancel',
	'user_friend_offer_not_found' => 'Request not found',
	'user_friend_offer_already_done' => 'This request processed already',
	'user_friend_accept_notice_title' => 'Your request accepted',
	'user_friend_accept_notice_text' => 'User %%login%% accepted your friendship request',
	'user_friend_reject_notice_title' => 'Your request denied',
	'user_friend_reject_notice_text' => 'User %%login%% rejected your friendship offer',	
	'user_friend_del_notice_title' => 'You\'ve been removed from friendship list',
	'user_friend_del_notice_text' => 'User %%login%% is not your friend anymore',
	
	'user_rating' => 'Rating',
	'user_skill' => 'Power',
	'user_date_last' => 'Last visit',
	'user_date_registration' => 'Registration date',
	'user_empty' => 'No such user',
	'user_stats' => 'Stats',
	'user_stats_all' => 'All users',
	'user_stats_active' => 'Active',
	'user_stats_noactive' => 'Non active',
	'user_stats_sex_man' => 'Males',
	'user_stats_sex_woman' => 'Females',
	'user_stats_sex_other' => 'Gender not specified',
	
	'user_not_found' => 'User <b>%%login%%</b> wasn\'t not found',
	'user_not_found_by_id' => 'User <b>#%%id%%</b> wasn\'t not found',
	
	/**
	 * User's profile menu
	 */
	'people_menu_users' => 'Users',
	'people_menu_users_all' => 'All',
	'people_menu_users_online' => 'Online',
	'people_menu_users_new' => 'New',
	
	/**
	 * Registration
	 */
	'registration_invite' => 'Registration by invitation',
	'registration_invite_code' => 'Invitation code',
	'registration_invite_code_error' => 'Wrong invitation code',
	'registration_invite_check' => 'Check the code',
	'registration_activate_ok' => 'Congratulations! Your account activated.',
	'registration_activate_error_code' => 'Wrong activation code!',
	'registration_activate_error_reactivate' => 'Your account activated already',
	'registration_confirm_header' => 'Account activation',
	'registration_confirm_text' => 'You\'ve almost finished your registration. Now you need to activate your account. Activation instructions sent to e-mail address you\'ve provided during registration.',
	'registration' => 'Registration',
	'registration_is_authorization' => 'Your user\'s been registered and activated already!',
	'registration_login' => 'Login',
	'registration_login_error' => 'Wrong login. It should be from 3 upto 30 chars',
	'registration_login_error_used' => 'This login is already registered in the system',
	'registration_login_notice' => 'Can consist of letter (A-Z a-z), numbers (0-9). It\'s not recommended to use the following char - (_). Login should consist of 3 upto 30 chars.',
	'registration_mail' => 'E-mail',
	'registration_mail_error' => 'Wronf e-mail address',
	'registration_mail_error_used' => 'This e-mail is already registered.',
	'registration_mail_notice' => 'We need your e-mail address to authenticate you during registration.',
	'registration_password' => 'Password',
	'registration_password_error' => 'Wrong password. It should consist of at least 5 chars.',
	'registration_password_error_different' => 'Wrong password verification',
	'registration_password_notice' => 'Password should consist of at least 5 chars and can\'t be the same as your login.',
	'registration_password_retry' => 'Re-enter password',
	'registration_captcha' => 'Re-enter captcha code',
	'registration_captcha_error' => 'Wrong code',
	'registration_submit' => 'Submit registration',
	'registration_ok' => 'Congratulation with successful registration!',
			
	/**
	 * Vote for users
	 */
	'user_vote_up' => 'Like',
	'user_vote_down' => 'Don\'t like',	
	'user_vote_error_already' => 'You\'ve already voted for this yser!',
	'user_vote_error_self' => 'You can\'t vote for yourself!',
	'user_vote_error_guest' => 'You have to logon before voting',	
	'user_vote_error_acl' => 'You don\'t have enough rating and power to vote!',	
	'user_vote_ok' => 'Your vote counted',	
	'user_vote_count' => 'Votes',
	
	/**
	 * User profile menu
	 */
	'user_menu_profile' => 'Profile',
	'user_menu_profile_whois' => 'Whois',
	
	'user_menu_profile_favourites' => 'Favourite topics',
	'user_menu_profile_favourites_comments' => 'Favourite comments',
	
	'user_menu_profile_tags' => 'Tags',
	'user_menu_publication' => 'Publications',
	'user_menu_publication_blog' => 'Blog',
	'user_menu_publication_comment' => 'Comments',
	'user_menu_publication_comment_rss' => 'RSS feed',
	
	/**
	 * Profile
	 */
	'profile_privat' => 'Private',
	'profile_sex' => 'Gender',
	'profile_sex_man' => 'Male',
	'profile_sex_woman' => 'Female',
	'profile_birthday' => 'Date of birth',
	'profile_place' => 'Location',
	'profile_about' => 'About me',
	'profile_site' => 'Site',
	'profile_activity' => 'Activity',
	'profile_friends' => 'Friends',
	'profile_friends_self' => 'Friend of',
	'profile_invite_from' => 'Invited',
	'profile_invite_to' => 'Invitees',
	'profile_blogs_self' => 'Created',
	'profile_blogs_join' => 'Joined to',
	'profile_blogs_moderation' => 'Moderater of',
	'profile_blogs_administration' => 'Administrator of',
	'profile_date_registration' => 'Registered at',
	'profile_date_last' => 'Last visit',
	'profile_social_contacts' => 'Contacts and social services',
	
	
	
	/**
	 * Configs.
	 */
	'settings_profile_edit' => 'Edit profile',
	'settings_profile_name' => 'Name',
	'settings_profile_name_notice' => 'Name should consist of 2 upto 20 chars.',
	'settings_profile_mail' => 'E-mail',
	'settings_profile_mail_error' => 'Wrong e-mail format',
	'settings_profile_mail_error_used' => 'This e-mail is already in use',
	'settings_profile_mail_notice' => 'Your real e-mail address fo notifications.',
	'settings_profile_sex' => 'Gender',
	'settings_profile_sex_man' => 'male',
	'settings_profile_sex_woman' => 'female',
	'settings_profile_sex_other' => 'other',
	'settings_profile_birthday' => 'Date of birth',
	'settings_profile_country' => 'Country',
	'settings_profile_city' => 'City',
	'settings_profile_icq' => 'ICQ',
	'settings_profile_site' => 'Site',
	'settings_profile_site_url' => 'Site\'s URL',
	'settings_profile_site_name' => 'Sites name',
	'settings_profile_about' => 'About me',
	'settings_profile_password_current' => 'Current password',
	'settings_profile_password_current_error' => 'Wrong current password',
	'settings_profile_password_new' => 'New password',
	'settings_profile_password_new_error' => 'Wrong password. It should be 5 chars at least',
	'settings_profile_password_confirm' => 'Re-enter new password',
	'settings_profile_password_confirm_error' => 'Passwords differ. Wrong input.',
	'settings_profile_avatar' => 'Avatar',
	'settings_profile_avatar_error' => 'Can\'t load avatar',
	'settings_profile_avatar_delete' => 'delete',
	'settings_profile_foto' => 'Photo',
	'settings_profile_foto_error' => 'Can\'t load photo',
	'settings_profile_foto_delete' => 'delete',
	'settings_profile_submit' => 'save profile',
	'settings_profile_submit_ok' => 'Profile successfully saved',
	'settings_invite' => 'Invitations management',
	'settings_invite_available' => 'Available',
	'settings_invite_available_no' => 'No invitation available yet',
	'settings_invite_used' => 'Used',
	'settings_invite_mail' => 'Send invitation via e-mail',
	'settings_invite_mail_error' => 'Wrong e-mail format',
	'settings_invite_mail_notice' => 'Invitation will be sent to this e-mail',
	'settings_invite_many' => 'multiple invitations',
	'settings_invite_submit' => 'submit invitation',
	'settings_invite_submit_ok' => 'Invitation sent',
	'settings_tuning' => 'Site configs',
	'settings_tuning_notice' => 'E-mail notifications',
	'settings_tuning_notice_new_topic' => 'on a new topic in the blog',
	'settings_tuning_notice_new_comment' => 'on a new comment',
	'settings_tuning_notice_new_talk' => 'on a new Personal Message',
	'settings_tuning_notice_reply_comment' => 'on reply to comment',
	'settings_tuning_notice_new_friend' => 'On joining to a friends list',
	'settings_tuning_submit' => 'Save configs',
	'settings_tuning_submit_ok' => 'Configs saved',
	
	
	/**
	 * Configs menu
	 */
	'settings_menu' => 'Settings',
	'settings_menu_profile' => 'Profile',
	'settings_menu_tuning' => 'Configs',
	'settings_menu_invite' => 'Invites',
	
	/**
	 * Password restore
	 */
	'password_reminder' => 'Password reminder',
	'password_reminder_email' => 'Your e-mail',
	'password_reminder_submit' => 'Link to reset the password',
	'password_reminder_send_password' => 'New password sent to your e-mail address.',
	'password_reminder_send_link' => 'Link for password reset sent to your e-mail address.',
	'password_reminder_bad_code' => 'Wrong code for password reset.',
	'password_reminder_bad_email' => 'Can\'t find user with this e-mail address',
	
	/**
	 * Panel
	 */
	'panel_b' => 'bold',
	'panel_i' => 'italic',
	'panel_u' => 'underline',
	'panel_s' => 'strike through',
	'panel_url' => 'type link',
	'panel_url_promt' => 'Type link',
	'panel_code' => 'code',
	'panel_video' => 'video',
	'panel_image' => 'image',
	'panel_cut' => 'cut',
	'panel_quote' => 'quote',
	'panel_list' => 'List',
	'panel_list_ul' => 'UL LI',
	'panel_list_ol' => 'OL LI',
	'panel_title' => 'Header',
	'panel_title_h4' => 'H4',
	'panel_title_h5' => 'H5',
	'panel_title_h6' => 'H6',
	
	/**
	 * Blocks
	 */
	'block_city_tags' => 'Cities',
	'block_country_tags' => 'Countries',
	'block_blog_info' => 'Blog description',
	'block_blog_info_note' => 'Note',
	'block_blog_info_note_text' => '<strong>Tag &lt;cut&gt; shortens long articles</strong>, hiding them totally or partially under the link («read more»). Hidden part is not visible in the blog but will be available on the full Topic\'s page.',
	'block_blogs' => 'Blogs',
	'block_blogs_top' => 'Top',
	'block_blogs_join' => 'Blogs I\'ve joined',
	'block_blogs_join_error' => 'You\'re not a member of any group blogs',
	'block_blogs_self' => 'My blogs',
	'block_blogs_self_error' => 'You don\'t have group blogs',
	'block_blogs_all' => 'All blogs',
	'block_stream' => 'Live',
	'block_stream_topics' => 'Publications',
	'block_stream_topics_no' => 'No topics.',
	'block_stream_comments' => 'Comments',
	'block_stream_comments_no' => 'No comments.',
	'block_stream_comments_all' => 'All comments',
	
	'block_friends' => 'Friends',
	'block_friends_check' => 'Check all',
	'block_friends_uncheck' => 'Uncheck',
	'block_friends_empty' => 'Empty friends list',
	
	'site_history_back' => 'Go back',
	'site_go_main' => 'Go to the main page',
	
	/**
	 * Search
	 */
	'search' => 'Search',
	'search_submit' => 'Find now',
	'search_results' => 'Search result',
	'search_results_empty' => 'Thats strange. No results found.',
	'search_results_count_topics' => 'topics',
	'search_results_count_comments' => 'comments',
	
	/**
	 * Malbox
	 */
	'talk_menu_inbox' => 'Mailbox',
	'talk_menu_inbox_list' => 'Correspondence',
	'talk_menu_inbox_create' => 'Create new',
	'talk_menu_inbox_favourites' => 'Favourites',
	'talk_inbox' => 'Mailbox',
	'talk_inbox_target' => 'Recipients',
	'talk_inbox_title' => 'Subject',
	'talk_inbox_date' => 'Date',
	'talk_inbox_delete' => 'Delete correspondence',
	'talk_inbox_delete_confirm' => 'Do you really want to delete correspondence?',
	'talk_comments' => 'Correspondence',
	'talk_comment_add_text_error' => 'Message text should consist of 2 upto 3000 chars',
	'talk_create' => 'New message',
	'talk_create_users' => 'To',
	'talk_create_users_error' => 'You have to list recipients of your message',
	'talk_create_users_error_not_found' => 'We don\'t have user with login',
	'talk_create_title' => 'Subject',
	'talk_create_title_error' => 'Subject should consist of 2 upto 200 chars',
	'talk_create_text' => 'Message',
	'talk_create_text_error' => 'Message text should consist of 2 upto 3000 chars',
	'talk_create_submit' => 'Send',
	'talk_time_limit' => 'You can\'t send messages in such a frequent rate',
	
	'talk_favourite_inbox' => 'Favourite messages',
	'talk_favourite_add' => 'Add to favourites',
	'talk_favourite_add_ok' => 'Message added to favourites',
	'talk_favourite_add_no' => 'This message is not in your Favourites\' list',
	'talk_favourite_add_already' => 'This message is already in your Favourites\' list',
	'talk_favourite_del' => 'remove from favourites',
	'talk_favourite_del_ok' => 'Message removed from Favourites\' list',	
	
	'talk_filter_title' => 'Filter',
	'talk_filter_erase' => 'Remove filter',
	'talk_filter_erase_form' => 'Empty form',
	'talk_filter_label_sender' => 'Sender',
	'talk_filter_label_keyword' => 'Search in the subjects',
	'talk_filter_label_date' => 'Set dates',
	'talk_filter_notice_sender' => 'Specify sender\'s login',
	'talk_filter_notice_keyword' => 'Specify keywords',
	'talk_filter_notice_date' => 'Date should be in the following format 25.12.2008',
	'talk_filter_submit' => 'Submit filter',
	'talk_filter_error' => 'Filter error',
	'talk_filter_error_date_format' => 'Wrong date format',
	'talk_filter_result_count' => 'Found %%count%% messages',
	'talk_filter_result_empty' => 'No messages found according to specified criteria',
	
	'talk_user_in_blacklist' => 'User <b>%%login%%</b> blacklisted your messages',
	'talk_blacklist_title' => 'Blacklist messages from:',
	'talk_blacklist_empty' => 'Accept from all',
	'talk_balcklist_add_label' => 'Add users',
	'talk_balcklist_add_notice' => 'Type one or more logins',
	'talk_balcklist_add_submit' => 'Blacklist',
	'talk_blacklist_add_ok' => 'User <b>%%login%%</b> added to blacklist',
	'talk_blacklist_user_already_have' => 'User <b>%%login%%</b> already in your black list',
	'talk_blacklist_delete_ok' => 'User <b>%%login%%</b> removed from blacklist',
	'talk_blacklist_user_not_found' => 'User <b>%%login%%</b> is not in your black list',
	'talk_blacklist_add_self' => 'You can\'t add yourself to the blacklist',
	
	'talk_speaker_title' => 'Message members',
	'talk_speaker_add_label' => 'Add member',
	'talk_speaker_delete_ok' => 'Member <b>%%login%%</b> deleted successfully',
	'talk_speaker_user_not_found' => 'User <b>%%login%%</b> is not a member of this message',
	'talk_speaker_user_already_exist' => ' <b>%%login%%</b> is a member of this message already',
	'talk_speaker_add_ok' => 'User <b>%%login%%</b> added successfully',
	'talk_speaker_delete_by_self' => 'Member <b>%%login%%</b> deleted this message',
	'talk_speaker_add_self' => 'You can\'t add yourself as a member',
	
	'talk_not_found' => 'Message not found',
	
	/**
	 * Rating TOP
	 */
	'top' => 'Rating',
	'top_blogs' => 'TOP Blogs',
	'top_topics' => 'TOP Topics',
	'top_comments' => 'TOP Comments',
	
	/**
	 * Tag search
	 */
	'tag_title' => 'Tag search',
	
	/**
	 * Paging
	 */
	'paging_next' => 'Next',
	'paging_previos' => 'Previous',
	'paging_last' => 'Last',
	'paging_first' => 'First',
	'paging' => 'Pages',
	
	/**
	 * Image upload
	 */
	'uploadimg' => 'Upload image',
	'uploadimg_file' => 'File',
	'uploadimg_file_error' => 'Can\'t process the file. Please check file type and size.',
	'uploadimg_url' => 'Image URL',
	'uploadimg_url_error_type' => 'File is not an image',
	'uploadimg_url_error_read' => 'Can\'t read external file',
	'uploadimg_url_error_size' => 'File exceeds its 500KB maximum size',
	'uploadimg_url_error' => 'Can\'t process external file',
	'uploadimg_align' => 'Align',
	'uploadimg_align_no' => 'No',
	'uploadimg_align_left' => 'Left',
	'uploadimg_align_right' => 'Right',
	'uploadimg_submit' => 'Submit',
	'uploadimg_cancel' => 'Cancel',
	'uploadimg_title' => 'Title',
	
	/**
	 * Notifications
	 */
	'notify_subject_comment_new' => 'New comment added to your topic',
	'notify_subject_comment_reply' => 'You\'ve recieved reply to your comment',
	'notify_subject_topic_new' => 'New topic in the blog',
	'notify_subject_registration_activate' => 'Registration',
	'notify_subject_registration' => 'Registration',
	'notify_subject_invite' => 'Registration invitation',
	'notify_subject_talk_new' => 'You\'ve got a new message',
	'notify_subject_talk_comment_new' => 'You\'ve got a new comment to the message',
	'notify_subject_user_friend_new' => 'You\'ve been added to Friends\' list',
	'notify_subject_blog_invite_new' => 'You\'ve got a blog membership offer',
	'notify_subject_reminder_code' => 'Password reminder',
	'notify_subject_reminder_password' => 'New password',
	
	/**
	 * Plugin administration page
	 */
	'plugins_administartion_title' => 'Plugin administration',
	'plugins_plugin_name' => 'Name',
	'plugins_plugin_author' => 'Creator',
	'plugins_plugin_version' => 'Version',
	'plugins_plugin_action' => '',
	'plugins_plugin_activate' => 'Activate',
	'plugins_plugin_deactivate' => 'Deactivate',
	'plugins_unknown_action' => 'Requested unknown action',
	'plugins_action_ok' => 'Action successfully completed',
	'plugins_activation_overlap' => 'Conflict with an active plugin. Resource %%resource%% reconfigured to %%delegate%% by %%plugin%% plugin.',
	'plugins_activation_overlap_inherit' => 'Conflict with an active plugin. Resource %%resource%% used as inheritor in %%plugin%% plugin.',
	'plugins_activation_file_not_found' => 'Plugin not found',
	'plugins_activation_version_error' => 'Plugin requires LiveStreet kernel ver. %%version%% or higher',
	'plugins_activation_requires_error' => 'Plugin requires activated <b>%%plugin%%</b> plugin',
	'plugins_submit_delete' => 'Delete plugins',
	'plugins_delete_confirm' => 'Do you really want to delete those plugins?',
	
	
	'system_error_event_args' => 'Wrong number of arguments during event addition',
	'system_error_event_method' => 'Added event method not found',
	'system_error_404' => 'Unfortunately there is no such page. Probably deleted or wasn\'t there in the first place.',
	'system_error_module' => 'Can\'t find module class',
	'system_error_module_no_method' => 'There is no required method in the module',
	'system_error_cache_type' => 'Wrong cache type',
	'system_error_template' => 'Can\'t find template',
	'system_error_template_block' => 'Can\'t find template of added blog',
	
	'error' => 'Error',
	'attention' => 'Attention',
	'system_error' => 'System error. Please try later.',
	'exit' => 'Exit',
	'need_authorization' => 'Please login!',
	'or' => 'or',
	'window_close' => 'close',
	'not_access' => 'No access',	
	'install_directory_exists' => 'To continue your work with portal please delete /install directory.',	
	'login' => 'Login',	
	'date_day' => 'day',
	'date_month' => 'month',
	
	'month_array' => array(
		1=>array('January','January','January'),
		2=>array('February','February','February'),
		3=>array('March','March','March'),
		4=>array('April','April','April'),
		5=>array('May','May','May'),
		6=>array('June','June','June'),
		7=>array('July','July','July'),
		8=>array('August','August','August'),
		9=>array('September','September','September'),
		10=>array('October','October','October'),
		11=>array('November','November','November'),
		12=>array('December','December','December'),	
	),
 	
	'date_year' => 'year',
	
	'date_now' => 'Today\'s date',
	'date_today' => 'Today at',
	'date_yesterday' => 'Yesterday at',
	'date_tomorrow' => 'Tomorrow at',
	'date_minutes_back' => '%%minutes%% minutes ago; %%minutes%% minutes ago; %%minutes%% minutes ago',
	'date_minutes_back_less' => 'Less than a minute ago',
	'date_hours_back' => '%%hours%% hours ago; %%hours%% hours ago; %%hours%% hours ago',
	'date_hours_back_less' => 'Less than an hour ago',
);

?>