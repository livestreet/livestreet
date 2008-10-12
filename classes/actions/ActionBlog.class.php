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
 * Класс обработки URL'ов вида /blog/
 *
 */
class ActionBlog extends Action {
	/**
	 * Какое меню активно
	 *
	 * @var unknown_type
	 */
	protected $sMenuItemSelect='blog';
	/**
	 * Какое подменю активно
	 *
	 * @var unknown_type
	 */
	protected $sMenuSubItemSelect='good';
	/**
	 * УРЛ блога который подставляется в меню
	 *
	 * @var unknown_type
	 */
	protected $sMenuSubBlogUrl;
	/**
	 * Текущий пользователь
	 *
	 * @var unknown_type
	 */
	protected $oUserCurrent=null;
	
	/**
	 * Число новых топиков в коллективных блогах
	 *
	 * @var unknown_type
	 */
	protected $iCountTopicsCollectiveNew=0;
	/**
	 * Число новых топиков в персональных блогах
	 *
	 * @var unknown_type
	 */
	protected $iCountTopicsPersonalNew=0;
	/**
	 * Число новых топиков в конкретном блоге
	 *
	 * @var unknown_type
	 */
	protected $iCountTopicsBlogNew=0;
	/**
	 * Число новых топиков
	 *
	 * @var unknown_type
	 */
	protected $iCountTopicsNew=0;
	/**
	 * Список URL с котрыми запрещено создавать блог
	 *
	 * @var unknown_type
	 */
	protected $aBadBlogUrl=array('new','good','bad','edit','add');
	
	/**
	 * Инизиализация экшена
	 *
	 */
	public function Init() {		
		/**
		 * Устанавливаем евент по дефолту, т.е. будем показывать хорошие топики из коллективных блогов
		 */
		$this->SetDefaultEvent('good');
		$this->sMenuSubBlogUrl=DIR_WEB_ROOT.'/blog';
		/**
		 * Достаём текущего пользователя
		 */
		$this->oUserCurrent=$this->User_GetUserCurrent();
		/**
		 * Определяем какие блоки нужно выводить справа
		 */
		$this->Viewer_AddBlocksRight(array('comments','tags','blogs'));		
		/**
		 * Подсчитываем новые топики
		 */
		$this->iCountTopicsCollectiveNew=$this->Topic_GetCountTopicsCollectiveNew();
		$this->iCountTopicsPersonalNew=$this->Topic_GetCountTopicsPersonalNew();	
		$this->iCountTopicsBlogNew=$this->iCountTopicsCollectiveNew;
		$this->iCountTopicsNew=$this->iCountTopicsCollectiveNew+$this->iCountTopicsPersonalNew;
	}
	
