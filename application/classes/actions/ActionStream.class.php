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
 * Экшен обработки ленты активности
 *
 * @package actions
 * @since 1.0
 */
class ActionStream extends Action {
	/**
	 * Текущий пользователь
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserCurrent;
	/**
	 * Какое меню активно
	 *
	 * @var string
	 */
	protected $sMenuItemSelect='user';

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		/**
		 * Личная лента доступна только для авторизованных, для гостей показываем общую ленту
		 */
		$this->oUserCurrent = $this->User_getUserCurrent();
		if ($this->oUserCurrent) {
			$this->SetDefaultEvent('user');
		} else {
			$this->SetDefaultEvent('all');
		}
		$this->Viewer_Assign('aStreamEventTypes', $this->Stream_getEventTypes());

		$this->Viewer_Assign('sMenuHeadItemSelect', 'stream');
		/**
		 * Загружаем в шаблон JS текстовки
		 */
		$this->Lang_AddLangJs(array(
								  'stream_subscribes_already_subscribed','error'
							  ));
	}
	/**
	 * Регистрация евентов
	 *
	 */
	protected function RegisterEvent() {
		$this->AddEvent('user', 'EventUser');
		$this->AddEvent('all', 'EventAll');
		$this->AddEvent('subscribe', 'EventSubscribe'); // TODO: возможно нужно удалить
		$this->AddEvent('ajaxadduser', 'EventAjaxAddUser');
		$this->AddEvent('ajaxremoveuser', 'EventAjaxRemoveUser');
		$this->AddEvent('switchEventType', 'EventSwitchEventType');
		$this->AddEvent('get_more_custom', 'EventGetMore');
		$this->AddEvent('get_more_user', 'EventGetMoreUser');
		$this->AddEvent('get_more_all', 'EventGetMoreAll');
	}

	/**
	 * Список событий в ленте активности пользователя
	 *
	 */
	protected function EventUser() {
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			return parent::EventNotFound();
		}
		$this->Viewer_AddBlock('right','activitySettings');
		$this->Viewer_AddBlock('right','activityUsers');

		/**
		 * Читаем события
		 */
		$aEvents = $this->Stream_Read();
		$this->Viewer_Assign('bDisableGetMoreButton', $this->Stream_GetCountByReaderId($this->oUserCurrent->getId()) < Config::Get('module.stream.count_default'));
		$this->Viewer_Assign('aStreamEvents', $aEvents);
		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_Assign('iStreamLastId', $oEvenLast->getId());
			$this->Viewer_Assign('sDateLast', $oEvenLast->getDateAdded());
		}
	}
	/**
	 * Список событий в общей ленте активности сайта
	 *
	 */
	protected function EventAll() {
		$this->sMenuItemSelect='all';
		/**
		 * Читаем события
		 */
		$aEvents = $this->Stream_ReadAll();
		$this->Viewer_Assign('bDisableGetMoreButton', $this->Stream_GetCountAll() < Config::Get('module.stream.count_default'));
		$this->Viewer_Assign('aStreamEvents', $aEvents);
		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_Assign('iStreamLastId', $oEvenLast->getId());
			$this->Viewer_Assign('sDateLast', $oEvenLast->getDateAdded());
		}
	}
	/**
	 * Активаци/деактивация типа события
	 *
	 */
	protected function EventSwitchEventType() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			return parent::EventNotFound();
		}
		if (!getRequest('type')) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
		/**
		 * Активируем/деактивируем тип
		 */
		$this->Stream_switchUserEventType($this->oUserCurrent->getId(), getRequestStr('type'));
		$this->Message_AddNotice($this->Lang_Get('stream_subscribes_updated'), $this->Lang_Get('attention'));
	}
	/**
	 * Погрузка событий (замена постраничности)
	 *
	 */
	protected function EventGetMore() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			return parent::EventNotFound();
		}
		/**
		 * Необходимо передать последний просмотренный ID событий
		 */
		$iFromId = getRequestStr('iLastId');
		if (!$iFromId)  {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Получаем события
		 */
		$aEvents = $this->Stream_Read(null, $iFromId);

		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aStreamEvents', $aEvents);
		$oViewer->Assign('sDateLast', getRequestStr('sDateLast'));
		$this->Viewer_AssignAjax('iCountLoaded', count($aEvents));

		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_AssignAjax('iLastId', $oEvenLast->getId());
		}
		/**
		 * Возвращаем данные в ajax ответе
		 */
		$this->Viewer_AssignAjax('sHtml', $oViewer->Fetch('actions/ActionStream/events.tpl'));
	}
	/**
	 * Погрузка событий для всего сайта
	 *
	 */
	protected function EventGetMoreAll() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Необходимо передать последний просмотренный ID событий
		 */
		$iFromId = getRequestStr('iLastId');
		if (!$iFromId)  {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Получаем события
		 */
		$aEvents = $this->Stream_ReadAll(null, $iFromId);

		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aStreamEvents', $aEvents);
		$oViewer->Assign('sDateLast', getRequestStr('sDateLast'));
		$this->Viewer_AssignAjax('iCountLoaded', count($aEvents));

		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_AssignAjax('iLastId', $oEvenLast->getId());
		}
		/**
		 * Возвращаем данные в ajax ответе
		 */
		$this->Viewer_AssignAjax('sHtml', $oViewer->Fetch('actions/ActionStream/events.tpl'));
	}
	/**
	 * Подгрузка событий для пользователя
	 *
	 */
	protected function EventGetMoreUser() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Необходимо передать последний просмотренный ID событий
		 */
		$iFromId = getRequestStr('iLastId');
		if (!$iFromId)  {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		if (!($oUser=$this->User_GetUserById(getRequestStr('iTargetId')))) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Получаем события
		 */
		$aEvents = $this->Stream_ReadByUserId($oUser->getId(), null, $iFromId);

		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aStreamEvents', $aEvents);
		$oViewer->Assign('sDateLast', getRequestStr('sDateLast'));
		$this->Viewer_AssignAjax('iCountLoaded', count($aEvents));

		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_AssignAjax('iLastId', $oEvenLast->getId());
		}
		/**
		 * Возвращаем данные в ajax ответе
		 */
		$this->Viewer_AssignAjax('sHtml', $oViewer->Fetch('actions/ActionStream/events.tpl'));
	}
	/**
	 * Подписка на пользователя по ID
	 *
	 */
	protected function EventSubscribe() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			return parent::EventNotFound();
		}
		/**
		 * Проверяем существование пользователя
		 */
		if (!$this->User_getUserById(getRequestStr('id'))) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
		if ($this->oUserCurrent->getId() == getRequestStr('id')) {
			$this->Message_AddError($this->Lang_Get('user_list_add.notices.error_self'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Подписываем на пользователя
		 */
		$this->Stream_subscribeUser($this->oUserCurrent->getId(), getRequestStr('id'));
		$this->Message_AddNotice($this->Lang_Get('stream_subscribes_updated'), $this->Lang_Get('attention'));
	}
	/**
	 * Подписка на пользователя по логину
	 *
	 */
	protected function EventAjaxAddUser() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$aUsers=getRequest('aUserList',null,'post');
		/**
		 * Валидация
		 */
		if ( ! is_array($aUsers) ) {
			return $this->EventErrorDebug();
		}
		/**
		 * Если пользователь не авторизирован, возвращаем ошибку
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

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
			 * Если пользователь не найден или неактивен, возвращаем ошибку
			 */
			if ($oUser=$this->User_GetUserByLogin($sUser) and $oUser->getActivate()==1) {
				$this->Stream_subscribeUser($this->oUserCurrent->getId(),$oUser->getId());
				$oViewer = $this->Viewer_GetLocalViewer();
				$oViewer->Assign('oUser', $oUser);
				$oViewer->Assign('bUserListSmallShowActions', true);

				$aResult[]=array(
					'bStateError'=>false,
					'sMsgTitle'=>$this->Lang_Get('attention'),
					'sMsg'=>$this->Lang_Get('common.success.add',array('login'=>htmlspecialchars($sUser))),
					'sUserId'=>$oUser->getId(),
					'sUserLogin'=>htmlspecialchars($sUser),
					'sUserWebPath'=>$oUser->getUserWebPath(),
					'sUserAvatar48'=>$oUser->getProfileAvatarPath(48),
					'sHtml'=>$oViewer->Fetch("user_list_small_item.tpl")
				);
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
		$this->Viewer_AssignAjax('aUserList',$aResult);
	}
	/**
	 * Отписка от пользователя
	 *
	 */
	protected function EventAjaxRemoveUser() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			return $this->EventErrorDebug();
		}
		/**
		 * Пользователь с таким ID существует?
		 */
		if (!$this->User_GetUserById(getRequestStr('iUserId'))) {
			return $this->EventErrorDebug();
		}
		/**
		 * Отписываем
		 */
		$this->Stream_unsubscribeUser($this->oUserCurrent->getId(), getRequestStr('iUserId'));
		$this->Message_AddNotice($this->Lang_Get('stream_subscribes_updated'), $this->Lang_Get('attention'));
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
	}
}
