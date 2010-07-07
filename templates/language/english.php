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
 * Contains all text messages of engine.
 */
return array(
	/**
	 * Blogs
	 */
	'blogs' => 'Blogs',
	'blogs_title' => 'Title and Owner',
	'blogs_readers' => 'Readers',
	'blogs_rating' => 'Rating',
	'blogs_owner' => 'Owner',
	'blogs_personal_title' => 'Blog by',
	'blogs_personal_description' => 'This is your personal blog.',
	
	'blog_no_topic' => 'No topic is here',
	'blog_rss' => 'RSS channel',
	'blog_rating' => 'Rating',
	'blog_vote_count' => 'Votes',
	'blog_about' => 'About blog',
	/**
	 * Popular blogs
	 */
	'blog_popular' => 'Popular blogs',
	'blog_popular_rating' => 'Rating',
	'blog_popular_all' => 'All blogs',
	/**
	 * Blog users
	 */
	'blog_user_count' => 'Subscribers',
	'blog_user_administrators' => 'Administrators',
	'blog_user_moderators' => 'Moderators',
	'blog_user_moderators_empty' => 'There are no moderators here',
	'blog_user_readers' => 'Readers',	
	'blog_user_readers_empty' => 'There are no readers here',	
	/**
	 * Voting for blog
	 */
	'blog_vote_up' => 'Like it',
	'blog_vote_down' => 'Don\'t like it',
	'blog_vote_count_text' => 'Total votes:',
	'blog_vote_error_already' => 'You have already voted for this blog!',
	'blog_vote_error_self' => 'You cannot vote for your blog!',
	'blog_vote_error_acl' => 'You do not have enough ranking for voting!',
	'blog_vote_error_close' => 'You cannot vote on private blog',
	'blog_vote_ok' => 'Your vote had been counted',
	/**
	 * Joining and leaving the blog
	 */
	'blog_join' => 'Join the blog',	
	'blog_join_ok' => 'You have joined the blog',	
	'blog_join_error_invite' => 'Joining this blog is by invitation only!',	
	'blog_join_error_self' => 'Why do you want to join this blog? You are it\'s owner!',	
	'blog_leave' => 'Leave the blog',	
	'blog_leave_ok' => 'You have left the blog',	
	/**
	 * Blog Menu
	 */
	'blog_menu_all' => 'All',
	'blog_menu_all_good' => 'Good',
	'blog_menu_all_new' => 'New',
	'blog_menu_all_list' => 'All blogs',
	'blog_menu_collective' => 'Collective',
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
	'blog_menu_top_period_24h' => 'For 24h period',
	'blog_menu_top_period_7d' => 'for 7h period',
	'blog_menu_top_period_30d' => 'for 30h period',
	'blog_menu_top_period_all' => 'For all period',	
	'blog_menu_create' => 'Blog creating',
	/**
	 * Create/Edit blog
	 */
	'blog_edit' => 'Edit',
	'blog_delete' => 'Delete',
	'blog_create' => 'Create a new blog',
	'blog_create_acl' => 'You are not strong enough to create a blog',
	'blog_create_title' => 'Blog title',
	'blog_create_title_notice' => 'Name of the blog should be filled with sense, so you can understand what this blog is about.',
	'blog_create_title_error' => 'Blog title should contain 2-200 symboles',
	'blog_create_title_error_unique' => 'Blog with such name already exists',
	'blog_create_url' => 'Blog URL',
	'blog_create_url_notice' => 'Blog URL, in which it will be available, can contain only letters, numbers, hyphen, spaces will be replaced with "_". According to the sense URL must match with blog title, after blog creating the editing of this option will be unavailable',
	'blog_create_url_error' => 'Blog URL should contain 2-50 symbols and be written in Latin alphabet + numbers and symbols "-", "_"',
	'blog_create_url_error_badword' => 'Blog URL must be different from:',
	'blog_create_url_error_unique' => 'Blog with this URL already exists',
	'blog_create_description' => 'Blog description',
	'blog_create_description_notice' => 'By the way, you can use html-tags',
	'blog_create_description_error' => 'Text descriptions of the blog should contain 10-3000 symbols',
	'blog_create_type' => 'Blog type',
	'blog_create_type_open' => 'open',
	'blog_create_type_close' => 'Closed',
	'blog_create_type_open_notice' => 'Open — everyone can join this blog, topics are visible to all',
	'blog_create_type_close_notice' => 'Closed — joining this topic is by blog administration invitation only, topics are seen only to subscribers',
	'blog_create_type_error' => 'Unknown type blog',
	'blog_create_rating' => 'Rating restrictions',
	'blog_create_rating_notice' => 'Rating which user needs to have for writing in this blog',
	'blog_create_rating_error' => 'Value restriction of ranking should be a number',
	'blog_create_avatar' => 'Icon',
	'blog_create_avatar_error' => 'Icon\'s loading failed',
	'blog_create_avatar_delete' => 'Delete',
	'blog_create_submit' => 'Save',
	'blog_create_submit_notice' => 'After clicking on "Save" the blog will be created',
	/**
	 * Blog administration
	 */
	'blog_admin' => 'Blog administration',
	'blog_admin_not_authorization' => 'In order to change the blog, first you must log in your account',
	'blog_admin_profile' => 'Profile',
	'blog_admin_users' => 'Users',
	'blog_admin_users_administrator' => 'Administrator',
	'blog_admin_users_moderator' => 'Moderator',
	'blog_admin_users_reader' => 'Reader',
	'blog_admin_users_bun' => 'Banned user',
	'blog_admin_users_current_administrator' => 'This is you &mdash; the real administration!',
	'blog_admin_users_empty' => 'Nobody belongs to this blog',
	'blog_admin_users_submit' => 'Save',
	'blog_admin_users_submit_notice' => 'After clicking on "Save" user rights will be preserved',
	'blog_admin_users_submit_ok' => 'Rights are reserved',
	'blog_admin_users_submit_error' => 'Something is wrong',
	
	'blog_admin_delete_confirm' => 'Are you sure you want to delete a blog?',
	'blog_admin_delete_move' => 'Move topics to blog',
	'blog_delete_clear' => 'Delete topics',
	'blog_admin_delete_success' => 'Blog has been successfully deleted',
	'blog_admin_delete_not_empty' => 'You cannot delete a block with records. Previously, remove all the records from the blog.',
	'blog_admin_delete_move_error' => 'Moving of the topics from deletable blog failed',
	'blog_admin_delete_move_personal' => 'You cannot move topics in a personal blog',
	
	'blog_admin_user_add_label' => 'Invite users:',
	'blog_admin_user_invited' => 'List of invitees:',
	'blog_close_show' => 'This is a private blog, you do not have rights to view the content',
	'blog_user_invite_add_self' => 'You cannot send an invitation to yourself',
	'blog_user_invite_add_ok' => 'The invitation has been sent to user%%login%%',
	'blog_user_already_invited' => 'This invitation has already sent to user %%login%%',
	'blog_user_already_exists' => 'The user %%login%% is already in the blog',
	'blog_user_already_reject' => 'The user %%login%% rejected invitation',
	'blog_user_invite_title' => "This is an invitation to become a reader of the blog '%% blog_title %%'",
	'blog_user_invite_text' => "The blog user %%login%% invites you to be a reader of the closed blog '%%blog_title%%'.<br/><br/><a href='%%accept_path%%'>Accept</a> - <a href='%%reject_path%%'>Reject</a>",
	'blog_user_invite_already_done' => 'You are already a user of this blog',
	'blog_user_invite_accept' => 'Invitation is accepted',
	'blog_user_invite_reject' => 'Invitation is rejected',
	'blog_user_invite_readd' => 'Repeat',
	
	/**
	 * Topics
	 */
	'topic_title' => 'Topics',
	'topic_read_more' => 'Read more',
	'topic_date' => 'Date',
	'topic_user' => 'Author text',
	'topic_time_limit' => 'You cannot create topics too often',
	'topic_comment_read' => 'Read comments',
	'topic_comment_add' => 'Comment',
	'topic_comment_add_title' => 'Write a comment',
	'topic_comment_add_text_error' => 'Text of the comment should contain 2 to 3000 symbols and do not contain any rubbish',
	'topic_comment_acl' => 'Your rating is too small to write comments',
	'topic_comment_limit' => 'You cannot write comments too often',
	'topic_comment_notallow' => 'The author of topic forbade to add comments',
	'topic_comment_spam' => 'Stop! Spamming!',
	'topic_unpublish' => 'Topic is in the drafts',
	'topic_favourite_add' => 'Add to Favorites',
	'topic_favourite_add_ok' => 'The topic has been added to Favorites',
	'topic_favourite_add_no' => 'This topic is not in your Favorites',
	'topic_favourite_add_already' => 'This topic already exists in your Favorites',
	'topic_favourite_del' => 'Remove from Favorites',
	'topic_favourite_del_ok' => 'Topic has been removed from Favorites',
	
	'block_stream_comments_all' => 'Entire air',
	'block_stream_topics_all' => 'Entire air',
	'comments_all' => 'Live',
	/**
	 * Topic menu
	 */
	'topic_menu_add' => 'New',
	'topic_menu_add_topic' => 'Topic',
	'topic_menu_add_question' => 'Query',
	'topic_menu_add_link' => 'Link',
	'topic_menu_saved' => 'Drafts',
	'topic_menu_published' => 'Published',
	/**
	 * Topic creating
	 */
	'topic_topic_create' => 'Create a topic',
	'topic_topic_edit' => 'Edit a topic',
	'topic_create' => 'Write',
	'topic_create_blog' => 'What blog is it published in?',
	'topic_create_blog_personal' => 'my personal blog',
	'topic_create_blog_error_unknown' => 'Are you trying to post it to unknown blog topic?',
	'topic_create_blog_error_nojoin' => 'You are not a member of this blog!',
	'topic_create_blog_error_noacl' => 'You are not quite strong to post in this blog',
	'topic_create_blog_error_noallow' => 'You cannot write in this blog',
	'topic_create_title' => 'Title',
	'topic_create_title_notice' => 'The title should be filled with meaning, so that you can understand what this topic will be about.',
	'topic_create_title_error' => 'Topic title should contain from 2 to 200 symbols',
	'topic_create_text' => 'Text',
	'topic_create_text_notice' => ' html-tags are available',
	'topic_create_text_error' => 'The test of the topic should contain from 2 to 200 symbols',
	'topic_create_text_error_unique' => 'You have already written a topic with such content',
	'topic_create_tags' => 'Tags',
	'topic_create_tags_notice' => 'Tags must be separated by the comma. For example: Habrahabr clone, blogs, raiting, google, boobs, brick.',
	'topic_create_tags_error_bad' => 'Please, check a correctness of tags',
	'topic_create_tags_error' => 'Topic tags should contain from 2 to 50 symbols with a total length not more than 500 symbols',
	'topic_create_forbid_comment' => 'Forbid comments',
	'topic_create_forbid_comment_notice' => 'If you put this tick, you will not be able to leave comments to the topic',
	'topic_create_publish_index' => 'Go to the main page with priority',
	'topic_create_publish_index_notice' => 'If you put this tick, the topic immediately will get to the main page (option is available to administrators only)', 
	'topic_create_submit_publish' => 'Publish',
	'topic_create_submit_save' => 'Save to Drafts',
	'topic_create_submit_preview' => 'Preview',
	'topic_create_submit_notice' => 'If you click "Save to drafts", the text of the topic will be visible only to you, and there will be a lock icon next to its title. To make a topic visible for all, click "Publish."',
	'topic_create_notice' => 'Do not forget: tag <cut> reduces long entries, hiding them completely or partially under reference ("read more"). The hidden part is not visible in your blog, but is full available on the topic page.',
	'topic_create_error' => 'Technical difficulties arose alone with topic\'s adding. Please try again later.',
	
	'topic_edit' => 'Edit',
	'topic_delete' => 'Delete',
	'topic_delete_confirm' => 'Do you actually want to delete the topic?',
	/**
	 * Topic-link
	 */
	'topic_link' => 'Topic-link',
	'topic_link_title' => 'Links',
	'topic_link_title_edit' => 'Link\'s edit',
	'topic_link_title_create' => 'Link\'s adding',
	'topic_link_create' => 'Creating a topic-link',
	'topic_link_edit' => 'Editing a topic-link',
	'topic_link_count_jump' => 'Link jumps:',
	'topic_link_create_url' => 'Link',
	'topic_link_create_url_notice' => 'For example, http://livestreet.ru/blog/dev_livestreet/113.html',
	'topic_link_create_url_error' => 'The link must contain from 2 to 200 symbols',
	'topic_link_create_text' => 'Short description (maximum 500 symbols, HTML-tags are forbidden)',
	'topic_link_create_text_notice' => 'HTML-tags are forbidden',
	'topic_link_create_text_error' => 'Description of the link should contain 10-500 symboles',
	/**
	 * Topic-query
	 */
	'topic_question_title' => 'Queries',
	'topic_question_title_edit' => 'Query\'s editing',
	'topic_question_title_create' => 'Query\'s adding',
	'topic_question_vote' => 'Vote',
	'topic_question_vote_ok' => 'Your vote has been counted',
	'topic_question_vote_already' => 'Your vote is counted!',
	'topic_question_vote_result' => 'Voted',
	'topic_question_abstain' => 'Abstain',
	'topic_question_abstain_result' => 'Abstained',
	'topic_question_create' => 'Creating of a topic-query',
	'topic_question_edit' => 'Editing of a topic-query',
	'topic_question_create_title' => 'Question',
	'topic_question_create_title_notice' => 'The question should be filled with meaning, so that you can understand what this query will be about',
	'topic_question_create_title_error' => 'The question should contain from 2 to 200 symbols',
	'topic_question_create_answers' => 'Response variants',	
	'topic_question_create_answers_error' => 'The response should contain from 1 to 100 symbols',	
	'topic_question_create_answers_error_min' => 'There must be 2 variants of response as a minimum',	
	'topic_question_create_answers_error_max' => 'The maximum number of possible responses is 20',	
	'topic_question_create_text' => 'Short description (maximum 500 symbols, HTML-tags are forbidden)',
	'topic_question_create_text_notice' => 'HTML-tags are forbidden',
	'topic_question_create_text_error' => 'The query description must contain no more than 500 symbols',
	/**
	 * Voting for the topic
	 */
	'topic_vote_up' => 'Like',
	'topic_vote_down' => 'Dont like',	
	'topic_vote_error_already' => 'You have already voted for this topic!',
	'topic_vote_error_self' => 'You may not vote for your topic!',
	'topic_vote_error_guest' => 'it is essential to be authorised for voting',
	'topic_vote_error_time' => 'Voting time for the topic is up!',
	'topic_vote_error_acl' => 'You have not enough raiting and strenth for voting!',
	'topic_vote_no' => 'None voted',
	'topic_vote_ok' => 'Your vote has been counted',
	'topic_vote_ok_abstain' => 'You have abstained to view the topic rating',
	'topic_vote_count' => 'Total number of votes',
	
	/**
	 * Comments
	 */
	'comment_title' => 'Comments',
	'comment_collapse' => 'Collapse',
	'comment_expand' => 'Expand',
	'comment_goto_parent' => 'Answer to',
	'comment_goto_child' => 'Back to answer',
	'comment_bad_open' => 'Open a comment',
	'comment_answer' => 'Answer',
	'comment_delete' => 'Delete',
	'comment_delete_ok' => 'The comment has been deleted',
	'comment_repair' => 'Restore',
	'comment_repair_ok' => 'The comment has been restored',
	'comment_was_delete' => 'The comment has been deleted',
	'comment_add' => 'Add',
	'comment_preview' => 'Preview',
	'comment_unregistered' => 'Registered and authorized users can post comments only.',
	/**
	 * Voting for comment
	 */
	'comment_vote_error' => 'Try to vote later',
	'comment_vote_error_value' => 'You can vote only +1 or -1!',
	'comment_vote_error_acl' => 'You have not enough raiting and strenth for voting!',
	'comment_vote_error_already' => 'You have already voted for this comment!',
	'comment_vote_error_time' => 'Voting duration for comment is up!',
	'comment_vote_error_self' => 'You cannot vote for your own comment!',
	'comment_vote_error_noexists' => 'You vote for a nonexistent comment!',
	'comment_vote_ok' => 'Your vote has been counted',

	'comment_favourite_add' => 'Add to Favourites',
	'comment_favourite_add_ok' => 'The commenthas been added to Favourites',
	'comment_favourite_add_no' => 'This comment is not in your Favourites',
	'comment_favourite_add_already' => 'This comment is in your Favourites',
	'comment_favourite_del' => 'Remove from Favourites',
	'comment_favourite_del_ok' => 'The comment has been deleted from Favourites',

	
	/**
	 * People
	 */
	'people' => 'People',
	
	
	/**
	 * User
	 */
	'user' => 'User',
	'user_list' => 'Users',
	'user_list_new' => 'New users',
	'user_list_online_last' => 'Last online',
	'user_good' => 'Positive',
	'user_bad' => 'Negative',
	'user_privat_messages' => 'Private messages',
	'user_privat_messages_new' => 'You have new messages',
	'user_settings' => 'Settings',
	'user_settings_profile' => 'Profile settings',
	'user_settings_tuning' => 'Site settings',
	'user_login' => 'Login or e-mail',
	'user_login_submit' => 'Enter',
	'user_login_remember' => 'Remember me',
	'user_login_bad' => 'Something is wrong! Probably, incorrect login (e-mail) or password.',
	'user_password' => 'Password',
	'user_password_reminder' => 'Remind a password',
	'user_exit_notice' => 'Come again by all means.',
	'user_authorization' => 'Authorization',
	'user_registration' => 'Registration',
	'user_write_prvmsg' => 'Write a letter',

	'user_friend_add' => 'Add to Friends',
	'user_friend_add_ok' => 'You have a new friend',
	'user_friend_add_self' => 'Your friend is you!',
	'user_friend_del' => 'Delete from Friends',
	'user_friend_del_ok' => 'This friend has been deleted',
	'user_friend_del_no' => 'The friend has been not found!',
	'user_friend_offer_reject' => 'Request has been rejected',
	'user_friend_offer_send' => 'Request has been sent',	
	'user_friend_already_exist' => 'The user is already your friend',
	'user_friend_offer_title' => 'The user %%login%% invites you to be friends',
	'user_friend_offer_text' => "The user %%login%% wishes to add you to Friends.<br/><br/>%%user_text%%<br/><br/><a href='%%accept_path%%'>Accept</a> - <a href='%%reject_path%%'>Reject</a>",
	'user_friend_add_deleted' => 'This user has been refused to be friends with you',
	'user_friend_add_text_label' => 'Present yourself:',
	'user_friend_add_submit' => 'Send',
	'user_friend_add_cansel' => 'Cancel',
	'user_friend_offer_not_found' => 'Request has not been found',
	'user_friend_offer_already_done' => 'Request is already processed',
	'user_friend_accept_notice_title' => 'Your request has been accepted',
	'user_friend_accept_notice_text' => 'The user %%login%% has accepted your invitation to friendship',
	'user_friend_reject_notice_title' => 'Your request has been rejected',
	'user_friend_reject_notice_text' => 'The user %%login%% has refused to be friends with you',	
	'user_friend_del_notice_title' => 'You have been deleted from Friends',
	'user_friend_del_notice_text' => 'You have no friend %%login%% any more',
	
	'user_rating' => 'Rating',
	'user_skill' => 'Power',
	'user_date_last' => 'Last visit',
	'user_date_registration' => 'Registration date',
	'user_empty' => 'There are no such',
	'user_stats' => 'Statistic',
	'user_stats_all' => 'All users',
	'user_stats_active' => 'Active',
	'user_stats_noactive' => 'Non-active',
	'user_stats_sex_man' => 'Men',
	'user_stats_sex_woman' => 'Women',
	'user_stats_sex_other' => 'Sex is not indicated',
	
	'user_not_found' => 'The user <b>%%login%%</b> has not been found',
	'user_not_found_by_id' => 'The user <b>#%%id%%</b> has not been found',
	
	/**
	 * User Profile Menu
	 */
	'people_menu_users' => 'Users',
	'people_menu_users_all' => 'All',
	'people_menu_users_online' => 'Online',
	'people_menu_users_new' => 'New',
	
	/**
	 * Registration
	 */
	'registration_invite' => 'Registration at the invitation',
	'registration_invite_code' => 'Invitation code',
	'registration_invite_code_error' => 'Invalid invitation code',
	'registration_invite_check' => 'Check a code',
	'registration_activate_ok' => 'Congratulations! Your account has successfully been activated.',
	'registration_activate_error_code' => 'Invalid activation code!',
	'registration_activate_error_reactivate' => 'Your account has been already activated',
	'registration_confirm_header' => 'Account Activation',
	'registration_confirm_text' => 'You are about to complete the registration, just activate your account. Instructions on how to activate it emailed to the address noted at registration.',
	'registration' => 'Registration',
	'registration_is_authorization' => 'You have already registered, and even logged in!',
	'registration_login' => 'Login',
	'registration_login_error' => 'Invalid login, 3-30 symbols are allowed',
	'registration_login_error_used' => 'This login is already taken',
	'registration_login_notice' => 'It can only consist of letters (A-Z a-z), numbers (0-9). Underscore character (_) is better not to use. The length of the username can not be less than 3 and more than 30 symbols',
	'registration_mail' => 'E-mail address',
	'registration_mail_error' => 'Invalid format for e-mail',
	'registration_mail_error_used' => 'This e-mail is already in use',
	'registration_mail_notice' => 'We need your E-mail address for checking registration and security purposes',
	'registration_password' => 'Password',
	'registration_password_error' => 'Invalid password, use more than 5 symbols',
	'registration_password_error_different' => 'Passwords are different',
	'registration_password_notice' => 'Password must contain not less than 5 symbols and cant match with you login. Dont use simple passwords, be careful.',
	'registration_password_retry' => 'Confirm the password',
	'registration_captcha' => 'Enter the numbers and letters',
	'registration_captcha_error' => 'Invalid code',
	'registration_submit' => 'Register',
	'registration_ok' => 'Congratulations! Registration has been successfully completed',
			
	/**
	 * Voting for user
	 */
	'user_vote_up' => 'Like',
	'user_vote_down' => 'Don\'t like',	
	'user_vote_error_already' => 'You have already voted for this user!',
	'user_vote_error_self' => 'You can\'t vote for yourself!',
	'user_vote_error_guest' => 'You must be authourized for voting',	
	'user_vote_error_acl' => 'You do not have enough ranking and strength for voting!',	
	'user_vote_ok' => 'Your vote has been counted',	
	'user_vote_count' => 'Votes',
	
	/**
	 * User Profile Menu
	 */
	'user_menu_profile' => 'Profile',
	'user_menu_profile_whois' => 'Who is',
	
	'user_menu_profile_favourites' => 'Favourite topics',
	'user_menu_profile_favourites_comments' => 'Favorite comments',
	
	'user_menu_profile_tags' => 'Tags',
	'user_menu_publication' => 'Publication',
	'user_menu_publication_blog' => 'Blog',
	'user_menu_publication_comment' => 'Comments',
	'user_menu_publication_comment_rss' => 'RSS channel',
	
	/**
	 * Profile 
	 */
	'profile_privat' => 'Personal',
	'profile_sex' => 'Sex',
	'profile_sex_man' => 'Male',
	'profile_sex_woman' => 'Female',
	'profile_birthday' => 'Date of birthday',
	'profile_place' => 'Location',
	'profile_about' => 'About yourself',
	'profile_site' => 'Site',
	'profile_activity' => 'Activity',
	'profile_friends' => 'Friends',
	'profile_friends_self' => 'You are a friend of',
	'profile_invite_from' => 'Invited',
	'profile_invite_to' => 'Invitees',
	'profile_blogs_self' => 'Created',
	'profile_blogs_join' => 'joined to',
	'profile_blogs_moderation' => 'Moderate',
	'profile_blogs_administration' => 'Administrate',
	'profile_date_registration' => 'registered',
	'profile_date_last' => 'Last visit',
	'profile_social_contacts' => 'Contacts and social services',
	
	
	
	/**
	 * Settings
	 */
	'settings_profile_edit' => 'Profile editing',
	'settings_profile_name' => 'Name',
	'settings_profile_name_notice' => 'The length of the name could not be less than 2 and more than 20 symbols.',
	'settings_profile_mail' => 'E-mail',
	'settings_profile_mail_error' => 'Invalid format for e-mail',
	'settings_profile_mail_error_used' => 'This e-mail is already in use',
	'settings_profile_mail_notice' => 'All notifications will come to your real e-mail address',
	'settings_profile_sex' => 'Sex',
	'settings_profile_sex_man' => 'Male',
	'settings_profile_sex_woman' => 'Female',
	'settings_profile_sex_other' => 'No answer',
	'settings_profile_birthday' => 'Date of birthday',
	'settings_profile_country' => 'Country',
	'settings_profile_city' => 'City',
	'settings_profile_icq' => 'ICQ',
	'settings_profile_site' => 'Site',
	'settings_profile_site_url' => 'Site\'s URL',
	'settings_profile_site_name' => 'Site\'s name',
	'settings_profile_about' => 'About yourself',
	'settings_profile_password_current' => 'Current password',
	'settings_profile_password_current_error' => 'Invalid current password',
	'settings_profile_password_new' => 'New password',
	'settings_profile_password_new_error' => 'Invalid password,  use more than 5 symbols',
	'settings_profile_password_confirm' => 'Confirm a new password',
	'settings_profile_password_confirm_error' => 'Passwords are different',
	'settings_profile_avatar' => 'Icon',
	'settings_profile_avatar_error' => 'Loading an icon failed',
	'settings_profile_avatar_delete' => 'Delete',
	'settings_profile_foto' => 'Picture',
	'settings_profile_foto_error' => 'Loading an picture failed',
	'settings_profile_foto_delete' => 'Delete',
	'settings_profile_submit' => 'Save profile',
	'settings_profile_submit_ok' => 'Profile has been successfully saved',
	'settings_invite' => 'Invitation administration',
	'settings_invite_available' => 'Available',
	'settings_invite_available_no' => 'Уou have no available invitations',
	'settings_invite_used' => 'Used',
	'settings_invite_mail' => 'Invite by e-mail address',
	'settings_invite_mail_error' => 'Invalid format for e-mail',
	'settings_invite_mail_notice' => 'An invitation to the registration will be sent to this e-mail',
	'settings_invite_many' => 'Many',
	'settings_invite_submit' => 'Send an invitation',
	'settings_invite_submit_ok' => 'The invitation has been sent',
	'settings_tuning' => 'Site settings',
	'settings_tuning_notice' => 'E-mail notifications',
	'settings_tuning_notice_new_topic' => 'about a new topic in a blog',
	'settings_tuning_notice_new_comment' => 'about a new comment in a topic',
	'settings_tuning_notice_new_talk' => 'about a new private message',
	'settings_tuning_notice_reply_comment' => 'about a reply to your comment',
	'settings_tuning_notice_new_friend' => 'about adding you as a friend',
	'settings_tuning_submit' => 'Save settings',
	'settings_tuning_submit_ok' => 'Settings have been successfully saved',
	
	
	/**
	 *Configuration menu
	 */
	'settings_menu' => 'Settings',
	'settings_menu_profile' => 'Profile',
	'settings_menu_tuning' => 'Tuning',
	'settings_menu_invite' => 'Invitations',
	
	/**
	 * Remind a password 
	 */
	'password_reminder' => 'Remind a password',
	'password_reminder_email' => 'Your e-mail',
	'password_reminder_submit' => 'Get a link for changing your password',
	'password_reminder_send_password' => 'A new password has been sent to your email address.',
	'password_reminder_send_link' => 'Link to remind the password has been sent to your email address.',
	'password_reminder_bad_code' => 'Invalid code for password recovery.',
	'password_reminder_bad_email' => 'The user with this e-mail has not been found',
	
	/**
	 * Panel
	 */
	'panel_b' => 'bold',
	'panel_i' => 'italic',
	'panel_u' => 'underlined',
	'panel_s' => 'strikeout',
	'panel_url' => 'insert a link',
	'panel_url_promt' => 'Enter a link',
	'panel_code' => 'code',
	'panel_video' => 'video',
	'panel_image' => 'image',
	'panel_cut' => 'cut',
	'panel_quote' => 'quote',
	'panel_list' => 'List',
	'panel_list_ul' => 'UL LI',
	'panel_list_ol' => 'OL LI',
	'panel_title' => 'Title',
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
	'block_blog_info_note_text' => '<strong>Tag &lt;cut&gt; reduces long entries</strong>, hiding them completely or partially under reference ("read more"). The hidden part is not visible in your blog, but is full available on the topic\'s page.',
	'block_blogs' => 'Blogs',
	'block_blogs_top' => 'Top',
	'block_blogs_join' => 'Joined',
	'block_blogs_join_error' => 'You are not a member of the group blogs',
	'block_blogs_self' => 'Mine',
	'block_blogs_self_error' => 'You do not have your own group blogs',
	'block_blogs_all' => 'All blogs',
	'block_stream' => 'Live',
	'block_stream_topics' => 'Publications',
	'block_stream_topics_no' => 'No topics.',
	'block_stream_comments' => 'Comments',
	'block_stream_comments_no' => 'No comments.',
	'block_stream_comments_all' => 'Entire air',
	
	'block_friends' => 'Friends',
	'block_friends_check' => 'Mark all friends',
	'block_friends_uncheck' => 'Remove a mark',
	'block_friends_empty' => 'The list of your friends is empty',
	
	'site_history_back' => 'Go back',
	'site_go_main' => 'Go to the main page',
	
	/**
	 * Search
	 */
	'search' => 'Search',
	'search_submit' => 'Find',
	'search_results' => 'Search results',
	'search_results_empty' => 'Surprisingly, but no search results',
	'search_results_count_topics' => 'topics',
	'search_results_count_comments' => 'comments',
	
	/**
	 * Mail
	 */
	'talk_menu_inbox' => 'Mail box',
	'talk_menu_inbox_list' => 'Correspondence',
	'talk_menu_inbox_create' => 'New letter',
	'talk_menu_inbox_favourites' => 'Favourites',
	'talk_inbox' => 'Mail box',
	'talk_inbox_target' => 'Addressees',
	'talk_inbox_title' => 'Topic',
	'talk_inbox_date' => 'Date',
	'talk_inbox_delete' => 'Delete correspondence',
	'talk_inbox_delete_confirm' => 'Do you really want to delete correspondence?',
	'talk_comments' => 'Correspondence',
	'talk_comment_add_text_error' => 'Text of commentary should contain from 2 to 3000 symbols',
	'talk_create' => 'New letter',
	'talk_create_users' => 'To',
	'talk_create_users_error' => 'It is necessary to name, to whom you want to send a message',
	'talk_create_users_error_not_found' => 'There is no user with login...',
	'talk_create_title' => 'Title',
	'talk_create_title_error' => 'Message title should contain from 2 to 200 symbols',
	'talk_create_text' => 'Message',
	'talk_create_text_error' => 'Text of the message should contain 2-3000 symbols',
	'talk_create_submit' => 'Send',
	'talk_time_limit' => 'You cannot send private messages too often',
	
	'talk_favourite_inbox' => 'Favourite letters',
	'talk_favourite_add' => 'Add to Favourites',
	'talk_favourite_add_ok' => 'The letter has been added to Favourites',
	'talk_favourite_add_no' => 'This letter in not in your Favourites',
	'talk_favourite_add_already' => 'This letter is already in your Favourites',
	'talk_favourite_del' => 'Delete from Favourites',
	'talk_favourite_del_ok' => 'The letter has been deleted from Favourites',	
	
	'talk_filter_title' => 'Filter',
	'talk_filter_erase' => 'Reset the filter',
	'talk_filter_erase_form' => 'Clear the form',
	'talk_filter_label_sender' => 'Sender',
	'talk_filter_label_keyword' => 'Search in title',
	'talk_filter_label_date' => 'Date restrictions',
	'talk_filter_notice_sender' => 'Name a sender\'s login',
	'talk_filter_notice_keyword' => 'Enter 1 or some words',
	'talk_filter_notice_date' => 'Date is entered in 25.12.2008 format',
	'talk_filter_submit' => 'Filter',
	'talk_filter_error' => 'The error filtering',
	'talk_filter_error_date_format' => 'Invalid date format',
	'talk_filter_result_count' => 'Found letters: %%count%% ',
	'talk_filter_result_empty' => 'There are no letters by your criteria',
	
	'talk_user_in_blacklist' => 'The user <b>%%login%%</b> doesnt take letters from you',
	'talk_blacklist_title' => 'Not to accept letters from:',
	'talk_blacklist_empty' => 'Accept letters from all',
	'talk_balcklist_add_label' => 'Add users',
	'talk_balcklist_add_notice' => 'Enter 1 or some logins',
	'talk_balcklist_add_submit' => 'Dont accept',
	'talk_blacklist_add_ok' => 'The user <b>%%login%%</b> has been successfully added',
	'talk_blacklist_user_already_have' => 'The user <b>%%login%%</b> is already in you black list',
	'talk_blacklist_delete_ok' => 'The user <b>%%login%%</b> has been successfully deleted',
	'talk_blacklist_user_not_found' => 'The user <b>%%login%%</b> is not in your black list',
	'talk_blacklist_add_self' => 'You cannot add yourself to the black list',
	
	'talk_speaker_title' => 'Speakers',
	'talk_speaker_add_label' => 'Add the speaker',
	'talk_speaker_delete_ok' => 'The speaker <b>%%login%%</b> has been successfully deleted',
	'talk_speaker_user_not_found' => 'The user <b>%%login%%</b> does not take part into a conversation',
	'talk_speaker_user_already_exist' => ' <b>%%login%%</b> is already a participant of conversation',
	'talk_speaker_add_ok' => 'The speaker <b>%%login%%</b> has been successfully added',
	'talk_speaker_delete_by_self' => 'The participant <b>%%login%%</b> deleted this conversation',
	'talk_speaker_add_self' => 'You cannot add yourself to participants',
	
	'talk_not_found' => 'Talking has not been found',
	
	/**
	 * TOP rating
	 */
	'top' => 'Rating',
	'top_blogs' => 'TOP blogs',
	'top_topics' => 'TOP topics',
	'top_comments' => 'TOP comments',
	
	/**
	 * Search by titles
	 */
	'tag_title' => 'Search by titles',
	
	/**
	 * Per page
	 */
	'paging_next' => 'Next',
	'paging_previos' => 'Previous',
	'paging_last' => 'Last',
	'paging_first' => 'First',
	'paging' => 'Pages',
	
	/**
	 * Image uploading
	 */
	'uploadimg' => 'Insert the image',
	'uploadimg_file' => 'File',
	'uploadimg_file_error' => 'Unable to process the file, verify the type and size of the file',
	'uploadimg_url' => 'Image link',
	'uploadimg_url_error_type' => 'File is not an image',
	'uploadimg_url_error_read' => 'Impossible to read the external file',
	'uploadimg_url_error_size' => 'File size exceeds the maximum of 500kb',
	'uploadimg_url_error' => 'Impossible to process the external file',
	'uploadimg_align' => 'Alignment',
	'uploadimg_align_no' => 'No',
	'uploadimg_align_left' => 'Left',
	'uploadimg_align_right' => 'Right',
	'uploadimg_submit' => 'Load',
	'uploadimg_cancel' => 'Cancel',
	'uploadimg_title' => 'Description',
	
	/**
	 * Notifications
	 */
	'notify_subject_comment_new' => 'A new comment has been left to your topic',
	'notify_subject_comment_reply' => 'You are answered to your comment',
	'notify_subject_topic_new' => 'New topic in a blog',
	'notify_subject_registration_activate' => 'registration',
	'notify_subject_registration' => 'registration',
	'notify_subject_invite' => 'Invitation to registration',
	'notify_subject_talk_new' => 'You have a new letter',
	'notify_subject_talk_comment_new' => 'You have a new comment to a letter',
	'notify_subject_user_friend_new' => 'You have been added to Friends',
	'notify_subject_blog_invite_new' => 'You have been invited to join the blog',
	'notify_subject_reminder_code' => 'Remind a password',
	'notify_subject_reminder_password' => 'New password',
	
	/**
	 * Plug-in Administration page
	 */
	'plugins_administartion_title' => 'Plug-in administration',
	'plugins_plugin_name' => 'name',
	'plugins_plugin_author' => 'Author',
	'plugins_plugin_version' => 'Version',
	'plugins_plugin_action' => '',
	'plugins_plugin_activate' => 'Activate',
	'plugins_plugin_deactivate' => 'Deactivate',
	'plugins_unknown_action' => 'Unknown action is noted',
	'plugins_action_ok' => 'Successfully done ',
	'plugins_activation_overlap' => 'Conflict with activated plug-in. Resource %%resource%% is overriden into %%delegate%% with plug-in %%plugin%%.',
	'plugins_activation_overlap_inherit' => 'Conflict with activated plug-in. Resource %%resource%% is used as the heir to the plug-in %%plugin%%.',	
	'plugins_activation_file_not_found' => 'Plug-in file has not been found',
	'plugins_activation_version_error' => 'To use the plug-in you need a LiveStreet hard core with version %%version%% or higher',
	'plugins_activation_requires_error' => 'For plug-in work the activated plug-in <b>%%plugin%%</b> is necessary',
	'plugins_submit_delete' => 'Delete plug-ins',
	'plugins_delete_confirm' => 'Are you sure you want to delete these plug-ins?',
	
	
	'system_error_event_args' => 'Incorrect number of arguments for event\'s adding',
	'system_error_event_method' => 'Method of event\'s adding has not been found',
	'system_error_404' => 'Unfortunatelly, this page does not exist. Probably, it had been deleted from server, or it had never been here.',
	'system_error_module' => 'Unable to find a class module',
	'system_error_module_no_method' => 'There is no nesecceary method in module',
	'system_error_cache_type' => 'Invalid cache type',
	'system_error_template' => 'Unable to find a template',
	'system_error_template_block' => 'Unable to find a template of plug-in option',
	
	'error' => 'Error',
	'attention' => 'Attention',
	'system_error' => 'System error, try again later',
	'exit' => 'Exit',
	'need_authorization' => 'You need to log in!',
	'or' => 'or',
	'window_close' => 'close',
	'not_access' => 'no access',	
	'install_directory_exists' => 'For working with site, please, delete the directory file /install.',	
	'login' => 'Log in',	
	'date_day' => 'Day',
	'date_month' => 'Month',
	
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
	
	'date_now' => 'just now',
	'date_today' => 'Today at...',
	'date_yesterday' => 'Yesterday at',
	'date_tomorrow' => 'Tomorrow at',
	'date_minutes_back' => '%%minutes%% minutes ago; %%minutes%% minutes ago; %%minutes%% minutes ago',
	'date_minutes_back_less' => 'Less a minute ago',
	'date_hours_back' => '%%hours%% an hour ago; %%hours%% hours ago; %%hours%% hours ago',
	'date_hours_back_less' => 'Less an hour ago',
);

?>