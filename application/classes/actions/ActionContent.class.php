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
 * Экшен обработки УРЛа вида /content/ - управление своими топиками
 *
 * @package actions
 * @since 1.0
 */
class ActionContent extends Action {
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
	protected $sMenuSubItemSelect='topic';
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
		if (!$this->User_IsAuthorization()) {
			return parent::EventNotFound();
		}
		$this->oUserCurrent=$this->User_GetUserCurrent();
		/**
		 * Усанавливаем дефолтный евент
		 */
		$this->SetDefaultEvent('add');
		/**
		 * Устанавливаем title страницы
		 */
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_title'));
	}
	/**
	 * Регистрируем евенты
	 *
	 */
	protected function RegisterEvent() {
		$this->AddEventPreg('/^add$/i','/^[a-z_0-9]{1,50}$/i','/^$/i','EventAdd');
		$this->AddEventPreg('/^edit$/i','/^\d{1,10}$/i','/^$/i','EventEdit');
		$this->AddEventPreg('/^delete$/i','/^\d{1,10}$/i','/^$/i','EventDelete');

		$this->AddEventPreg('/^published$/i','/^(page([1-9]\d{0,5}))?$/i','EventShowTopics');
		$this->AddEventPreg('/^drafts$/i','/^(page([1-9]\d{0,5}))?$/i','EventShowTopics');

		$this->AddEventPreg('/^ajax$/i','/^add$/i','/^$/i','EventAjaxAdd');
		$this->AddEventPreg('/^ajax$/i','/^edit$/i','/^$/i','EventAjaxEdit');
		$this->AddEventPreg('/^ajax$/i','/^preview$/i','/^$/i','EventAjaxPreview');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

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
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('content').$this->sCurrentEvent);
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_menu_'.$this->sCurrentEvent));
	}

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

	protected function EventEdit() {
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
		if (!$this->Topic_IsAllowTopicType($oTopic->getType())) {
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
		$this->Viewer_Assign('sTopicType',$oTopic->getType());
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_topic_edit'));

		$this->Viewer_Assign('oTopicEdit', $oTopic);
		$this->SetTemplateAction('add');
	}

	/**
	 * Добавление топика
	 *
	 */
	protected function EventAdd() {
		$sTopicType=$this->GetParam(0);
		if (!$this->Topic_IsAllowTopicType($sTopicType)) {
			return parent::EventNotFound();
		}
		$this->sMenuSubItemSelect=$sTopicType;
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topic_add_show');
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('sTopicType',$sTopicType);
		$this->Viewer_Assign('aBlogsAllow',$this->Blog_GetBlogsAllowByUser($this->oUserCurrent));
		$this->Viewer_AddHtmlTitle($this->Lang_Get('topic_topic_create'));
		$this->SetTemplateAction('add');
	}

	protected function EventAjaxEdit() {
		$this->Viewer_SetResponseAjax();

		$aTopicRequest=getRequest('topic');
		if (!(isset($aTopicRequest['id']) and $oTopic=$this->Topic_GetTopicById($aTopicRequest['id']))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}
		if (!$this->Topic_IsAllowTopicType($oTopic->getType())) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}
		/**
		 * Проверяем разрешено ли постить топик по времени
		 */
		if (isPost('submit_topic_publish') and !$oTopic->getPublishDraft() and !$this->ACL_CanPostTopicTime($this->oUserCurrent)) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_time_limit'),$this->Lang_Get('error'));
			return;
		}

		/**
		 * Если права на редактирование
		 */
		if (!$this->ACL_IsAllowEditTopic($oTopic,$this->oUserCurrent)) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}
		/**
		 * Сохраняем старое значение идентификатора блога
		 */
		$sBlogIdOld = $oTopic->getBlogId();

		$oTopic->_setDataSafe(getRequest('topic'));
		$oTopic->setProperties(getRequest('property'));
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
		if ($this->ACL_IsAllowPublishIndex($this->oUserCurrent))	{
			if (isset($_REQUEST['topic']['topic_publish_index'])) {
				$oTopic->setPublishIndex(1);
			} else {
				$oTopic->setPublishIndex(0);
			}
		}
		/**
		 * Запрет на комментарии к топику
		 */
		$oTopic->setForbidComment(0);
		if (isset($_REQUEST['topic']['topic_forbid_comment'])) {
			$oTopic->setForbidComment(1);
		}

		if ($oTopic->_Validate()) {
			$oBlog=$oTopic->getBlog();
			/**
			 * Проверяем права на постинг в блог
			 */
			if (!$this->ACL_IsAllowBlog($oBlog,$this->oUserCurrent)) {
				$this->Message_AddErrorSingle($this->Lang_Get('topic_create_blog_error_noallow'),$this->Lang_Get('error'));
				return false;
			}
			/**
			 * Получаемый и устанавливаем разрезанный текст по тегу <cut>
			 */
			list($sTextShort,$sTextNew,$sTextCut) = $this->Text_Cut($oTopic->getTextSource());
			$oTopic->setCutText($sTextCut);
			// TODO: передача параметров в Topic_Parser пока не используется - нужно заменить на этот вызов все места с парсингом топика
			$oTopic->setText($this->Topic_Parser($sTextNew,$oTopic));
			$oTopic->setTextShort($this->Topic_Parser($sTextShort,$oTopic));
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
					$this->Topic_SendNotifyTopicNew($oBlog,$oTopic,$oTopic->getUser());
				}
				if (!$oTopic->getPublish() and !$this->oUserCurrent->isAdministrator() and $this->oUserCurrent->getId()!=$oTopic->getUserId()) {
					$sUrlRedirect=$oBlog->getUrlFull();
				} else {
					$sUrlRedirect=$oTopic->getUrl();
				}

				$this->Viewer_AssignAjax('sUrlRedirect',$sUrlRedirect);
				$this->Message_AddNotice('Обновление прошло успешно',$this->Lang_Get('attention'));
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			}
		} else {
			$this->Message_AddError($oTopic->_getValidateError(),$this->Lang_Get('error'));
		}
	}

	protected function EventAjaxAdd() {
		$this->Viewer_SetResponseAjax();
		/**
		 * TODO: Здесь нужна проверка прав на создание топика
		 */
		$sTopicType=getRequestStr('topic_type');
		if (!$this->Topic_IsAllowTopicType($sTopicType)) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}
		/**
		 * Проверяем разрешено ли постить топик по времени
		 */
		if (isPost('submit_topic_publish') and !$this->ACL_CanPostTopicTime($this->oUserCurrent)) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_time_limit'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Создаем топик
		 */
		$oTopic=Engine::GetEntity('Topic');
		$oTopic->_setDataSafe(getRequest('topic'));

		$oTopic->setProperties(getRequest('property'));
		$oTopic->setUserId($this->oUserCurrent->getId());
		$oTopic->setDateAdd(date("Y-m-d H:i:s"));
		$oTopic->setUserIp(func_getIp());
		$oTopic->setTopicType($sTopicType);
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
			if (isset($_REQUEST['topic']['topic_publish_index'])) {
				$oTopic->setPublishIndex(1);
			}
		}
		/**
		 * Запрет на комментарии к топику
		 */
		$oTopic->setForbidComment(0);
		if (isset($_REQUEST['topic']['topic_forbid_comment'])) {
			$oTopic->setForbidComment(1);
		}

		if ($oTopic->_Validate()) {
			$oBlog=$oTopic->getBlog();
			/**
			 * Проверяем права на постинг в блог
			 */
			if (!$this->ACL_IsAllowBlog($oBlog,$this->oUserCurrent)) {
				$this->Message_AddErrorSingle($this->Lang_Get('topic_create_blog_error_noallow'),$this->Lang_Get('error'));
				return false;
			}
			/**
			 * Получаем и устанавливаем разрезанный текст по тегу <cut>
			 */
			list($sTextShort,$sTextNew,$sTextCut) = $this->Text_Cut($oTopic->getTextSource());
			$oTopic->setCutText($sTextCut);
			$oTopic->setText($this->Topic_Parser($sTextNew,$oTopic));
			$oTopic->setTextShort($this->Topic_Parser($sTextShort,$oTopic));

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
				 * Фиксируем ID у media файлов топика
				 */
				if (isset($_COOKIE['media_target_tmp_topic']) and is_string($_COOKIE['media_target_tmp_topic'])) {
					$aTargetItems=$this->Media_GetTargetItemsByTargetTmpAndTargetType($_COOKIE['media_target_tmp_topic'],'topic');
					foreach($aTargetItems as $oTarget) {
						$oTarget->setTargetTmp(null);
						$oTarget->setTargetId($oTopic->getId());
						$oTarget->Update();
					}
				}
				setcookie('media_target_tmp_topic',null);
				/**
				 * Добавляем автора топика в подписчики на новые комментарии к этому топику
				 */
				$oUser=$oTopic->getUser();
				if ($oUser) {
					$this->Subscribe_AddSubscribeSimple('topic_new_comment',$oTopic->getId(),$oUser->getMail(),$oUser->getId());
				}
				/**
				 * Делаем рассылку спама всем, кто состоит в этом блоге
				 */
				if ($oTopic->getPublish()==1 and $oBlog->getType()!='personal') {
					$this->Topic_SendNotifyTopicNew($oBlog,$oTopic,$oUser);
				}
				/**
				 * Добавляем событие в ленту
				 */
				$this->Stream_write($oTopic->getUserId(), 'add_topic', $oTopic->getId(),$oTopic->getPublish() && $oBlog->getType()!='close');


				$this->Viewer_AssignAjax('sUrlRedirect',$oTopic->getUrl());
				$this->Message_AddNotice('Добавление прошло успешно',$this->Lang_Get('attention'));
			} else {
				$this->Message_AddError('Возникла ошибка при добавлении',$this->Lang_Get('error'));
			}
		} else {
			$this->Message_AddError($oTopic->_getValidateError(),$this->Lang_Get('error'));
		}
	}

	public function EventAjaxPreview() {
		/**
		 * Т.к. используется обработка отправки формы, то устанавливаем тип ответа 'jsonIframe' (тот же JSON только обернутый в textarea)
		 * Это позволяет избежать ошибок в некоторых браузерах, например, Opera
		 */
		$this->Viewer_SetResponseAjax('jsonIframe',false);
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Допустимый тип топика?
		 */
		if (!$this->Topic_IsAllowTopicType($sType=getRequestStr('topic_type'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_create_type_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Создаем объект топика для валидации данных
		 */
		$oTopic=Engine::GetEntity('ModuleTopic_EntityTopic');

		$aTopicRequest=getRequest('topic');
		$oTopic->setTitle(isset($aTopicRequest['topic_title']) ? strip_tags($aTopicRequest['topic_title']) : '');
		$oTopic->setTextSource(isset($aTopicRequest['topic_text_source']) ? $aTopicRequest['topic_text_source'] : '');
		$oTopic->setTags(isset($aTopicRequest['topic_tags']) ? $aTopicRequest['topic_tags'] : '');
		$oTopic->setDateAdd(date("Y-m-d H:i:s"));
		$oTopic->setUserId($this->oUserCurrent->getId());
		$oTopic->setType($sType);
		/**
		 * Валидируем необходимые поля топика
		 */
		$oTopic->_Validate(array('topic_title','topic_text','topic_tags','topic_type'),false);
		if ($oTopic->_hasValidateErrors()) {
			$this->Message_AddErrorSingle($oTopic->_getValidateError());
			return false;
		}
		/**
		 * Формируем текст топика
		 */
		list($sTextShort,$sTextNew,$sTextCut) = $this->Text_Cut($oTopic->getTextSource());
		$oTopic->setCutText($sTextCut);
		$oTopic->setText($this->Topic_Parser($sTextNew,$oTopic));
		$oTopic->setTextShort($this->Topic_Parser($sTextShort,$oTopic));
		/**
		 * Рендерим шаблон для предпросмотра топика
		 */
		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('oTopic',$oTopic);
		$sTemplate="topics/topic_preview_{$oTopic->getType()}.tpl";
		if (!$this->Viewer_TemplateExists($sTemplate)) {
			$sTemplate='topics/topic_preview.tpl';
		}
		$sTextResult=$oViewer->Fetch($sTemplate);
		/**
		 * Передаем результат в ajax ответ
		 */
		$this->Viewer_AssignAjax('sText',$sTextResult);
		return true;
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