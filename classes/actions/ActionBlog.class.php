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
 * Экшен обработки URL'ов вида /blog/
 *
 * @package actions
 * @since 1.0
 */
class ActionBlog extends Action {
	/**
	 * Главное меню
	 *
	 * @var string
	 */
	protected $sMenuHeadItemSelect='blog';
	/**
	 * Какое меню активно
	 *
	 * @var string
	 */
	protected $sMenuItemSelect='blog';
	/**
	 * Какое подменю активно
	 *
	 * @var string
	 */
	protected $sMenuSubItemSelect='good';
	/**
	 * УРЛ блога который подставляется в меню
	 *
	 * @var string
	 */
	protected $sMenuSubBlogUrl;
	/**
	 * Текущий пользователь
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserCurrent=null;
	/**
	 * Число новых топиков в коллективных блогах
	 *
	 * @var int
	 */
	protected $iCountTopicsCollectiveNew=0;
	/**
	 * Число новых топиков в персональных блогах
	 *
	 * @var int
	 */
	protected $iCountTopicsPersonalNew=0;
	/**
	 * Число новых топиков в конкретном блоге
	 *
	 * @var int
	 */
	protected $iCountTopicsBlogNew=0;
	/**
	 * Число новых топиков
	 *
	 * @var int
	 */
	protected $iCountTopicsNew=0;
	/**
	 * Список URL с котрыми запрещено создавать блог
	 *
	 * @var array
	 */
	protected $aBadBlogUrl=array('new','good','bad','discussed','top','edit','add','admin','delete','invite','ajaxaddcomment','ajaxaddbloginvite','ajaxresponsecomment','ajaxrebloginvite','ajaxbloginfo','ajaxblogjoin');

