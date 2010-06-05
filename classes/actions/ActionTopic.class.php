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
 * Обработка УРЛа вида /topic/ - управление своими топиками
 *
 */
class ActionTopic extends Action {
	/**
	 * Главное меню
	 *
	 * @var unknown_type
	 */
	protected $sMenuHeadItemSelect='blog';
	/**
	 * Меню
	 *
	 * @var unknown_type
	 */
	protected $sMenuItemSelect='topic';
	/**
	 * СубМеню
	 *
	 * @var unknown_type
	 */
	protected $sMenuSubItemSelect='add';
	/**
	 * Текущий юзер
	 *
	 * @var unknown_type
	 */
	protected $oUserCurrent=null;
	
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
			return parent::EventNotFound();
		}
		$this->oUserCurrent=$this->User_GetUserCurrent();
		$this->SetDefaultEvent('add');		
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_title'));
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('add','EventAdd');			
		$this->AddEventPreg('/^published$/i','/^(page(\d+))?$/i','EventShowTopics');	
		$this->AddEventPreg('/^saved$/i','/^(page(\d+))?$/i','EventShowTopics');	
		$this->AddEvent('edit','EventEdit');	
		$this->AddEvent('delete','EventDelete');
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Редактирование топика
	 *
	 * @return unknown
	 */
	protected function EventEdit() {
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='';
		$this->sMenuItemSelect='topic';
		/**
		 * Получаем номер топика из УРЛ и проверяем существует ли он
		 */
		$sTopicId=$this->GetParam(0);
		if (!($oTopic=$this->Topic_GetTopicById($sTopicId))) {
			return parent::EventNotFound();
		}
		/**
		 * Если права на редактирование
		 */		
		if (!$this->ACL_IsAllowEditTopic($oTopic,$this->oUserCurrent)) {
			return parent::EventNotFound();
		}	
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topic_edit_show',array('oTopic'=>$oTopic));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aBlogsAllow',$this->Blog_GetBlogsAllowByUser($this->oUserCurrent));
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_topic_edit'));
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('add');		
		/**
		 * Проверяем отправлена ли форма с данными(хотяб одна кнопка)
		 */		
		if (isset($_REQUEST['submit_topic_publish']) or isset($_REQUEST['submit_topic_save'])) {
			/**
		 	* Обрабатываем отправку формы
		 	*/
			return $this->SubmitEdit($oTopic);
		} else {
			/**
		 	* Заполняем поля формы для редактирования
		 	* Только перед отправкой формы!
		 	*/
			$_REQUEST['topic_title']=$oTopic->getTitle();
			$_REQUEST['topic_text']=$oTopic->getTextSource();
			$_REQUEST['topic_tags']=$oTopic->getTags();
			$_REQUEST['blog_id']=$oTopic->getBlogId();
			$_REQUEST['topic_id']=$oTopic->getId();
			$_REQUEST['topic_publish_index']=$oTopic->getPublishIndex();
			$_REQUEST['topic_forbid_comment']=$oTopic->getForbidComment();
		}	
	}
	/**
	 * Удаление топика
	 *
	 * @return unknown
	 */
	protected function EventDelete() {
		$this->Security_ValidateSendForm();
		/**
		 * Получаем номер топика из УРЛ и проверяем существует ли он
		 */
		$sTopicId=$this->GetParam(0);
		if (!($oTopic=$this->Topic_GetTopicById($sTopicId))) {
			return parent::EventNotFound();
		}
		/**
		 * проверяем есть ли право на удаление топика
		 */		
		if (!$this->ACL_IsAllowDeleteTopic($oTopic,$this->oUserCurrent)) {
			return parent::EventNotFound();
		}
		/**
		 * Удаляем топик
		 */
		$this->Hook_Run('topic_delete_before', array('oTopic'=>$oTopic));
		$this->Topic_DeleteTopic($oTopic);
		$this->Hook_Run('topic_delete_after', array('oTopic'=>$oTopic));
		/**
		 * Перенаправляем на страницу со списком топиков из блога этого топика
		 */
		Router::Location($oTopic->getBlog()->getUrlFull());
	}
	/**
	 * Добавление топика
	 *
	 * @return unknown
	 */
	protected function EventAdd() {
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='add';	
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topic_add_show');
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aBlogsAllow',$this->Blog_GetBlogsAllowByUser($this->oUserCurrent));				
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_topic_create'));
		/**
		 * Обрабатываем отправку формы
		 */
		return $this->SubmitAdd();		
	}	
	/**
	 * Выводит список топиков
	 *
	 */
	protected function EventShowTopics() {		
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect=$this->sCurrentEvent;
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;				
		/**
		 * Получаем список топиков
		 */					
		$aResult=$this->Topic_GetTopicsPersonalByUser($this->oUserCurrent->getId(),$this->sCurrentEvent=='published' ? 1 : 0,$iPage,Config::Get('module.topic.per_page'));	
		$aTopics=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),4,Router::GetPath('topic').$this->sCurrentEvent);
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);						
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_menu_'.$this->sCurrentEvent));
	}
	/**
	 * Обработка добавлени топика
	 *
	 * @return unknown
	 */
	protected function SubmitAdd() {
		/**
		 * Проверяем отправлена ли форма с данными(хотяб одна кнопка)
		 */		
		if (!isPost('submit_topic_publish') and !isPost('submit_topic_save')) {
			return false;
		}	
		/**
		 * Проверка корректности полей формы
		 */
		if (!$this->checkTopicFields()) {
			return false;	
		}		
		/**
		 * Определяем в какой блог делаем запись
		 */
		$iBlogId=getRequest('blog_id');	
		if ($iBlogId==0) {
			$oBlog=$this->Blog_GetPersonalBlogByUserId($this->oUserCurrent->getId());
		} else {
			$oBlog=$this->Blog_GetBlogById($iBlogId);
		}	
		/**
		 * Если блог не определен выдаем предупреждение
		 */
		if (!$oBlog) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_create_blog_error_unknown'),$this->Lang_Get('error'));
			return false;
		}		
		/**
		 * Проверяем права на постинг в блог
		 */
		if (!$this->ACL_IsAllowBlog($oBlog,$this->oUserCurrent)) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_create_blog_error_noallow'),$this->Lang_Get('error'));
			return false;
		}					
		/**
		 * Проверяем топик на уникальность
		 */
		if ($oTopicEquivalent=$this->Topic_GetTopicUnique($this->oUserCurrent->getId(),md5(getRequest('topic_text')))) {			
			$this->Message_AddErrorSingle($this->Lang_Get('topic_create_text_error_unique'),$this->Lang_Get('error'));
			return false;
		}	
		/**
		 * Проверяем разрешено ли постить топик по времени
		 */
		if (isPost('submit_topic_publish') and !$this->ACL_CanPostTopicTime($this->oUserCurrent)) {			
			$this->Message_AddErrorSingle($this->Lang_Get('topic_time_limit'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Теперь можно смело добавлять топик к блогу
		 */
		$oTopic=Engine::GetEntity('Topic');
		$oTopic->setBlogId($oBlog->getId());
		$oTopic->setUserId($this->oUserCurrent->getId());
		$oTopic->setType('topic');
		$oTopic->setTitle(getRequest('topic_title'));
		$oTopic->setTextHash(md5(getRequest('topic_text')));
		/**
		 * Получаемый и устанавливаем разрезанный текст по тегу <cut>
		 */
		list($sTextShort,$sTextNew,$sTextCut) = $this->Text_Cut(getRequest('topic_text'));
		
		$oTopic->setCutText($sTextCut);
		$oTopic->setText($this->Text_Parser($sTextNew));
		$oTopic->setTextShort($this->Text_Parser($sTextShort));
		
		$oTopic->setTextSource(getRequest('topic_text'));
		$oTopic->setTags(getRequest('topic_tags'));
		$oTopic->setDateAdd(date("Y-m-d H:i:s"));
		$oTopic->setUserIp(func_getIp());
		/**
		 * Публикуем или сохраняем
		 */
		if (isset($_REQUEST['submit_topic_publish'])) {
			$oTopic->setPublish(1);
			$oTopic->setPublishDraft(1);
		} else {
			$oTopic->setPublish(0);
			$oTopic->setPublishDraft(0);
		}		
		/**
		 * Принудительный вывод на главную
		 */
		$oTopic->setPublishIndex(0);
		if ($this->oUserCurrent->isAdministrator())	{
			if (getRequest('topic_publish_index')) {
				$oTopic->setPublishIndex(1);
			} 
		}	
		/**
		 * Запрет на комментарии к топику
		 */
		$oTopic->setForbidComment(0);
		if (getRequest('topic_forbid_comment')) {
			$oTopic->setForbidComment(1);
		}

		/**
		 * Запускаем выполнение хуков
		 */
		$this->Hook_Run('topic_add_before', array('oTopic'=>$oTopic,'oBlog'=>$oBlog));
		/**
		 * Добавляем топик
		 */		
		if ($this->Topic_AddTopic($oTopic)) {
			$this->Hook_Run('topic_add_after', array('oTopic'=>$oTopic,'oBlog'=>$oBlog));
			/**
			 * Получаем топик, чтоб подцепить связанные данные
			 */
			$oTopic=$this->Topic_GetTopicById($oTopic->getId());
			/**
			 * Делаем рассылку спама всем, кто состоит в этом блоге
			 */
			if ($oTopic->getPublish()==1 and $oBlog->getType()!='personal') {
				$this->Topic_SendNotifyTopicNew($oBlog,$oTopic,$this->oUserCurrent);				
			}	
			Router::Location($oTopic->getUrl());
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return Router::Action('error');
		}		
	}
	/**
	 * Обработка редактирования топика
	 *
	 * @param unknown_type $oTopic
	 * @return unknown
	 */
	protected function SubmitEdit($oTopic) {				
		/**
		 * Проверка корректности полей формы
		 */
		if (!$this->checkTopicFields()) {
			return false;	
		}	
		/**
		 * Определяем в какой блог делаем запись
		 */
		$iBlogId=getRequest('blog_id');	
		if ($iBlogId==0) {
			$oBlog=$this->Blog_GetPersonalBlogByUserId($oTopic->getUserId());
		} else {
			$oBlog=$this->Blog_GetBlogById($iBlogId);
		}
		/**
		 * Если блог не определен выдаем предупреждение
		 */
		if (!$oBlog) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_create_blog_error_unknown'),$this->Lang_Get('error'));
			return false;
		}
		/**
		 * Проверяем права на постинг в блог
		 */
		if (!$this->ACL_IsAllowBlog($oBlog,$this->oUserCurrent)) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_create_blog_error_noallow'),$this->Lang_Get('error'));
			return false;
		}
		/**
		 * Проверяем топик на уникальность
		 */
		if ($oTopicEquivalent=$this->Topic_GetTopicUnique($oTopic->getUserId(),md5(getRequest('topic_text')))) {			
			if ($oTopicEquivalent->getId()!=$oTopic->getId()) {
				$this->Message_AddErrorSingle($this->Lang_Get('topic_create_text_error_unique'),$this->Lang_Get('error'));
				return false;
			}
		}
		/**
		 * Проверяем разрешено ли постить топик по времени
		 */
		if (isPost('submit_topic_publish') and !$oTopic->getPublishDraft() and !$this->ACL_CanPostTopicTime($this->oUserCurrent)) {			
			$this->Message_AddErrorSingle($this->Lang_Get('topic_time_limit'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Сохраняем старое значение идентификатора блога
		 */
		$sBlogIdOld = $oTopic->getBlogId();
		/**
		 * Теперь можно смело редактировать топик
		 */		
		$oTopic->setBlogId($oBlog->getId());
		$oTopic->setTitle(getRequest('topic_title'));
		$oTopic->setTextHash(md5(getRequest('topic_text')));
		/**
		 * Получаемый и устанавливаем разрезанный текст по тегу <cut>
		 */
		list($sTextShort,$sTextNew,$sTextCut) = $this->Text_Cut(getRequest('topic_text'));

		$oTopic->setCutText($sTextCut);
		$oTopic->setText($this->Text_Parser($sTextNew));
		$oTopic->setTextShort($this->Text_Parser($sTextShort));
		
		$oTopic->setTextSource(getRequest('topic_text'));
		$oTopic->setTags(getRequest('topic_tags'));
		$oTopic->setUserIp(func_getIp());
		/**
		 * Публикуем или сохраняем в черновиках
		 */
		$bSendNotify=false;
		if (isset($_REQUEST['submit_topic_publish'])) {
			$oTopic->setPublish(1);
			if ($oTopic->getPublishDraft()==0) {
				$oTopic->setPublishDraft(1);
				$oTopic->setDateAdd(date("Y-m-d H:i:s"));
				$bSendNotify=true;
			}
		} else {
			$oTopic->setPublish(0);
		}
		/**
		 * Принудительный вывод на главную
		 */
		if ($this->oUserCurrent->isAdministrator())	{
			if (getRequest('topic_publish_index')) {
				$oTopic->setPublishIndex(1);
			} else {
				$oTopic->setPublishIndex(0);
			}
		}
		/**
		 * Запрет на комментарии к топику
		 */
		$oTopic->setForbidComment(0);
		if (getRequest('topic_forbid_comment')) {
			$oTopic->setForbidComment(1);
		}
		$this->Hook_Run('topic_edit_before', array('oTopic'=>$oTopic,'oBlog'=>$oBlog));
		/**
		 * Сохраняем топик
		 */
		if ($this->Topic_UpdateTopic($oTopic)) {
			$this->Hook_Run('topic_edit_after', array('oTopic'=>$oTopic,'oBlog'=>$oBlog,'bSendNotify'=>&$bSendNotify));
			/**
			 * Обновляем данные в комментариях, если топик был перенесен в новый блог
			 */
			if($sBlogIdOld!=$oTopic->getBlogId()) {
				$this->Comment_UpdateTargetParentByTargetId($oTopic->getBlogId(), 'topic', $oTopic->getId());
				$this->Comment_UpdateTargetParentByTargetIdOnline($oTopic->getBlogId(), 'topic', $oTopic->getId());
			}
			/**
			 * Рассылаем о новом топике подписчикам блога
			 */
			if ($bSendNotify)	 {
				$this->Topic_SendNotifyTopicNew($oBlog,$oTopic,$oTopic->getUser());
			}
			if (!$oTopic->getPublish() and !$this->oUserCurrent->isAdministrator() and $this->oUserCurrent->getId()!=$oTopic->getUserId()) {
				Router::Location($oBlog->getUrlFull());
			}
			Router::Location($oTopic->getUrl());
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return Router::Action('error');
		}		
	}
	/**
	 * Проверка полей формы
	 *
	 * @return unknown
	 */
	protected function checkTopicFields() {
		$this->Security_ValidateSendForm();
		
		$bOk=true;
		/**
		 * Проверяем есть ли блог в кторый постим
		 */
		if (!func_check(getRequest('blog_id',null,'post'),'id')) {
			$this->Message_AddError($this->Lang_Get('topic_create_blog_error_unknown'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли заголовок топика
		 */
		if (!func_check(getRequest('topic_title',null,'post'),'text',2,200)) {
			$this->Message_AddError($this->Lang_Get('topic_create_title_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли содержание топика
		 */
		if (!func_check(getRequest('topic_text',null,'post'),'text',2,Config::Get('module.topic.max_length'))) {
			$this->Message_AddError($this->Lang_Get('topic_create_text_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли теги(метки)
		 */
		if (!func_check(getRequest('topic_tags',null,'post'),'text',2,500)) {
			$this->Message_AddError($this->Lang_Get('topic_create_tags_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * проверяем ввод тегов 
		 */
		$sTags=getRequest('topic_tags',null,'post');
		$aTags=explode(',',$sTags);
		$aTagsNew=array();
		$aTagsNewLow=array();
		foreach ($aTags as $sTag) {
			$sTag=trim($sTag);
			if (func_check($sTag,'text',2,50) and !in_array(mb_strtolower($sTag,'UTF-8'),$aTagsNewLow)) {
				$aTagsNew[]=$sTag;
				$aTagsNewLow[]=mb_strtolower($sTag,'UTF-8');
			}
		}
		if (!count($aTagsNew)) {
			$this->Message_AddError($this->Lang_Get('topic_create_tags_error_bad'),$this->Lang_Get('error'));
			$bOk=false;
		} else {
			$_REQUEST['topic_tags']=join(',',$aTagsNew);
		}
		/**
		 * Выполнение хуков
		 */
		$this->Hook_Run('check_topic_fields', array('bOk'=>&$bOk));
		
		return $bOk;
	}
	/**
	 * При завершении экшена загружаем необходимые переменные
	 *
	 */
	public function EventShutdown() {
		$this->Viewer_Assign('sMenuHeadItemSelect',$this->sMenuHeadItemSelect);
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);
	}
}
?>