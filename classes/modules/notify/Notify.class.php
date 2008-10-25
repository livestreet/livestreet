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
 * Модуль рассылок уведомлений пользователям
 *
 */
class Notify extends Module {
	protected $oViewerLocal=null;
	/**
	 * Инициализация модуля
	 * Создаём локальный экземпляр модуля Viewer
	 * Момент довольно спорный, но позволяет избавить основной шаблон от мусора уведомлений
	 *
	 */
	public function Init() {		
		if (!class_exists('Viewer')) {
			require_once("./classes/modules/sys_viewer/Viewer.class.php");
		}
		$this->oViewerLocal=new Viewer(Engine::getInstance());
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
		$sBody=$this->oViewerLocal->Fetch("notify.comment_new.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject('К вашему топику оставили новый комментарий');
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
		$sBody=$this->oViewerLocal->Fetch("notify.comment_reply.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject('Вам ответили на ваш комментарий');
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
	public function SendTopicNewToSubscribeBlog(BlogEntity_BlogUser $oBlogUserTo, TopicEntity_Topic $oTopic, BlogEntity_Blog $oBlog, UserEntity_User $oUserTopic) {
		/**
		 * Проверяем можно ли юзеру рассылать уведомление
		 */
		if (!$oBlogUserTo->getUserSettingsNoticeNewTopic()) {
			return ;
		}
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oBlogUserTo',$oBlogUserTo);
		$this->oViewerLocal->Assign('oTopic',$oTopic);
		$this->oViewerLocal->Assign('oBlog',$oBlog);
		$this->oViewerLocal->Assign('oUserTopic',$oUserTopic);
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch("notify.topic_new.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oBlogUserTo->getUserMail(),$oBlogUserTo->getUserLogin());
		$this->Mail_SetSubject('Новый топик в блоге «'.htmlspecialchars($oBlog->getTitle()).'»');
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
		$sBody=$this->oViewerLocal->Fetch("notify.registration_activate.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject('Регистрация');
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
		$sBody=$this->oViewerLocal->Fetch("notify.registration.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject('Регистрация');
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
		$sBody=$this->oViewerLocal->Fetch("notify.invite.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($sMailTo);
		$this->Mail_SetSubject('Приглашение на регистрацию');
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
		$sBody=$this->oViewerLocal->Fetch("notify.talk_new.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject('У вас новое письмо');
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
		$sBody=$this->oViewerLocal->Fetch("notify.talk_comment_new.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject('У вас новый комментарий к письму');
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	
	public function SendUserFriendNew(UserEntity_User $oUserTo,UserEntity_User $oUserFrom) {		
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUserTo',$oUserTo);
		$this->oViewerLocal->Assign('oUserFrom',$oUserFrom);		
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch("notify.user_friend_new.tpl");
		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
		$this->Mail_SetSubject('Вас добавили в друзья');
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
}
?>