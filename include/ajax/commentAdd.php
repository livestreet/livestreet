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
 * Добавление комментария
 */

set_include_path(get_include_path().PATH_SEPARATOR.dirname(dirname(dirname(__FILE__))));
$sDirRoot=dirname(dirname(dirname(__FILE__)));
require_once($sDirRoot."/config/config.ajax.php");

$aParams=@$_REQUEST;
$bStateError=true;
$sMsg=$oEngine->Lang_Get('system_error');
$sMsgTitle=$oEngine->Lang_Get('error');
$sCommentId=0;

if (get_magic_quotes_gpc()) {
	func_stripslashes($aParams);
}

if ($oEngine->User_IsAuthorization()) {	
	$oUserCurrent=$oEngine->User_GetUserCurrent();
	
		if (isset($aParams['cmt_topic_id']) and $oTopic=$oEngine->Topic_GetTopicById($aParams['cmt_topic_id'],$oUserCurrent)) {
	
			$bOK=true;			
			/**
			 * Проверяем разрешено ли постить комменты
			 */
			if (!$oEngine->ACL_CanPostComment($oUserCurrent) and !$oUserCurrent->isAdministrator()) {				
				$sMsg=$oEngine->Lang_Get('topic_comment_acl');
				$bOK=false;
			}			
			/**
			 * Проверяем разрешено ли постить комменты по времени
			 */
			if (!$oEngine->ACL_CanPostCommentTime($oUserCurrent) and !$oUserCurrent->isAdministrator()) {				
				$sMsg=$oEngine->Lang_Get('topic_comment_limit');
				$bOK=false;
			}
			/**
			 * Проверяем запрет на добавления коммента автором топика
			 */
			if ($oTopic->getForbidComment()) {				
				$sMsg=$oEngine->Lang_Get('topic_comment_notallow');
				$bOK=false;
			}
			
			/**
			 * Проверяем текст комментария
			 */
			$sText=$oEngine->Text_Parser($aParams['comment_text']);
			if (!func_check($sText,'text',2,10000)) {				
				$sMsg=$oEngine->Lang_Get('topic_comment_add_text_error');
				$bOK=false;
			}			
			/**
			 * Проверям на какой коммент отвечаем
			 */
			$sParentId=(int)$aParams['reply'];
			if (!func_check($sParentId,'id')) {				
				$sMsg=$oEngine->Lang_Get('system_error');
				$bOK=false;
			}
			$oCommentParent=null;
			if ($sParentId!=0) {
				/**
				 * Проверяем существует ли комментарий на который отвечаем
				 */
				if (!($oCommentParent=$oEngine->Comment_GetCommentById($sParentId))) {
					$sMsg=$oEngine->Lang_Get('system_error');
					$bOK=false;
				}
				/**
				 * Проверяем из одного топика ли новый коммент и тот на который отвечаем
				 */
				if ($oCommentParent->getTopicId()!=$oTopic->getId()) {
					$sMsg=$oEngine->Lang_Get('system_error');
					$bOK=false;
				}
			} else {
				/**
				 * Корневой комментарий
				 */
				$sParentId=null;
			}
			/**
			 * Проверка на дублирующий коммент
			 */
			if ($oEngine->Comment_GetCommentUnique($oTopic->getId(),$oUserCurrent->getId(),$sParentId,md5($sText))) {				
				$sMsg=$oEngine->Lang_Get('topic_comment_spam');
				$bOK=false;
			}			
			/**
			 * Создаём коммент
			 */
			$oCommentNew=new CommentEntity_TopicComment();
			$oCommentNew->setTopicId($oTopic->getId());
			$oCommentNew->setUserId($oUserCurrent->getId());
			/**
			 * Парсим коммент на предмет ХТМЛ тегов
			 */
						
			$oCommentNew->setText($sText);
			$oCommentNew->setDate(date("Y-m-d H:i:s"));
			$oCommentNew->setUserIp(func_getIp());
			$oCommentNew->setPid($sParentId);
			$oCommentNew->setTextHash(md5($sText));
			/**
			 * Добавляем коммент
			 */
			if ($bOK and $oEngine->Comment_AddComment($oCommentNew)) {
				$sCommentId=$oCommentNew->getId();
				
				if ($oTopic->getPublish()) {
					/**
			 		* Добавляем коммент в прямой эфир если топик не в черновиках
			 		*/					
					$oTopicCommentOnline=new CommentEntity_TopicCommentOnline();
					$oTopicCommentOnline->setTopicId($oCommentNew->getTopicId());
					$oTopicCommentOnline->setCommentId($oCommentNew->getId());
					$oEngine->Comment_AddTopicCommentOnline($oTopicCommentOnline);
				}
				/**
				 * Сохраняем дату последнего коммента для юзера
				 */
				$oUserCurrent->setDateCommentLast(date("Y-m-d H:i:s"));
				$oEngine->User_Update($oUserCurrent);
				/**
				 * Отправка уведомления автору топика
				 */
				$oUserTopic=$oEngine->User_GetUserById($oTopic->getUserId());
				if ($oCommentNew->getUserId()!=$oUserTopic->getId()) {					
					$oEngine->Notify_SendCommentNewToAuthorTopic($oUserTopic,$oTopic,$oCommentNew,$oUserCurrent);
				}
				/**
				 * Отправляем уведомление тому на чей коммент ответили
				 */
				if ($oCommentParent and $oCommentParent->getUserId()!=$oTopic->getUserId() and $oCommentNew->getUserId()!=$oCommentParent->getUserId()) {					
					$oUserAuthorComment=$oEngine->User_GetUserById($oCommentParent->getUserId());					
					$oEngine->Notify_SendCommentReplyToAuthorParentComment($oUserAuthorComment,$oTopic,$oCommentNew,$oUserCurrent);					
				}
				$bStateError=false;
			} else {				
				//$sMsgTitle=$oEngine->Lang_Get('error');
				//$sMsg=$oEngine->Lang_Get('system_error');				
			}
		}	
} else {
	$sMsgTitle=$oEngine->Lang_Get('error');
	$sMsg=$oEngine->Lang_Get('need_authorization');
}

$GLOBALS['_RESULT'] = array(
"bStateError"     => $bStateError,
"sMsgTitle"   => $sMsgTitle,
"sMsg"   => $sMsg,
"sCommentId" => $sCommentId,
);

?>