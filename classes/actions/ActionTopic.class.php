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
		if (!$this->Topic_IsAllowEditTopic($oTopic,$this->oUserCurrent)) {
			return parent::EventNotFound();
		}
		/**
		 * Добавляем блок вывода информации о блоге
		 */
		$this->Viewer_AddBlocks('right',array('block.blogInfo.tpl'));		
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
		if (!$this->Topic_IsAllowDeleteTopic($oTopic,$this->oUserCurrent)) {
			return parent::EventNotFound();
		}
		/**
		 * Удаляем топик
		 */
		$this->Topic_DeleteTopic($oTopic->getId());
		/**
		 * Перенаправляем на страницу со списком топиков из блога этого топика
		 */
		func_header_location($oTopic->getBlogUrlFull());
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
		 * Добавляем блок вывода информации о блоге
		 */		
		$this->Viewer_AddBlocks('right',array('block.blogInfo.tpl'));		
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
		$aResult=$this->Topic_GetTopicsPersonalByUser($this->oUserCurrent->getId(),$this->sCurrentEvent=='published' ? 1 : 0,$iPage,BLOG_TOPIC_PER_PAGE);	
		$aTopics=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/'.ROUTE_PAGE_TOPIC.'/'.$this->sCurrentEvent);
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
		if (!isset($_REQUEST['submit_topic_publish']) and !isset($_REQUEST['submit_topic_save'])) {
			return false;
		}	
		$this->Security_ValidateSendForm();	
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
		if (!$this->Blog_IsAllowBlog($oBlog,$this->oUserCurrent)) {
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
		 * Теперь можно смело добавлять топик к блогу
		 */
		$oTopic=new TopicEntity_Topic();
		$oTopic->setBlogId($oBlog->getId());
		$oTopic->setUserId($this->oUserCurrent->getId());
		$oTopic->setType('topic');
		$oTopic->setTitle(getRequest('topic_title'));
		$oTopic->setCutText(null);	
		$oTopic->setTextHash(md5(getRequest('topic_text')));
		/**
		 * Парсим на предмет ХТМЛ тегов
		 */
		$sText=$this->Text_Parser(getRequest('topic_text'));	
		$sTextShort=$sText;
		$sTextNew=$sText;
		$sTextTemp=str_replace("\r\n",'[<rn>]',getRequest('topic_text'));
		$sTextTemp=str_replace("\n",'[<n>]',$sTextTemp);
		if (preg_match("/^(.*)<cut(.*)>(.*)$/Ui",$sTextTemp,$aMatch)) {			
			$aMatch[1]=str_replace('[<rn>]',"\r\n",$aMatch[1]);
			$aMatch[1]=str_replace('[<n>]',"\r\n",$aMatch[1]);
			$aMatch[3]=str_replace('[<rn>]',"\r\n",$aMatch[3]);
			$aMatch[3]=str_replace('[<n>]',"\r\n",$aMatch[3]);				
			$sTextShort=$this->Text_Parser($aMatch[1]);
			$sTextNew=$this->Text_Parser($aMatch[1].' '.$aMatch[3]);							
			if (preg_match('/^\s*name\s*=\s*"(.+)"\s*\/?$/Ui',$aMatch[2],$aMatchCut)) {				
				$oTopic->setCutText(trim($aMatchCut[1]));
			}				
		}		
		
		$oTopic->setText($sTextNew);
		$oTopic->setTextShort($sTextShort);
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
		 * Добавляем топик
		 */
		if ($this->Topic_AddTopic($oTopic)) {
			/**
			 * Получаем топик, чтоб подцепить связанные данные
			 */
			$oTopic=$this->Topic_GetTopicById($oTopic->getId());
			//Делаем рассылку спама всем, кто состоит в этом блоге
			if ($oTopic->getPublish()==1 and $oBlog->getType()!='personal') {				
				$this->Topic_SendNotifyTopicNew($oBlog,$oTopic,$this->oUserCurrent);				
			}			
			func_header_location($oTopic->getUrl());
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
		$this->Security_ValidateSendForm();	
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
		if (!$this->Blog_IsAllowBlog($oBlog,$this->oUserCurrent)) {
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
		 * Теперь можно смело редактировать топик
		 */		
		$oTopic->setBlogId($oBlog->getId());		
		$oTopic->setTitle(getRequest('topic_title'));	
		$oTopic->setCutText(null);	
		$oTopic->setTextHash(md5(getRequest('topic_text')));	
		/**
		 * Парсим на предмет ХТМЛ тегов
		 */
		$sText=$this->Text_Parser(getRequest('topic_text'));	
		$sTextShort=$sText;
		$sTextNew=$sText;
		$sTextTemp=str_replace("\r\n",'[<rn>]',getRequest('topic_text'));
		$sTextTemp=str_replace("\n",'[<n>]',$sTextTemp);
		if (preg_match("/^(.*)<cut(.*)>(.*)$/Ui",$sTextTemp,$aMatch)) {			
			$aMatch[1]=str_replace('[<rn>]',"\r\n",$aMatch[1]);
			$aMatch[1]=str_replace('[<n>]',"\r\n",$aMatch[1]);
			$aMatch[3]=str_replace('[<rn>]',"\r\n",$aMatch[3]);
			$aMatch[3]=str_replace('[<n>]',"\r\n",$aMatch[3]);				
			$sTextShort=$this->Text_Parser($aMatch[1]);
			$sTextNew=$this->Text_Parser($aMatch[1].' '.$aMatch[3]);							
			if (preg_match('/^\s*name\s*=\s*"(.+)"\s*\/?$/Ui',$aMatch[2],$aMatchCut)) {				
				$oTopic->setCutText(trim($aMatchCut[1]));
			}				
		}		
		
		$oTopic->setText($sTextNew);
		$oTopic->setTextShort($sTextShort);
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
		/**
		 * Сохраняем топик
		 */
		if ($this->Topic_UpdateTopic($oTopic)) {	
			/**
			 * Рассылаем о новом топике подписчикам блога
			 */
			if ($bSendNotify)	 {
				$this->Topic_SendNotifyTopicNew($oBlog,$oTopic,$this->oUserCurrent);
			}
			if (!$oTopic->getPublish() and !$this->oUserCurrent->isAdministrator() and $this->oUserCurrent->getId()!=$oTopic->getUserId()) {
				func_header_location($oBlog->getUrlFull());
			}
			func_header_location($oTopic->getUrl());
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
		$bOk=true;
		/**
		 * Проверяем есть ли блог в кторый постим
		 */
		if (!func_check(getRequest('blog_id'),'id')) {
			$this->Message_AddError($this->Lang_Get('topic_create_blog_error_unknown'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли заголовок топика
		 */
		if (!func_check(getRequest('topic_title'),'text',2,200)) {
			$this->Message_AddError($this->Lang_Get('topic_create_title_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли содержание топика
		 */
		if (!func_check(getRequest('topic_text'),'text',2,15000)) {
			$this->Message_AddError($this->Lang_Get('topic_create_text_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли теги(метки)
		 */
		if (!func_check(getRequest('topic_tags'),'text',2,500)) {
			$this->Message_AddError($this->Lang_Get('topic_create_tags_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * проверяем ввод тегов 
		 */
		$sTags=getRequest('topic_tags');
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