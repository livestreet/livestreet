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
 * Обработка УРЛа вида /photoset/ - управление своими топиками(тип: фотосет)
 *
 * @package actions
 * @since 1.0
 */
class ActionPhotoset extends Action {
	/**
	 * Главное меню
	 *
	 * @var string
	 */
	protected $sMenuHeadItemSelect='blog';
	/**
	 * Меню
	 *
	 * @var string
	 */
	protected $sMenuItemSelect='topic';
	/**
	 * СубМеню
	 *
	 * @var string
	 */
	protected $sMenuSubItemSelect='photoset';
	/**
	 * Текущий юзер
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserCurrent=null;

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		/**
		 * Проверяем авторизован ли юзер
		 */
		$this->oUserCurrent=$this->User_GetUserCurrent();
		$this->SetDefaultEvent('add');
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_photoset_title'));
		/**
		 * Загружаем в шаблон JS текстовки
		 */
		$this->Lang_AddLangJs(array(
								  'topic_photoset_photo_delete','topic_photoset_mark_as_preview','topic_photoset_photo_delete_confirm',
								  'topic_photoset_is_preview','topic_photoset_upload_choose'
							  ));
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {
		$this->AddEvent('add','EventAdd'); // Добавление топика
		$this->AddEvent('edit','EventEdit'); // Редактирование топика
		$this->AddEvent('deleteimage','EventDeletePhoto'); // Удаление изображения
		$this->AddEvent('upload','EventUpload'); // Загрузка изображения
		$this->AddEvent('getMore','EventGetMore');	// Загрузка изображения на сервер
		$this->AddEvent('setimagedescription','EventSetPhotoDescription'); // Установка описания к фото
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * AJAX подгрузка следующих фото
	 *
	 */
	protected function EventGetMore() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Существует ли топик
		 */
		$oTopic = $this->Topic_getTopicById(getRequest('topic_id'));
		if (!$oTopic || !getRequest('last_id')) {
			$this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
			return false;
		}
		/**
		 * Получаем список фото
		 */
		$aPhotos = $oTopic->getPhotosetPhotos(getRequest('last_id'), Config::Get('module.topic.photoset.per_page'));
		$aResult = array();
		if (count($aPhotos)) {
			/**
			 * Формируем данные для ajax ответа
			 */
			foreach($aPhotos as $oPhoto) {
				$aResult[] = array('id' => $oPhoto->getId(), 'path_thumb' => $oPhoto->getWebPath('50crop'), 'path' => $oPhoto->getWebPath(), 'description' => $oPhoto->getDescription());
			}
			$this->Viewer_AssignAjax('photos', $aResult);
		}
		$this->Viewer_AssignAjax('bHaveNext', count($aPhotos)==Config::Get('module.topic.photoset.per_page'));
	}
	/**
	 * AJAX удаление фото
	 *
	 */
	protected function EventDeletePhoto() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Проверяем авторизован ли юзер
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Поиск фото по id
		 */
		$oPhoto = $this->Topic_getTopicPhotoById(getRequest('id'));
		if ($oPhoto) {
			if ($oPhoto->getTopicId()) {
				/**
				 * Проверяем права на топик
				 */
				if ($oTopic=$this->Topic_GetTopicById($oPhoto->getTopicId()) and $this->ACL_IsAllowEditTopic($oTopic,$this->oUserCurrent)) {
					if ($oTopic->getPhotosetCount()>1) {
						$this->Topic_deleteTopicPhoto($oPhoto);
						/**
						 * Если удаляем главную фотку топика, то её необходимо сменить
						 */
						if ($oPhoto->getId()==$oTopic->getPhotosetMainPhotoId()) {
							$aPhotos = $oTopic->getPhotosetPhotos(0,1);
							$oTopic->setPhotosetMainPhotoId($aPhotos[0]->getId());
						}
						$oTopic->setPhotosetCount($oTopic->getPhotosetCount()-1);
						$this->Topic_UpdateTopic($oTopic);
						$this->Message_AddNotice($this->Lang_Get('topic_photoset_photo_deleted'), $this->Lang_Get('attention'));
					} else {
						$this->Message_AddError($this->Lang_Get('topic_photoset_photo_deleted_error_last'), $this->Lang_Get('error'));
					}
					return;
				}
			} else {
				$this->Topic_deleteTopicPhoto($oPhoto);
				$this->Message_AddNotice($this->Lang_Get('topic_photoset_photo_deleted'), $this->Lang_Get('attention'));
				return;
			}
		}
		$this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
	}
	/**
	 * AJAX установка описания фото
	 *
	 */
	protected function EventSetPhotoDescription() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Проверяем авторизован ли юзер
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Поиск фото по id
		 */
		$oPhoto = $this->Topic_getTopicPhotoById(getRequest('id'));
		if ($oPhoto) {
			if ($oPhoto->getTopicId()) {
				// проверяем права на топик
				if ($oTopic=$this->Topic_GetTopicById($oPhoto->getTopicId()) and $this->ACL_IsAllowEditTopic($oTopic,$this->oUserCurrent)) {
					$oPhoto->setDescription(htmlspecialchars(strip_tags(getRequest('text'))));
					$this->Topic_updateTopicPhoto($oPhoto);
				}
			} else {
				$oPhoto->setDescription(htmlspecialchars(strip_tags(getRequest('text'))));
				$this->Topic_updateTopicPhoto($oPhoto);
			}
		}
	}
	/**
	 * AJAX загрузка фоток
	 *
	 * @return unknown
	 */
	protected function EventUpload() {
		/**
		 * Устанавливаем формат Ajax ответа
		 * В зависимости от типа загрузчика устанавливается тип ответа
		 */
		if (getRequest('is_iframe')) {
			$this->Viewer_SetResponseAjax('jsonIframe', false);
		} else {
			$this->Viewer_SetResponseAjax('json');
		}
		/**
		 * Проверяем авторизован ли юзер
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Файл был загружен?
		 */
		if (!isset($_FILES['Filedata']['tmp_name'])) {
			$this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
			return false;
		}

		$iTopicId = getRequest('topic_id');
		$sTargetId = null;
		$iCountPhotos = 0;
		// Если от сервера не пришёл id топика, то пытаемся определить временный код для нового топика. Если и его нет. то это ошибка
		if (!$iTopicId) {
			$sTargetId = empty($_COOKIE['ls_photoset_target_tmp']) ? getRequest('ls_photoset_target_tmp') : $_COOKIE['ls_photoset_target_tmp'];
			if (!$sTargetId) {
				$this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
				return false;
			}
			$iCountPhotos = $this->Topic_getCountPhotosByTargetTmp($sTargetId);
		} else {
			/**
			 * Загрузка фото к уже существующему топику
			 */
			$oTopic = $this->Topic_getTopicById($iTopicId);
			if (!$oTopic or !$this->ACL_IsAllowEditTopic($oTopic,$this->oUserCurrent)) {
				$this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
				return false;
			}
			$iCountPhotos = $this->Topic_getCountPhotosByTopicId($iTopicId);
		}
		/**
		 * Максимальное количество фото в топике
		 */
		if ($iCountPhotos >= Config::Get('module.topic.photoset.count_photos_max')) {
			$this->Message_AddError($this->Lang_Get('topic_photoset_error_too_much_photos', array('MAX' => Config::Get('module.topic.photoset.count_photos_max'))), $this->Lang_Get('error'));
			return false;
		}
		/**
		 * Максимальный размер фото
		 */
		if (filesize($_FILES['Filedata']['tmp_name']) > Config::Get('module.topic.photoset.photo_max_size')*1024) {
			$this->Message_AddError($this->Lang_Get('topic_photoset_error_bad_filesize', array('MAX' => Config::Get('module.topic.photoset.photo_max_size'))), $this->Lang_Get('error'));
			return false;
		}
		/**
		 * Загружаем файл
		 */
		$sFile = $this->Topic_UploadTopicPhoto($_FILES['Filedata']);
		if ($sFile) {
			/**
			 * Создаем фото
			 */
			$oPhoto = Engine::GetEntity('Topic_TopicPhoto');
			$oPhoto->setPath($sFile);
			if ($iTopicId) {
				$oPhoto->setTopicId($iTopicId);
			} else {
				$oPhoto->setTargetTmp($sTargetId);
			}
			if ($oPhoto = $this->Topic_addTopicPhoto($oPhoto)) {
				/**
				 * Если топик уже существует (редактирование), то обновляем число фоток в нём
				 */
				if (isset($oTopic)) {
					$oTopic->setPhotosetCount($oTopic->getPhotosetCount()+1);
					$this->Topic_UpdateTopic($oTopic);
				}

				$this->Viewer_AssignAjax('file', $oPhoto->getWebPath('100crop'));
				$this->Viewer_AssignAjax('id', $oPhoto->getId());
				$this->Message_AddNotice($this->Lang_Get('topic_photoset_photo_added'), $this->Lang_Get('attention'));
			} else {
				$this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
			}
		} else {
			$this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
		}
	}
	/**
	 * Редактирование топика
	 *
	 */
	protected function EventEdit() {
		/**
		 * Проверяем авторизован ли юзер
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Получаем номер топика из УРЛ и проверяем существует ли он
		 */
		$sTopicId=$this->GetParam(0);
		if (!($oTopic=$this->Topic_GetTopicById($sTopicId))) {
			return parent::EventNotFound();
		}
		/**
		 * Проверяем тип топика
		 */
		if ($oTopic->getType()!='photoset') {
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
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_photoset_title_edit'));
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('add');

		if (!is_numeric(getRequest('topic_id'))) {
			$_REQUEST['topic_id']='';
		}
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
			$_REQUEST['topic_main_photo']=$oTopic->getPhotosetMainPhotoId();
		}
		$this->Viewer_Assign('aPhotos', $this->Topic_getPhotosByTopicId($oTopic->getId()));
	}
	/**
	 * Добавление топика
	 *
	 */
	protected function EventAdd() {
		/**
		 * Проверяем авторизован ли юзер
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return Router::Action('error');
		}
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topic_add_show');
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aBlogsAllow',$this->Blog_GetBlogsAllowByUser($this->oUserCurrent));
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_photoset_title_create'));

		if (!is_numeric(getRequest('topic_id'))) {
			$_REQUEST['topic_id']='';
		}
		/**
		 * Если нет временного ключа для нового топика, то генерируеи. если есть, то загружаем фото по этому ключу
		 */
		if (empty($_COOKIE['ls_photoset_target_tmp'])) {
			setcookie('ls_photoset_target_tmp',  func_generator(), time()+24*3600,Config::Get('sys.cookie.path'),Config::Get('sys.cookie.host'));
		} else {
			setcookie('ls_photoset_target_tmp', $_COOKIE['ls_photoset_target_tmp'], time()+24*3600,Config::Get('sys.cookie.path'),Config::Get('sys.cookie.host'));
			$this->Viewer_Assign('aPhotos', $this->Topic_getPhotosByTargetTmp($_COOKIE['ls_photoset_target_tmp']));
		}
		/**
		 * Обрабатываем отправку формы
		 */
		return $this->SubmitAdd();
	}
	/**
	 * Обработка добавлени топика
	 *
	 * @return mixed
	 */
	protected function SubmitAdd() {
		/**
		 * Проверяем отправлена ли форма с данными(хотяб одна кнопка)
		 */
		if (!isPost('submit_topic_publish') and !isPost('submit_topic_save')) {
			return false;
		}
		$oTopic=Engine::GetEntity('Topic');
		$oTopic->_setValidateScenario('photoset');
		/**
		 * Заполняем поля для валидации
		 */
		$oTopic->setBlogId(getRequest('blog_id'));
		$oTopic->setTitle(strip_tags(getRequest('topic_title')));
		$oTopic->setTextSource(getRequest('topic_text'));
		$oTopic->setTags(getRequest('topic_tags'));
		$oTopic->setUserId($this->oUserCurrent->getId());
		$oTopic->setType('photoset');
		$oTopic->setDateAdd(date("Y-m-d H:i:s"));
		$oTopic->setUserIp(func_getIp());
		/**
		 * Проверка корректности полей формы
		 */
		if (!$this->checkTopicFields($oTopic)) {
			return false;
		}
		/**
		 * Определяем в какой блог делаем запись
		 */
		$iBlogId=$oTopic->getBlogId();
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
		 * Проверяем разрешено ли постить топик по времени
		 */
		if (isPost('submit_topic_publish') and !$this->ACL_CanPostTopicTime($this->oUserCurrent)) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_time_limit'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Теперь можно смело добавлять топик к блогу
		 */
		$oTopic->setBlogId($oBlog->getId());
		/**
		 * Получаемый и устанавливаем разрезанный текст по тегу <cut>
		 */
		list($sTextShort,$sTextNew,$sTextCut) = $this->Text_Cut($oTopic->getTextSource());
		$oTopic->setCutText($sTextCut);
		$oTopic->setText($this->Text_Parser($sTextNew));
		$oTopic->setTextShort($this->Text_Parser($sTextShort));

		$sTargetTmp=$_COOKIE['ls_photoset_target_tmp'];
		$aPhotos = $this->Topic_getPhotosByTargetTmp($sTargetTmp);
		if (!($oPhotoMain=$this->Topic_getTopicPhotoById(getRequest('topic_main_photo')) and $oPhotoMain->getTargetTmp()==$sTargetTmp)) {
			$oPhotoMain=$aPhotos[0];
		}
		$oTopic->setPhotosetMainPhotoId($oPhotoMain->getId());
		$oTopic->setPhotosetCount(count($aPhotos));
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
		if ($this->ACL_IsAllowPublishIndex($this->oUserCurrent))	{
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
			 * Обновляем количество топиков в блоге
			 */
			$this->Blog_RecalculateCountTopicByBlogId($oTopic->getBlogId());
			/**
			 * Добавляем автора топика в подписчики на новые комментарии к этому топику
			 */
			$this->Subscribe_AddSubscribeSimple('topic_new_comment',$oTopic->getId(),$this->oUserCurrent->getMail());
			/**
			 * Делаем рассылку спама всем, кто состоит в этом блоге
			 */
			if ($oTopic->getPublish()==1 and $oBlog->getType()!='personal') {
				$this->Topic_SendNotifyTopicNew($oBlog,$oTopic,$this->oUserCurrent);
			}
			/**
			 * Привязываем фото к id топика
			 * здесь нужно это делать одним запросом, а не перебором сущностей
			 */
			if (count($aPhotos)) {
				foreach($aPhotos as $oPhoto) {
					$oPhoto->setTargetTmp(null);
					$oPhoto->setTopicId($oTopic->getId());
					$this->Topic_updateTopicPhoto($oPhoto);
				}
			}
			/**
			 * Удаляем временную куку
			 */
			setcookie('ls_photoset_target_tmp', null);
			/**
			 * Добавляем событие в ленту
			 */
			$this->Stream_write($oTopic->getUserId(), 'add_topic', $oTopic->getId(),$oTopic->getPublish() && $oBlog->getType()!='close');
			Router::Location($oTopic->getUrl());
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return Router::Action('error');
		}
	}
	/**
	 * Обработка редактирования топика
	 *
	 * @param ModuleTopic_EntityTopic $oTopic
	 * @return mixed
	 */
	protected function SubmitEdit($oTopic) {
		$oTopic->_setValidateScenario('photoset');
		/**
		 * Сохраняем старое значение идентификатора блога
		 */
		$sBlogIdOld = $oTopic->getBlogId();
		/**
		 * Заполняем поля для валидации
		 */
		$oTopic->setBlogId(getRequest('blog_id'));
		$oTopic->setTitle(strip_tags(getRequest('topic_title')));
		$oTopic->setTextSource(getRequest('topic_text'));
		$oTopic->setTags(getRequest('topic_tags'));
		$oTopic->setUserIp(func_getIp());
		/**
		 * Проверка корректности полей формы
		 */
		if (!$this->checkTopicFields($oTopic)) {
			return false;
		}
		/**
		 * Определяем в какой блог делаем запись
		 */
		$iBlogId=$oTopic->getBlogId();
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
		 * Проверяем разрешено ли постить топик по времени
		 */
		if (isPost('submit_topic_publish') and !$oTopic->getPublishDraft() and !$this->ACL_CanPostTopicTime($this->oUserCurrent)) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_time_limit'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Теперь можно смело редактировать топик
		 */
		$oTopic->setBlogId($oBlog->getId());
		/**
		 * Получаемый и устанавливаем разрезанный текст по тегу <cut>
		 */
		list($sTextShort,$sTextNew,$sTextCut) = $this->Text_Cut($oTopic->getTextSource());
		$oTopic->setCutText($sTextCut);
		$oTopic->setText($this->Text_Parser($sTextNew));
		$oTopic->setTextShort($this->Text_Parser($sTextShort));

		$aPhotos = $oTopic->getPhotosetPhotos();
		if (!($oPhotoMain=$this->Topic_getTopicPhotoById(getRequest('topic_main_photo')) and $oPhotoMain->getTopicId()==$oTopic->getId())) {
			$oPhotoMain=$aPhotos[0];
		}
		$oTopic->setPhotosetMainPhotoId($oPhotoMain->getId());
		$oTopic->setPhotosetCount(count($aPhotos));
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
		if ($this->ACL_IsAllowPublishIndex($this->oUserCurrent))	{
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
			 * Обновляем количество топиков в блоге
			 */
			if ($sBlogIdOld!=$oTopic->getBlogId()) {
				$this->Blog_RecalculateCountTopicByBlogId($sBlogIdOld);
			}
			$this->Blog_RecalculateCountTopicByBlogId($oTopic->getBlogId());
			/**
			 * Добавляем событие в ленту
			 */
			$this->Stream_write($oTopic->getUserId(), 'add_topic', $oTopic->getId(),$oTopic->getPublish() && $oBlog->getType()!='close');
			/**
			 * Рассылаем о новом топике подписчикам блога
			 */
			if ($bSendNotify)	 {
				$this->Topic_SendNotifyTopicNew($oBlog,$oTopic,$this->oUserCurrent);
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
	 * @return bool
	 */
	protected function checkTopicFields($oTopic) {
		$this->Security_ValidateSendForm();

		$bOk=true;
		if (!$oTopic->_Validate()) {
			$this->Message_AddError($oTopic->_getValidateError(),$this->Lang_Get('error'));
			$bOk=false;
		}

		$sTargetId = null;
		$iCountPhotos = 0;
		if (!$oTopic->getTopicId()) {
			if (isset($_COOKIE['ls_photoset_target_tmp'])) {
				$iCountPhotos = $this->Topic_getCountPhotosByTargetTmp($_COOKIE['ls_photoset_target_tmp']);
			} else {
				$this->Message_AddError($this->Lang_Get('system_error'), $this->Lang_Get('error'));
				return false;
			}
		} else {
			$iCountPhotos = $this->Topic_getCountPhotosByTopicId($oTopic->getId());
		}
		if ($iCountPhotos < Config::Get('module.topic.photoset.count_photos_min') || $iCountPhotos  > Config::Get('module.topic.photoset.count_photos_max')) {
			$this->Message_AddError($this->Lang_Get('topic_photoset_error_count_photos', array('MIN' => Config::Get('module.topic.photoset.count_photos_min'), 'MAX' => Config::Get('module.topic.photoset.count_photos_max'))), $this->Lang_Get('error'));
			return false;
		}
		/**
		 * Выполнение хуков
		 */
		$this->Hook_Run('check_photoset_fields', array('bOk'=>&$bOk));

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