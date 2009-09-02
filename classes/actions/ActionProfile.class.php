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
 * Обрабатывает профайл юзера, т.е. УРЛ вида /profile/login/
 *
 */
class ActionProfile extends Action {
	/**
	 * Логин юзера из УРЛа
	 *
	 * @var unknown_type
	 */
	protected $sUserLogin=null;
	/**
	 * Объект юзера чей профиль мы смотрим
	 *
	 * @var unknown_type
	 */
	protected $oUserProfile;
	
	public function Init() {	
		$this->Viewer_AddBlocks('right',array('actions/ActionProfile/sidebar.tpl'));
	}
	
	protected function RegisterEvent() {			
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^(whois)?$/i','EventWhois');				
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^favourites$/i','/^comments$/i','/^(page(\d+))?$/i','EventFavouriteComments');			
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^favourites$/i','/^(page(\d+))?$/i','EventFavourite');			
		$this->AddEvent('friendoffer','EventFriendOffer');
	}
			
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	/**
	 * Выводит список избранноего юзера
	 *
	 */
	protected function EventFavourite() {	
		/**
		 * Получаем логин из УРЛа
		 */
		$sUserLogin=$this->sCurrentEvent;					
		/**
		 * Проверяем есть ли такой юзер
		 */
		if (!($this->oUserProfile=$this->User_GetUserByLogin($sUserLogin))) {			
			return parent::EventNotFound();
		}	
		/**
		 * Передан ли номер страницы
		 */		
		$iPage=$this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;		
		/**
		 * Получаем список избранных топиков
		 */				
		$aResult=$this->Topic_GetTopicsFavouriteByUserId($this->oUserProfile->getId(),$iPage,Config::Get('module.topic.per_page'));			
		$aTopics=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */					
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),4,Router::GetPath('profile').$this->oUserProfile->getLogin().'/favourites');		
		/**
		 * Загружаем переменные в шаблон
		 */			
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_profile').' '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_profile_favourites'));
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('favourites');
	}
	/**
	 * Выводит список избранноего юзера
	 *
	 */
	protected function EventFavouriteComments() {	
		/**
		 * Получаем логин из УРЛа
		 */
		$sUserLogin=$this->sCurrentEvent;					
		/**
		 * Проверяем есть ли такой юзер
		 */
		if (!($this->oUserProfile=$this->User_GetUserByLogin($sUserLogin))) {			
			return parent::EventNotFound();
		}	
		/**
		 * Передан ли номер страницы
		 */		
		$iPage=$this->GetParamEventMatch(2,2) ? $this->GetParamEventMatch(2,2) : 1;		
		/**
		 * Получаем список избранных комментариев
		 */				
		$aResult=$this->Comment_GetCommentsFavouriteByUserId($this->oUserProfile->getId(),$iPage,Config::Get('module.comment.per_page'));			
		$aComments=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */					
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.comment.per_page'),4,Router::GetPath('profile').$this->oUserProfile->getLogin().'/favourites/comments');		
		/**
		 * Загружаем переменные в шаблон
		 */			
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aComments',$aComments);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_profile').' '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_profile_favourites_comments'));
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('comments');
	}	
	/**
	 * Показывает инфу профиля
	 *
	 */
	protected function EventWhois() {
		/**
		 * Получаем логин из УРЛа
		 */
		$sUserLogin=$this->sCurrentEvent;					
		/**
		 * Проверяем есть ли такой юзер
		 */
		if (!($this->oUserProfile=$this->User_GetUserByLogin($sUserLogin))) {			
			return parent::EventNotFound();
		}
		/**
		 * Получаем список друзей
		 */
		$aUsersFriend=$this->User_GetUsersFriend($this->oUserProfile->getId());
		
		if (Config::Get('general.reg.invite')) {
			/**
			 * Получаем список тех кого пригласил юзер
			 */
			$aUsersInvite=$this->User_GetUsersInvite($this->oUserProfile->getId());	
			$this->Viewer_Assign('aUsersInvite',$aUsersInvite);
			/**
			 * Получаем того юзера, кто пригласил текущего
			 */
			$oUserInviteFrom=$this->User_GetUserInviteFrom($this->oUserProfile->getId());			
			$this->Viewer_Assign('oUserInviteFrom',$oUserInviteFrom);
		}	
		/**
		 * Получаем список юзеров блога
		 */
		$aBlogUsers=$this->Blog_GetBlogUsersByUserId($this->oUserProfile->getId(),0);
		$aBlogModerators=$this->Blog_GetBlogUsersByUserId($this->oUserProfile->getId(),1);
		$aBlogAdministrators=$this->Blog_GetBlogUsersByUserId($this->oUserProfile->getId(),2);		
		/**
		 * Получаем список блогов которые создал юзер
		 */
		$aBlogsOwner=$this->Blog_GetBlogsByOwnerId($this->oUserProfile->getId());
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('profile_whois_show',array("oUserProfile"=>$this->oUserProfile));	
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aBlogUsers',$aBlogUsers);
		$this->Viewer_Assign('aBlogModerators',$aBlogModerators);
		$this->Viewer_Assign('aBlogAdministrators',$aBlogAdministrators);
		$this->Viewer_Assign('aBlogsOwner',$aBlogsOwner);
		$this->Viewer_Assign('aUsersFriend',$aUsersFriend);		
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_profile').' '.$this->oUserProfile->getLogin());
		$this->Viewer_AddHtmlTitle($this->Lang_Get('user_menu_profile_whois'));
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('whois');				
	}	
	
	/**
	 * Добавление пользователя в друзья, по отправленной заявке
	 */
	public function EventFriendOffer() {	
		$sUserId=$this->GetParam(1);
		$sAction=$this->GetParam(0);
		
		/**
		 * Получаем текущего пользователя
		 */
		if(!$this->User_IsAuthorization()) {
			return $this->EventNotFound();
		}
		$oUserCurrent = $this->User_GetUserCurrent();
		
		/**
		 * Получаем объект пользователя приславшего заявку,
		 * если пользователь не найден, переводим в раздел сообщений (Talk) -
		 * так как пользователь мог перейти сюда либо из talk-сообщений,
		 * либо из e-mail письма-уведомления
		 */
		if(!$oUser=$this->User_GetUserById($sUserId)) {
			$this->Message_AddError($this->Lang_Get('user_not_found'),$this->Lang_Get('error'),true);
			func_header_location(Router::GetPath('talk'));
			return ;
		}
		
		/**
		 * Получаем связь дружбы из базы данных.
		 * Если связь не найдена либо статус отличен от OFFER,
		 * переходим в раздел Talk и возвращаем сообщение об ошибке
		 */
		$oFriend=$this->User_GetFriend($oUserCurrent->getId(),$oUser->getId(),0);
		if(!$oFriend || ($oFriend->getFriendStatus()!=LsUser::USER_FRIEND_OFFER+LsUser::USER_FRIEND_NULL)) {
			$this->Message_AddError($this->Lang_Get('user_not_found'),$this->Lang_Get('error'),true);
			func_header_location(Router::GetPath('talk'));
			return ;			
		}

		/**
		 * Устанавливаем новый статус связи
		 */
		$oFriend->setStatusTo(
			($sAction=='accept')
				? LsUser::USER_FRIEND_ACCEPT
				: LsUser::USER_FRIEND_REJECT
		);
		
		if ($this->User_UpdateFriend($oFriend)) {
			$this->Message_AddNoticeSingle(
				$this->Lang_Get('user_friend_add_ok'),
				$this->Lang_Get('attention'),
				true
			);
		} else {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('system_error'),
				$this->Lang_Get('error'),
				true
			);
		}
		func_header_location(Router::GetPath('talk'));
	}
	
	/**
	 * Выполняется при завершении работы экшена
	 *
	 */
	public function EventShutdown() {	
		if (!$this->oUserProfile)	 {
			return ;
		}
		/**
		 * Загружаем в шаблон необходимые переменные
		 */
		$iCountTopicFavourite=$this->Topic_GetCountTopicsFavouriteByUserId($this->oUserProfile->getId());
		$iCountTopicUser=$this->Topic_GetCountTopicsPersonalByUser($this->oUserProfile->getId(),1);
		$iCountCommentUser=$this->Comment_GetCountCommentsByUserId($this->oUserProfile->getId(),'topic');
		$iCountCommentFavourite=$this->Comment_GetCountCommentsFavouriteByUserId($this->oUserProfile->getId());
		
		$this->Viewer_Assign('oUserProfile',$this->oUserProfile);		
		$this->Viewer_Assign('iCountTopicUser',$iCountTopicUser);		
		$this->Viewer_Assign('iCountCommentUser',$iCountCommentUser);		
		$this->Viewer_Assign('iCountTopicFavourite',$iCountTopicFavourite);
		$this->Viewer_Assign('iCountCommentFavourite',$iCountCommentFavourite);
		$this->Viewer_Assign('USER_FRIEND_NULL',LsUser::USER_FRIEND_NULL);
		$this->Viewer_Assign('USER_FRIEND_OFFER',LsUser::USER_FRIEND_OFFER);
		$this->Viewer_Assign('USER_FRIEND_ACCEPT',LsUser::USER_FRIEND_ACCEPT);
		$this->Viewer_Assign('USER_FRIEND_REJECT',LsUser::USER_FRIEND_REJECT);
		$this->Viewer_Assign('USER_FRIEND_DELETE',LsUser::USER_FRIEND_DELETE);		
	}
}
?>