	/**
	 * Регистрируем евенты, по сути определяем УРЛы вида /blog/.../
	 *
	 */
	protected function RegisterEvent() {		
		$this->AddEvent('good','EventGood');	
		$this->AddEvent('bad','EventBad');	
		$this->AddEvent('new','EventNew');
		$this->AddEvent('add','EventAddBlog');
		$this->AddEvent('edit','EventEditBlog');
		
		$this->AddEventPreg('/^(\d+)\.html$/i','EventShowTopicPersonal');
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^(\d+)\.html$/i','EventShowTopic');
		
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^$/i','EventShowBlogGood');
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^page(\d+)$/i','EventShowBlogGood');
		
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^bad$/i','/^$/i','EventShowBlogBad');
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^bad$/i','/^page(\d+)$/i','EventShowBlogBad');
		
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^new$/i','/^$/i','EventShowBlogNew');
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^new$/i','/^page(\d+)$/i','EventShowBlogNew');
		
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^profile$/i','/^$/i','EventShowBlogProfile');
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^profile$/i','/^page(\d+)$/i','EventShowBlogProfile');		
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	/**
	 * Добавление нового блога
	 *
	 * @return unknown
	 */
	protected function EventAddBlog() {
		$this->Viewer_AddHtmlTitle('Создание блога');
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='add';
		$this->sMenuItemSelect='add_blog';				
		/**
		 * Проверяем авторизован ли пользователь
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle('Для того чтобы создать блог, сначало нужно войти под своим аккаунтом.','Нет доступа');
			return Router::Action('error');
		}		
		/**
		 * Проверяем хватает ли рейтинга юзеру чтоб создать блог
		 */
		if (!$this->ACL_CanCreateBlog($this->User_GetUserCurrent())) {
			$this->Message_AddErrorSingle('Вы еще не достаточно окрепли чтобы создавать свой блог','Ошибка');
			return Router::Action('error');
		}		
		/**
		 * Запускаем проверку корректности ввода полей при добалении блога
		 */
		if (!$this->checkBlogFields()) {
			return false;	
		}		
		/**
		 * Если всё ок то пытаемся создать блог
		 */
		$oBlog=new BlogEntity_Blog();
		$oBlog->setOwnerId($this->oUserCurrent->getId());
		$oBlog->setTitle(getRequest('blog_title'));
		/**
		 * Парсим текст на предмет разных ХТМЛ тегов
		 */
		$sText=$this->Text_Parser(getRequest('blog_description'));				
		$oBlog->setDescription($sText);
		$oBlog->setType(getRequest('blog_type'));
		$oBlog->setDateAdd(date("Y-m-d H:i:s"));
		$oBlog->setLimitRatingTopic(getRequest('blog_limit_rating_topic'));
		$oBlog->setUrl(getRequest('blog_url'));
		/**
		 * Создаём блог
		 */
		if ($this->Blog_AddBlog($oBlog)) {
			func_header_location(DIR_WEB_ROOT.'/blog/'.$oBlog->getUrl().'/');
		} else {
			$this->Message_AddError('Внутреняя ошибка, повторите позже','Ошибка');
		}
	}
	
	/**
	 * Редактирование блога
	 *
	 * @return unknown
	 */
	protected function EventEditBlog() {		
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='saved';
		$this->sMenuItemSelect='';
		
		/**
		 * Проверяем передан ли в УРЛе номер блога
		 */
		$sBlogId=$this->GetParam(0);
		if (!$oBlog=$this->Blog_GetBlogById($sBlogId)) {
			return parent::EventNotFound();
		}
		/**
		 * Проверям авторизован ли пользователь
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle('Для того чтобы изменить блог, сначало нужно войти под своим аккаунтом.','Нет доступа');
			return Router::Action('error');
		}
		/**
		 * Явлется ли авторизованный пользователь хозяином блога
		 */
		if ($oBlog->getOwnerId()!=$this->oUserCurrent->getId()  and !$this->oUserCurrent->isAdministrator()) {
			return parent::EventNotFound();
		}			
		$this->Viewer_AddHtmlTitle($oBlog->getTitle());
		$this->Viewer_AddHtmlTitle('Редактирование блога');
		/**
		 * Устанавливаем шалон для вывода
		 */		
		$this->SetTemplateAction('add');
		/**
		 * Если нажали кнопку "Сохранить"
		 */
		if (isset($_REQUEST['submit_blog_add'])) {
			/**
			 * Запускаем проверку корректности ввода полей при редактировании блога
			 */
			if (!$this->checkBlogFields()) {
				return false;
			}			
			$oBlog->setTitle(getRequest('blog_title'));
			/**
			 * Парсим описание блога на предмет ХТМЛ тегов
			 */
			$sText=$this->Text_Parser(getRequest('blog_description'));			
			$oBlog->setDescription($sText);
			$oBlog->setType(getRequest('blog_type'));
			$oBlog->setLimitRatingTopic(getRequest('blog_limit_rating_topic'));
			$oBlog->setUrl(getRequest('blog_url'));		
			/**
			 * Обновляем блог
			 */
			if ($this->Blog_UpdateBlog($oBlog)) {
				func_header_location(DIR_WEB_ROOT.'/blog/'.$oBlog->getUrl().'/');
			} else {
				$this->Message_AddErrorSingle('Возникли технические неполадки при изменении блога, пожалуйста повторите позже.','Внутреняя ошибка');
				return Router::Action('error');
			}
		} else {
			/**
			 * Загружаем данные в форму редактирования блога
			 */
			$_REQUEST['blog_title']=$oBlog->getTitle();
			$_REQUEST['blog_url']=$oBlog->getUrl();
			$_REQUEST['blog_type']=$oBlog->getType();
			$_REQUEST['blog_description']=$oBlog->getDescription();
			$_REQUEST['blog_limit_rating_topic']=$oBlog->getLimitRatingTopic();
			$_REQUEST['blog_id']=$oBlog->getId();
		}
		
		
	}
	
	/**
	 * Проверка полей блога
	 *
	 * @return unknown
	 */
	protected function checkBlogFields() {
		/**
		 * Проверяем только если была отправлена форма с данными
		 */
		if (!isset($_REQUEST['submit_blog_add'])) {
			$_REQUEST['blog_limit_rating_topic']=0;
			return false;
		}
		
		$bOk=true;
		/**
		* Проверяем есть ли название блога
		*/
		if (!func_check(getRequest('blog_title'),'text',2,200)) {
			$this->Message_AddError('Название блога должно быть от 2 до 200 символов','Ошибка');
			$bOk=false;
		}
		/**
		* Проверяем есть ли заголовок топика, с заменой всех пробельных символов на "_"
		*/		
		$blogUrl=preg_replace("/\s+/",'_',getRequest('blog_url'));
		$_REQUEST['blog_url']=$blogUrl;	
		if (!func_check(getRequest('blog_url'),'login',2,50)) {
			$this->Message_AddError('URL блога должен быть от 2 до 50 символов и только на латинице + цифры и знаки "-", "_"','Ошибка');
			$bOk=false;
		}
		/**
		 * Проверяем на счет плохих УРЛов
		 */
		if (in_array(getRequest('blog_url'),$this->aBadBlogUrl)) {
			$this->Message_AddError('URL блога должен отличаться от: '.join(',',$this->aBadBlogUrl),'Ошибка');
			$bOk=false;
		}
		/**
		 * Проверяем есть ли описание блога
		 */
		if (!func_check(getRequest('blog_description'),'text',10,3000)) {
			$this->Message_AddError('Текст описания блога должен быть от 10 до 3000 символов','Ошибка');
			$bOk=false;
		}
		/**
		 * Проверяем доступные типы блога для создания, пока доступен только один тип - open
		 */
		if (!in_array(getRequest('blog_type'),array('open'))) {
			$this->Message_AddError('Неизвестный тип блога','Ошибка');
			$bOk=false;
		}
		/**
		 * Преобразуем ограничение по рейтингу в число 
		 */				
		if (!func_check(getRequest('blog_limit_rating_topic'),'float')) {
			$this->Message_AddError('Значение ограничения рейтинга должно быть числом','Ошибка');
			$bOk=false;
		}		
		return $bOk;
	}
	
	/**
	 * Вывод хороших топиков из коллективных блогов
	 *
	 */
	protected function EventGood() {	
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='good';
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
		$aResult=$this->Topic_GetTopicsCollectiveGood($iCount,$iPage,BLOG_TOPIC_PER_PAGE);			
		$aTopics=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/blog/'.$this->sCurrentEvent);
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('aPaging',$aPaging);		
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');	
	}	

	/**
	 * Вывод плохих топиков из коллективных блогов
	 *
	 */
	protected function EventBad() {	
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='bad';
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
		$aResult=$this->Topic_GetTopicsCollectiveBad($iCount,$iPage,BLOG_TOPIC_PER_PAGE);			
		$aTopics=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */	
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/blog/'.$this->sCurrentEvent);		
		/**
		 * Загружаем переменные в шаблон
		 */			
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aTopics',$aTopics);	
		/**
		 * Устанавливаем шаблон вывода
		 */			
		$this->SetTemplateAction('index');
	}
	/**
	 * Вывод новых топиков из коллективных блогов
	 *
	 */
	protected function EventNew() {	
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='new';
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
		$aResult=$this->Topic_GetTopicsCollectiveNew($iCount,$iPage,BLOG_TOPIC_PER_PAGE);			
		$aTopics=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/blog/'.$this->sCurrentEvent);							
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aTopics',$aTopics);
		/**
		 * Устанавливаем шаблон вывода
		 */				
		$this->SetTemplateAction('index');
	}
		
	/**
	 * Показ топика из персонального блога
	 *
	 * @param unknown_type $iTopicId
	 * @return unknown
	 */
	protected function EventShowTopicPersonal() {		
		$iTopicId=$this->GetEventMatch(1);
		/**
		 * Меню
		 */
		$this->sMenuItemSelect='log';
		$this->sMenuSubItemSelect='';
		/**
		 * Проверяем есть ли такой топик
		 */
		if (!($oTopic=$this->Topic_GetTopicById($iTopicId,$this->oUserCurrent,1))) {
			return parent::EventNotFound();
		}
		/**
		 * Если запросили не персональный топик то перенаправляем на страницу для вывода коллектиного топика
		 */
		if ($oTopic->getBlogType()!='personal') {
			func_header_location(DIR_WEB_ROOT.'/blog/'.$oTopic->getBlogUrl().'/'.$oTopic->getId().'.html');
		}
		/**
		 * Обрабатываем добавление коммента
		 */
		$this->SubmitComment($oTopic);
		/**
		 * Достаём комменты к топику
		 */
		$aComments=$this->Comment_GetCommentsByTopicId($oTopic->getId());
		/**
		 * Проверяем находится ли топик в избранном у текущего юзера
		 */
		$bInFavourite=false;
		if ($this->oUserCurrent) {
			if ($this->Topic_GetFavouriteTopic($oTopic->getId(),$this->oUserCurrent->getId())) {
				$bInFavourite=true;
			}
		}
		/**
		 * Получаем дату прочтения топика
		 */
		$dDate=date("Y-m-d H:i:s");
		if ($this->oUserCurrent) {
			if ($return=$this->Topic_GetDateRead($oTopic->getId(),$this->oUserCurrent->getId())) {
				$dDate=$return;
			}
		}
		/**
		 * Отмечаем дату прочтения топика
		 */
		if ($this->oUserCurrent) {
			$this->Topic_SetDateRead($oTopic->getId(),$this->oUserCurrent->getId());
		}
		/**
		 * Загружаем переменные в шаблон
		 */		
		$this->Viewer_Assign('bInFavourite',$bInFavourite);
		$this->Viewer_Assign('dDateTopicRead',$dDate);
		$this->Viewer_Assign('oTopic',$oTopic);
		$this->Viewer_Assign('aComments',$aComments);
		$this->Viewer_AddHtmlTitle($oTopic->getBlogTitle());
		$this->Viewer_AddHtmlTitle($oTopic->getTitle());
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('topic');
	}
	
	/**
	 * Показ топика из коллективного блога
	 *
	 * @param unknown_type $sBlogUrl
	 * @param unknown_type $iTopicId
	 * @return unknown
	 */
	protected function EventShowTopic() {	
		$sBlogUrl=$this->sCurrentEvent;
		$iTopicId=$this->GetParamEventMatch(0,1);
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='';	
		/**
		 * Проверяем есть ли такой топик
		 */		
		if (!($oTopic=$this->Topic_GetTopicById($iTopicId,$this->oUserCurrent,1))) {
			return parent::EventNotFound();
		}
		/**
		 * Если запросили топик из персонального блогато перенаправляем на страницу вывода коллективного топика
		 */
		if ($oTopic->getBlogType()=='personal') {
			func_header_location(DIR_WEB_ROOT.'/blog/'.$oTopic->getId().'.html');
		}
		/**
		 * Если номер топика правильный но УРЛ блога косяный то корректируем его и перенаправляем на нужный адрес
		 */
		if ($oTopic->getBlogUrl()!=$sBlogUrl) {
			func_header_location(DIR_WEB_ROOT.'/blog/'.$oTopic->getBlogUrl().'/'.$oTopic->getId().'.html');
		}
		/**
		 * Обрабатываем добавление коммента
		 */
		$this->SubmitComment($oTopic);
		/**
		 * Достаём комменты к топику
		 */
		$aComments=$this->Comment_GetCommentsByTopicId($oTopic->getId());
		/**
		 * Проверяем находится ли топик в избранном у текущего юзера
		 */
		$bInFavourite=false;
		if ($this->oUserCurrent) {
			if ($this->Topic_GetFavouriteTopic($oTopic->getId(),$this->oUserCurrent->getId())) {
				$bInFavourite=true;
			}
		}
		/**
		 * Получаем дату прочтения топика
		 */
		$dDate=date("Y-m-d H:i:s");
		if ($this->oUserCurrent) {
			if ($return=$this->Topic_GetDateRead($oTopic->getId(),$this->oUserCurrent->getId())) {
				$dDate=$return;
			}
		}
		/**
		 * Отмечаем дату прочтения топика
		 */
		if ($this->oUserCurrent) {
			$this->Topic_SetDateRead($oTopic->getId(),$this->oUserCurrent->getId());
		}
		/**
		 * Загружаем переменные в шаблон
		 */		
		$this->Viewer_Assign('bInFavourite',$bInFavourite);
		$this->Viewer_Assign('dDateTopicRead',$dDate);
		$this->Viewer_Assign('oTopic',$oTopic);
		$this->Viewer_Assign('aComments',$aComments);	
		$this->Viewer_AddHtmlTitle($oTopic->getBlogTitle());
		$this->Viewer_AddHtmlTitle($oTopic->getTitle());
		/**
		 * Устанавливаем шаблон вывода
		 */	
		$this->SetTemplateAction('topic');
	}
	
	/**
	 * Вывод хороших топиков из коллективного блога
	 *
	 * @param unknown_type $sBlogUrl
	 * @param unknown_type $sPage
	 * @return unknown
	 */
	protected function EventShowBlogGood() {
		$sBlogUrl=$this->sCurrentEvent;
		$sPage=$this->GetParam(0);
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='good';
		$this->sMenuSubBlogUrl=DIR_WEB_ROOT.'/blog/'.$sBlogUrl;
		/**
		 * Проверяем есть ли блог с таким УРЛ
		 */		
		if (!($oBlog=$this->Blog_GetBlogByUrl($sBlogUrl))) {
			return parent::EventNotFound();
		}			
		/**
		 * Проверяем является ли текущий пользователь пользователем блога
		 */
		$bNeedJoin=true;
		if ($this->oUserCurrent) {
			if ($this->Blog_GetRelationBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId())) {
				$bNeedJoin=false;
			}
		}
		/**
		 * Передан ли номер страницы
		 */
		if (preg_match("/^page(\d+)$/i",$sPage,$aMatch)) {			
			$iPage=$aMatch[1];
		} else {
			$iPage=1;
		}		
		/**
		 * Получаем список топиков
		 */
		$iCount=0;			
		$aResult=$this->Topic_GetTopicsByBlogGood($oBlog,$iCount,$iPage,BLOG_TOPIC_PER_PAGE);	
		$aTopics=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/blog/'.$sBlogUrl.'');			
		/**
		 * Получаем число новых топиков в текущем блоге
		 */
		$this->iCountTopicsBlogNew=$this->Topic_GetCountTopicsByBlogNew($oBlog);		
		/**
		 * Загружаем переменные в шаблон
		 */				
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('oBlog',$oBlog);
		$this->Viewer_Assign('bNeedJoin',$bNeedJoin);
		$this->Viewer_AddHtmlTitle($oBlog->getTitle());
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('blog');
	}
	
