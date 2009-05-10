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
 * Модуль рассылок уведомлений пользователям
 *
 */
class LsNotify extends Module {
	protected $oViewerLocal=null;
	/**
	 * Инициализация модуля
	 * Создаём локальный экземпляр модуля Viewer
	 * Момент довольно спорный, но позволяет избавить основной шаблон от мусора уведомлений
	 *
	 */
	public function Init() {		
		if (!class_exists('LsViewer')) {
			require_once(DIR_SERVER_ROOT."/classes/modules/sys_viewer/Viewer.class.php");
		}
		$this->oViewerLocal=new LsViewer(Engine::getInstance());
		$this->oViewerLocal->Init();
		$this->oViewerLocal->VarAssign();
	}
	
	/**
	 * Отправляет юзеру уведомление о новом комментарии в его топике
	 *
	 * @param UserEntity_User $oUserTo
	 * @param TopicEntity_Topic $oTopic
	 * @param CommentEntity_TopicComment $oComment
	 * @param UserEntity_User $oUserComment
	 */
	public function SendCommentNewToAuthorTopic(UserEntity_User $oUserTo, TopicEntity_Topic $oTopic, CommentEntity_TopicComment $oComment, UserEntity_User $oUserComment) {
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
		 */
		if (!$oUserTo->getSettingsNoticeNewComment()) {
			return ;
		}
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUserTo',$oUserTo);
		$this->oViewerLocal->Assign('oTopic',$oTopic);
		$this->oViewerLocal->Assign('oComment',$oComment);
		$this->oViewerLocal->Assign('oUserComment',$oUserComment);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.comment_new.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_comment_new'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	
	/**
	 * Отправляет юзеру уведомление об ответе на его комментарий
	 *
	 * @param UserEntity_User $oUserTo
	 * @param TopicEntity_Topic $oTopic
	 * @param CommentEntity_TopicComment $oComment
	 * @param UserEntity_User $oUserComment
	 */
	public function SendCommentReplyToAuthorParentComment(UserEntity_User $oUserTo, TopicEntity_Topic $oTopic, CommentEntity_TopicComment $oComment, UserEntity_User $oUserComment) {
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
		 */
		if (!$oUserTo->getSettingsNoticeReplyComment()) {
			return ;
		}
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUserTo',$oUserTo);
		$this->oViewerLocal->Assign('oTopic',$oTopic);
		$this->oViewerLocal->Assign('oComment',$oComment);
		$this->oViewerLocal->Assign('oUserComment',$oUserComment);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.comment_reply.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_comment_reply'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	
	/**
	 * Отправляет юзеру уведомление о новом топике в блоге, в котором он состоит
	 *
	 * @param UserEntity_User $oUserTo
	 * @param TopicEntity_Topic $oTopic
	 * @param BlogEntity_Blog $oBlog
	 * @param UserEntity_User $oUserTopic
	 */
	public function SendTopicNewToSubscribeBlog(UserEntity_User $oUserTo, TopicEntity_Topic $oTopic, BlogEntity_Blog $oBlog, UserEntity_User $oUserTopic) {
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
		 */
		if (!$oUserTo->getSettingsNoticeNewTopic()) {
			return ;
		}
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUserTo',$oUserTo);
		$this->oViewerLocal->Assign('oTopic',$oTopic);
		$this->oViewerLocal->Assign('oBlog',$oBlog);
		$this->oViewerLocal->Assign('oUserTopic',$oUserTopic);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.topic_new.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_topic_new').' «'.htmlspecialchars($oBlog->getTitle()).'»');
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	
	/**
	 * Отправляет уведомление при регистрации с активацией
	 *
	 * @param UserEntity_User $oUser
	 * @param string $sPassword
	 */
	public function SendRegistrationActivate(UserEntity_User $oUser,$sPassword) {		
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUser',$oUser);		
		$this->oViewerLocal->Assign('sPassword',$sPassword);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.registration_activate.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_registration_activate'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	
	/**
	 * Отправляет уведомление о регистрации
	 *
	 * @param UserEntity_User $oUser
	 * @param string $sPassword
	 */
	public function SendRegistration(UserEntity_User $oUser,$sPassword) {		
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUser',$oUser);		
		$this->oViewerLocal->Assign('sPassword',$sPassword);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.registration.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_registration'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	
	/**
	 * Отправляет инвайт
	 *
	 * @param UserEntity_User $oUserFrom
	 * @param string $sMailTo
	 * @param UserEntity_Invite $oInvite
	 */
	public function SendInvite(UserEntity_User $oUserFrom,$sMailTo,UserEntity_Invite $oInvite) {		
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUserFrom',$oUserFrom);	
		$this->oViewerLocal->Assign('sMailTo',$sMailTo);	
		$this->oViewerLocal->Assign('oInvite',$oInvite);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.invite.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($sMailTo);
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_invite'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	
	/**
	 * Отправляет уведомление при новом личном сообщении
	 *
	 * @param UserEntity_User $oUserTo
	 * @param UserEntity_User $oUserFrom
	 * @param TalkEntity_Talk $oTalk
	 */
	public function SendTalkNew(UserEntity_User $oUserTo,UserEntity_User $oUserFrom,TalkEntity_Talk $oTalk) {
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
		 */
		if (!$oUserTo->getSettingsNoticeNewTalk()) {
			return ;
		}
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUserTo',$oUserTo);
		$this->oViewerLocal->Assign('oUserFrom',$oUserFrom);		
		$this->oViewerLocal->Assign('oTalk',$oTalk);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.talk_new.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_talk_new'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	
	public function SendTalkCommentNew(UserEntity_User $oUserTo,UserEntity_User $oUserFrom,TalkEntity_Talk $oTalk,TalkEntity_TalkComment $oTalkComment) {
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
		 */
		if (!$oUserTo->getSettingsNoticeNewTalk()) {
			return ;
		}
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUserTo',$oUserTo);
		$this->oViewerLocal->Assign('oUserFrom',$oUserFrom);		
		$this->oViewerLocal->Assign('oTalk',$oTalk);
		$this->oViewerLocal->Assign('oTalkComment',$oTalkComment);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.talk_comment_new.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_talk_comment_new'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	
	public function SendUserFriendNew(UserEntity_User $oUserTo,UserEntity_User $oUserFrom) {		
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
		 */
		if (!$oUserTo->getSettingsNoticeNewFriend()) {
			return ;
		}
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUserTo',$oUserTo);
		$this->oViewerLocal->Assign('oUserFrom',$oUserFrom);		
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.user_friend_new.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_user_friend_new'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	/**
	 * Уведомление при восстановлении пароля
	 *
	 * @param UserEntity_User $oUser
	 * @param UserEntity_Reminder $oReminder
	 */
	public function SendReminderCode(UserEntity_User $oUser,UserEntity_Reminder $oReminder) {		
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUser',$oUser);		
		$this->oViewerLocal->Assign('oReminder',$oReminder);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.reminder_code.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_reminder_code'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	/**
	 * Уведомление с новым паролем после его восставновления
	 *
	 * @param UserEntity_User $oUser
	 * @param unknown_type $sNewPassword
	 */
	public function SendReminderPassword(UserEntity_User $oUser,$sNewPassword) {		
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUser',$oUser);		
		$this->oViewerLocal->Assign('sNewPassword',$sNewPassword);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch('notify/'.$this->Lang_GetLang()."/notify.reminder_password.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_reminder_password'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
}
?>