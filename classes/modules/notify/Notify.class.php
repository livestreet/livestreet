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

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
require_once('mapper/Notify.mapper.class.php');

/**
 * Модуль рассылок уведомлений пользователям
 *
 */
class LsNotify extends Module {
	/**
	 * Статусы степени обработки заданий отложенной публикации в базе данных
	 */
	const NOTIFY_TASK_STATUS_NULL=1;
	/**
	 * Объект локального вьювера для рендеринга сообщений
	 *
	 * @var LsViewer
	 */
	protected $oViewerLocal=null;
	/**
	 * Массив заданий на удаленную публикацию
	 * 
	 * @var array
	 */
	protected $aTask=array();
	/**
	 * Меппер
	 *
	 * @var Mapper_Notify
	 */
	protected $oMapper=null;
	/**
	 * Инициализация модуля
	 * Создаём локальный экземпляр модуля Viewer
	 * Момент довольно спорный, но позволяет избавить основной шаблон от мусора уведомлений
	 *
	 */
	public function Init() {		
		if (!class_exists('LsViewer')) {
			require_once(Config::Get('path.root.engine')."/modules/viewer/Viewer.class.php");
		}
		$this->oViewerLocal=$this->Viewer_GetLocalViewer();
		$this->oMapper=new Mapper_Notify($this->Database_GetConnect());
	}
	
	/**
	 * Отправляет юзеру уведомление о новом комментарии в его топике
	 *
	 * @param UserEntity_User $oUserTo
	 * @param TopicEntity_Topic $oTopic
	 * @param CommentEntity_TopicComment $oComment
	 * @param UserEntity_User $oUserComment
	 */
	public function SendCommentNewToAuthorTopic(UserEntity_User $oUserTo, TopicEntity_Topic $oTopic, CommentEntity_Comment $oComment, UserEntity_User $oUserComment) {
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
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.comment_new.tpl'));
		
		/**
		 * Если в конфигураторе указан отложенный метод отправки, 
		 * то добавляем задание в массив. В противном случае,
		 * сразу отсылаем на email
		 */
		if(Config::Get('module.notify.delayed')) {
			$oNotifyTask=Engine::GetEntity(
				'Notify_Task', 
				array(
					'user_mail'      => $oUserTo->getMail(),
					'user_login'     => $oUserTo->getLogin(),
					'notify_text'    => $sBody,
					'notify_subject' => $this->Lang_Get('notify_subject_comment_new'),
					'date_created'   => date("Y-m-d H:i:s"),
					'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
				)
			);
			if(Config::Get('module.notify.insert_single')) {
				$this->aTask[] = $oNotifyTask;
			} else {
				$this->oMapper->AddTask($oNotifyTask);
			}
		} else {
			/**
			 * Отправляем мыло
			 */
			$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
			$this->Mail_SetSubject($this->Lang_Get('notify_subject_comment_new'));
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}		
	}
	
