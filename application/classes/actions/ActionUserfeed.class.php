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
 * Обрабатывает пользовательские ленты контента
 *
 * @package actions
 * @since 1.0
 */
class ActionUserfeed extends Action {
	/**
	 * Текущий пользователь
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserCurrent;

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		/**
		 * Доступ только у авторизованных пользователей
		 */
		$this->oUserCurrent = $this->User_getUserCurrent();
		if (!$this->oUserCurrent) {
			parent::EventNotFound();
		}
		$this->SetDefaultEvent('index');

		$this->Viewer_Assign('sMenuItemSelect', 'feed');
	}
	/**
	 * Регистрация евентов
	 *
	 */
	protected function RegisterEvent() {
		$this->AddEvent('index', 'EventIndex');
		$this->AddEvent('subscribe', 'EventSubscribe');
		$this->AddEvent('ajaxadduser', 'EventAjaxAddUser');
		$this->AddEvent('unsubscribe', 'EventUnSubscribe');
		$this->AddEvent('get_more', 'EventGetMore');
	}
	/**
	 * Выводит ленту контента(топики) для пользователя
	 *
	 */
	protected function EventIndex() {
		/**
		 * Получаем топики
		 */
		$aTopics = $this->Userfeed_read($this->oUserCurrent->getId());
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topics_list_show',array('aTopics'=>$aTopics));
		$this->Viewer_Assign('aTopics', $aTopics);
		if (count($aTopics)) {
			$this->Viewer_Assign('iUserfeedLastId', end($aTopics)->getId());
		}
		if (count($aTopics) < Config::Get('module.userfeed.count_default')) {
			$this->Viewer_Assign('bDisableGetMoreButton', true);
		} else {
			$this->Viewer_Assign('bDisableGetMoreButton', false);
		}
		$this->SetTemplateAction('list');
	}
	/**
	 * Подгрузка ленты топиков (замена постраничности)
	 *
	 */
	protected function EventGetMore() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Проверяем последний просмотренный ID топика
		 */
		$iFromId = getRequestStr('last_id');
		if (!$iFromId)  {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Получаем топики
		 */
		$aTopics = $this->Userfeed_read($this->oUserCurrent->getId(), null, $iFromId);
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topics_list_show',array('aTopics'=>$aTopics));
		/**
		 * Загружаем данные в ajax ответ
		 */
		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('aTopics',  $aTopics);
		$this->Viewer_AssignAjax('result', $oViewer->Fetch('topics/topic_list.tpl'));
		$this->Viewer_AssignAjax('topics_count', count($aTopics));

		if (count($aTopics)) {
			$this->Viewer_AssignAjax('iUserfeedLastId', end($aTopics)->getId());
		}
	}
	/**
	 * Подписка на контент блога или пользователя
	 *
	 */
	protected function EventSubscribe() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		/**
		 * Проверяем наличие ID блога или пользователя
		 */
		if (!getRequest('id')) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
		}
		$sType = getRequestStr('type');
		$iType = null;
		/**
		 * Определяем тип подписки
		 */
		switch($sType) {
			case 'blogs':
				$iType = ModuleUserfeed::SUBSCRIBE_TYPE_BLOG;
				/**
				 * Проверяем существование блога
				 */
				if (!$this->Blog_GetBlogById(getRequestStr('id'))) {
					$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
					return;
				}
				break;
			case 'users':
				$iType = ModuleUserfeed::SUBSCRIBE_TYPE_USER;
				/**
				 * Проверяем существование пользователя
				 */
				if (!$this->User_GetUserById(getRequestStr('id'))) {
					$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
					return;
				}
				if ($this->oUserCurrent->getId() == getRequestStr('id')) {
					$this->Message_AddError($this->Lang_Get('userfeed_error_subscribe_to_yourself'),$this->Lang_Get('error'));
					return;
				}
				break;
			default:
				$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
		}
		/**
		 * Подписываем
		 */
		$this->Userfeed_subscribeUser($this->oUserCurrent->getId(), $iType, getRequestStr('id'));
		$this->Message_AddNotice($this->Lang_Get('userfeed_subscribes_updated'), $this->Lang_Get('attention'));
	}
	/**
	 * Подписка на пользвователя по логину
	 *
	 */
	protected function EventAjaxAddUser() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$sUsers=getRequestStr('sUserList',null,'post');
		/**
		 * Если пользователь не авторизирован, возвращаем ошибку
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
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
			 * Если пользователь не найден или неактивен, возвращаем ошибку
			 */
			if ($oUser=$this->User_GetUserByLogin($sUser) and $oUser->getActivate()==1) {
				$this->Userfeed_subscribeUser($this->oUserCurrent->getId(), ModuleUserfeed::SUBSCRIBE_TYPE_USER, $oUser->getId());

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
					'sUserHtml'=>$oViewer->Fetch("user_list_small_item.tpl")
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
		$this->Viewer_AssignAjax('aUsers',$aResult);
	}
	/**
	 * Отписка от блога или пользователя
	 *
	 */
	protected function EventUnsubscribe() {
		/**
		 * Устанавливаем формат Ajax ответа
		 */
		$this->Viewer_SetResponseAjax('json');
		$sId=getRequestStr('id');

		$sType = getRequestStr('type');
		$iType = null;
		/**
		 * Определяем от чего отписываемся
		 */
		switch($sType) {
			case 'blogs':
				$iType = ModuleUserfeed::SUBSCRIBE_TYPE_BLOG;
				break;
			case 'users':
				$iType = ModuleUserfeed::SUBSCRIBE_TYPE_USER;
				$sId=getRequestStr('iUserId');
				break;
			default:
				$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
		}
		if (!$sId) {
			$this->Message_AddError($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		/**
		 * Отписываем пользователя
		 */
		$this->Userfeed_unsubscribeUser($this->oUserCurrent->getId(), $iType, $sId);
		$this->Message_AddNotice($this->Lang_Get('userfeed_subscribes_updated'), $this->Lang_Get('attention'));
	}
	/**
	 * При завершении экшена загружаем в шаблон необходимые переменные
	 *
	 */
	public function EventShutdown() {
		/**
		 * Подсчитываем новые топики
		 */
		$iCountTopicsCollectiveNew=$this->Topic_GetCountTopicsCollectiveNew();
		$iCountTopicsPersonalNew=$this->Topic_GetCountTopicsPersonalNew();
		$iCountTopicsNew=$iCountTopicsCollectiveNew+$iCountTopicsPersonalNew;
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('iCountTopicsCollectiveNew',$iCountTopicsCollectiveNew);
		$this->Viewer_Assign('iCountTopicsPersonalNew',$iCountTopicsPersonalNew);
		$this->Viewer_Assign('iCountTopicsNew',$iCountTopicsNew);
	}
}