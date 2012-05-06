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
		$this->AddEvent('subscribe', 'EventSubscribe');
		$this->AddEvent('subscribeByLogin', 'EventSubscribeByLogin');
		$this->AddEvent('unsubscribe', 'EventUnSubscribe');
		$this->AddEvent('switchEventType', 'EventSwitchEventType');
		$this->AddEvent('get_more', 'EventGetMore');
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
			parent::EventNotFound();
		}
		$this->Viewer_AddBlock('right','streamConfig');
		/**
		 * Читаем события
		 */
		$aEvents = $this->Stream_Read();
		$this->Viewer_Assign('bDisableGetMoreButton', $this->Stream_GetCountByReaderId($this->oUserCurrent->getId()) < Config::Get('module.stream.count_default'));
		$this->Viewer_Assign('aStreamEvents', $aEvents);
		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_Assign('iStreamLastId', $oEvenLast->getId());
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
			parent::EventNotFound();
		}
		if (!getRequest('type')) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
		/**
		 * Активируем/деактивируем тип
		 */
		$this->Stream_switchUserEventType($this->oUserCurrent->getId(), getRequest('type'));
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
			parent::EventNotFound();
		}
		/**
		 * Необходимо передать последний просмотренный ID событий
		 */
		$iFromId = getRequest('last_id');
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
		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_AssignAjax('iStreamLastId', $oEvenLast->getId());
		}
		/**
		 * Возвращаем данные в ajax ответе
		 */
		$this->Viewer_AssignAjax('result', $oViewer->Fetch('actions/ActionStream/events.tpl'));
		$this->Viewer_AssignAjax('events_count', count($aEvents));
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
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			parent::EventNotFound();
		}
		/**
		 * Необходимо передать последний просмотренный ID событий
		 */
		$iFromId = getRequest('last_id');
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
		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_AssignAjax('iStreamLastId', $oEvenLast->getId());
		}
		/**
		 * Возвращаем данные в ajax ответе
		 */
		$this->Viewer_AssignAjax('result', $oViewer->Fetch('actions/ActionStream/events.tpl'));
		$this->Viewer_AssignAjax('events_count', count($aEvents));
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
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			parent::EventNotFound();
		}
		/**
		 * Необходимо передать последний просмотренный ID событий
		 */
		$iFromId = getRequest('last_id');
		if (!$iFromId)  {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		if (!($oUser=$this->User_GetUserById(getRequest('user_id')))) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Получаем события
		 */
		$aEvents = $this->Stream_ReadByUserId($oUser->getId(), null, $iFromId);

		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aStreamEvents', $aEvents);
		if (count($aEvents)) {
			$oEvenLast=end($aEvents);
			$this->Viewer_AssignAjax('iStreamLastId', $oEvenLast->getId());
		}
		/**
		 * Возвращаем данные в ajax ответе
		 */
		$this->Viewer_AssignAjax('result', $oViewer->Fetch('actions/ActionStream/events.tpl'));
		$this->Viewer_AssignAjax('events_count', count($aEvents));
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
			parent::EventNotFound();
		}
		/**
		 * Проверяем существование пользователя
		 */
		if (!$this->User_getUserById(getRequest('id'))) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
		if ($this->oUserCurrent->getId() == getRequest('id')) {
			$this->Message_AddError($this->Lang_Get('stream_error_subscribe_to_yourself'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Подписываем на пользователя
		 */
		$this->Stream_subscribeUser($this->oUserCurrent->getId(), getRequest('id'));
		$this->Message_AddNotice($this->Lang_Get('stream_subscribes_updated'), $this->Lang_Get('attention'));
	}
	/**
	 * Подписка на пользователя по логину
	 *
	 */
	protected function EventSubscribeByLogin() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			parent::EventNotFound();
		}
		if (!getRequest('login')) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Проверяем существование пользователя
		 */
		$oUser = $this->User_getUserByLogin(getRequest('login'));
		if (!$oUser) {
			$this->Message_AddError($this->Lang_Get('user_not_found',array('login'=>htmlspecialchars(getRequest('login')))),$this->Lang_Get('error'));
			return;
		}
		if ($this->oUserCurrent->getId() == $oUser->getId()) {
			$this->Message_AddError($this->Lang_Get('stream_error_subscribe_to_yourself'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Подписываем на пользователя
		 */
		$this->Stream_subscribeUser($this->oUserCurrent->getId(),  $oUser->getId());
		$this->Viewer_AssignAjax('uid', $oUser->getId());
		$this->Viewer_AssignAjax('user_login', $oUser->getLogin());
		$this->Viewer_AssignAjax('user_web_path', $oUser->getUserWebPath());
		$this->Viewer_AssignAjax('user_avatar_48', $oUser->getProfileAvatarPath(48));
		$this->Message_AddNotice($this->Lang_Get('userfeed_subscribes_updated'), $this->Lang_Get('attention'));
	}
	/**
	 * Отписка от пользователя
	 *
	 */
	protected function EventUnsubscribe() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Пользователь авторизован?
		 */
		if (!$this->oUserCurrent) {
			parent::EventNotFound();
		}
		/**
		 * Пользователь с таким ID существует?
		 */
		if (!$this->User_getUserById(getRequest('id'))) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
		/**
		 * Отписываем
		 */
		$this->Stream_unsubscribeUser($this->oUserCurrent->getId(), getRequest('id'));
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