	/**
	 * Отправляет юзеру уведомление об ответе на его комментарий
	 *
	 * @param UserEntity_User $oUserTo
	 * @param TopicEntity_Topic $oTopic
	 * @param CommentEntity_TopicComment $oComment
	 * @param UserEntity_User $oUserComment
	 */
	public function SendCommentReplyToAuthorParentComment(UserEntity_User $oUserTo, TopicEntity_Topic $oTopic, CommentEntity_Comment $oComment, UserEntity_User $oUserComment) {
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
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.comment_reply.tpl'));
		/**
		 * Если в конфигураторе указан отложенный метод отправки, 
		 * то добавляем задание в массив. В противном случае,
		 * сразу отсылаем на email
		 */
		if(Config::Get('module.notify.delayed')) {
			$oNotifyTask=Engine::GetEntity(
				'Notify_Task', 
				array(
					'user_mail'      => $oUserTo->getMail(),
					'user_login'     => $oUserTo->getLogin(),
					'notify_text'    => $sBody,
					'notify_subject' => $this->Lang_Get('notify_subject_comment_reply'),
					'date_created'   => date("Y-m-d H:i:s"),
					'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
				)
			);
			if(Config::Get('module.notify.insert_single')) {
				$this->aTask[] = $oNotifyTask;
			} else {
				$this->oMapper->AddTask($oNotifyTask);
			}
		} else {
			/**
			 * Отправляем мыло
			 */
			$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
			$this->Mail_SetSubject($this->Lang_Get('notify_subject_comment_reply'));
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}
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
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.topic_new.tpl'));
		/**
		 * Если в конфигураторе указан отложенный метод отправки, 
		 * то добавляем задание в массив. В противном случае,
		 * сразу отсылаем на email
		 */
		if(Config::Get('module.notify.delayed')) {
			$oNotifyTask=Engine::GetEntity(
				'Notify_Task', 
				array(
					'user_mail'      => $oUserTo->getMail(),
					'user_login'     => $oUserTo->getLogin(),
					'notify_text'    => $sBody,
					'notify_subject' => $this->Lang_Get('notify_subject_topic_new').' «'.htmlspecialchars($oBlog->getTitle()).'»',
					'date_created'   => date("Y-m-d H:i:s"),
					'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
				)
			);
			if(Config::Get('module.notify.insert_single')) {
				$this->aTask[] = $oNotifyTask;
			} else {
				$this->oMapper->AddTask($oNotifyTask);
			}
		} else {		
			/**
			 * Отправляем мыло
			 */
			$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
			$this->Mail_SetSubject($this->Lang_Get('notify_subject_topic_new').' «'.htmlspecialchars($oBlog->getTitle()).'»');
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}
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
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.registration_activate.tpl'));
				
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
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.registration.tpl'));
	
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
		 * Если в конфигураторе указан отложенный метод отправки, 
		 * то добавляем задание в массив. В противном случае,
		 * сразу отсылаем на email
		 */
		if(Config::Get('module.notify.delayed')) {
			$oNotifyTask=Engine::GetEntity(
				'Notify_Task', 
				array(
					'user_mail'      => $sMailTo,
					'user_login'     => null,
					'notify_text'    => $sBody,
					'notify_subject' => $this->Lang_Get('notify_subject_invite'),
					'date_created'   => date("Y-m-d H:i:s"),
					'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
				)
			);
			if(Config::Get('module.notify.insert_single')) {
				$this->aTask[] = $oNotifyTask;
			} else {
				$this->oMapper->AddTask($oNotifyTask);
			}
		} else {	
			/**
			 * Отправляем мыло
			 */
			$this->Mail_SetAdress($sMailTo);
			$this->Mail_SetSubject($this->Lang_Get('notify_subject_invite'));
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}
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
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.talk_new.tpl'));
		/**
		 * Если в конфигураторе указан отложенный метод отправки, 
		 * то добавляем задание в массив. В противном случае,
		 * сразу отсылаем на email
		 */
		if(Config::Get('module.notify.delayed')) {
			$oNotifyTask=Engine::GetEntity(
				'Notify_Task', 
				array(
					'user_mail'      => $oUserTo->getMail(),
					'user_login'     => $oUserTo->getLogin(),
					'notify_text'    => $sBody,
					'notify_subject' => $this->Lang_Get('notify_subject_talk_new'),
					'date_created'   => date("Y-m-d H:i:s"),
					'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
				)
			);
			if(Config::Get('module.notify.insert_single')) {
				$this->aTask[] = $oNotifyTask;
			} else {
				$this->oMapper->AddTask($oNotifyTask);
			}
		} else {	
			/**
			 * Отправляем мыло
			 */
			$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
			$this->Mail_SetSubject($this->Lang_Get('notify_subject_talk_new'));
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}
	}
	
	public function SendTalkCommentNew(UserEntity_User $oUserTo,UserEntity_User $oUserFrom,TalkEntity_Talk $oTalk,CommentEntity_Comment $oTalkComment) {
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
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.talk_comment_new.tpl'));
		/**
		 * Если в конфигураторе указан отложенный метод отправки, 
		 * то добавляем задание в массив. В противном случае,
		 * сразу отсылаем на email
		 */
		if(Config::Get('module.notify.delayed')) {
			$oNotifyTask=Engine::GetEntity(
				'Notify_Task', 
				array(
					'user_mail'      => $oUserTo->getMail(),
					'user_login'     => $oUserTo->getLogin(),
					'notify_text'    => $sBody,
					'notify_subject' => $this->Lang_Get('notify_subject_talk_comment_new'),
					'date_created'   => date("Y-m-d H:i:s"),
					'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
				)
			);
			if(Config::Get('module.notify.insert_single')) {
				$this->aTask[] = $oNotifyTask;
			} else {
				$this->oMapper->AddTask($oNotifyTask);
			}
		} else {	
			/**
			 * Отправляем мыло
			 */
			$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
			$this->Mail_SetSubject($this->Lang_Get('notify_subject_talk_comment_new'));
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}
	}
	
