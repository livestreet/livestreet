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
	}
	
	protected function RegisterEvent() {			
		$this->AddEvent('friendoffer','EventFriendOffer');
		$this->AddEvent('ajaxfriendadd', 'EventAjaxFriendAdd');
		$this->AddEvent('ajaxfrienddelete', 'EventAjaxFriendDelete');
		$this->AddEvent('ajaxfriendaccept', 'EventAjaxFriendAccept');
				
		$this->AddEventPreg('/^.+$/i','/^(whois)?$/i','EventWhois');
		$this->AddEventPreg('/^.+$/i','/^favourites$/i','/^comments$/i','/^(page(\d+))?$/i','EventFavouriteComments');
		$this->AddEventPreg('/^.+$/i','/^favourites$/i','/^(page(\d+))?$/i','EventFavourite');
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
		$aBlogUsers=$this->Blog_GetBlogUsersByUserId($this->oUserProfile->getId(),ModuleBlog::BLOG_USER_ROLE_USER);
		$aBlogModerators=$this->Blog_GetBlogUsersByUserId($this->oUserProfile->getId(),ModuleBlog::BLOG_USER_ROLE_MODERATOR);
		$aBlogAdministrators=$this->Blog_GetBlogUsersByUserId($this->oUserProfile->getId(),ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);		
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
		require_once Config::Get('path.root.engine').'/lib/external/XXTEA/encrypt.php';		
		$sUserId=xxtea_decrypt(base64_decode(rawurldecode(getRequest('code'))), Config::Get('module.talk.encrypt'));
		if (!$sUserId) {
			return $this->EventNotFound();
		}
		list($sUserId,)=explode('_',$sUserId,2);
		
		$sAction=$this->GetParam(0);
		
		/**
		 * Получаем текущего пользователя
		 */
		if(!$this->User_IsAuthorization()) {
			return $this->EventNotFound();
		}
		$this->oUserCurrent = $this->User_GetUserCurrent();
		
		/**
		 * Получаем объект пользователя приславшего заявку,
		 * если пользователь не найден, переводим в раздел сообщений (Talk) -
		 * так как пользователь мог перейти сюда либо из talk-сообщений,
		 * либо из e-mail письма-уведомления
		 */
		if(!$oUser=$this->User_GetUserById($sUserId)) {
			$this->Message_AddError($this->Lang_Get('user_not_found'),$this->Lang_Get('error'),true);
			Router::Location(Router::GetPath('talk'));
			return ;
		}
		
		/**
		 * Получаем связь дружбы из базы данных.
		 * Если связь не найдена либо статус отличен от OFFER,
		 * переходим в раздел Talk и возвращаем сообщение об ошибке
		 */
		$oFriend=$this->User_GetFriend($this->oUserCurrent->getId(),$oUser->getId(),0);
		if(!$oFriend 
			|| !in_array(
					$oFriend->getFriendStatus(), 
					array(
						ModuleUser::USER_FRIEND_OFFER+ModuleUser::USER_FRIEND_NULL,
					)
				)
			) {
			$sMessage=($oFriend)
				? $this->Lang_Get('user_friend_offer_already_done')
				: $this->Lang_Get('user_friend_offer_not_found');
			$this->Message_AddError($sMessage,$this->Lang_Get('error'),true);
			
			Router::Location(Router::GetPath('talk'));
			return ;			
		}		
		
		/**
		 * Устанавливаем новый статус связи
		 */
		$oFriend->setStatusTo(
			($sAction=='accept')
				? ModuleUser::USER_FRIEND_ACCEPT
				: ModuleUser::USER_FRIEND_REJECT
		);
		
		if ($this->User_UpdateFriend($oFriend)) {
			$sMessage=($sAction=='accept')
				? $this->Lang_Get('user_friend_add_ok')
				: $this->Lang_Get('user_friend_offer_reject');
			
			$this->Message_AddNoticeSingle($sMessage,$this->Lang_Get('attention'),true);
			$this->NoticeFriendOffer($oUser,$sAction);
		} else {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('system_error'),
				$this->Lang_Get('error'),
				true
			);
		}
		Router::Location(Router::GetPath('talk'));
	}
	
	public function EventAjaxFriendAccept() {
		$this->Viewer_SetResponseAjax('json');
		$sUserId=getRequest('idUser',null,'post');

		/**
		 * Если пользователь не авторизирован, возвращаем ошибку
		 */		
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('need_authorization'),
				$this->Lang_Get('error')
			);
			return;				
		}
		$this->oUserCurrent=$this->User_GetUserCurrent();
		/**
		 * При попытке добавить в друзья себя, возвращаем ошибку
		 */
		if ($this->oUserCurrent->getId()==$sUserId) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_friend_add_self'),
				$this->Lang_Get('error')
			);
			return;
		}
		
		/**
		 * Если пользователь не найден, возвращаем ошибку
		 */
		if( !$oUser=$this->User_GetUserById($sUserId) ) {		
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_not_found'),
				$this->Lang_Get('error')
			);
			return;				
		}
		$this->oUserProfile=$oUser;
		/**
		 * Получаем статус дружбы между пользователями
		 */
		$oFriend=$this->User_GetFriend($oUser->getId(),$this->oUserCurrent->getId());		
		/**
		 * При попытке потдвердить ранее отклоненную заявку,
		 * проверяем, чтобы изменяющий был принимающей стороной
		 */
		if($oFriend 
			&& ($oFriend->getStatusFrom()==ModuleUser::USER_FRIEND_OFFER||$oFriend->getStatusFrom()==ModuleUser::USER_FRIEND_ACCEPT) 
			&& ($oFriend->getStatusTo()==ModuleUser::USER_FRIEND_REJECT||$oFriend->getStatusTo()==ModuleUser::USER_FRIEND_NULL) 
			&& $oFriend->getUserTo()==$this->oUserCurrent->getId()) {
			
				/**
				 * Меняем статус с отвергнутое, на акцептованное				 
				 */
				$oFriend->setStatusByUserId(ModuleUser::USER_FRIEND_ACCEPT,$this->oUserCurrent->getId());
				if($this->User_UpdateFriend($oFriend)) {
					$this->Message_AddNoticeSingle($this->Lang_Get('user_friend_add_ok'),$this->Lang_Get('attention'));
					$this->NoticeFriendOffer($oUser,'accept');
					
					$oViewerLocal=$this->GetViewerLocal();
					$oViewerLocal->Assign('oUserFriend',$oFriend);
					$this->Viewer_AssignAjax('sToggleText',$oViewerLocal->Fetch("actions/ActionProfile/friend_item.tpl"));		
				
				} else {
					$this->Message_AddErrorSingle(
						$this->Lang_Get('system_error'),
						$this->Lang_Get('error')
					);
				}
				return;
		}

		$this->Message_AddErrorSingle(
			$this->Lang_Get('system_error'),
			$this->Lang_Get('error')
		);
		return;	
	}

	/**
	 * Отправляет пользователю Talk уведомление о принятии или отклонении его заявки
	 *
	 * @param ModuleUser_EntityUser $oUser
	 * @param stirng          $sAction
	 */
	protected function NoticeFriendOffer($oUser,$sAction) {
		/**
		 * Проверяем допустимость действия
		 */
		if(!in_array($sAction,array('accept','reject'))) {
			return false;
		}
		/**
		 * Проверяем настройки (нужно ли отправлять уведомление)
		 */
		if(!Config::Get("module.user.friend_notice.{$sAction}")) {
			return false;
		}
		
		$sTitle=$this->Lang_Get("user_friend_{$sAction}_notice_title");
		$sText=$this->Lang_Get(
			"user_friend_{$sAction}_notice_text",
			array(
				'login'=>$this->oUserCurrent->getLogin(),
			)
		);
		$oTalk=$this->Talk_SendTalk($sTitle,$sText,$this->oUserCurrent,array($oUser),false,false);
		$this->Talk_DeleteTalkUserByArray($oTalk->getId(),$this->oUserCurrent->getId());
	}
	
	public function EventAjaxFriendAdd() {
		$this->Viewer_SetResponseAjax('json');
		$sUserId=getRequest('idUser');
		$sUserText=getRequest('userText','');

		/**
		 * Если пользователь не авторизирован, возвращаем ошибку
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('need_authorization'),
				$this->Lang_Get('error')
			);
			return;				
		}
		$this->oUserCurrent=$this->User_GetUserCurrent();
				
		/**
		 * При попытке добавить в друзья себя, возвращаем ошибку
		 */
		if ($this->oUserCurrent->getId()==$sUserId) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_friend_add_self'),
				$this->Lang_Get('error')
			);
			return;
		}
		
		/**
		 * Если пользователь не найден, возвращаем ошибку
		 */
		if( !$oUser=$this->User_GetUserById($sUserId) ) {		
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_not_found'),
				$this->Lang_Get('error')
			);
			return;				
		}
		$this->oUserProfile=$oUser;
		/**
		 * Получаем статус дружбы между пользователями
		 */
		$oFriend=$this->User_GetFriend($oUser->getId(),$this->oUserCurrent->getId());
		/**
		 * Если связи ранее не было в базе данных, добавляем новую
		 */
		if( !$oFriend ) {		
			$this->SubmitAddFriend($oUser,$sUserText,$oFriend);
			return;		
		}
		/**
		 * Если статус связи соответствует статусам отправленной и акцептованной заявки, 
		 * то предупреждаем что этот пользователь уже является нашим другом
		 */
		if($oFriend->getFriendStatus()==ModuleUser::USER_FRIEND_OFFER + ModuleUser::USER_FRIEND_ACCEPT) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_friend_already_exist'),
				$this->Lang_Get('error')
			);
			return;
		}
		/**
		 * Если пользователь ранее отклонил нашу заявку, 
		 * возвращаем сообщение об ошибке
		 */
		if($oFriend->getUserFrom()==$this->oUserCurrent->getId() 
				&& $oFriend->getStatusTo()==ModuleUser::USER_FRIEND_REJECT ) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_friend_offer_reject'),
				$this->Lang_Get('error')
			);
			return;	
		}
		/**
		 * Если дружба была удалена, то проверяем кто ее удалил
		 * и разрешаем восстановить только удалившему
		 */
		if($oFriend->getFriendStatus()>ModuleUser::USER_FRIEND_DELETE 
				&& $oFriend->getFriendStatus()<ModuleUser::USER_FRIEND_REJECT) {
			/**
			 * Определяем статус связи текущего пользователя
			 */
			$iStatusCurrent	= $oFriend->getStatusByUserId($this->oUserCurrent->getId());
				
			if($iStatusCurrent==ModuleUser::USER_FRIEND_DELETE) {
				/**
				 * Меняем статус с удаленного, на акцептованное				 
				 */
				$oFriend->setStatusByUserId(ModuleUser::USER_FRIEND_ACCEPT,$this->oUserCurrent->getId());
				if($this->User_UpdateFriend($oFriend)) {
					$this->Message_AddNoticeSingle($this->Lang_Get('user_friend_add_ok'),$this->Lang_Get('attention'));

					$oViewerLocal=$this->GetViewerLocal();
					$oViewerLocal->Assign('oUserFriend',$oFriend);
					$this->Viewer_AssignAjax('sToggleText',$oViewerLocal->Fetch("actions/ActionProfile/friend_item.tpl"));		
				
				} else {
					$this->Message_AddErrorSingle(
						$this->Lang_Get('system_error'),
						$this->Lang_Get('error')
					);
				}
				return;
			} else {
				$this->Message_AddErrorSingle(
					$this->Lang_Get('user_friend_add_deleted'),
					$this->Lang_Get('error')
				);
				return;	
			}
		}
	}

	/**
	 * Функция создает локальный объект вьювера для рендеринга html-объектов в ajax запросах
	 *
	 * @return ModuleViewer
	 */
	protected function GetViewerLocal() {
		/**
		 * Получаем HTML код inject-объекта
		 */
		$oViewerLocal=$this->Viewer_GetLocalViewer();
		$oViewerLocal->Assign('oUserCurrent',$this->oUserCurrent);
		$oViewerLocal->Assign('oUserProfile',$this->oUserProfile);

		$oViewerLocal->Assign('USER_FRIEND_NULL',ModuleUser::USER_FRIEND_NULL);
		$oViewerLocal->Assign('USER_FRIEND_OFFER',ModuleUser::USER_FRIEND_OFFER);
		$oViewerLocal->Assign('USER_FRIEND_ACCEPT',ModuleUser::USER_FRIEND_ACCEPT);
		$oViewerLocal->Assign('USER_FRIEND_REJECT',ModuleUser::USER_FRIEND_REJECT);
		$oViewerLocal->Assign('USER_FRIEND_DELETE',ModuleUser::USER_FRIEND_DELETE);
	
		return $oViewerLocal;
	}
	
	protected function SubmitAddFriend($oUser,$sUserText,$oFriend=null) {
		$oFriendNew=Engine::GetEntity('User_Friend');
		$oFriendNew->setUserTo($oUser->getId());
		$oFriendNew->setUserFrom($this->oUserCurrent->getId());
		// Добавляем заявку в друзья
		$oFriendNew->setStatusFrom(ModuleUser::USER_FRIEND_OFFER);
		$oFriendNew->setStatusTo(ModuleUser::USER_FRIEND_NULL);
					
		$bStateError=($oFriend)
			? !$this->User_UpdateFriend($oFriendNew)
			: !$this->User_AddFriend($oFriendNew);
		
		if ( !$bStateError ) {
			$this->Message_AddNoticeSingle($this->Lang_Get('user_friend_offer_send'),$this->Lang_Get('attention'));
			
			$sTitle=$this->Lang_Get(
				'user_friend_offer_title',
				array(
					'login'=>$this->oUserCurrent->getLogin(),
					'friend'=>$oUser->getLogin()
				)
			);
			
			require_once Config::Get('path.root.engine').'/lib/external/XXTEA/encrypt.php';
			$sCode=$this->oUserCurrent->getId().'_'.$oUser->getId();
			$sCode=rawurlencode(base64_encode(xxtea_encrypt($sCode, Config::Get('module.talk.encrypt'))));
			
			$aPath=array(
				'accept'=>Router::GetPath('profile').'friendoffer/accept/?code='.$sCode,
				'reject'=>Router::GetPath('profile').'friendoffer/reject/?code='.$sCode
			);
			
			$sText=$this->Lang_Get(
				'user_friend_offer_text',
				array(
					'login'=>$this->oUserCurrent->getLogin(),
					'accept_path'=>$aPath['accept'],
					'reject_path'=>$aPath['reject'],
					'user_text'=>$sUserText
				)
			);
			$oTalk=$this->Talk_SendTalk($sTitle,$sText,$this->oUserCurrent,array($oUser),false,false);
			/**
			 * Отправляем пользователю заявку
			 */
			$this->Notify_SendUserFriendNew(
				$oUser,$this->oUserCurrent,$sUserText,
				Router::GetPath('talk').'read/'.$oTalk->getId().'/'
			);		
			/**
			 * Удаляем отправляющего юзера из переписки
			 */	
			$this->Talk_DeleteTalkUserByArray($oTalk->getId(),$this->oUserCurrent->getId());
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}	
		
		$oViewerLocal=$this->GetViewerLocal();
		$oViewerLocal->Assign('oUserFriend',$oFriendNew);			
		$this->Viewer_AssignAjax('sToggleText',$oViewerLocal->Fetch("actions/ActionProfile/friend_item.tpl"));		
	}
	
	/**
	 * Удаление пользователя из друзей
	 */
	public function EventAjaxFriendDelete() {
		$this->Viewer_SetResponseAjax('json');
		$sUserId=getRequest('idUser',null,'post');
		
		/**
		 * Если пользователь не авторизирован, возвращаем ошибку
		 */		
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('need_authorization'),
				$this->Lang_Get('error')
			);
			return;				
		}
		$this->oUserCurrent=$this->User_GetUserCurrent();

		/**
		 * При попытке добавить в друзья себя, возвращаем ошибку
		 */
		if ($this->oUserCurrent->getId()==$sUserId) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_friend_add_self'),
				$this->Lang_Get('error')
			);
			return;
		}
		
		/**
		 * Если пользователь не найден, возвращаем ошибку
		 */
		if( !$oUser=$this->User_GetUserById($sUserId) ) {		
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_friend_del_no'),
				$this->Lang_Get('error')
			);
			return;
		}
		$this->oUserProfile=$oUser;
		/**
		 * Получаем статус дружбы между пользователями.
		 * Если статус не определен, или отличается от принятой заявки,
		 * возвращаем ошибку
		 */
		$oFriend=$this->User_GetFriend($oUser->getId(),$this->oUserCurrent->getId());
		$aAllowedFriendStatus = array(ModuleUser::USER_FRIEND_ACCEPT+ModuleUser::USER_FRIEND_OFFER,ModuleUser::USER_FRIEND_ACCEPT+ModuleUser::USER_FRIEND_ACCEPT);
		if(!$oFriend || !in_array($oFriend->getFriendStatus(),$aAllowedFriendStatus)) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_friend_del_no'),
				$this->Lang_Get('error')
			);
			return;
		}
		
		if( $this->User_DeleteFriend($oFriend) ) {
			$this->Message_AddNoticeSingle($this->Lang_Get('user_friend_del_ok'),$this->Lang_Get('attention'));

			$oViewerLocal=$this->GetViewerLocal();
			$oViewerLocal->Assign('oUserFriend',$oFriend);
			$this->Viewer_AssignAjax('sToggleText',$oViewerLocal->Fetch("actions/ActionProfile/friend_item.tpl"));		
			
			/**
			 * Отправляем пользователю сообщение об удалении дружеской связи
			 */
			if(Config::Get('module.user.friend_notice.delete')) {
				$sText=$this->Lang_Get(
					'user_friend_del_notice_text',
					array(
						'login'=>$this->oUserCurrent->getLogin(),
					)
				);
				$oTalk=$this->Talk_SendTalk(
					$this->Lang_Get('user_friend_del_notice_title'),
					$sText,$this->oUserCurrent,
					array($oUser),false,false
				);
				$this->Talk_DeleteTalkUserByArray($oTalk->getId(),$this->oUserCurrent->getId());			
			}
			return;	
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
	}
	
	/**
	 * Выполняется при завершении работы экшена
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
		$this->Viewer_Assign('USER_FRIEND_NULL',ModuleUser::USER_FRIEND_NULL);
		$this->Viewer_Assign('USER_FRIEND_OFFER',ModuleUser::USER_FRIEND_OFFER);
		$this->Viewer_Assign('USER_FRIEND_ACCEPT',ModuleUser::USER_FRIEND_ACCEPT);
		$this->Viewer_Assign('USER_FRIEND_REJECT',ModuleUser::USER_FRIEND_REJECT);
		$this->Viewer_Assign('USER_FRIEND_DELETE',ModuleUser::USER_FRIEND_DELETE);
	}
}
?>