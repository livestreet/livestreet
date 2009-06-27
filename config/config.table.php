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
 * Название таблиц в БД
 *
 */
define('DB_PREFIX_TABLE','prefix_');

define('DB_TABLE_USER',DB_PREFIX_TABLE.'user');
define('DB_TABLE_BLOG',DB_PREFIX_TABLE.'blog');
define('DB_TABLE_TOPIC',DB_PREFIX_TABLE.'topic');
define('DB_TABLE_TOPIC_TAG',DB_PREFIX_TABLE.'topic_tag');
define('DB_TABLE_COMMENT',DB_PREFIX_TABLE.'comment');
define('DB_TABLE_VOTE',DB_PREFIX_TABLE.'vote');
define('DB_TABLE_TOPIC_READ',DB_PREFIX_TABLE.'topic_read');
define('DB_TABLE_BLOG_USER',DB_PREFIX_TABLE.'blog_user');
define('DB_TABLE_BLOG_VOTE',DB_PREFIX_TABLE.'blog_vote');
define('DB_TABLE_TOPIC_COMMENT_VOTE',DB_PREFIX_TABLE.'topic_comment_vote');
define('DB_TABLE_USER_VOTE',DB_PREFIX_TABLE.'user_vote');
define('DB_TABLE_FAVOURITE_TOPIC',DB_PREFIX_TABLE.'favourite_topic');
define('DB_TABLE_TALK',DB_PREFIX_TABLE.'talk');
define('DB_TABLE_TALK_USER',DB_PREFIX_TABLE.'talk_user');
define('DB_TABLE_TALK_COMMENT',DB_PREFIX_TABLE.'talk_comment');
define('DB_TABLE_FRIEND',DB_PREFIX_TABLE.'friend');
define('DB_TABLE_TOPIC_CONTENT',DB_PREFIX_TABLE.'topic_content');
define('DB_TABLE_TOPIC_QUESTION_VOTE',DB_PREFIX_TABLE.'topic_question_vote');
define('DB_TABLE_USER_ADMINISTRATOR',DB_PREFIX_TABLE.'user_administrator');
define('DB_TABLE_COMMENT_ONLINE',DB_PREFIX_TABLE.'comment_online');
define('DB_TABLE_INVITE',DB_PREFIX_TABLE.'invite');
define('DB_TABLE_PAGE',DB_PREFIX_TABLE.'page');
define('DB_TABLE_CITY',DB_PREFIX_TABLE.'city');
define('DB_TABLE_CITY_USER',DB_PREFIX_TABLE.'city_user');
define('DB_TABLE_COUNTRY',DB_PREFIX_TABLE.'country');
define('DB_TABLE_COUNTRY_USER',DB_PREFIX_TABLE.'country_user');
define('DB_TABLE_REMINDER',DB_PREFIX_TABLE.'reminder');
define('DB_TABLE_SESSION',DB_PREFIX_TABLE.'session');
?>