	/**
	 * Отправляет пользователю сообщение о добавлении его в друзья
	 *
	 * @param UserEntity_User $oUserTo
	 * @param UserEntity_User $oUserFrom
	 */
	public function SendUserFriendNew(UserEntity_User $oUserTo,UserEntity_User $oUserFrom, $sText,$sPath) {		
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
		$this->oViewerLocal->Assign('sText',$sText);
		$this->oViewerLocal->Assign('sPath',$sPath);
		
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.user_friend_new.tpl'));
		/**
		 * Если в конфигураторе указан отложенный метод отправки, 
		 * то добавляем задание в массив. В противном случае,
		 * сразу отсылаем на email
		 */
		if(Config::Get('module.notify.delayed')) {
			$oNotifyTask=Engine::GetEntity(
				'Notify_Task', 
				array(
					'user_mail'      => $oUserTo->getMail(),
					'user_login'     => $oUserTo->getLogin(),
					'notify_text'    => $sBody,
					'notify_subject' => $this->Lang_Get('notify_subject_user_friend_new'),
					'date_created'   => date("Y-m-d H:i:s"),
					'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
				)
			);
			if(Config::Get('module.notify.insert_single')) {
				$this->aTask[] = $oNotifyTask;
			} else {
				$this->oMapper->AddTask($oNotifyTask);
			}
		} else {	
			/**
			 * Отправляем мыло
			 */
			$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
			$this->Mail_SetSubject($this->Lang_Get('notify_subject_user_friend_new'));
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}
	}

	/**
	 * Отправляет пользователю сообщение о приглашение его в закрытый блог
	 *
	 * @param UserEntity_User $oUserTo
	 * @param UserEntity_User $oUserFrom
	 */
	public function SendBlogUserInvite(UserEntity_User $oUserTo,UserEntity_User $oUserFrom, BlogEntity_Blog $oBlog,$sPath) {		
		/**
		 * Передаём в шаблон переменные
		 */
		$this->oViewerLocal->Assign('oUserTo',$oUserTo);
		$this->oViewerLocal->Assign('oUserFrom',$oUserFrom);		
		$this->oViewerLocal->Assign('oBlog',$oBlog);
		$this->oViewerLocal->Assign('sPath',$sPath);
		
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.blog_invite_new.tpl'));
		/**
		 * Если в конфигураторе указан отложенный метод отправки, 
		 * то добавляем задание в массив. В противном случае,
		 * сразу отсылаем на email
		 */
		if(Config::Get('module.notify.delayed')) {
			$oNotifyTask=Engine::GetEntity(
				'Notify_Task', 
				array(
					'user_mail'      => $oUserTo->getMail(),
					'user_login'     => $oUserTo->getLogin(),
					'notify_text'    => $sBody,
					'notify_subject' => $this->Lang_Get('notify_subject_blog_invite_new'),
					'date_created'   => date("Y-m-d H:i:s"),
					'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
				)
			);
			if(Config::Get('module.notify.insert_single')) {
				$this->aTask[] = $oNotifyTask;
			} else {
				$this->oMapper->AddTask($oNotifyTask);
			}
		} else {	
			/**
			 * Отправляем мыло
			 */
			$this->Mail_SetAdress($oUserTo->getMail(),$oUserTo->getLogin());
			$this->Mail_SetSubject($this->Lang_Get('notify_subject_blog_invite_new'));
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}
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
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.reminder_code.tpl'));

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
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath('notify.reminder_password.tpl'));

