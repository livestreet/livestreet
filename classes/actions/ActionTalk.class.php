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
 * Обрабатывает разговоры(почту)
 *
 */
class ActionTalk extends Action {
	/**
	 * Текущий юзер
	 *
	 * @var unknown_type
	 */
	protected $oUserCurrent=null;
	/**
	 * Массив ID юзеров адресатов
	 *
	 * @var unknown_type
	 */
	protected $aUsersId=array();
		
	/**
	 * Инициализация 
	 *
	 * @return unknown
	 */
	public function Init() {	
		/**
		 * Проверяем авторизован ли юзер
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'));
			return Router::Action('error'); 
		}
		/**
		 * Получаем текущего юзера
		 */
		$this->oUserCurrent=$this->User_GetUserCurrent();
		$this->SetDefaultEvent('inbox');	
		$this->Viewer_AddHtmlTitle($this->Lang_Get('talk_menu_inbox'));	
	}
	
	protected function RegisterEvent() {		
		$this->AddEvent('inbox','EventInbox');	
		$this->AddEvent('add','EventAdd');	
		$this->AddEvent('read','EventRead');				
		$this->AddEvent('delete','EventDelete');
		$this->AddEvent('ajaxaddcomment','AjaxAddComment');
		$this->AddEvent('ajaxresponsecomment','AjaxResponseComment');
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	protected function EventDelete() {
		$this->Security_ValidateSendForm();
		/**
		 * Получаем номер сообщения из УРЛ и проверяем существует ли оно
		 */
		$sTalkId=$this->GetParam(0);
		if (!$oTalk=$this->Talk_GetTalkByIdAndUserId($sTalkId,$this->oUserCurrent->getId())) {
			return parent::EventNotFound();
		}		
		/**
		 * Обработка удаления сообщения
		 */				
		if ($oTalkUser=$this->Talk_GetTalkUser($sTalkId,$this->oUserCurrent->getId())) {
			if ($this->Talk_DeleteTalkUser($oTalkUser)) {
				func_header_location(DIR_WEB_ROOT.'/'.ROUTE_PAGE_TALK.'/');
			} else {
				$this->Message_AddError($this->Lang_Get('system_error'));
			}
		}				
	}
	
	
	protected function EventInbox() {				
		/**
		 * Обработка удаления сообщений
		 */
		if (isset($_REQUEST['submit_talk_del'])) {
			$this->Security_ValidateSendForm();
			$aTalksIdDel=getRequest('talk_del');
			if (is_array($aTalksIdDel)) {
				foreach ($aTalksIdDel as $sTalkId => $value) {
					if ($oTalkUser=$this->Talk_GetTalkUser($sTalkId,$this->oUserCurrent->getId())) {
						$this->Talk_DeleteTalkUser($oTalkUser);
					}
				}
			}
		}
		/**
		 * Получаем список сообщений
		 */
		$aTalks=$this->Talk_GetTalksByUserId($this->oUserCurrent->getId());
		$this->Viewer_Assign('aTalks',$aTalks);		
	}	
	
	protected function EventAdd() {		
		$this->Viewer_AddHtmlTitle($this->Lang_Get('talk_menu_inbox_create'));
		/**
		 * Проверяем отправлена ли форма с данными
		 */		
		if (!isset($_REQUEST['submit_talk_add'])) {
			return false;
		}
		$this->Security_ValidateSendForm();		
		/**
		 * Проверка корректности полей формы
		 */
		if (!$this->checkTalkFields()) {
			return false;	
		}					

		if ($oTalk=$this->Talk_SendTalk($this->Text_Parser(getRequest('talk_title')),$this->Text_Parser(getRequest('talk_text')),$this->oUserCurrent,$this->aUsersId)) {
			func_header_location(DIR_WEB_ROOT.'/'.ROUTE_PAGE_TALK.'/read/'.$oTalk->getId().'/');
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return Router::Action('error');
		}				
	}
	
	
	protected function EventRead() {
		/**
		 * Получаем номер сообщения из УРЛ и проверяем существует ли оно
		 */
		$sTalkId=$this->GetParam(0);
		if (!($oTalk=$this->Talk_GetTalkById($sTalkId))) {
			return parent::EventNotFound();
		}
		if (!($oTalkUser=$this->Talk_GetTalkUser($oTalk->getId(),$this->oUserCurrent->getId()))) {
			return parent::EventNotFound();
		}
		/**
		 * Обрабатываем добавление коммента
		 */
		if (isset($_REQUEST['submit_comment'])) {
			$this->SubmitComment();
		}
		/**
		 * Достаём комменты к сообщению
		 */		
		$aReturn=$this->Comment_GetCommentsByTargetId($oTalk->getId(),'talk');
		$iMaxIdComment=$aReturn['iMaxIdComment'];	
		$aComments=$aReturn['comments'];
		/**
		 * Помечаем дату последнего просмотра
		 */
		$oTalkUser->setDateLast(date("Y-m-d H:i:s"));
		$oTalkUser->setCommentIdLast($iMaxIdComment);
		$oTalkUser->setCommentCountNew(0);
		$this->Talk_UpdateTalkUser($oTalkUser);
		
						
		$this->Viewer_AddHtmlTitle($oTalk->getTitle());
		$this->Viewer_Assign('oTalk',$oTalk);	
		$this->Viewer_Assign('aComments',$aComments);
		$this->Viewer_Assign('iMaxIdComment',$iMaxIdComment);
	}
	
	
	protected function checkTalkFields() {
		$bOk=true;		
		/**
		 * Проверяем есть ли заголовок
		 */
		if (!func_check(getRequest('talk_title'),'text',2,200)) {
			$this->Message_AddError($this->Lang_Get('talk_create_title_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли содержание топика
		 */
		if (!func_check(getRequest('talk_text'),'text',2,3000)) {
			$this->Message_AddError($this->Lang_Get('talk_create_text_error'),$this->Lang_Get('error'));
			$bOk=false;
		}		
		/**
		 * проверяем адресатов 
		 */
		$sUsers=getRequest('talk_users');
		$aUsers=explode(',',$sUsers);		
		$aUsersNew=array();
		$this->aUsersId=array();
		foreach ($aUsers as $sUser) {
			$sUser=trim($sUser);			
			if ($sUser=='' or strtolower($sUser)==strtolower($this->oUserCurrent->getLogin())) {
				continue;
			}
			if ($oUser=$this->User_GetUserByLogin($sUser) and $oUser->getActivate()==1) {
				$this->aUsersId[]=$oUser->getId();
				
			} else {
				$this->Message_AddError($this->Lang_Get('talk_create_users_error_not_found').' «'.htmlspecialchars($sUser).'»',$this->Lang_Get('error'));
				$bOk=false;
			}	
			$aUsersNew[]=$sUser;		
		}
		if (!count($aUsersNew)) {
			$this->Message_AddError($this->Lang_Get('talk_create_users_error'),$this->Lang_Get('error'));
			$_REQUEST['talk_users']='';
			$bOk=false;
		} else {
			$_REQUEST['talk_users']=join(',',$aUsersNew);
		}
		//$bOk=false;
		return $bOk;
	}
	
	/**
	 * Получение новых комментариев
	 *
	 */
	protected function AjaxResponseComment() {
		$this->Viewer_SetResponseAjax();
		$idCommentLast=getRequest('idCommentLast');		
		/**
		 * Проверям авторизован ли пользователь
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;			
		}
		/**
		 * Проверяем разговор
		 */
		if (!($oTalk=$this->Talk_GetTalkById(getRequest('idTarget')))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		if (!($oTalkUser=$this->Talk_GetTalkUser($oTalk->getId(),$this->oUserCurrent->getId()))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
			
		
		$aReturn=$this->Comment_GetCommentsNewByTargetId($oTalk->getId(),'talk',$idCommentLast);
		$iMaxIdComment=$aReturn['iMaxIdComment'];
		
		$oTalkUser->setDateLast(date("Y-m-d H:i:s"));
		if ($iMaxIdComment!=0) {
			$oTalkUser->setCommentIdLast($iMaxIdComment);
		}
		$oTalkUser->setCommentCountNew(0);
		$this->Talk_UpdateTalkUser($oTalkUser);
		
		$aComments=array();
		$aCmts=$aReturn['comments'];
		if ($aCmts and is_array($aCmts)) {
			foreach ($aCmts as $aCmt) {
				$aComments[]=array(
					'html' => $aCmt['html'],
					'idParent' => $aCmt['obj']->getPid(),
					'id' => $aCmt['obj']->getId(),
				);
			}
		}
		
		$this->Viewer_AssingAjax('aComments',$aComments);
		$this->Viewer_AssingAjax('iMaxIdComment',$iMaxIdComment);	
	}
	/**
	 * Обработка добавление комментария к топику через ajax
	 *
	 */
	protected function AjaxAddComment() {
		$this->Viewer_SetResponseAjax();
		$this->SubmitComment();
	}	
	/**
	 * Обработка добавление комментария к топику
	 *	 
	 * @return unknown
	 */
	protected function SubmitComment() {
		/**
		 * Проверям авторизован ли пользователь
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;			
		}
		/**
		 * Проверяем разговор
		 */
		if (!($oTalk=$this->Talk_GetTalkById(getRequest('cmt_target_id')))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		if (!($oTalkUser=$this->Talk_GetTalkUser($oTalk->getId(),$this->oUserCurrent->getId()))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}			
		/**
		* Проверяем текст комментария
		*/
		$sText=$this->Text_Parser(getRequest('comment_text'));
		if (!func_check($sText,'text',2,3000)) {			
			$this->Message_AddErrorSingle($this->Lang_Get('talk_comment_add_text_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		* Проверям на какой коммент отвечаем
		*/
		$sParentId=(int)getRequest('reply');
		if (!func_check($sParentId,'id')) {			
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		$oCommentParent=null;
		if ($sParentId!=0) {
			/**
			* Проверяем существует ли комментарий на который отвечаем
			*/
			if (!($oCommentParent=$this->Comment_GetCommentById($sParentId))) {				
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
			}
			/**
			* Проверяем из одного топика ли новый коммент и тот на который отвечаем
			*/
			if ($oCommentParent->getTargetId()!=$oTalk->getId()) {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
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
		if ($this->Comment_GetCommentUnique($oTalk->getId(),'talk',$this->oUserCurrent->getId(),$sParentId,md5($sText))) {			
			$this->Message_AddErrorSingle($this->Lang_Get('topic_comment_spam'),$this->Lang_Get('error'));
			return;
		}
		/**
		* Создаём коммент
		*/
		$oCommentNew=new CommentEntity_Comment();
		$oCommentNew->setTargetId($oTalk->getId());
		$oCommentNew->setTargetType('talk');
		$oCommentNew->setUserId($this->oUserCurrent->getId());		
		$oCommentNew->setText($sText);
		$oCommentNew->setDate(date("Y-m-d H:i:s"));
		$oCommentNew->setUserIp(func_getIp());
		$oCommentNew->setPid($sParentId);
		$oCommentNew->setTextHash(md5($sText));
			
		/**
		* Добавляем коммент
		*/
		if ($this->Comment_AddComment($oCommentNew)) {
			$this->Viewer_AssingAjax('sCommentId',$oCommentNew->getId());
			$oTalk->setDateLast(date("Y-m-d H:i:s"));
			$oTalk->setCountComment($oTalk->getCountComment()+1);
			$this->Talk_UpdateTalk($oTalk);
			/**
			* Отсылаем уведомления всем адресатам
			*/
			$aUsersTalk=$this->Talk_GetUsersTalk($oTalk->getId());
			foreach ($aUsersTalk as $oUserTalk) {
				if ($oUserTalk->getId()!=$oCommentNew->getUserId()) {
					$this->Notify_SendTalkCommentNew($oUserTalk,$this->oUserCurrent,$oTalk,$oCommentNew);
				}
			}
			/**
			 * Увеличиваем число новых комментов
			 */
			$this->Talk_increaseCountCommentNew($oTalk->getId(),$oCommentNew->getUserId()); 			
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
	}	
}
?>