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
 * Экшен обработки личной почты (сообщения /talk/)
 *
 * @package actions
 * @since 1.0
 */
class ActionTalk extends Action {
	/**
	 * Текущий юзер
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserCurrent=null;
	/**
	 * Подменю
	 *
	 * @var string
	 */
	protected $sMenuSubItemSelect='';
	/**
	 * Массив ID юзеров адресатов
	 *
	 * @var array
	 */
	protected $aUsersId=array();

	/**
	 * Инициализация
	 *
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

		/**
		 * Загружаем в шаблон JS текстовки
		 */
		$this->Lang_AddLangJs(array(
								  'delete'
							  ));
	}
	/**
	 * Регистрация евентов
	 */
	protected function RegisterEvent() {
		$this->AddEvent('inbox','EventInbox');
		$this->AddEvent('add','EventAdd');
		$this->AddEvent('read','EventRead');
		$this->AddEvent('delete','EventDelete');
		$this->AddEvent('ajaxaddcomment','AjaxAddComment');
		$this->AddEvent('ajaxresponsecomment','AjaxResponseComment');
		$this->AddEvent('favourites','EventFavourites');
		$this->AddEvent('blacklist','EventBlacklist');
		$this->AddEvent('ajaxaddtoblacklist', 'AjaxAddToBlacklist');
		$this->AddEvent('ajaxdeletefromblacklist', 'AjaxDeleteFromBlacklist');
		$this->AddEvent('ajaxdeletetalkuser', 'AjaxDeleteTalkUser');
		$this->AddEvent('ajaxaddtalkuser', 'AjaxAddTalkUser');
		$this->AddEvent('ajaxnewmessages', 'AjaxNewMessages');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Удаление письма
	 */
	protected function EventDelete() {
		$this->Security_ValidateSendForm();
		/**
		 * Получаем номер сообщения из УРЛ и проверяем существует ли оно
		 */
		$sTalkId=$this->GetParam(0);
		if (!($oTalk=$this->Talk_GetTalkById($sTalkId))) {
			return parent::EventNotFound();
		}
		/**
		 * Пользователь входит в переписку?
		 */
		if (!($oTalkUser=$this->Talk_GetTalkUser($oTalk->getId(),$this->oUserCurrent->getId()))) {
			return parent::EventNotFound();
		}
		/**
		 * Обработка удаления сообщения
		 */
		$this->Talk_DeleteTalkUserByArray($sTalkId,$this->oUserCurrent->getId());
		Router::Location(Router::GetPath('talk'));
	}
	/**
	 * Отображение списка сообщений
	 */
	protected function EventInbox() {
		/**
		 * Обработка удаления сообщений
		 */
		if (getRequest('submit_talk_del')) {
			$this->Security_ValidateSendForm();

			$aTalksIdDel=getRequest('talk_select');
			if (is_array($aTalksIdDel)) {
				$this->Talk_DeleteTalkUserByArray(array_keys($aTalksIdDel),$this->oUserCurrent->getId());
			}
		}
		/**
		 * Обработка отметки о прочтении
		 */
		if (getRequest('submit_talk_read')) {
			$this->Security_ValidateSendForm();

			$aTalksIdDel=getRequest('talk_select');
			if (is_array($aTalksIdDel)) {
				$this->Talk_MarkReadTalkUserByArray(array_keys($aTalksIdDel),$this->oUserCurrent->getId());
			}
		}
		$this->sMenuSubItemSelect='inbox';
		/**
		 * Количество сообщений на страницу
		 */
		$iPerPage=Config::Get('module.talk.per_page');
		/**
		 * Формируем фильтр для поиска сообщений
		 */
		$aFilter=$this->BuildFilter();
		/**
		 * Если только новые, то добавляем условие в фильтр
		 */
		if ($this->GetParam(0)=='new') {
			$this->sMenuSubItemSelect='new';
			$aFilter['only_new']=true;
			$iPerPage=50; // новых отображаем только последние 50 писем, без постраничности
		}
		/**
		 * Передан ли номер страницы
		 */
		$iPage=preg_match("/^page(\d+)$/i",$this->getParam(0),$aMatch) ? $aMatch[1] : 1;
		/**
		 * Получаем список писем
		 */
		$aResult=$this->Talk_GetTalksByFilter(
			$aFilter,$iPage,$iPerPage
		);

		$aTalks=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging(
			$aResult['count'],$iPage,$iPerPage,Config::Get('pagination.pages.count'),
			Router::GetPath('talk').$this->sCurrentEvent,
			array_intersect_key(
				$_REQUEST,
				array_fill_keys(
					array('start','end','keyword','sender','keyword_text','favourite'),
					''
				)
			)
		);
		/**
		 * Показываем сообщение, если происходит поиск по фильтру
		 */
		if(getRequest('submit_talk_filter')) {
			$this->Message_AddNotice(
				($aResult['count'])
					? $this->Lang_Get('talk_filter_result_count',array('count'=>$aResult['count']))
					: $this->Lang_Get('talk_filter_result_empty')
			);
		}
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aTalks',$aTalks);
	}
	/**
	 * Формирует из REQUEST массива фильтр для отбора писем
	 *
	 * @return array
	 */
	protected function BuildFilter() {
		/**
		 * Текущий пользователь
		 */
		$aFilter = array(
			'user_id'=>$this->oUserCurrent->getId(),
		);
		/**
		 * Дата старта поиска
		 */
		if($start=getRequest('start')) {
			if(func_check($start,'text',6,10) && substr_count($start,'.')==2) {
				list($d,$m,$y)=explode('.',$start);
				if(@checkdate($m,$d,$y)) {
					$aFilter['date_min']="{$y}-{$m}-{$d}";
				} else {
					$this->Message_AddError(
						$this->Lang_Get('talk_filter_error_date_format'),
						$this->Lang_Get('talk_filter_error')
					);
					unset($_REQUEST['start']);
				}
			} else {
				$this->Message_AddError(
					$this->Lang_Get('talk_filter_error_date_format'),
					$this->Lang_Get('talk_filter_error')
				);
				unset($_REQUEST['start']);
			}
		}
		/**
		 * Дата окончания поиска
		 */
		if($end=getRequest('end')) {
			if(func_check($end,'text',6,10) && substr_count($end,'.')==2) {
				list($d,$m,$y)=explode('.',$end);
				if(@checkdate($m,$d,$y)) {
					$aFilter['date_max']="{$y}-{$m}-{$d} 23:59:59";
				} else {
					$this->Message_AddError(
						$this->Lang_Get('talk_filter_error_date_format'),
						$this->Lang_Get('talk_filter_error')
					);
					unset($_REQUEST['end']);
				}
			} else {
				$this->Message_AddError(
					$this->Lang_Get('talk_filter_error_date_format'),
					$this->Lang_Get('talk_filter_error')
				);
				unset($_REQUEST['end']);
			}
		}
		/**
		 * Ключевые слова в теме сообщения
		 */
		if($sKeyRequest=getRequest('keyword')){
			$sKeyRequest=urldecode($sKeyRequest);
			preg_match_all('~(\S+)~u',$sKeyRequest,$aWords);

			if(is_array($aWords[1])&&isset($aWords[1])&&count($aWords[1])) {
				$aFilter['keyword']='%'.implode('%',$aWords[1]).'%';
			} else {
				unset($_REQUEST['keyword']);
			}
		}
		/**
		 * Ключевые слова в тексте сообщения
		 */
		if($sKeyRequest=getRequest('keyword_text')){
			$sKeyRequest=urldecode($sKeyRequest);
			preg_match_all('~(\S+)~u',$sKeyRequest,$aWords);

			if(is_array($aWords[1])&&isset($aWords[1])&&count($aWords[1])) {
				$aFilter['text_like']='%'.implode('%',$aWords[1]).'%';
			} else {
				unset($_REQUEST['keyword_text']);
			}
		}
		/**
		 * Отправитель
		 */
		if($sender=getRequest('sender')){
			$aFilter['user_login']=urldecode($sender);
		}
		/**
		 * Искать только в избранных письмах
		 */
		if (getRequest('favourite')) {
			$aTalkIdResult=$this->Favourite_GetFavouritesByUserId($this->oUserCurrent->getId(),'talk',1,500); // ограничиваем
			$aFilter['id']=$aTalkIdResult['collection'];
			$_REQUEST['favourite']=1;
		} else {
			unset($_REQUEST['favourite']);
		}
		return $aFilter;
	}
	/**
	 * Отображение списка блэк-листа
	 */
	protected function EventBlacklist() {
		$this->sMenuSubItemSelect='blacklist';
		$aUsersBlacklist=$this->Talk_GetBlacklistByUserId($this->oUserCurrent->getId());
		$this->Viewer_Assign('aUsersBlacklist',$aUsersBlacklist);
	}
	/**
	 * Отображение списка избранных писем
	 */
	protected function EventFavourites() {
		$this->sMenuSubItemSelect='favourites';
		/**
		 * Передан ли номер страницы
		 */
		$iPage=preg_match("/^page(\d+)$/i",$this->getParam(0),$aMatch) ? $aMatch[1] : 1;
		/**
		 * Получаем список писем
		 */
		$aResult=$this->Talk_GetTalksFavouriteByUserId(
			$this->oUserCurrent->getId(),
			$iPage,Config::Get('module.talk.per_page')
		);
		$aTalks=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging(
			$aResult['count'],$iPage,Config::Get('module.talk.per_page'),Config::Get('pagination.pages.count'),
			Router::GetPath('talk').$this->sCurrentEvent
		);
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aTalks',$aTalks);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('talk_favourite_inbox'));
	}
	/**
	 * Страница создания письма
	 */
	protected function EventAdd() {
		$this->sMenuSubItemSelect='add';
		$this->Viewer_AddHtmlTitle($this->Lang_Get('talk_menu_inbox_create'));
		/**
		 * Получаем список друзей
		 */
		$aUsersFriend=$this->User_GetUsersFriend($this->oUserCurrent->getId());
		if($aUsersFriend['collection']) {
			$this->Viewer_Assign('aUsersFriend',$aUsersFriend['collection']);
		}
		/**
		 * Проверяем отправлена ли форма с данными
		 */
		if (!isPost('submit_talk_add')) {
			return false;
		}
		/**
		 * Проверка корректности полей формы
		 */
		if (!$this->checkTalkFields()) {
			return false;
		}
		/**
		 * Проверяем разрешено ли отправлять инбокс по времени
		 */
		if (!$this->ACL_CanSendTalkTime($this->oUserCurrent)) {
			$this->Message_AddErrorSingle($this->Lang_Get('talk_time_limit'),$this->Lang_Get('error'));
			return false;
		}
		/**
		 * Отправляем письмо
		 */
		if ($oTalk=$this->Talk_SendTalk($this->Text_Parser(strip_tags(getRequest('talk_title'))),$this->Text_Parser(getRequest('talk_text')),$this->oUserCurrent,$this->aUsersId)) {
			Router::Location(Router::GetPath('talk').'read/'.$oTalk->getId().'/');
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return Router::Action('error');
		}
	}
	/**
	 * Чтение письма
	 */
	protected function EventRead() {
		$this->sMenuSubItemSelect='read';
		/**
		 * Получаем номер сообщения из УРЛ и проверяем существует ли оно
		 */
		$sTalkId=$this->GetParam(0);
		if (!($oTalk=$this->Talk_GetTalkById($sTalkId))) {
			return parent::EventNotFound();
		}
		/**
		 * Пользователь есть в переписке?
		 */
		if (!($oTalkUser=$this->Talk_GetTalkUser($oTalk->getId(),$this->oUserCurrent->getId()))) {
			return parent::EventNotFound();
		}
		/**
		 * Пользователь активен в переписке?
		 */
		if($oTalkUser->getUserActive()!=ModuleTalk::TALK_USER_ACTIVE){
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
		/**
		 * Подсчитываем нужно ли отображать комментарии.
		 * Комментарии не отображаются, если у вестки только один читатель
		 * и ранее созданных комментариев нет.
		 */
		if(count($aComments)==0) {
			$iActiveSpeakers=0;
			foreach((array)$oTalk->getTalkUsers() as $oTalkUser) {
				if( ($oTalkUser->getUserId()!=$this->oUserCurrent->getId())
					&& $oTalkUser->getUserActive()==ModuleTalk::TALK_USER_ACTIVE ){
					$iActiveSpeakers++;
					break;
				}
			}
			if($iActiveSpeakers==0) {
				$this->Viewer_Assign('bNoComments',true);
			}
		}
	}
	/**
	 * Проверка полей при создании письма
	 *
	 * @return bool
	 */
	protected function checkTalkFields() {
		$this->Security_ValidateSendForm();

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
		 * Проверяем адресатов
		 */
		$sUsers=getRequest('talk_users');
		$aUsers=explode(',',$sUsers);
		$aUsersNew=array();
		$aUserInBlacklist = $this->Talk_GetBlacklistByTargetId($this->oUserCurrent->getId());

		$this->aUsersId=array();
		foreach ($aUsers as $sUser) {
			$sUser=trim($sUser);
			if ($sUser=='' or strtolower($sUser)==strtolower($this->oUserCurrent->getLogin())) {
				continue;
			}
			if ($oUser=$this->User_GetUserByLogin($sUser) and $oUser->getActivate()==1) {
				// Проверяем, попал ли отправиль в блек лист
				if(!in_array($oUser->getId(),$aUserInBlacklist)) {
					$this->aUsersId[]=$oUser->getId();
				} else {
					$this->Message_AddError(
						str_replace(
							'login',
							$oUser->getLogin(),
							$this->Lang_Get('talk_user_in_blacklist',array('login'=>htmlspecialchars($oUser->getLogin())))
						),
						$this->Lang_Get('error')
					);
					$bOk=false;
					continue;
				}
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
			if (count($aUsersNew)>Config::Get('module.talk.max_users') and !$this->oUserCurrent->isAdministrator()) {
				$this->Message_AddError($this->Lang_Get('talk_create_users_error_many'),$this->Lang_Get('error'));
				$bOk=false;
			}
			$_REQUEST['talk_users']=join(',',$aUsersNew);
		}
		/**
		 * Выполнение хуков
		 */
		$this->Hook_Run('check_talk_fields', array('bOk'=>&$bOk));

		return $bOk;
	}
	/**
	 * Получение новых комментариев
	 *
	 */
	protected function AjaxResponseComment() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
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
		/**
		 * Получаем комментарии
		 */
		$aReturn=$this->Comment_GetCommentsNewByTargetId($oTalk->getId(),'talk',$idCommentLast);
		$iMaxIdComment=$aReturn['iMaxIdComment'];
		/**
		 * Отмечаем дату прочтения письма
		 */
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
		$this->Viewer_AssignAjax('aComments',$aComments);
		$this->Viewer_AssignAjax('iMaxIdComment',$iMaxIdComment);
	}
	/**
	 * Обработка добавление комментария к письму через ajax
	 *
	 */
	protected function AjaxAddComment() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$this->SubmitComment();
	}
	/**
	 * Обработка добавление комментария к письму
	 *
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
		 * Проверяем разрешено ли отправлять инбокс по времени
		 */
		if (!$this->ACL_CanPostTalkCommentTime($this->oUserCurrent)) {
			$this->Message_AddErrorSingle($this->Lang_Get('talk_time_limit'),$this->Lang_Get('error'));
			return false;
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
		$oCommentNew=Engine::GetEntity('Comment');
		$oCommentNew->setTargetId($oTalk->getId());
		$oCommentNew->setTargetType('talk');
		$oCommentNew->setUserId($this->oUserCurrent->getId());
		$oCommentNew->setText($sText);
		$oCommentNew->setDate(date("Y-m-d H:i:s"));
		$oCommentNew->setUserIp(func_getIp());
		$oCommentNew->setPid($sParentId);
		$oCommentNew->setTextHash(md5($sText));
		$oCommentNew->setPublish(1);
		/**
		 * Добавляем коммент
		 */
		if ($this->Comment_AddComment($oCommentNew)) {
			$this->Viewer_AssignAjax('sCommentId',$oCommentNew->getId());
			$oTalk->setDateLast(date("Y-m-d H:i:s"));
			$oTalk->setUserIdLast($oCommentNew->getUserId());
			$oTalk->setCommentIdLast($oCommentNew->getId());
			$oTalk->setCountComment($oTalk->getCountComment()+1);
			$this->Talk_UpdateTalk($oTalk);
			/**
			 * Отсылаем уведомления всем адресатам
			 */
			$aUsersTalk=$this->Talk_GetUsersTalk($oTalk->getId(), ModuleTalk::TALK_USER_ACTIVE);

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
	/**
	 * Добавление нового пользователя(-лей) в блек лист (ajax)
	 *
	 */
	public function AjaxAddToBlacklist() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$sUsers=getRequest('users',null,'post');
		/**
		 * Если пользователь не авторизирован, возвращаем ошибку
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$aUsers=explode(',',$sUsers);
		/**
		 * Получаем блекслист пользователя
		 */
		$aUserBlacklist = $this->Talk_GetBlacklistByUserId($this->oUserCurrent->getId());

		$aResult=array();
		/**
		 * Обрабатываем добавление по каждому из переданных логинов
		 */
		foreach ($aUsers as $sUser) {
			$sUser=trim($sUser);
			if ($sUser=='') {
				continue;
			}
			/**
			 * Если пользователь пытается добавить в блеклист самого себя,
			 * возвращаем ошибку
			 */
			if(strtolower($sUser)==strtolower($this->oUserCurrent->getLogin())) {
				$aResult[]=array(
					'bStateError'=>true,
					'sMsgTitle'=>$this->Lang_Get('error'),
					'sMsg'=>$this->Lang_Get('talk_blacklist_add_self')
				);
				continue;
			}
			/**
			 * Если пользователь не найден или неактивен, возвращаем ошибку
			 */
			if ($oUser=$this->User_GetUserByLogin($sUser) and $oUser->getActivate()==1) {
				if(!isset($aUserBlacklist[$oUser->getId()])) {
					if($this->Talk_AddUserToBlackList($oUser->getId(),$this->oUserCurrent->getId())) {
						$aResult[]=array(
							'bStateError'=>false,
							'sMsgTitle'=>$this->Lang_Get('attention'),
							'sMsg'=>$this->Lang_Get('talk_blacklist_add_ok',array('login'=>htmlspecialchars($sUser))),
							'sUserId'=>$oUser->getId(),
							'sUserLogin'=>htmlspecialchars($sUser),
							'sUserWebPath'=>$oUser->getUserWebPath(),
							'sUserAvatar48'=>$oUser->getProfileAvatarPath(48)
						);
					} else {
						$aResult[]=array(
							'bStateError'=>true,
							'sMsgTitle'=>$this->Lang_Get('error'),
							'sMsg'=>$this->Lang_Get('system_error'),
							'sUserLogin'=>htmlspecialchars($sUser)
						);
					}
				} else {
					/**
					 * Попытка добавить уже существующего в блеклисте пользователя, возвращаем ошибку
					 */
					$aResult[]=array(
						'bStateError'=>true,
						'sMsgTitle'=>$this->Lang_Get('error'),
						'sMsg'=>$this->Lang_Get('talk_blacklist_user_already_have',array('login'=>htmlspecialchars($sUser))),
						'sUserLogin'=>htmlspecialchars($sUser)
					);
					continue;
				}
			} else {
				$aResult[]=array(
					'bStateError'=>true,
					'sMsgTitle'=>$this->Lang_Get('error'),
					'sMsg'=>$this->Lang_Get('user_not_found',array('login'=>htmlspecialchars($sUser))),
					'sUserLogin'=>htmlspecialchars($sUser)
				);
			}
		}
		/**
		 * Передаем во вьевер массив с результатами обработки по каждому пользователю
		 */
		$this->Viewer_AssignAjax('aUsers',$aResult);
	}
	/**
	 * Удаление пользователя из блек листа (ajax)
	 *
	 */
	public function AjaxDeleteFromBlacklist() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$idTarget=getRequest('idTarget',null,'post');
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
		/**
		 * Если пользователь не существуем, возращаем ошибку
		 */
		if (!$oUserTarget=$this->User_GetUserById($idTarget)) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_not_found_by_id',array('id'=>htmlspecialchars($idTarget))),
				$this->Lang_Get('error')
			);
			return;
		}
		/**
		 * Получаем блеклист пользователя
		 */
		$aBlacklist=$this->Talk_GetBlacklistByUserId($this->oUserCurrent->getId());
		/**
		 * Если указанный пользователь не найден в блекслисте, возвращаем ошибку
		 */
		if (!isset($aBlacklist[$oUserTarget->getId()])) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get(
					'talk_blacklist_user_not_found',
					array('login'=>$oUserTarget->getLogin())
				),
				$this->Lang_Get('error')
			);
			return;
		}
		/**
		 * Производим удаление пользователя из блекслиста
		 */
		if(!$this->Talk_DeleteUserFromBlacklist($idTarget,$this->oUserCurrent->getId())) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('system_error'),
				$this->Lang_Get('error')
			);
			return;
		}
		$this->Message_AddNoticeSingle(
			$this->Lang_Get(
				'talk_blacklist_delete_ok',
				array('login'=>$oUserTarget->getLogin())
			),
			$this->Lang_Get('attention')
		);
	}
	/**
	 * Удаление участника разговора (ajax)
	 *
	 */
	public function AjaxDeleteTalkUser() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$idTarget=getRequest('idTarget',null,'post');
		$idTalk=getRequest('idTalk',null,'post');
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
		/**
		 * Если удаляемый участник не существует в базе данных, возвращаем ошибку
		 */
		if (!$oUserTarget=$this->User_GetUserById($idTarget)) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('user_not_found_by_id',array('id'=>htmlspecialchars($idTarget))),
				$this->Lang_Get('error')
			);
			return;
		}
		/**
		 * Если разговор не найден, или пользователь не является его автором (либо админом), возвращаем ошибку
		 */
		if((!$oTalk=$this->Talk_GetTalkById($idTalk))
			|| ( ($oTalk->getUserId()!=$this->oUserCurrent->getId()) && !$this->oUserCurrent->isAdministrator() ) ) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('talk_not_found'),
				$this->Lang_Get('error')
			);
			return;
		}
		/**
		 * Получаем список всех участников разговора
		 */
		$aTalkUsers=$oTalk->getTalkUsers();
		/**
		 * Если пользователь не является участником разговора или удалил себя самостоятельно  возвращаем ошибку
		 */
		if(!isset($aTalkUsers[$idTarget])
			|| $aTalkUsers[$idTarget]->getUserActive()==ModuleTalk::TALK_USER_DELETE_BY_SELF) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get(
					'talk_speaker_user_not_found',
					array('login'=>$oUserTarget->getLogin())
				),
				$this->Lang_Get('error')
			);
			return;
		}
		/**
		 * Удаляем пользователя из разговора,  если удаление прошло неудачно - возвращаем системную ошибку
		 */
		if(!$this->Talk_DeleteTalkUserByArray($idTalk,$idTarget,ModuleTalk::TALK_USER_DELETE_BY_AUTHOR)) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('system_error'),
				$this->Lang_Get('error')
			);
			return;
		}
		$this->Message_AddNoticeSingle(
			$this->Lang_Get(
				'talk_speaker_delete_ok',
				array('login'=>$oUserTarget->getLogin())
			),
			$this->Lang_Get('attention')
		);
	}
	/**
	 * Добавление нового участника разговора (ajax)
	 *
	 */
	public function AjaxAddTalkUser() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$sUsers=getRequest('users',null,'post');
		$idTalk=getRequest('idTalk',null,'post');
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
		/**
		 * Если разговор не найден, или пользователь не является его автором (или админом), возвращаем ошибку
		 */
		if((!$oTalk=$this->Talk_GetTalkById($idTalk))
			|| ( ($oTalk->getUserId()!=$this->oUserCurrent->getId()) && !$this->oUserCurrent->isAdministrator() ) ) {
			$this->Message_AddErrorSingle(
				$this->Lang_Get('talk_not_found'),
				$this->Lang_Get('error')
			);
			return;
		}
		/**
		 * Получаем список всех участников разговора
		 */
		$aTalkUsers=$oTalk->getTalkUsers();
		$aUsers=explode(',',$sUsers);
		/**
		 * Получаем список пользователей, которые не принимают письма
		 */
		$aUserInBlacklist = $this->Talk_GetBlacklistByTargetId($this->oUserCurrent->getId());
		/**
		 * Ограничения на максимальное число участников разговора
		 */
		if (count($aTalkUsers)>=Config::Get('module.talk.max_users') and !$this->oUserCurrent->isAdministrator()) {
			$this->Message_AddError($this->Lang_Get('talk_create_users_error_many'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Обрабатываем добавление по каждому переданному логину пользователя
		 */
		foreach ($aUsers as $sUser) {
			$sUser=trim($sUser);
			if($sUser=='') {
				continue;
			}
			/**
			 * Попытка добавить себя
			 */
			if (strtolower($sUser)==strtolower($this->oUserCurrent->getLogin())) {
				$aResult[]=array(
					'bStateError'=>true,
					'sMsgTitle'=>$this->Lang_Get('error'),
					'sMsg'=>$this->Lang_Get('talk_speaker_add_self')
				);
				continue;
			}
			if ( ($oUser=$this->User_GetUserByLogin($sUser))
				&& ($oUser->getActivate()==1) ) {
				if(!in_array($oUser->getId(),$aUserInBlacklist)) {
					if(array_key_exists($oUser->getId(),$aTalkUsers)) {
						switch($aTalkUsers[$oUser->getId()]->getUserActive()) {
							/**
							 * Если пользователь ранее был удален админом разговора, то добавляем его снова
							 */
							case ModuleTalk::TALK_USER_DELETE_BY_AUTHOR:
								if (
									$this->Talk_AddTalkUser(
										Engine::GetEntity('Talk_TalkUser',
														  array(
															  'talk_id'=>$idTalk,
															  'user_id'=>$oUser->getId(),
															  'date_last'=>null,
															  'talk_user_active'=>ModuleTalk::TALK_USER_ACTIVE
														  )
										)
									)
								) {
									$this->Notify_SendTalkNew($oUser,$this->oUserCurrent,$oTalk);
									$aResult[]=array(
										'bStateError'=>false,
										'sMsgTitle'=>$this->Lang_Get('attention'),
										'sMsg'=>$this->Lang_Get('talk_speaker_add_ok',array('login',htmlspecialchars($sUser))),
										'sUserId'=>$oUser->getId(),
										'sUserLogin'=>$oUser->getLogin(),
										'sUserLink'=>$oUser->getUserWebPath(),
										'sUserWebPath'=>$oUser->getUserWebPath(),
										'sUserAvatar48'=>$oUser->getProfileAvatarPath(48)
									);
									$bState=true;
								} else {
									$aResult[]=array(
										'bStateError'=>true,
										'sMsgTitle'=>$this->Lang_Get('error'),
										'sMsg'=>$this->Lang_Get('system_error')
									);
								}
								break;
							/**
							 * Если пользователь является активным участником разговора, возвращаем ошибку
							 */
							case ModuleTalk::TALK_USER_ACTIVE:
								$aResult[]=array(
									'bStateError'=>true,
									'sMsgTitle'=>$this->Lang_Get('error'),
									'sMsg'=>$this->Lang_Get('talk_speaker_user_already_exist',array('login'=>htmlspecialchars($sUser)))
								);
								break;
							/**
							 * Если пользователь удалил себя из разговора самостоятельно, то блокируем повторное добавление
							 */
							case ModuleTalk::TALK_USER_DELETE_BY_SELF:
								$aResult[]=array(
									'bStateError'=>true,
									'sMsgTitle'=>$this->Lang_Get('error'),
									'sMsg'=>$this->Lang_Get('talk_speaker_delete_by_self',array('login'=>htmlspecialchars($sUser)))
								);
								break;

							default:
								$aResult[]=array(
									'bStateError'=>true,
									'sMsgTitle'=>$this->Lang_Get('error'),
									'sMsg'=>$this->Lang_Get('system_error')
								);
						}
					} elseif (
						$this->Talk_AddTalkUser(
							Engine::GetEntity('Talk_TalkUser',
											  array(
												  'talk_id'=>$idTalk,
												  'user_id'=>$oUser->getId(),
												  'date_last'=>null,
												  'talk_user_active'=>ModuleTalk::TALK_USER_ACTIVE
											  )
							)
						)
					) {
						$this->Notify_SendTalkNew($oUser,$this->oUserCurrent,$oTalk);
						$aResult[]=array(
							'bStateError'=>false,
							'sMsgTitle'=>$this->Lang_Get('attention'),
							'sMsg'=>$this->Lang_Get('talk_speaker_add_ok',array('login',htmlspecialchars($sUser))),
							'sUserId'=>$oUser->getId(),
							'sUserLogin'=>$oUser->getLogin(),
							'sUserLink'=>$oUser->getUserWebPath(),
							'sUserWebPath'=>$oUser->getUserWebPath(),
							'sUserAvatar48'=>$oUser->getProfileAvatarPath(48)
						);
						$bState=true;
					} else {
						$aResult[]=array(
							'bStateError'=>true,
							'sMsgTitle'=>$this->Lang_Get('error'),
							'sMsg'=>$this->Lang_Get('system_error')
						);
					}
				} else {
					/**
					 * Добавляем пользователь не принимает сообщения
					 */
					$aResult[]=array(
						'bStateError'=>true,
						'sMsgTitle'=>$this->Lang_Get('error'),
						'sMsg'=>$this->Lang_Get('talk_user_in_blacklist',array('login'=>htmlspecialchars($sUser)))
					);
				}
			} else {
				/**
				 * Пользователь не найден в базе данных или не активен
				 */
				$aResult[]=array(
					'bStateError'=>true,
					'sMsgTitle'=>$this->Lang_Get('error'),
					'sMsg'=>$this->Lang_Get('user_not_found',array('login'=>htmlspecialchars($sUser)))
				);
			}
		}
		/**
		 * Передаем во вьевер массив результатов обработки по каждому пользователю
		 */
		$this->Viewer_AssignAjax('aUsers',$aResult);
	}
	/**
	 * Возвращает количество новых сообщений
	 */
	public function AjaxNewMessages() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');

		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$iCountTalkNew=$this->Talk_GetCountTalkNew($this->oUserCurrent->getId());
		$this->Viewer_AssignAjax('iCountTalkNew',$iCountTalkNew);
	}
	/**
	 * Обработка завершения работу экшена
	 */
	public function EventShutdown() {
		if (!$this->oUserCurrent)	 {
			return ;
		}
		$iCountTalkFavourite=$this->Talk_GetCountTalksFavouriteByUserId($this->oUserCurrent->getId());
		$this->Viewer_Assign('iCountTalkFavourite',$iCountTalkFavourite);

		$iCountTopicFavourite=$this->Topic_GetCountTopicsFavouriteByUserId($this->oUserCurrent->getId());
		$iCountTopicUser=$this->Topic_GetCountTopicsPersonalByUser($this->oUserCurrent->getId(),1);
		$iCountCommentUser=$this->Comment_GetCountCommentsByUserId($this->oUserCurrent->getId(),'topic');
		$iCountCommentFavourite=$this->Comment_GetCountCommentsFavouriteByUserId($this->oUserCurrent->getId());
		$iCountNoteUser=$this->User_GetCountUserNotesByUserId($this->oUserCurrent->getId());

		$this->Viewer_Assign('oUserProfile',$this->oUserCurrent);
		$this->Viewer_Assign('iCountWallUser',$this->Wall_GetCountWall(array('wall_user_id'=>$this->oUserCurrent->getId(),'pid'=>null)));
		/**
		 * Общее число публикация и избранного
		 */
		$this->Viewer_Assign('iCountCreated',$iCountNoteUser+$iCountTopicUser+$iCountCommentUser);
		$this->Viewer_Assign('iCountFavourite',$iCountCommentFavourite+$iCountTopicFavourite);
		$this->Viewer_Assign('iCountFriendsUser',$this->User_GetCountUsersFriend($this->oUserCurrent->getId()));

		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);
		/**
		 * Передаем во вьевер константы состояний участников разговора
		 */
		$this->Viewer_Assign('TALK_USER_ACTIVE',ModuleTalk::TALK_USER_ACTIVE);
		$this->Viewer_Assign('TALK_USER_DELETE_BY_SELF',ModuleTalk::TALK_USER_DELETE_BY_SELF);
		$this->Viewer_Assign('TALK_USER_DELETE_BY_AUTHOR',ModuleTalk::TALK_USER_DELETE_BY_AUTHOR);
	}
}
?>