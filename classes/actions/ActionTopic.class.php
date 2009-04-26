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
		$this->AddEvent('saved','EventSaved');
		$this->AddEvent('published','EventPublished');		
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
		if (!$oTopic=$this->Topic_GetTopicById($sTopicId,null,-1)) {
			return parent::EventNotFound();
		}
		/**
		 * проверяем кто владелец топика, либо модератор и администратор блога
		 */
		$oBlogUser=$this->Blog_GetRelationBlogUserByBlogIdAndUserId($oTopic->getBlogId(),$this->oUserCurrent->getId());		
		$bIsAdministratorBlog=$oBlogUser ? $oBlogUser->getIsAdministrator() : false;
		$bIsModeratorBlog=$oBlogUser ? $oBlogUser->getIsModerator() : false;
		
		if ($oTopic->getUserId()!=$this->oUserCurrent->getId() and !$this->oUserCurrent->isAdministrator() and !$bIsAdministratorBlog and !$bIsModeratorBlog and $oTopic->getBlogOwnerId()!=$this->oUserCurrent->getId()) {
			return parent::EventNotFound();
		}
		/**
		 * Добавляем блок вывода информации о блоге
		 */
		$this->Viewer_AddBlocks('right',array('block.blogInfo.tpl'));
		/**
		 * Получаем данные для отображения формы
		 * Если админ то делаем доступными все блоги
		 */
		$aAllowBlogsUser=array();
		$aBlogsOwner=array();
		if ($this->oUserCurrent->isAdministrator()) {
			$aBlogsOwner=$this->Blog_GetBlogs();
		} else {
			$aBlogsOwner=$this->Blog_GetBlogsByOwnerId($this->oUserCurrent->getId());		
			$aBlogsUser=$this->Blog_GetRelationBlogUsersByUserId($this->oUserCurrent->getId());			
			foreach ($aBlogsUser as $oBlogUser) {
				$oBlog=$this->Blog_GetBlogById($oBlogUser->getBlogId());
				// делаем через "or" чтоб дать возможность юзеру отредактировать свой топик в блоге в котором он уже не может постить, т.е. для тех топиков что были запощены раньше и был доступ в блог
				if ($this->ACL_CanAddTopic($this->oUserCurrent,$oBlog) or $oTopic->getBlogId()==$oBlog->getId()) {
					$aAllowBlogsUser[]=$oBlogUser;
				}
			}
		}
		
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aBlogsUser',$aAllowBlogsUser);
		$this->Viewer_Assign('aBlogsOwner',$aBlogsOwner);
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
		/**
		 * Получаем номер топика из УРЛ и проверяем существует ли он
		 */
		$sTopicId=$this->GetParam(0);
		if (!$oTopic=$this->Topic_GetTopicById($sTopicId,null,-1)) {
			return parent::EventNotFound();
		}
		/**
		 * проверяем есть ли право на удаление топика
		 */
		$oBlogUser=$this->Blog_GetRelationBlogUserByBlogIdAndUserId($oTopic->getBlogId(),$this->oUserCurrent->getId());		
		$bIsAdministratorBlog=$oBlogUser ? $oBlogUser->getIsAdministrator() : false;
		$bIsModeratorBlog=$oBlogUser ? $oBlogUser->getIsModerator() : false;
		
		if (!$this->oUserCurrent->isAdministrator() and !$bIsAdministratorBlog and $oTopic->getBlogOwnerId()!=$this->oUserCurrent->getId()) {
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
		 * Получаем данные для отображения формы
		 */
		$aAllowBlogsUser=array();
		$aBlogsOwner=array();
		if ($this->oUserCurrent->isAdministrator()) {
			$aBlogsOwner=$this->Blog_GetBlogs();
		} else {
			$aBlogsOwner=$this->Blog_GetBlogsByOwnerId($this->oUserCurrent->getId());
			$aBlogsUser=$this->Blog_GetRelationBlogUsersByUserId($this->oUserCurrent->getId());			
			foreach ($aBlogsUser as $oBlogUser) {
				$oBlog=$this->Blog_GetBlogById($oBlogUser->getBlogId());
				if ($this->ACL_CanAddTopic($this->oUserCurrent,$oBlog)) {
					$aAllowBlogsUser[]=$oBlogUser;
				}
			}
		}
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aBlogsUser',$aAllowBlogsUser);
		$this->Viewer_Assign('aBlogsOwner',$aBlogsOwner);		
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_topic_create'));
		/**
		 * Обрабатываем отправку формы
		 */
		return $this->SubmitAdd();		
	}
	/**
	 * Выводит список сохранёных топиков
	 *
	 */
	protected function EventSaved() {	
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='saved';
		/**
		 * Передан ли номер страницы
		 */	
		if (preg_match("/^page(\d+)$/i",$this->getParam(0),$aMatch)) {			
			$iPage=$aMatch[1];
		} else {
			$iPage=1;
		}	
		/**
		 * Получаем список топиков
		 */	
		$iCount=0;			
		$aResult=$this->Topic_GetTopicsPersonalByUser($this->oUserCurrent->getId(),0,$iCount,$iPage,BLOG_TOPIC_PER_PAGE);	
		$aTopics=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/topic/saved');
		/**
		 * Загружаем переменные в шаблон
		 */					
		$this->Viewer_Assign('aPaging',$aPaging);							
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_menu_saved'));
	}
	/**
	 * Выводит список опубликованых топиков
	 *
	 */
	protected function EventPublished() {
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='published';
		/**
		 * Передан ли номер страницы
		 */
		if (preg_match("/^page(\d+)$/i",$this->getParam(0),$aMatch)) {			
			$iPage=$aMatch[1];
		} else {
			$iPage=1;
		}		
		/**
		 * Получаем список топиков
		 */
		$iCount=0;			
		$aResult=$this->Topic_GetTopicsPersonalByUser($this->oUserCurrent->getId(),1,$iCount,$iPage,BLOG_TOPIC_PER_PAGE);	
		$aTopics=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/topic/published');
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);						
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_menu_published'));
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
		 * Проверка состоит ли юзер в блоге в который постит
		 */
		if (!$this->Blog_GetRelationBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId())  and !$this->oUserCurrent->isAdministrator()) {
			if ($oBlog->getOwnerId()!=$this->oUserCurrent->getId()) {
				$this->Message_AddErrorSingle($this->Lang_Get('topic_create_blog_error_nojoin'),$this->Lang_Get('error'));
				return false;
			}
		}		
		/**
		 * Проверяем есть ли права на постинг топика в этот блог
		 */
		if (!$this->ACL_CanAddTopic($this->User_GetUserCurrent(),$oBlog) and !$this->oUserCurrent->isAdministrator()) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_create_blog_error_noacl'),$this->Lang_Get('error'));
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
		/**
		 * Создаёт анонс топика(обрезаем по тег <cut>)
		 */	
		$sTestShort=$sText;
		$sTestNew=$sText;
		$sTextTemp=str_replace("\r\n",'[<rn>]',$sText);
		$sTextTemp=str_replace("\n",'[<n>]',$sTextTemp);
		if (preg_match("/^(.*)<cut(.*)>(.*)$/Ui",$sTextTemp,$aMatch)) {			
			$sTestShort=$aMatch[1];		
			$sTestShort=str_replace('[<rn>]',"\r\n",$sTestShort);
			$sTestShort=str_replace('[<n>]',"\r\n",$sTestShort);	
			$sTestShort=$this->Text_Parser($sTestShort);
			$sTestNew=$aMatch[1].' '.$aMatch[3];							
			if (preg_match('/^\s*name\s*=\s*"(.+)"\s*\/?$/Ui',$aMatch[2],$aMatchCut)) {				
				$oTopic->setCutText(trim($aMatchCut[1]));
			}				
		}		
		$sTestNew=str_replace('[<rn>]',"\r\n",$sTestNew);
		$sTestNew=str_replace('[<n>]',"\r\n",$sTestNew);
					
		$oTopic->setText($sTestNew);
		$oTopic->setTextShort($sTestShort);
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
			//Делаем рассылку спама всем, кто состоит в этом блоге
			if ($oTopic->getPublish()==1 and $oBlog->getType()!='personal') {
				$aUsers=$this->Blog_GetBlogUsersByBlogId($oBlog->getId());
				foreach ($aUsers as $oUser) {
					if ($oUser->getId()==$this->oUserCurrent->getId()) {
						continue;
					}				
					$this->Notify_SendTopicNewToSubscribeBlog($oUser,$oTopic,$oBlog,$this->oUserCurrent);
				}
				//отправляем создателю блога
				if ($oBlog->getOwnerId()!=$this->oUserCurrent->getId()) {
					$oUser=$this->User_GetUserById($oBlog->getOwnerId());
					$this->Notify_SendTopicNewToSubscribeBlog($oUser,$oTopic,$oBlog,$this->oUserCurrent);
				}
			}
			
			func_header_location(DIR_WEB_ROOT.'/blog/'.$oTopic->getId().'.html');
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
		 * Проверка состоит ли юзер в блоге в который постит
		 * Если нужно разрешить редактировать топик в блоге в котором юзер уже не состоит
		 * Если юзер является администратором либо модератором блога, то разрешаем ему перенос в другой блог
		 */
		$oBlogUser=$this->Blog_GetRelationBlogUserByBlogIdAndUserId($oTopic->getBlogId(),$this->oUserCurrent->getId());		
		$bIsAdministratorBlog=$oBlogUser ? $oBlogUser->getIsAdministrator() : false;
		$bIsModeratorBlog=$oBlogUser ? $oBlogUser->getIsModerator() : false;
		
		if (!$this->Blog_GetRelationBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId()) and !$this->oUserCurrent->isAdministrator() and !$bIsAdministratorBlog and !$bIsModeratorBlog and $oTopic->getBlogOwnerId()!=$this->oUserCurrent->getId()) {
			if ($oBlog->getOwnerId()!=$this->oUserCurrent->getId()) {
				$this->Message_AddErrorSingle($this->Lang_Get('topic_create_blog_error_nojoin'),$this->Lang_Get('error'));
				return false;
			}
		}		
		/**
		 * Проверяем есть ли права на постинг топика в этот блог
		 * Условие $oBlog->getId()!=$oTopic->getBlogId()  для того чтоб разрешить отредактировать топик в блоге в который сейчас юзер не имеет права на постинг, но раньше успел в него запостить этот топик
		 */
		if (!$this->ACL_CanAddTopic($this->oUserCurrent,$oBlog) and $oBlog->getId()!=$oTopic->getBlogId() and !$this->oUserCurrent->isAdministrator()) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_create_blog_error_noacl'),$this->Lang_Get('error'));
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
		$sTestShort=$sText;
		$sTestNew=$sText;
		$sTextTemp=str_replace("\r\n",'[<rn>]',$sText);
		$sTextTemp=str_replace("\n",'[<n>]',$sTextTemp);
		if (preg_match("/^(.*)<cut(.*)>(.*)$/Ui",$sTextTemp,$aMatch)) {			
			$sTestShort=$aMatch[1];		
			$sTestShort=str_replace('[<rn>]',"\r\n",$sTestShort);
			$sTestShort=str_replace('[<n>]',"\r\n",$sTestShort);	
			$sTestShort=$this->Text_Parser($sTestShort);
			$sTestNew=$aMatch[1].' '.$aMatch[3];							
			if (preg_match('/^\s*name\s*=\s*"(.+)"\s*\/?$/Ui',$aMatch[2],$aMatchCut)) {				
				$oTopic->setCutText(trim($aMatchCut[1]));
			}				
		}		
		$sTestNew=str_replace('[<rn>]',"\r\n",$sTestNew);
		$sTestNew=str_replace('[<n>]',"\r\n",$sTestNew);
		
		$oTopic->setText($sTestNew);
		$oTopic->setTextShort($sTestShort);
		$oTopic->setTextSource(getRequest('topic_text'));
		$oTopic->setTags(getRequest('topic_tags'));		
		$oTopic->setUserIp(func_getIp());
		/**
		 * Публикуем или сохраняем в черновиках
		 */
		if (isset($_REQUEST['submit_topic_publish'])) {
			$oTopic->setPublish(1);
			if ($oTopic->getPublishDraft()==0) {
				$oTopic->setPublishDraft(1);
				$oTopic->setDateAdd(date("Y-m-d H:i:s"));
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
			if (!$oTopic->getPublish() and !$this->oUserCurrent->isAdministrator() and $this->oUserCurrent->getId()!=$oTopic->getUserId()) {
				func_header_location($oTopic->getBlogUrlFull());
			}
			func_header_location(DIR_WEB_ROOT.'/blog/'.$oTopic->getId().'.html');
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