	/**
	 * Инизиализация экшена
	 *
	 */
	public function Init() {
		/**
		 * Устанавливаем евент по дефолту, т.е. будем показывать хорошие топики из коллективных блогов
		 */
		$this->SetDefaultEvent('good');
		$this->sMenuSubBlogUrl=Router::GetPath('blog');
		/**
		 * Достаём текущего пользователя
		 */
		$this->oUserCurrent=$this->User_GetUserCurrent();
		/**
		 * Подсчитываем новые топики
		 */
		$this->iCountTopicsCollectiveNew=$this->Topic_GetCountTopicsCollectiveNew();
		$this->iCountTopicsPersonalNew=$this->Topic_GetCountTopicsPersonalNew();
		$this->iCountTopicsBlogNew=$this->iCountTopicsCollectiveNew;
		$this->iCountTopicsNew=$this->iCountTopicsCollectiveNew+$this->iCountTopicsPersonalNew;
		/**
		 * Загружаем в шаблон JS текстовки
		 */
		$this->Lang_AddLangJs(array(
								  'blog_join','blog_leave'
							  ));
	}
	/**
	 * Регистрируем евенты, по сути определяем УРЛы вида /blog/.../
	 *
	 */
	protected function RegisterEvent() {
		$this->AddEventPreg('/^good$/i','/^(page(\d+))?$/i',array('EventTopics','topics'));
		$this->AddEvent('good',array('EventTopics','topics'));
		$this->AddEventPreg('/^bad$/i','/^(page(\d+))?$/i',array('EventTopics','topics'));
		$this->AddEventPreg('/^new$/i','/^(page(\d+))?$/i',array('EventTopics','topics'));
		$this->AddEventPreg('/^newall$/i','/^(page(\d+))?$/i',array('EventTopics','topics'));
		$this->AddEventPreg('/^discussed$/i','/^(page(\d+))?$/i',array('EventTopics','topics'));
		$this->AddEventPreg('/^top$/i','/^(page(\d+))?$/i',array('EventTopics','topics'));

		$this->AddEvent('add','EventAddBlog');
		$this->AddEvent('edit','EventEditBlog');
		$this->AddEvent('delete','EventDeleteBlog');
		$this->AddEventPreg('/^admin$/i','/^\d+$/i','/^(page(\d+))?$/i','EventAdminBlog');
		$this->AddEvent('invite','EventInviteBlog');

		$this->AddEvent('ajaxaddcomment','AjaxAddComment');
		$this->AddEvent('ajaxresponsecomment','AjaxResponseComment');
		$this->AddEvent('ajaxaddbloginvite', 'AjaxAddBlogInvite');
		$this->AddEvent('ajaxrebloginvite', 'AjaxReBlogInvite');
		$this->AddEvent('ajaxremovebloginvite', 'AjaxRemoveBlogInvite');
		$this->AddEvent('ajaxbloginfo', 'AjaxBlogInfo');
		$this->AddEvent('ajaxblogjoin', 'AjaxBlogJoin');

		$this->AddEventPreg('/^(\d+)\.html$/i','/^$/i',array('EventShowTopic','topic'));
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^(\d+)\.html$/i',array('EventShowTopic','topic'));

		$this->AddEventPreg('/^[\w\-\_]+$/i','/^(page(\d+))?$/i',array('EventShowBlog','blog'));
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^bad$/i','/^(page(\d+))?$/i',array('EventShowBlog','blog'));
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^new$/i','/^(page(\d+))?$/i',array('EventShowBlog','blog'));
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^newall$/i','/^(page(\d+))?$/i',array('EventShowBlog','blog'));
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^discussed$/i','/^(page(\d+))?$/i',array('EventShowBlog','blog'));
		$this->AddEventPreg('/^[\w\-\_]+$/i','/^top$/i','/^(page(\d+))?$/i',array('EventShowBlog','blog'));

		$this->AddEventPreg('/^[\w\-\_]+$/i','/^users$/i','/^(page(\d+))?$/i','EventShowUsers');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Добавление нового блога
	 *
	 */
	protected function EventAddBlog() {
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($this->Lang_Get('blog_create'));
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='add';
		$this->sMenuItemSelect='blog';
		/**
		 * Проверяем авторизован ли пользователь
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Проверяем хватает ли рейтинга юзеру чтоб создать блог
		 */
		if (!$this->ACL_CanCreateBlog($this->oUserCurrent) and !$this->oUserCurrent->isAdministrator()) {
			$this->Message_AddErrorSingle($this->Lang_Get('blog_create_acl'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		$this->Hook_Run('blog_add_show');
		/**
		 * Запускаем проверку корректности ввода полей при добалении блога.
		 * Дополнительно проверяем, что был отправлен POST запрос.
		 */
		if (!$this->checkBlogFields()) {
			return false;
		}
		/**
		 * Если всё ок то пытаемся создать блог
		 */
		$oBlog=Engine::GetEntity('Blog');
		$oBlog->setOwnerId($this->oUserCurrent->getId());
		$oBlog->setTitle(strip_tags(getRequest('blog_title')));
		/**
		 * Парсим текст на предмет разных ХТМЛ тегов
		 */
		$sText=$this->Text_Parser(getRequest('blog_description'));
		$oBlog->setDescription($sText);
		$oBlog->setType(getRequest('blog_type'));
		$oBlog->setDateAdd(date("Y-m-d H:i:s"));
		$oBlog->setLimitRatingTopic(getRequest('blog_limit_rating_topic'));
		$oBlog->setUrl(getRequest('blog_url'));
		$oBlog->setAvatar(null);
		/**
		 * Загрузка аватара, делаем ресайзы
		 */
		if (isset($_FILES['avatar']) and is_uploaded_file($_FILES['avatar']['tmp_name'])) {
			if ($sPath=$this->Blog_UploadBlogAvatar($_FILES['avatar'],$oBlog)) {
				$oBlog->setAvatar($sPath);
			} else {
				$this->Message_AddError($this->Lang_Get('blog_create_avatar_error'),$this->Lang_Get('error'));
				return false;
			}
		}
		/**
		 * Создаём блог
		 */
		$this->Hook_Run('blog_add_before', array('oBlog'=>$oBlog));
		if ($this->Blog_AddBlog($oBlog)) {
			$this->Hook_Run('blog_add_after', array('oBlog'=>$oBlog));
			/**
			 * Получаем блог, это для получение полного пути блога, если он в будущем будет зависит от других сущностей(компании, юзер и т.п.)
			 */
			$oBlog->Blog_GetBlogById($oBlog->getId());

			/**
			 * Добавляем событие в ленту
			 */
			$this->Stream_write($oBlog->getOwnerId(), 'add_blog', $oBlog->getId());
			Router::Location($oBlog->getUrlFull());
		} else {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
	}
	/**
	 * Редактирование блога
	 *
	 */
	protected function EventEditBlog() {
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='';
		$this->sMenuItemSelect='profile';
		/**
		 * Проверяем передан ли в УРЛе номер блога
		 */
		$sBlogId=$this->GetParam(0);
		if (!$oBlog=$this->Blog_GetBlogById($sBlogId)) {
			return parent::EventNotFound();
		}
		/**
		 * Проверяем тип блога
		 */
		if ($oBlog->getType()=='personal') {
			return parent::EventNotFound();
		}
		/**
		 * Проверям авторизован ли пользователь
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Проверка на право редактировать блог
		 */
		if (!$this->ACL_IsAllowEditBlog($oBlog, $this->oUserCurrent)) {
			return parent::EventNotFound();
		}

		$this->Hook_Run('blog_edit_show',array('oBlog'=>$oBlog));
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($oBlog->getTitle());
		$this->Viewer_AddHtmlTitle($this->Lang_Get('blog_edit'));

		$this->Viewer_Assign('oBlogEdit',$oBlog);
		/**
		 * Устанавливаем шалон для вывода
		 */
		$this->SetTemplateAction('add');
		/**
		 * Если нажали кнопку "Сохранить"
		 */
		if (isPost('submit_blog_add')) {
			/**
			 * Запускаем проверку корректности ввода полей при редактировании блога
			 */
			if (!$this->checkBlogFields($oBlog)) {
				return false;
			}
			$oBlog->setTitle(strip_tags(getRequest('blog_title')));
			/**
			 * Парсим описание блога на предмет ХТМЛ тегов
			 */
			$sText=$this->Text_Parser(getRequest('blog_description'));
			$oBlog->setDescription($sText);
			/**
			 * Сбрасываем кеш, если поменяли тип блога
			 * Нужна доработка, т.к. в этом блоге могут быть топики других юзеров
			 */
			if ($oBlog->getType()!=getRequest('blog_type')) {
				$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("topic_update_user_{$oBlog->getOwnerId()}"));
			}
			$oBlog->setType(getRequest('blog_type'));
			$oBlog->setLimitRatingTopic(getRequest('blog_limit_rating_topic'));
			if ($this->oUserCurrent->isAdministrator()) {
				$oBlog->setUrl(getRequest('blog_url'));	// разрешаем смену URL блога только админу
			}
			/**
			 * Загрузка аватара, делаем ресайзы
			 */
			if (isset($_FILES['avatar']) and is_uploaded_file($_FILES['avatar']['tmp_name'])) {
				if ($sPath=$this->Blog_UploadBlogAvatar($_FILES['avatar'],$oBlog)) {
					$oBlog->setAvatar($sPath);
				} else {
					$this->Message_AddError($this->Lang_Get('blog_create_avatar_error'),$this->Lang_Get('error'));
					return false;
				}
			}
			/**
			 * Удалить аватар
			 */
			if (isset($_REQUEST['avatar_delete'])) {
				$this->Blog_DeleteBlogAvatar($oBlog);
				$oBlog->setAvatar(null);
			}
			/**
			 * Обновляем блог
			 */
			$this->Hook_Run('blog_edit_before', array('oBlog'=>$oBlog));
			if ($this->Blog_UpdateBlog($oBlog)) {
				$this->Hook_Run('blog_edit_after', array('oBlog'=>$oBlog));
				Router::Location($oBlog->getUrlFull());
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
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
	 * Управление пользователями блога
	 *
	 */
	protected function EventAdminBlog() {
		/**
		 * Меню
		 */
		$this->sMenuItemSelect='admin';
		$this->sMenuSubItemSelect='';
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
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Проверка на право управлением пользователями блога
		 */
		if (!$this->ACL_IsAllowAdminBlog($oBlog, $this->oUserCurrent)) {
			return parent::EventNotFound();
		}
		/**
		 * Обрабатываем сохранение формы
		 */
		if (isPost('submit_blog_admin')) {
			$this->Security_ValidateSendForm();

			$aUserRank=getRequest('user_rank',array());
			if (!is_array($aUserRank)) {
				$aUserRank=array();
			}
			foreach ($aUserRank as $sUserId => $sRank) {
				if (!($oBlogUser=$this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(),$sUserId))) {
					$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
					break;
				}
				/**
				 * Увеличиваем число читателей блога
				 */
				if (in_array($sRank,array('administrator','moderator','reader')) and $oBlogUser->getUserRole()==ModuleBlog::BLOG_USER_ROLE_BAN) {
					$oBlog->setCountUser($oBlog->getCountUser()+1);
				}

				switch ($sRank) {
					case 'administrator':
						$oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
						break;
					case 'moderator':
						$oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_MODERATOR);
						break;
					case 'reader':
						$oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
						break;
					case 'ban':
						if ($oBlogUser->getUserRole()!=ModuleBlog::BLOG_USER_ROLE_BAN) {
							$oBlog->setCountUser($oBlog->getCountUser()-1);
						}
						$oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_BAN);
						break;
					default:
						$oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_GUEST);
				}
				$this->Blog_UpdateRelationBlogUser($oBlogUser);
				$this->Message_AddNoticeSingle($this->Lang_Get('blog_admin_users_submit_ok'));
			}
			$this->Blog_UpdateBlog($oBlog);
		}
		/**
		 * Текущая страница
		 */
		$iPage= $this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;
		/**
		 * Получаем список подписчиков блога
		 */
		$aResult=$this->Blog_GetBlogUsersByBlogId(
			$oBlog->getId(),
			array(
				ModuleBlog::BLOG_USER_ROLE_BAN,
				ModuleBlog::BLOG_USER_ROLE_USER,
				ModuleBlog::BLOG_USER_ROLE_MODERATOR,
				ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR
			),$iPage,Config::Get('module.blog.users_per_page')
		);
		$aBlogUsers=$aResult['collection'];
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.blog.users_per_page'),Config::Get('pagination.pages.count'),Router::GetPath('blog')."admin/{$oBlog->getId()}");
		$this->Viewer_Assign('aPaging',$aPaging);
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($oBlog->getTitle());
		$this->Viewer_AddHtmlTitle($this->Lang_Get('blog_admin'));

		$this->Viewer_Assign('oBlogEdit',$oBlog);
		$this->Viewer_Assign('aBlogUsers',$aBlogUsers);
		/**
		 * Устанавливаем шалон для вывода
		 */
		$this->SetTemplateAction('admin');
		/**
		 * Если блог закрытый, получаем приглашенных
		 * и добавляем блок-форму для приглашения
		 */
		if($oBlog->getType()=='close') {
			$aBlogUsersInvited=$this->Blog_GetBlogUsersByBlogId($oBlog->getId(),ModuleBlog::BLOG_USER_ROLE_INVITE,null);
			$this->Viewer_Assign('aBlogUsersInvited',$aBlogUsersInvited['collection']);
			$this->Viewer_AddBlock('right','actions/ActionBlog/invited.tpl');
		}
	}
	/**
	 * Проверка полей блога
	 *
	 * @param ModuleBlog_EntityBlog|null $oBlog
	 * @return bool
	 */
	protected function checkBlogFields($oBlog=null) {
		/**
		 * Проверяем только если была отправлена форма с данными (методом POST)
		 */
		if (!isPost('submit_blog_add')) {
			$_REQUEST['blog_limit_rating_topic']=0;
			return false;
		}
		$this->Security_ValidateSendForm();

		$bOk=true;
		/**
		 * Проверяем есть ли название блога
		 */
		if (!func_check(getRequest('blog_title'),'text',2,200)) {
			$this->Message_AddError($this->Lang_Get('blog_create_title_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли уже блог с таким названием
		 */
		if ($oBlogExists=$this->Blog_GetBlogByTitle(getRequest('blog_title'))) {
			if (!$oBlog or $oBlog->getId()!=$oBlogExists->getId()) {
				$this->Message_AddError($this->Lang_Get('blog_create_title_error_unique'),$this->Lang_Get('error'));
				$bOk=false;
			}
		}
		/**
		 * Проверяем есть ли URL блога, с заменой всех пробельных символов на "_"
		 */
		if (!$oBlog or $this->oUserCurrent->isAdministrator()) {
			$blogUrl=preg_replace("/\s+/",'_',getRequest('blog_url'));
			$_REQUEST['blog_url']=$blogUrl;
			if (!func_check(getRequest('blog_url'),'login',2,50)) {
				$this->Message_AddError($this->Lang_Get('blog_create_url_error'),$this->Lang_Get('error'));
				$bOk=false;
			}
		}
		/**
		 * Проверяем на счет плохих УРЛов
		 */
		if (in_array(getRequest('blog_url'),$this->aBadBlogUrl)) {
			$this->Message_AddError($this->Lang_Get('blog_create_url_error_badword').' '.join(',',$this->aBadBlogUrl),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем есть ли уже блог с таким URL
		 */
		if ($oBlogExists=$this->Blog_GetBlogByUrl(getRequest('blog_url'))) {
			if (!$oBlog or $oBlog->getId()!=$oBlogExists->getId()) {
				$this->Message_AddError($this->Lang_Get('blog_create_url_error_unique'),$this->Lang_Get('error'));
				$bOk=false;
			}
		}
		/**
		 * Проверяем есть ли описание блога
		 */
		if (!func_check(getRequest('blog_description'),'text',10,3000)) {
			$this->Message_AddError($this->Lang_Get('blog_create_description_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Проверяем доступные типы блога для создания
		 */
		if (!in_array(getRequest('blog_type'),array('open','close'))) {
			$this->Message_AddError($this->Lang_Get('blog_create_type_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Преобразуем ограничение по рейтингу в число
		 */
		if (!func_check(getRequest('blog_limit_rating_topic'),'float')) {
			$this->Message_AddError($this->Lang_Get('blog_create_rating_error'),$this->Lang_Get('error'));
			$bOk=false;
		}
		/**
		 * Выполнение хуков
		 */
		$this->Hook_Run('check_blog_fields', array('bOk'=>&$bOk));
		return $bOk;
	}
	/**
	 * Показ всех топиков
	 *
	 */
	protected function EventTopics() {
		$sPeriod=1; // по дефолту 1 день
		if (in_array(getRequest('period'),array(1,7,30,'all'))) {
			$sPeriod=getRequest('period');
		}
		$sShowType=$this->sCurrentEvent;
		if (!in_array($sShowType,array('discussed','top'))) {
			$sPeriod='all';
		}
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect=$sShowType=='newall' ? 'new' : $sShowType;
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;
		if ($iPage==1 and !getRequest('period')) {
			$this->Viewer_SetHtmlCanonical(Router::GetPath('blog').$sShowType.'/');
		}
		/**
		 * Получаем список топиков
		 */
		$aResult=$this->Topic_GetTopicsCollective($iPage,Config::Get('module.topic.per_page'),$sShowType,$sPeriod=='all' ? null : $sPeriod*60*60*24);
		/**
		 * Если нет топиков за 1 день, то показываем за неделю (7)
		 */
		if (in_array($sShowType,array('discussed','top')) and !$aResult['count'] and $iPage==1 and !getRequest('period')) {
			$sPeriod=7;
			$aResult=$this->Topic_GetTopicsCollective($iPage,Config::Get('module.topic.per_page'),$sShowType,$sPeriod=='all' ? null : $sPeriod*60*60*24);
		}
		$aTopics=$aResult['collection'];
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topics_list_show',array('aTopics'=>$aTopics));
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('blog').$sShowType,in_array($sShowType,array('discussed','top')) ? array('period'=>$sPeriod) : array());
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('blog_show',array('sShowType'=>$sShowType));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('aPaging',$aPaging);
		if (in_array($sShowType,array('discussed','top'))) {
			$this->Viewer_Assign('sPeriodSelectCurrent',$sPeriod);
			$this->Viewer_Assign('sPeriodSelectRoot',Router::GetPath('blog').$sShowType.'/');
		}
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}
	/**
	 * Показ топика
	 *
	 */
	protected function EventShowTopic() {
		$sBlogUrl='';
		if ($this->GetParamEventMatch(0,1)) {
			// из коллективного блога
			$sBlogUrl=$this->sCurrentEvent;
			$iTopicId=$this->GetParamEventMatch(0,1);
			$this->sMenuItemSelect='blog';
		} else {
			// из персонального блога
			$iTopicId=$this->GetEventMatch(1);
			$this->sMenuItemSelect='log';
		}
		$this->sMenuSubItemSelect='';
		/**
		 * Проверяем есть ли такой топик
		 */
		if (!($oTopic=$this->Topic_GetTopicById($iTopicId))) {
			return parent::EventNotFound();
		}
		/**
		 * Проверяем права на просмотр топика
		 */
		if (!$oTopic->getPublish() and (!$this->oUserCurrent or ($this->oUserCurrent->getId()!=$oTopic->getUserId() and !$this->oUserCurrent->isAdministrator()))) {
			return parent::EventNotFound();
		}
		/**
		 * Определяем права на отображение записи из закрытого блога
		 */
		if($oTopic->getBlog()->getType()=='close'
			and (!$this->oUserCurrent
				|| !in_array(
					$oTopic->getBlog()->getId(),
					$this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent)
				)
			)
		) {
			$this->Message_AddErrorSingle($this->Lang_Get('blog_close_show'),$this->Lang_Get('not_access'));
			return Router::Action('error');
		}
		/**
		 * Если запросили топик из персонального блога то перенаправляем на страницу вывода коллективного топика
		 */
		if ($sBlogUrl!='' and $oTopic->getBlog()->getType()=='personal') {
			Router::Location($oTopic->getUrl());
		}
		/**
		 * Если запросили не персональный топик то перенаправляем на страницу для вывода коллективного топика
		 */
		if ($sBlogUrl=='' and $oTopic->getBlog()->getType()!='personal') {
			Router::Location($oTopic->getUrl());
		}
		/**
		 * Если номер топика правильный но УРЛ блога косяный то корректируем его и перенаправляем на нужный адрес
		 */
		if ($sBlogUrl!='' and $oTopic->getBlog()->getUrl()!=$sBlogUrl) {
			Router::Location($oTopic->getUrl());
		}
		/**
		 * Обрабатываем добавление коммента
		 */
		if (isset($_REQUEST['submit_comment'])) {
			$this->SubmitComment();
		}
		/**
		 * Достаём комменты к топику
		 */
		if (!Config::Get('module.comment.nested_page_reverse') and Config::Get('module.comment.use_nested') and Config::Get('module.comment.nested_per_page')) {
			$iPageDef=ceil($this->Comment_GetCountCommentsRootByTargetId($oTopic->getId(),'topic')/Config::Get('module.comment.nested_per_page'));
		} else {
			$iPageDef=1;
		}
		$iPage=getRequest('cmtpage',0) ? (int)getRequest('cmtpage',0) : $iPageDef;
		$aReturn=$this->Comment_GetCommentsByTargetId($oTopic->getId(),'topic',$iPage,Config::Get('module.comment.nested_per_page'));
		$iMaxIdComment=$aReturn['iMaxIdComment'];
		$aComments=$aReturn['comments'];
		/**
		 * Если используется постраничность для комментариев - формируем ее
		 */
		if (Config::Get('module.comment.use_nested') and Config::Get('module.comment.nested_per_page')) {
			$aPaging=$this->Viewer_MakePaging($aReturn['count'],$iPage,Config::Get('module.comment.nested_per_page'),Config::Get('pagination.pages.count'),'');
			if (!Config::Get('module.comment.nested_page_reverse') and $aPaging) {
				// переворачиваем страницы в обратном порядке
				$aPaging['aPagesLeft']=array_reverse($aPaging['aPagesLeft']);
				$aPaging['aPagesRight']=array_reverse($aPaging['aPagesRight']);
			}
			$this->Viewer_Assign('aPagingCmt',$aPaging);
		}
		/**
		 * Отмечаем дату прочтения топика
		 */
		if ($this->oUserCurrent) {
			$oTopicRead=Engine::GetEntity('Topic_TopicRead');
			$oTopicRead->setTopicId($oTopic->getId());
			$oTopicRead->setUserId($this->oUserCurrent->getId());
			$oTopicRead->setCommentCountLast($oTopic->getCountComment());
			$oTopicRead->setCommentIdLast($iMaxIdComment);
			$oTopicRead->setDateRead(date("Y-m-d H:i:s"));
			$this->Topic_SetTopicRead($oTopicRead);
		}
		/**
		 * Выставляем SEO данные
		 */
		$sTextSeo=strip_tags($oTopic->getText());
		$this->Viewer_SetHtmlDescription(func_text_words($sTextSeo, Config::Get('seo.description_words_count')));
		$this->Viewer_SetHtmlKeywords($oTopic->getTags());
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topic_show',array("oTopic"=>$oTopic));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('oTopic',$oTopic);
		$this->Viewer_Assign('aComments',$aComments);
		$this->Viewer_Assign('iMaxIdComment',$iMaxIdComment);
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($oTopic->getBlog()->getTitle());
		$this->Viewer_AddHtmlTitle($oTopic->getTitle());
		$this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss').'comments/'.$oTopic->getId().'/',$oTopic->getTitle());
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('topic');
	}
	/**
	 * Страница со списком читателей блога
	 *
	 */
	protected function EventShowUsers() {
		$sBlogUrl=$this->sCurrentEvent;
		/**
		 * Проверяем есть ли блог с таким УРЛ
		 */
		if (!($oBlog=$this->Blog_GetBlogByUrl($sBlogUrl))) {
			return parent::EventNotFound();
		}
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='';
		$this->sMenuSubBlogUrl=$oBlog->getUrlFull();
		/**
		 * Текущая страница
		 */
		$iPage= $this->GetParamEventMatch(1,2) ? $this->GetParamEventMatch(1,2) : 1;
		$aBlogUsersResult=$this->Blog_GetBlogUsersByBlogId($oBlog->getId(),ModuleBlog::BLOG_USER_ROLE_USER,$iPage,Config::Get('module.blog.users_per_page'));
		$aBlogUsers=$aBlogUsersResult['collection'];
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aBlogUsersResult['count'],$iPage,Config::Get('module.blog.users_per_page'),Config::Get('pagination.pages.count'),$oBlog->getUrlFull().'users');
		$this->Viewer_Assign('aPaging',$aPaging);
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('blog_collective_show_users',array('oBlog'=>$oBlog));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aBlogUsers',$aBlogUsers);
		$this->Viewer_Assign('iCountBlogUsers',$aBlogUsersResult['count']);
		$this->Viewer_Assign('oBlog',$oBlog);
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($oBlog->getTitle());
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('users');
	}
	/**
	 * Вывод топиков из определенного блога
	 *
	 */
	protected function EventShowBlog() {
		$sPeriod=1; // по дефолту 1 день
		if (in_array(getRequest('period'),array(1,7,30,'all'))) {
			$sPeriod=getRequest('period');
		}
		$sBlogUrl=$this->sCurrentEvent;
		$sShowType=in_array($this->GetParamEventMatch(0,0),array('bad','new','newall','discussed','top')) ? $this->GetParamEventMatch(0,0) : 'good';
		if (!in_array($sShowType,array('discussed','top'))) {
			$sPeriod='all';
		}
		/**
		 * Проверяем есть ли блог с таким УРЛ
		 */
		if (!($oBlog=$this->Blog_GetBlogByUrl($sBlogUrl))) {
			return parent::EventNotFound();
		}
		/**
		 * Определяем права на отображение закрытого блога
		 */
		if($oBlog->getType()=='close'
			and (!$this->oUserCurrent
				or !in_array(
					$oBlog->getId(),
					$this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent)
				)
			)
		) {
			$bCloseBlog=true;
		} else {
			$bCloseBlog=false;
		}
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect=$sShowType=='newall' ? 'new' : $sShowType;
		$this->sMenuSubBlogUrl=$oBlog->getUrlFull();
		/**
		 * Передан ли номер страницы
		 */
		$iPage= $this->GetParamEventMatch(($sShowType=='good')?0:1,2) ? $this->GetParamEventMatch(($sShowType=='good')?0:1,2) : 1;
		if ($iPage==1 and !getRequest('period') and in_array($sShowType,array('discussed','top'))) {
			$this->Viewer_SetHtmlCanonical($oBlog->getUrlFull().$sShowType.'/');
		}

		if (!$bCloseBlog) {
			/**
			 * Получаем список топиков
			 */
			$aResult=$this->Topic_GetTopicsByBlog($oBlog,$iPage,Config::Get('module.topic.per_page'),$sShowType,$sPeriod=='all' ? null : $sPeriod*60*60*24);
			/**
			 * Если нет топиков за 1 день, то показываем за неделю (7)
			 */
			if (in_array($sShowType,array('discussed','top')) and !$aResult['count'] and $iPage==1 and !getRequest('period')) {
				$sPeriod=7;
				$aResult=$this->Topic_GetTopicsByBlog($oBlog,$iPage,Config::Get('module.topic.per_page'),$sShowType,$sPeriod=='all' ? null : $sPeriod*60*60*24);
			}
			$aTopics=$aResult['collection'];
			/**
			 * Формируем постраничность
			 */
			$aPaging=($sShowType=='good')
				? $this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),rtrim($oBlog->getUrlFull(),'/'))
				: $this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),$oBlog->getUrlFull().$sShowType,array('period'=>$sPeriod));
			/**
			 * Получаем число новых топиков в текущем блоге
			 */
			$this->iCountTopicsBlogNew=$this->Topic_GetCountTopicsByBlogNew($oBlog);

			$this->Viewer_Assign('aPaging',$aPaging);
			$this->Viewer_Assign('aTopics',$aTopics);
			if (in_array($sShowType,array('discussed','top'))) {
				$this->Viewer_Assign('sPeriodSelectCurrent',$sPeriod);
				$this->Viewer_Assign('sPeriodSelectRoot',$oBlog->getUrlFull().$sShowType.'/');
			}
		}
		/**
		 * Выставляем SEO данные
		 */
		$sTextSeo=strip_tags($oBlog->getDescription());
		$this->Viewer_SetHtmlDescription(func_text_words($sTextSeo, Config::Get('seo.description_words_count')));
		/**
		 * Получаем список юзеров блога
		 */
		$aBlogUsersResult=$this->Blog_GetBlogUsersByBlogId($oBlog->getId(),ModuleBlog::BLOG_USER_ROLE_USER,1,Config::Get('module.blog.users_per_page'));
		$aBlogUsers=$aBlogUsersResult['collection'];
		$aBlogModeratorsResult=$this->Blog_GetBlogUsersByBlogId($oBlog->getId(),ModuleBlog::BLOG_USER_ROLE_MODERATOR);
		$aBlogModerators=$aBlogModeratorsResult['collection'];
		$aBlogAdministratorsResult=$this->Blog_GetBlogUsersByBlogId($oBlog->getId(),ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
		$aBlogAdministrators=$aBlogAdministratorsResult['collection'];
		/**
		 * Для админов проекта получаем список блогов и передаем их во вьювер
		 */
		if($this->oUserCurrent and $this->oUserCurrent->isAdministrator()) {
			$aBlogs = $this->Blog_GetBlogs();
			unset($aBlogs[$oBlog->getId()]);

			$this->Viewer_Assign('aBlogs',$aBlogs);
		}
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('blog_collective_show',array('oBlog'=>$oBlog,'sShowType'=>$sShowType));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aBlogUsers',$aBlogUsers);
		$this->Viewer_Assign('aBlogModerators',$aBlogModerators);
		$this->Viewer_Assign('aBlogAdministrators',$aBlogAdministrators);
		$this->Viewer_Assign('iCountBlogUsers',$aBlogUsersResult['count']);
		$this->Viewer_Assign('iCountBlogModerators',$aBlogModeratorsResult['count']);
		$this->Viewer_Assign('iCountBlogAdministrators',$aBlogAdministratorsResult['count']+1);
		$this->Viewer_Assign('oBlog',$oBlog);
		$this->Viewer_Assign('bCloseBlog',$bCloseBlog);
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($oBlog->getTitle());
		$this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss').'blog/'.$oBlog->getUrl().'/',$oBlog->getTitle());
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('blog');
	}
	/**
	 * Обработка добавление комментария к топику через ajax
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
	 * Обработка добавление комментария к топику
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
		 * Проверяем топик
		 */
		if (!($oTopic=$this->Topic_GetTopicById(getRequest('cmt_target_id')))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Возможность постить коммент в топик в черновиках
		 */
		if (!$oTopic->getPublish() and $this->oUserCurrent->getId()!=$oTopic->getUserId() and !$this->oUserCurrent->isAdministrator()) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Проверяем разрешено ли постить комменты
		 */
		if (!$this->ACL_CanPostComment($this->oUserCurrent) and !$this->oUserCurrent->isAdministrator()) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_comment_acl'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Проверяем разрешено ли постить комменты по времени
		 */
		if (!$this->ACL_CanPostCommentTime($this->oUserCurrent) and !$this->oUserCurrent->isAdministrator()) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_comment_limit'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Проверяем запрет на добавления коммента автором топика
		 */
		if ($oTopic->getForbidComment()) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_comment_notallow'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Проверяем текст комментария
		 */
		$sText=$this->Text_Parser(getRequest('comment_text'));
		if (!func_check($sText,'text',2,10000)) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_comment_add_text_error'),$this->Lang_Get('error'));
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
			if ($oCommentParent->getTargetId()!=$oTopic->getId()) {
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
		if ($this->Comment_GetCommentUnique($oTopic->getId(),'topic',$this->oUserCurrent->getId(),$sParentId,md5($sText))) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_comment_spam'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Создаём коммент
		 */
		$oCommentNew=Engine::GetEntity('Comment');
		$oCommentNew->setTargetId($oTopic->getId());
		$oCommentNew->setTargetType('topic');
		$oCommentNew->setTargetParentId($oTopic->getBlog()->getId());
		$oCommentNew->setUserId($this->oUserCurrent->getId());
		$oCommentNew->setText($sText);
		$oCommentNew->setDate(date("Y-m-d H:i:s"));
		$oCommentNew->setUserIp(func_getIp());
		$oCommentNew->setPid($sParentId);
		$oCommentNew->setTextHash(md5($sText));
		$oCommentNew->setPublish($oTopic->getPublish());
		/**
		 * Добавляем коммент
		 */
		$this->Hook_Run('comment_add_before', array('oCommentNew'=>$oCommentNew,'oCommentParent'=>$oCommentParent,'oTopic'=>$oTopic));
		if ($this->Comment_AddComment($oCommentNew)) {
			$this->Hook_Run('comment_add_after', array('oCommentNew'=>$oCommentNew,'oCommentParent'=>$oCommentParent,'oTopic'=>$oTopic));

			$this->Viewer_AssignAjax('sCommentId',$oCommentNew->getId());
			if ($oTopic->getPublish()) {
				/**
				 * Добавляем коммент в прямой эфир если топик не в черновиках
				 */
				$oCommentOnline=Engine::GetEntity('Comment_CommentOnline');
				$oCommentOnline->setTargetId($oCommentNew->getTargetId());
				$oCommentOnline->setTargetType($oCommentNew->getTargetType());
				$oCommentOnline->setTargetParentId($oCommentNew->getTargetParentId());
				$oCommentOnline->setCommentId($oCommentNew->getId());

				$this->Comment_AddCommentOnline($oCommentOnline);
			}
			/**
			 * Сохраняем дату последнего коммента для юзера
			 */
			$this->oUserCurrent->setDateCommentLast(date("Y-m-d H:i:s"));
			$this->User_Update($this->oUserCurrent);

			/**
			 * Список емайлов на которые не нужно отправлять уведомление
			 */
			$aExcludeMail=array($this->oUserCurrent->getMail());
			/**
			 * Отправляем уведомление тому на чей коммент ответили
			 */
			if ($oCommentParent and $oCommentParent->getUserId()!=$oTopic->getUserId() and $oCommentNew->getUserId()!=$oCommentParent->getUserId()) {
				$oUserAuthorComment=$oCommentParent->getUser();
				$aExcludeMail[]=$oUserAuthorComment->getMail();
				$this->Notify_SendCommentReplyToAuthorParentComment($oUserAuthorComment,$oTopic,$oCommentNew,$this->oUserCurrent);
			}
			/**
			 * Отправка уведомления автору топика
			 */
			$this->Subscribe_Send('topic_new_comment',$oTopic->getId(),'notify.comment_new.tpl',$this->Lang_Get('notify_subject_comment_new'),array(
				'oTopic' => $oTopic,
				'oComment' => $oCommentNew,
				'oUserComment' => $this->oUserCurrent,
			),$aExcludeMail);
			/**
			 * Добавляем событие в ленту
			 */
			$this->Stream_write($oCommentNew->getUserId(), 'add_comment', $oCommentNew->getId(), $oTopic->getPublish() && $oTopic->getBlog()->getType()!='close');
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
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
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Топик существует?
		 */
		$idTopic=getRequest('idTarget',null,'post');
		if (!($oTopic=$this->Topic_GetTopicById($idTopic))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		$idCommentLast=getRequest('idCommentLast',null,'post');
		$selfIdComment=getRequest('selfIdComment',null,'post');
		$aComments=array();
		/**
		 * Если используется постраничность, возвращаем только добавленный комментарий
		 */
		if (getRequest('bUsePaging',null,'post') and $selfIdComment) {
			if ($oComment=$this->Comment_GetCommentById($selfIdComment) and $oComment->getTargetId()==$oTopic->getId() and $oComment->getTargetType()=='topic') {
				$oViewerLocal=$this->Viewer_GetLocalViewer();
				$oViewerLocal->Assign('oUserCurrent',$this->oUserCurrent);
				$oViewerLocal->Assign('bOneComment',true);

				$oViewerLocal->Assign('oComment',$oComment);
				$sText=$oViewerLocal->Fetch($this->Comment_GetTemplateCommentByTarget($oTopic->getId(),'topic'));
				$aCmt=array();
				$aCmt[]=array(
					'html' => $sText,
					'obj'  => $oComment,
				);
			} else {
				$aCmt=array();
			}
			$aReturn['comments']=$aCmt;
			$aReturn['iMaxIdComment']=$selfIdComment;
		} else {
			$aReturn=$this->Comment_GetCommentsNewByTargetId($oTopic->getId(),'topic',$idCommentLast);
		}
		$iMaxIdComment=$aReturn['iMaxIdComment'];

		$oTopicRead=Engine::GetEntity('Topic_TopicRead');
		$oTopicRead->setTopicId($oTopic->getId());
		$oTopicRead->setUserId($this->oUserCurrent->getId());
		$oTopicRead->setCommentCountLast($oTopic->getCountComment());
		$oTopicRead->setCommentIdLast($iMaxIdComment);
		$oTopicRead->setDateRead(date("Y-m-d H:i:s"));
		$this->Topic_SetTopicRead($oTopicRead);

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

		$this->Viewer_AssignAjax('iMaxIdComment',$iMaxIdComment);
		$this->Viewer_AssignAjax('aComments',$aComments);
	}
	/**
	 * Обработка ajax запроса на отправку
	 * пользователям приглашения вступить в закрытый блог
	 */
	protected function AjaxAddBlogInvite() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$sUsers=getRequest('users',null,'post');
		$sBlogId=getRequest('idBlog',null,'post');
		/**
		 * Если пользователь не авторизирован, возвращаем ошибку
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$this->oUserCurrent=$this->User_GetUserCurrent();
		/**
		 * Проверяем существование блога
		 */
		if(!$oBlog=$this->Blog_GetBlogById($sBlogId)) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Проверяем, имеет ли право текущий пользователь добавлять invite в blog
		 */
		$oBlogUser=$this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId());
		$bIsAdministratorBlog=$oBlogUser ? $oBlogUser->getIsAdministrator() : false;
		if ($oBlog->getOwnerId()!=$this->oUserCurrent->getId()  and !$this->oUserCurrent->isAdministrator() and !$bIsAdministratorBlog) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Получаем список пользователей блога (любого статуса)
		 * Это полный АХТУНГ - исправить!
		 */
		$aBlogUsersResult = $this->Blog_GetBlogUsersByBlogId(
			$oBlog->getId(),
			array(
				ModuleBlog::BLOG_USER_ROLE_BAN,
				ModuleBlog::BLOG_USER_ROLE_REJECT,
				ModuleBlog::BLOG_USER_ROLE_INVITE,
				ModuleBlog::BLOG_USER_ROLE_USER,
				ModuleBlog::BLOG_USER_ROLE_MODERATOR,
				ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR
			),null // пока костылем
		);
		$aBlogUsers=$aBlogUsersResult['collection'];
		$aUsers=explode(',',$sUsers);

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
			 * Если пользователь пытается добавить инвайт
			 * самому себе, возвращаем ошибку
			 */
			if(strtolower($sUser)==strtolower($this->oUserCurrent->getLogin())) {
				$aResult[]=array(
					'bStateError'=>true,
					'sMsgTitle'=>$this->Lang_Get('error'),
					'sMsg'=>$this->Lang_Get('blog_user_invite_add_self')
				);
				continue;
			}
			/**
			 * Если пользователь не найден или неактивен,
			 * возвращаем ошибку
			 */
			if (!$oUser=$this->User_GetUserByLogin($sUser) or $oUser->getActivate()!=1) {
				$aResult[]=array(
					'bStateError'=>true,
					'sMsgTitle'=>$this->Lang_Get('error'),
					'sMsg'=>$this->Lang_Get('user_not_found',array('login'=>htmlspecialchars($sUser))),
					'sUserLogin'=>htmlspecialchars($sUser)
				);
				continue;
			}

			if(!isset($aBlogUsers[$oUser->getId()])) {
				/**
				 * Создаем нового блог-пользователя со статусом INVITED
				 */
				$oBlogUserNew=Engine::GetEntity('Blog_BlogUser');
				$oBlogUserNew->setBlogId($oBlog->getId());
				$oBlogUserNew->setUserId($oUser->getId());
				$oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_INVITE);

				if($this->Blog_AddRelationBlogUser($oBlogUserNew)) {
					$aResult[]=array(
						'bStateError'=>false,
						'sMsgTitle'=>$this->Lang_Get('attention'),
						'sMsg'=>$this->Lang_Get('blog_user_invite_add_ok',array('login'=>htmlspecialchars($sUser))),
						'sUserLogin'=>htmlspecialchars($sUser),
						'sUserWebPath'=>$oUser->getUserWebPath(),
						'sUserAvatar48'=>$oUser->getProfileAvatarPath(48)
					);
					$this->SendBlogInvite($oBlog,$oUser);
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
				 * Попытка добавить приглашение уже существующему пользователю,
				 * возвращаем ошибку (сначала определяя ее точный текст)
				 */
				switch (true) {
					case ($aBlogUsers[$oUser->getId()]->getUserRole()==ModuleBlog::BLOG_USER_ROLE_INVITE):
						$sErrorMessage=$this->Lang_Get('blog_user_already_invited',array('login'=>htmlspecialchars($sUser)));
						break;
					case ($aBlogUsers[$oUser->getId()]->getUserRole()>ModuleBlog::BLOG_USER_ROLE_GUEST):
						$sErrorMessage=$this->Lang_Get('blog_user_already_exists',array('login'=>htmlspecialchars($sUser)));
						break;
					case ($aBlogUsers[$oUser->getId()]->getUserRole()==ModuleBlog::BLOG_USER_ROLE_REJECT):
						$sErrorMessage=$this->Lang_Get('blog_user_already_reject',array('login'=>htmlspecialchars($sUser)));
						break;
					default:
						$sErrorMessage=$this->Lang_Get('system_error');
				}
				$aResult[]=array(
					'bStateError'=>true,
					'sMsgTitle'=>$this->Lang_Get('error'),
					'sMsg'=>$sErrorMessage,
					'sUserLogin'=>htmlspecialchars($sUser)
				);
				continue;
			}
		}
		/**
		 * Передаем во вьевер массив с результатами обработки по каждому пользователю
		 */
		$this->Viewer_AssignAjax('aUsers',$aResult);
	}
	/**
	 * Обработка ajax запроса на отправку
	 * повторного приглашения вступить в закрытый блог
	 */
	protected function AjaxReBlogInvite() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$sUserId=getRequest('idUser',null,'post');
		$sBlogId=getRequest('idBlog',null,'post');
		/**
		 * Если пользователь не авторизирован, возвращаем ошибку
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$this->oUserCurrent=$this->User_GetUserCurrent();
		/**
		 * Проверяем существование блога
		 */
		if(!$oBlog=$this->Blog_GetBlogById($sBlogId)) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Пользователь существует и активен?
		 */
		if (!$oUser=$this->User_GetUserById($sUserId) or $oUser->getActivate()!=1) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Проверяем, имеет ли право текущий пользователь добавлять invite в blog
		 */
		$oBlogUser=$this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId());
		$bIsAdministratorBlog=$oBlogUser ? $oBlogUser->getIsAdministrator() : false;
		if ($oBlog->getOwnerId()!=$this->oUserCurrent->getId()  and !$this->oUserCurrent->isAdministrator() and !$bIsAdministratorBlog) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		$oBlogUser=$this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(),$oUser->getId());
		if ($oBlogUser->getUserRole()==ModuleBlog::BLOG_USER_ROLE_INVITE) {
			$this->SendBlogInvite($oBlog,$oUser);
			$this->Message_AddNoticeSingle($this->Lang_Get('blog_user_invite_add_ok',array('login'=>$oUser->getLogin())),$this->Lang_Get('attention'));
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
	}
	/**
	 * Обработка ajax запроса на удаление вступить в закрытый блог
	 */
	protected function AjaxRemoveBlogInvite() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$sUserId=getRequest('idUser',null,'post');
		$sBlogId=getRequest('idBlog',null,'post');
		/**
		 * Если пользователь не авторизирован, возвращаем ошибку
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$this->oUserCurrent=$this->User_GetUserCurrent();
		/**
		 * Проверяем существование блога
		 */
		if(!$oBlog=$this->Blog_GetBlogById($sBlogId)) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Пользователь существует и активен?
		 */
		if (!$oUser=$this->User_GetUserById($sUserId) or $oUser->getActivate()!=1) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Проверяем, имеет ли право текущий пользователь добавлять invite в blog
		 */
		$oBlogUser=$this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId());
		$bIsAdministratorBlog=$oBlogUser ? $oBlogUser->getIsAdministrator() : false;
		if ($oBlog->getOwnerId()!=$this->oUserCurrent->getId()  and !$this->oUserCurrent->isAdministrator() and !$bIsAdministratorBlog) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		$oBlogUser=$this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(),$oUser->getId());
		if ($oBlogUser->getUserRole()==ModuleBlog::BLOG_USER_ROLE_INVITE) {
			/**
			 * Удаляем связь/приглашение
			 */
			$this->Blog_DeleteRelationBlogUser($oBlogUser);
			$this->Message_AddNoticeSingle($this->Lang_Get('blog_user_invite_remove_ok',array('login'=>$oUser->getLogin())),$this->Lang_Get('attention'));
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
	}
	/**
	 * Выполняет отправку приглашения в блог
	 * (по внутренней почте и на email)
	 *
	 * @param ModuleBlog_EntityBlog $oBlog
	 * @param ModuleUser_EntityUser $oUser
	 */
	protected function SendBlogInvite($oBlog,$oUser) {
		$sTitle=$this->Lang_Get(
			'blog_user_invite_title',
			array(
				'blog_title'=>$oBlog->getTitle()
			)
		);

		require_once Config::Get('path.root.engine').'/lib/external/XXTEA/encrypt.php';
		/**
		 * Формируем код подтверждения в URL
		 */
		$sCode=$oBlog->getId().'_'.$oUser->getId();
		$sCode=rawurlencode(base64_encode(xxtea_encrypt($sCode, Config::Get('module.blog.encrypt'))));

		$aPath=array(
			'accept'=>Router::GetPath('blog').'invite/accept/?code='.$sCode,
			'reject'=>Router::GetPath('blog').'invite/reject/?code='.$sCode
		);

		$sText=$this->Lang_Get(
			'blog_user_invite_text',
			array(
				'login'=>$this->oUserCurrent->getLogin(),
				'accept_path'=>$aPath['accept'],
				'reject_path'=>$aPath['reject'],
				'blog_title'=>$oBlog->getTitle()
			)
		);
		$oTalk=$this->Talk_SendTalk($sTitle,$sText,$this->oUserCurrent,array($oUser),false,false);
		/**
		 * Отправляем пользователю заявку
		 */
		$this->Notify_SendBlogUserInvite(
			$oUser,$this->oUserCurrent,$oBlog,
			Router::GetPath('talk').'read/'.$oTalk->getId().'/'
		);
		/**
		 * Удаляем отправляющего юзера из переписки
		 */
		$this->Talk_DeleteTalkUserByArray($oTalk->getId(),$this->oUserCurrent->getId());
	}
	/**
	 * Обработка отправленого пользователю приглашения вступить в блог
	 */
	protected function EventInviteBlog() {
		require_once Config::Get('path.root.engine').'/lib/external/XXTEA/encrypt.php';
		/**
		 * Получаем код подтверждения из ревеста и дешефруем его
		 */
		$sCode=xxtea_decrypt(base64_decode(rawurldecode(getRequest('code'))), Config::Get('module.blog.encrypt'));
		if (!$sCode) {
			return $this->EventNotFound();
		}
		list($sBlogId,$sUserId)=explode('_',$sCode,2);

		$sAction=$this->GetParam(0);
		/**
		 * Получаем текущего пользователя
		 */
		if(!$this->User_IsAuthorization()) {
			return $this->EventNotFound();
		}
		$this->oUserCurrent = $this->User_GetUserCurrent();
		/**
		 * Если приглашенный пользователь не является авторизированным
		 */
		if($this->oUserCurrent->getId()!=$sUserId) {
			return $this->EventNotFound();
		}
		/**
		 * Получаем указанный блог
		 */
		if((!$oBlog=$this->Blog_GetBlogById($sBlogId)) || $oBlog->getType()!='close') {
			return $this->EventNotFound();
		}
		/**
		 * Получаем связь "блог-пользователь" и проверяем,
		 * чтобы ее тип был INVITE или REJECT
		 */
		if(!$oBlogUser=$this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId())) {
			return $this->EventNotFound();
		}
		if($oBlogUser->getUserRole()>ModuleBlog::BLOG_USER_ROLE_GUEST) {
			$sMessage=$this->Lang_Get('blog_user_invite_already_done');
			$this->Message_AddError($sMessage,$this->Lang_Get('error'),true);
			Router::Location(Router::GetPath('talk'));
			return ;
		}
		if(!in_array($oBlogUser->getUserRole(),array(ModuleBlog::BLOG_USER_ROLE_INVITE,ModuleBlog::BLOG_USER_ROLE_REJECT))) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'),true);
			Router::Location(Router::GetPath('talk'));
			return ;
		}
		/**
		 * Обновляем роль пользователя до читателя
		 */
		$oBlogUser->setUserRole(($sAction=='accept')?ModuleBlog::BLOG_USER_ROLE_USER:ModuleBlog::BLOG_USER_ROLE_REJECT);
		if(!$this->Blog_UpdateRelationBlogUser($oBlogUser)) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'),true);
			Router::Location(Router::GetPath('talk'));
			return ;
		}
		if ($sAction=='accept') {
			/**
			 * Увеличиваем число читателей блога
			 */
			$oBlog->setCountUser($oBlog->getCountUser()+1);
			$this->Blog_UpdateBlog($oBlog);
			$sMessage=$this->Lang_Get('blog_user_invite_accept');
			/**
			 * Добавляем событие в ленту
			 */
			$this->Stream_write($oBlogUser->getUserId(), 'join_blog', $oBlog->getId());
		} else {
			$sMessage=$this->Lang_Get('blog_user_invite_reject');
		}
		$this->Message_AddNotice($sMessage,$this->Lang_Get('attention'),true);
		/**
		 * Перенаправляем на страницу личной почты
		 */
		Router::Location(Router::GetPath('talk'));
	}
	/**
	 * Удаление блога
	 *
	 */
	protected function EventDeleteBlog() {
		$this->Security_ValidateSendForm();
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
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * проверяем есть ли право на удаление топика
		 */
		if (!$bAccess=$this->ACL_IsAllowDeleteBlog($oBlog,$this->oUserCurrent)) {
			return parent::EventNotFound();
		}
		$aTopics =  $this->Topic_GetTopicsByBlogId($sBlogId);
		switch ($bAccess) {
			case ModuleACL::CAN_DELETE_BLOG_EMPTY_ONLY :
				if(is_array($aTopics) and count($aTopics)) {
					$this->Message_AddErrorSingle($this->Lang_Get('blog_admin_delete_not_empty'),$this->Lang_Get('error'),true);
					Router::Location($oBlog->getUrlFull());
				}
				break;
			case ModuleACL::CAN_DELETE_BLOG_WITH_TOPICS :
				/**
				 * Если указан идентификатор блога для перемещения,
				 * то делаем попытку переместить топики.
				 *
				 * (-1) - выбран пункт меню "удалить топики".
				 */
				if($sBlogIdNew=getRequest('topic_move_to') and ($sBlogIdNew!=-1) and is_array($aTopics) and count($aTopics)) {
					if(!$oBlogNew = $this->Blog_GetBlogById($sBlogIdNew)){
						$this->Message_AddErrorSingle($this->Lang_Get('blog_admin_delete_move_error'),$this->Lang_Get('error'),true);
						Router::Location($oBlog->getUrlFull());
					}
					/**
					 * Если выбранный блог является персональным, возвращаем ошибку
					 */
					if($oBlogNew->getType()=='personal') {
						$this->Message_AddErrorSingle($this->Lang_Get('blog_admin_delete_move_personal'),$this->Lang_Get('error'),true);
						Router::Location($oBlog->getUrlFull());
					}
					/**
					 * Перемещаем топики
					 */
					$this->Topic_MoveTopics($sBlogId,$sBlogIdNew);
				}
				break;
			default:
				return parent::EventNotFound();
		}
		/**
		 * Удаляяем блог и перенаправляем пользователя к списку блогов
		 */
		$this->Hook_Run('blog_delete_before', array('sBlogId'=>$sBlogId));
		if($this->Blog_DeleteBlog($sBlogId)) {
			$this->Hook_Run('blog_delete_after', array('sBlogId'=>$sBlogId));
			$this->Message_AddNoticeSingle($this->Lang_Get('blog_admin_delete_success'),$this->Lang_Get('attention'),true);
			Router::Location(Router::GetPath('blogs'));
		} else {
			Router::Location($oBlog->getUrlFull());
		}
	}
	/**
	 * Получение описания блога
	 *
	 */
	protected function AjaxBlogInfo() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$sBlogId=getRequest('idBlog',null,'post');
		/**
		 * Определяем тип блога и получаем его
		 */
		if ($sBlogId==0) {
			if ($this->oUserCurrent) {
				$oBlog=$this->Blog_GetPersonalBlogByUserId($this->oUserCurrent->getId());
			}
		} else {
			$oBlog=$this->Blog_GetBlogById($sBlogId);
		}
		/**
		 * если блог найден, то возвращаем описание
		 */
		if ($oBlog) {
			$sText=$oBlog->getDescription();
			$this->Viewer_AssignAjax('sText',$sText);
		}
	}
	/**
	 * Подключение/отключение к блогу
	 *
	 */
	protected function AjaxBlogJoin() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Блог существует?
		 */
		$idBlog=getRequest('idBlog',null,'post');
		if (!($oBlog=$this->Blog_GetBlogById($idBlog))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Проверяем тип блога
		 */
		if (!in_array($oBlog->getType(),array('open','close'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('blog_join_error_invite'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Получаем текущий статус пользователя в блоге
		 */
		$oBlogUser=$this->Blog_GetBlogUserByBlogIdAndUserId($oBlog->getId(),$this->oUserCurrent->getId());
		if (!$oBlogUser || ($oBlogUser->getUserRole()<ModuleBlog::BLOG_USER_ROLE_GUEST && $oBlog->getType()=='close')) {
			if ($oBlog->getOwnerId()!=$this->oUserCurrent->getId()) {
				/**
				 * Присоединяем юзера к блогу
				 */
				$bResult=false;
				if($oBlogUser) {
					$oBlogUser->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
					$bResult = $this->Blog_UpdateRelationBlogUser($oBlogUser);
				} elseif($oBlog->getType()=='open') {
					$oBlogUserNew=Engine::GetEntity('Blog_BlogUser');
					$oBlogUserNew->setBlogId($oBlog->getId());
					$oBlogUserNew->setUserId($this->oUserCurrent->getId());
					$oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
					$bResult = $this->Blog_AddRelationBlogUser($oBlogUserNew);
				}
				if ($bResult) {
					$this->Message_AddNoticeSingle($this->Lang_Get('blog_join_ok'),$this->Lang_Get('attention'));
					$this->Viewer_AssignAjax('bState',true);
					/**
					 * Увеличиваем число читателей блога
					 */
					$oBlog->setCountUser($oBlog->getCountUser()+1);
					$this->Blog_UpdateBlog($oBlog);
					$this->Viewer_AssignAjax('iCountUser',$oBlog->getCountUser());
					/**
					 * Добавляем событие в ленту
					 */
					$this->Stream_write($this->oUserCurrent->getId(), 'join_blog', $oBlog->getId());
					/**
					 * Добавляем подписку на этот блог в ленту пользователя
					 */
					$this->Userfeed_subscribeUser($this->oUserCurrent->getId(), ModuleUserfeed::SUBSCRIBE_TYPE_BLOG, $oBlog->getId());
				} else {
					$sMsg=($oBlog->getType()=='close')
						? $this->Lang_Get('blog_join_error_invite')
						: $this->Lang_Get('system_error');
					$this->Message_AddErrorSingle($sMsg,$this->Lang_Get('error'));
					return;
				}
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('blog_join_error_self'),$this->Lang_Get('attention'));
				return;
			}
		}
		if ($oBlogUser && $oBlogUser->getUserRole()>ModuleBlog::BLOG_USER_ROLE_GUEST) {
			/**
			 * Покидаем блог
			 */
			if ($this->Blog_DeleteRelationBlogUser($oBlogUser)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('blog_leave_ok'),$this->Lang_Get('attention'));
				$this->Viewer_AssignAjax('bState',false);
				/**
				 * Уменьшаем число читателей блога
				 */
				$oBlog->setCountUser($oBlog->getCountUser()-1);
				$this->Blog_UpdateBlog($oBlog);
				$this->Viewer_AssignAjax('iCountUser',$oBlog->getCountUser());
				/**
				 * Удаляем подписку на этот блог в ленте пользователя
				 */
				$this->Userfeed_unsubscribeUser($this->oUserCurrent->getId(), ModuleUserfeed::SUBSCRIBE_TYPE_BLOG, $oBlog->getId());
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
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
		$this->Viewer_Assign('sMenuHeadItemSelect',$this->sMenuHeadItemSelect);
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);
		$this->Viewer_Assign('sMenuSubBlogUrl',$this->sMenuSubBlogUrl);
		$this->Viewer_Assign('iCountTopicsCollectiveNew',$this->iCountTopicsCollectiveNew);
		$this->Viewer_Assign('iCountTopicsPersonalNew',$this->iCountTopicsPersonalNew);
		$this->Viewer_Assign('iCountTopicsBlogNew',$this->iCountTopicsBlogNew);
		$this->Viewer_Assign('iCountTopicsNew',$this->iCountTopicsNew);

		$this->Viewer_Assign('BLOG_USER_ROLE_GUEST', ModuleBlog::BLOG_USER_ROLE_GUEST);
		$this->Viewer_Assign('BLOG_USER_ROLE_USER', ModuleBlog::BLOG_USER_ROLE_USER);
		$this->Viewer_Assign('BLOG_USER_ROLE_MODERATOR', ModuleBlog::BLOG_USER_ROLE_MODERATOR);
		$this->Viewer_Assign('BLOG_USER_ROLE_ADMINISTRATOR', ModuleBlog::BLOG_USER_ROLE_ADMINISTRATOR);
		$this->Viewer_Assign('BLOG_USER_ROLE_INVITE', ModuleBlog::BLOG_USER_ROLE_INVITE);
		$this->Viewer_Assign('BLOG_USER_ROLE_REJECT', ModuleBlog::BLOG_USER_ROLE_REJECT);
		$this->Viewer_Assign('BLOG_USER_ROLE_BAN', ModuleBlog::BLOG_USER_ROLE_BAN);
	}
}
?>