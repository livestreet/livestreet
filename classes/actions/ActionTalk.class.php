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
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	protected function EventDelete() {		
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
		/**
		 * Проверка корректности полей формы
		 */
		if (!$this->checkTalkFields()) {
			return false;	
		}					

		if ($this->Talk_SendTalk($this->Text_Parser(getRequest('talk_title')),$this->Text_Parser(getRequest('talk_text')),$this->oUserCurrent,$this->aUsersId)) {
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
		if (!$oTalk=$this->Talk_GetTalkByIdAndUserId($sTalkId,$this->oUserCurrent->getId())) {
			return parent::EventNotFound();
		}
		/**
		 * Помечаем дату последнего просмотра
		 */
		$this->Talk_SetTalkUserDateLast($oTalk->getId(),$this->oUserCurrent->getId());
		/**
		 * Обрабатываем добавление коммента
		 */
		$this->SubmitComment($oTalk);
		/**
		 * Достаём комменты к сообщению
		 */
		$aComments=$this->Talk_GetCommentsByTalkId($oTalk->getId());
		$aCommentsNew=array();
		foreach ($aComments as $oCom) {
			$array=$oCom->_getData();
			$array['obj']=$oCom;
			$aCommentsNew[]=$array;
		}
		
		$this->Viewer_AddHtmlTitle($oTalk->getTitle());
		$this->Viewer_Assign('oTalk',$oTalk);	
		$this->Viewer_Assign('aComments',$aComments);
		$this->Viewer_Assign('aCommentsNew',$aCommentsNew);
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
	 * Обработка добавление комментария к сообщению
	 *
	 * @param unknown_type $oTalk
	 * @return unknown
	 */
	protected function SubmitComment($oTalk) {
		/**
		 * Если нажали кнопку "Отправить"
		 */
		if (isset($_REQUEST['submit_comment'])) {			
			/**
			 * Проверяем текст комментария
			 */
			if (!func_check(getRequest('comment_text'),'text',2,3000)) {
				$this->Message_AddError($this->Lang_Get('talk_comment_add_text_error'),$this->Lang_Get('error'));
				return false;
			}
			/**
			 * Проверям на какой коммент отвечаем
			 */
			$sParentId=getRequest('reply',0);
			if (!func_check($sParentId,'id')) {
				$this->Message_AddError($this->Lang_Get('system_error'));
				return false;
			}
			if ($sParentId!=0) {
				/**
				 * Проверяем существует ли комментарий на который отвечаем
				 */
				if (!($oCommentParent=$this->Talk_GetCommentById($sParentId))) {
					return false;
				}
				/**
				 * Проверяем из одного сообщения ли новый коммент и тот на который отвечаем
				 */
				if ($oCommentParent->getTalkId()!=$oTalk->getId()) {
					return false;
				}
			} else {
				/**
				 * Корневой комментарий
				 */
				$sParentId=null;
			}
			/**
			 * Создаём коммент
			 */
			$oCommentNew=new TalkEntity_TalkComment();
			$oCommentNew->setTalkId($oTalk->getId());
			$oCommentNew->setUserId($this->oUserCurrent->getId());
			/**
			 * Парсим коммент на предмет ХТМЛ тегов
			 */
			$sText=$this->Text_Parser(getRequest('comment_text'));			
			$oCommentNew->setText($sText);
			$oCommentNew->setDate(date("Y-m-d H:i:s"));
			$oCommentNew->setUserIp(func_getIp());
			$oCommentNew->setPid($sParentId);
			/**
			 * Добавляем коммент
			 */
			if ($this->Talk_AddComment($oCommentNew)) {
				$oTalk->setDateLast(date("Y-m-d H:i:s"));
				$this->Talk_UpdateTalk($oTalk);
				/**
				 * Отсылаем уведомления всем адресатам
				 */
				$aUsersTalk=$this->Talk_GetTalkUsers($oCommentNew->getTalkId());
				foreach ($aUsersTalk as $oUserTalk) {
					if ($oUserTalk->getId()!=$oCommentNew->getUserId()) {						
						$this->Notify_SendTalkCommentNew($oUserTalk,$this->oUserCurrent,$oTalk,$oCommentNew);
					}
				}
				func_header_location(DIR_WEB_ROOT.'/'.ROUTE_PAGE_TALK.'/read/'.$oTalk->getId().'/#comment'.$oCommentNew->getId());
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
				return false;
			}
		}
	}
}
?>