		/**
		 * Отправляем мыло
		 */
		$this->Mail_SetAdress($oUser->getMail(),$oUser->getLogin());
		$this->Mail_SetSubject($this->Lang_Get('notify_subject_reminder_password'));
		$this->Mail_SetBody($sBody);
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	
	/**
	 * Универсальный метод отправки уведомлений на email
	 *
	 * @param UserEntity_User | string $oUserTo - кому отправляем (пользователь или email)
	 * @param unknown_type $sTemplate - шаблон для отправки
	 * @param unknown_type $sSubject - тема письма
	 * @param unknown_type $aAssign - ассоциативный массив для загрузки переменных в шаблон письма
	 * @param unknown_type $sPluginName - плагин из которого происходит отправка
	 */
	public function Send($oUserTo,$sTemplate,$sSubject,$aAssign=array(),$sPluginName=null) {		
		if ($oUserTo instanceof UserEntity_User) {
			$sMail=$oUserTo->getMail();
			$sName=$oUserTo->getLogin();
		} else {
			$sMail=$oUserTo;
			$sName='';
		}
		/**
		 * Передаём в шаблон переменные
		 */
		foreach ($aAssign as $k=>$v) {
			$this->oViewerLocal->Assign($k,$v);
		}				
		/**
		 * Формируем шаблон
		 */
		$sBody=$this->oViewerLocal->Fetch($this->GetTemplatePath($sTemplate,$sPluginName));
		/**
		 * Если в конфигураторе указан отложенный метод отправки, 
		 * то добавляем задание в массив. В противном случае,
		 * сразу отсылаем на email
		 */
		if(Config::Get('module.notify.delayed')) {
			$oNotifyTask=Engine::GetEntity(
				'Notify_Task', 
				array(
					'user_mail'      => $sMail,
					'user_login'     => $sName,
					'notify_text'    => $sBody,
					'notify_subject' => $sSubject,
					'date_created'   => date("Y-m-d H:i:s"),
					'notify_task_status' => self::NOTIFY_TASK_STATUS_NULL,
				)
			);
			if(Config::Get('module.notify.insert_single')) {
				$this->aTask[] = $oNotifyTask;
			} else {
				$this->oMapper->AddTask($oNotifyTask);
			}
		} else {	
			/**
			 * Отправляем мыло
			 */
			$this->Mail_SetAdress($sMail,$sName);
			$this->Mail_SetSubject($sSubject);
			$this->Mail_SetBody($sBody);
			$this->Mail_setHTML();
			$this->Mail_Send();
		}
	}
	
	
	/**
	 * При завершении работы модуля проверяем наличие 
	 * отложенных заданий в массиве и при необходимости
	 * передаем их в меппер
	 */	
	public function Shutdown() {
		if(!empty($this->aTask) && Config::Get('module.notify.delayed')) {
			$this->oMapper->AddTaskArray($this->aTask);
			$this->aTask=array();
		}
	}
	
	/**
	 * Получает массив заданий на публикацию из базы 
	 * с указанным количественным ограничением (выборка FIFO)
	 *
	 * @param  int   $iLimit
	 * @return array
	 */
	public function GetTasksDelayed($iLimit=10) {
		return ($aResult=$this->oMapper->GetTasks($iLimit))
			? $aResult
			: array();
	}
	/**
	 * Отправляет на e-mail 
	 *
	 * @param NotifyEntity_Task $oTask
	 */
	public function SendTask($oTask) {
		$this->Mail_SetAdress($oTask->getUserMail(),$oTask->getUserLogin());
		$this->Mail_SetSubject($oTask->getNotifySubject());
		$this->Mail_SetBody($oTask->getNotifyText());
		$this->Mail_setHTML();
		$this->Mail_Send();
	}
	/**
	 * Удаляет отложенное Notify-задание из базы
	 *
	 * @param  NotifyEntity_Task $oTask
	 * @return bool
	 */
	public function DeleteTask($oTask) {
		return $this->oMapper->DeleteTask($oTask);
	}
	/**
	 * Удаляет отложенные Notify-задания по списку идентификаторов
	 *
	 * @param  array $aArrayId
	 * @return bool	 
	 */
	public function DeleteTaskByArrayId($aArrayId) {
		return $this->oMapper->DeleteTaskByArrayId($aArrayId);
	}
	
	/**
	 * Возвращает путь к шаблону по переданному имени
	 *
	 * @param  string $sName
	 * @param  string $sPluginName
	 * @return string
	 */
	public function GetTemplatePath($sName,$sPluginName=null) {		
		if ($sPluginName) {
			$sPluginName = preg_match('/^Plugin([\w]+)(_[\w]+)?$/Ui',$sPluginName,$aMatches)
			? strtolower($aMatches[1])
			: strtolower($sPluginName);
			
			$sLangDir=Plugin::GetTemplatePath($sPluginName).'notify/'.$this->Lang_GetLang();
			if(is_dir($sLangDir)) {
				return $sLangDir.'/'.$sName;
			}
			return Plugin::GetTemplatePath($sPluginName).'notify/'.$this->Lang_GetLangDefault().'/'.$sName;
		} else {
			$sLangDir = 'notify/'.$this->Lang_GetLang();
			/**
		 	* Если директория с сообщениями на текущем языке отсутствует,
		 	* используем язык по умолчанию
		 	*/
			if(is_dir(rtrim(Config::Get('path.static.skin'),'/').'/'.$sLangDir)) {
				return $sLangDir.'/'.$sName;
			}
			return 'notify/'.$this->Lang_GetLangDefault().'/'.$sName;
		}
	}
}
?>