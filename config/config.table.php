<?
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
define('DB_TABLE_TOPIC_COMMENT',DB_PREFIX_TABLE.'topic_comment');
define('DB_TABLE_TOPIC_VOTE',DB_PREFIX_TABLE.'topic_vote');
define('DB_TABLE_TOPIC_READ',DB_PREFIX_TABLE.'topic_read');
define('DB_TABLE_BLOG_USER',DB_PREFIX_TABLE.'blog_user');
define('DB_TABLE_BLOG_VOTE',DB_PREFIX_TABLE.'blog_vote');
define('DB_TABLE_TOPIC_COMMENT_VOTE',DB_PREFIX_TABLE.'topic_comment_vote');
define('DB_TABLE_USER_VOTE',DB_PREFIX_TABLE.'user_vote');
define('DB_TABLE_FAVOURITE_TOPIC',DB_PREFIX_TABLE.'favourite_topic');
define('DB_TABLE_TALK',DB_PREFIX_TABLE.'talk');
define('DB_TABLE_TALK_USER',DB_PREFIX_TABLE.'talk_user');
define('DB_TABLE_TALK_COMMENT',DB_PREFIX_TABLE.'talk_comment');
define('DB_TABLE_FREND',DB_PREFIX_TABLE.'frend');
define('DB_TABLE_TOPIC_CONTENT',DB_PREFIX_TABLE.'topic_content');
define('DB_TABLE_TOPIC_QUESTION_VOTE',DB_PREFIX_TABLE.'topic_question_vote');
?>