	/**
	 * Вывод плохих топиков из коллективного блога
	 *
	 * @param unknown_type $sBlogUrl
	 * @param unknown_type $sPage
	 * @return unknown
	 */
	protected function EventShowBlogBad() {	
		$sBlogUrl=$this->sCurrentEvent;
		$sPage=$this->GetParam(1);
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='bad';
		$this->sMenuSubBlogUrl=DIR_WEB_ROOT.'/blog/'.$sBlogUrl;		
		/**
		 * Проверяем есть ли блог с таким УРЛ
		 */
		if (!($oBlog=$this->Blog_GetBlogByUrl($sBlogUrl))) {
			return parent::EventNotFound();
		}				
		/**
		 * Проверяем является ли текущий пользователь пользователем блога
		 */
		$bNeedJoin=true;
		if ($this->oUserCurrent) {
			if ($this->Blog_GetRelationBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId())) {
				$bNeedJoin=false;
			}
		}
		/**
		 * Передан ли номер страницы
		 */		
		if (preg_match("/^page(\d+)$/i",$sPage,$aMatch)) {						
			$iPage=$aMatch[1];
		} else {
			$iPage=1;
		}	
		/**
		 * Получаем список топиков
		 */	
		$iCount=0;			
		$aResult=$this->Topic_GetTopicsByBlogBad($oBlog,$iCount,$iPage,BLOG_TOPIC_PER_PAGE);	
		$aTopics=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */			
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/blog/'.$sBlogUrl.'/bad');						
		/**
		 * Получаем число новых топиков в текущем блоге
		 */		
		$this->iCountTopicsBlogNew=$this->Topic_GetCountTopicsByBlogNew($oBlog);
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);		
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('oBlog',$oBlog);
		$this->Viewer_Assign('bNeedJoin',$bNeedJoin);
		$this->Viewer_AddHtmlTitle($oBlog->getTitle());
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('blog');
	}
	
	/**
	 * Вывод новых топиков из коллективного блога
	 *
	 * @param unknown_type $sBlogUrl
	 * @param unknown_type $sPage
	 * @return unknown
	 */
	protected function EventShowBlogNew() {	
		$sBlogUrl=$this->sCurrentEvent;
		$sPage=$this->GetParam(1);	
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='new';
		$this->sMenuSubBlogUrl=DIR_WEB_ROOT.'/blog/'.$sBlogUrl;
		/**
		 * Проверяем есть ли блог с таким УРЛ
		 */	
		if (!($oBlog=$this->Blog_GetBlogByUrl($sBlogUrl))) {
			return parent::EventNotFound();
		}				
		/**
		 * Проверяем является ли текущий пользователь пользователем блога
		 */
		$bNeedJoin=true;
		if ($this->oUserCurrent) {
			if ($this->Blog_GetRelationBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId())) {
				$bNeedJoin=false;
			}
		}
		/**
		 * Передан ли номер страницы
		 */		
		if (preg_match("/^page(\d+)$/i",$sPage,$aMatch)) {	
					
			$iPage=$aMatch[1];
		} else {
			$iPage=1;
		}
		/**
		 * Получаем список топиков
		 */		
		$iCount=0;			
		$aResult=$this->Topic_GetTopicsByBlogNew($oBlog,$iCount,$iPage,BLOG_TOPIC_PER_PAGE);	
		$aTopics=$aResult['collection'];	
		/**
		 * Формируем постраничность
		 */				
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,BLOG_TOPIC_PER_PAGE,4,DIR_WEB_ROOT.'/blog/'.$sBlogUrl.'/bad');			
		/**
		 * Получаем число новых топиков в текущем блоге
		 */
		$this->iCountTopicsBlogNew=$this->Topic_GetCountTopicsByBlogNew($oBlog);
		/**
		 * Загружаем переменные в шаблон
		 */		
		$this->Viewer_Assign('aPaging',$aPaging);			
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('oBlog',$oBlog);
		$this->Viewer_Assign('bNeedJoin',$bNeedJoin);
		$this->Viewer_AddHtmlTitle($oBlog->getTitle());
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('blog');
	}
	
	/**
	 * Показать профайл блога
	 *
	 * @param unknown_type $sBlogUrl
	 * @param unknown_type $iPage
	 * @return unknown
	 */
	protected function EventShowBlogProfile() {	
		$sBlogUrl=$this->sCurrentEvent;
		$iPage=$this->GetParam(1);
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='';
		$this->sMenuItemSelect='';
		/**
		 * Проверяем есть ли блог с таким УРЛ
		 */
		if (!($oBlog=$this->Blog_GetBlogByUrl($sBlogUrl))) {
			return parent::EventNotFound();
		}
		/**
		 * Получаем список юзеров блога
		 */
		$aBlogUsers=$this->Blog_GetRelationBlogUsersByBlogId($oBlog->getId());
		$aBlogModerators=$this->Blog_GetBlogModeratorsByBlogId($oBlog->getId());
		$aBlogAdministrators=$this->Blog_GetBlogAdministratorsByBlogId($oBlog->getId());		
		/**
		 * Проверяем является ли текущий пользователь пользователем блога
		 */
		$bNeedJoin=true;
		if ($this->oUserCurrent) {
			if ($this->Blog_GetRelationBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId())) {
				$bNeedJoin=false;
			}
		}
		/**
		 * Загружаем переменные в шаблон
		 */			
		$this->Viewer_Assign('oBlog',$oBlog);
		$this->Viewer_Assign('aBlogUsers',$aBlogUsers);		
		$this->Viewer_Assign('aBlogModerators',$aBlogModerators);
		$this->Viewer_Assign('aBlogAdministrators',$aBlogAdministrators);
		$this->Viewer_Assign('bNeedJoin',$bNeedJoin);
		$this->Viewer_AddHtmlTitle($oBlog->getTitle());
		$this->Viewer_AddHtmlTitle('Профиль блога');
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('profile');
	}
	
	/**
	 * Обработка добавление комментария к топику
	 *
	 * @param unknown_type $oTopic
	 * @return unknown
	 */
	protected function SubmitComment($oTopic) {
		/**
		 * Если нажали кнопку "Отправить"
		 */
		if (isset($_REQUEST['submit_comment'])) {
			/**
			 * Проверяем авторизованл ли пользователь
			 */
			if (!$this->oUserCurrent) {
				$this->Message_AddErrorSingle('Для того чтобы что то написать, сначало нужно войти под своим аккаунтом.','Нет доступа');
				return Router::Action('error');
			}
			/**
			 * Проверяем разрешено ли постить комменты
			 */
			if (!$this->ACL_CanPostComment($this->oUserCurrent)) {
				$this->Message_AddError('Ваш рейтинг слишком мал для написания комментариев','Ошибка');
				return false;
			}
			/**
			 * Проверяем текст комментария
			 */
			$sText=$this->Text_Parser(getRequest('comment_text'));
			if (!func_check($sText,'text',2,10000)) {
				$this->Message_AddError('Текст комментария должен быть от 2 до 3000 символов и не содержать разного рода каку','Ошибка');
				return false;
			}
			/**
			 * Проверям на какой коммент отвечаем
			 */
			$sParentId=getRequest('reply',0);
			if (!func_check($sParentId,'id')) {
				$this->Message_AddError('Что то не так..','Ошибка');
				return false;
			}
			$oCommentParent=null;
			if ($sParentId!=0) {
				/**
				 * Проверяем существует ли комментарий на который отвечаем
				 */
				if (!($oCommentParent=$this->Comment_GetCommentById($sParentId))) {
					return false;
				}
				/**
				 * Проверяем из одного топика ли новый коммент и тот на который отвечаем
				 */
				if ($oCommentParent->getTopicId()!=$oTopic->getId()) {
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
			$oCommentNew=new CommentEntity_TopicComment();
			$oCommentNew->setTopicId($oTopic->getId());
			$oCommentNew->setUserId($this->oUserCurrent->getId());
			/**
			 * Парсим коммент на предмет ХТМЛ тегов
			 */
						
			$oCommentNew->setText($sText);
			$oCommentNew->setDate(date("Y-m-d H:i:s"));
			$oCommentNew->setUserIp(func_getIp());
			$oCommentNew->setPid($sParentId);
			/**
			 * Добавляем коммент
			 */
			if ($this->Comment_AddComment($oCommentNew)) {
				$sCommentText='';
				if (SYS_MAIL_INCLUDE_COMMENT_TEXT) {
					$sCommentText='Текст комментария: <i>'.$oCommentNew->getText().'</i><br>';
				}
				/**
				 * Отправка уведомления автору топика
				 */
				if ($oCommentNew->getUserId()!=$oTopic->getUserId()) {
					$oUserAuthor=$this->User_GetUserById($oTopic->getUserId());
					$this->Mail_SetAdress($oUserAuthor->getMail(),$oUserAuthor->getLogin());
					$this->Mail_SetSubject('К вашему топику оставили новый комментарий');
					$this->Mail_SetBody('
							Получен новый комментарий к вашему топику <b>«'.htmlspecialchars($oTopic->getTitle()).'»</b>, прочитать его можно перейдя по <a href="'.$oTopic->getUrl().'#comment'.$oCommentNew->getId().'">этой ссылке</a><br>							
							'.$sCommentText.'							
							<br>
							С уважением, администрация сайта <a href="'.DIR_WEB_ROOT.'">'.SITE_NAME.'</a>
						');
					$this->Mail_setHTML();
					$this->Mail_Send();
				}
				/**
				 * Отправляем уведомление тому на чем коммент ответили
				 */
				if ($oCommentParent and $oCommentParent->getUserId()!=$oTopic->getUserId() and $oCommentNew->getUserId()!=$oCommentParent->getUserId()) {
					$oUserAuthorComment=$this->User_GetUserById($oCommentParent->getUserId());
					$this->Mail_SetAdress($oUserAuthorComment->getMail(),$oUserAuthorComment->getLogin());
					$this->Mail_SetSubject('Вам ответили на ваш комментарий');
					$this->Mail_SetBody('
							Получен ответ на ваш комментарий в топике <b>«'.htmlspecialchars($oTopic->getTitle()).'»</b>, прочитать его можно перейдя по <a href="'.$oTopic->getUrl().'#comment'.$oCommentNew->getId().'">этой ссылке</a><br>							
							'.$sCommentText.'							
							<br>
							С уважением, администрация сайта <a href="'.DIR_WEB_ROOT.'">'.SITE_NAME.'</a>
						');
					$this->Mail_setHTML();
					$this->Mail_Send();
				}
				func_header_location(DIR_WEB_ROOT.'/blog/'.$oTopic->getId().'.html#comment'.$oCommentNew->getId());
			} else {
				$this->Message_AddErrorSingle('Возникли технические неполадки при добавлении комментария, пожалуйста повторите позже.','Внутреняя ошибка');
				return false;
			}
		}
	}
	
	
	/**
	 * Выполняется при завершении работы экшена
	 *
	 */
	public function EventShutdown() {		
		/**
		 * Загружаем в шаблон необходимые переменные
		 */
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);
		$this->Viewer_Assign('sMenuSubBlogUrl',$this->sMenuSubBlogUrl);
		$this->Viewer_Assign('iCountTopicsCollectiveNew',$this->iCountTopicsCollectiveNew);
		$this->Viewer_Assign('iCountTopicsPersonalNew',$this->iCountTopicsPersonalNew);
		$this->Viewer_Assign('iCountTopicsBlogNew',$this->iCountTopicsBlogNew);
		$this->Viewer_Assign('iCountTopicsNew',$this->iCountTopicsNew);
	}
}
?>