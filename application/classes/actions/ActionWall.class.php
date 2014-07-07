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
 * Стена
 *
 * @package actions
 * @since 1.0
 */
class ActionWall extends Action {
	/**
	 * Инициализация
	 */
	public function Init() {
		$this->oUserCurrent = $this->User_GetUserCurrent();
	}

	/**
	 * Регистрируем евенты
	 */
	protected function RegisterEvent() {
		// Добавление поста/комментария
		$this->AddEventPreg('/^add$/i', 'EventAdd');

		// Удаление поста/комментария
		$this->AddEventPreg('/^remove$/i', 'EventRemove');

		// Подгрузка постов
		$this->AddEventPreg('/^load$/i', 'EventLoad');

		// Подгрузка комментариев
		$this->AddEventPreg('/^load-comments$/i', 'EventLoadComments');
	}

	/**
	 * Проверка корректности профиля
	 */
	protected function CheckUserProfile() {
		if ( ! ( $this->oUserProfile = $this->User_GetUserById( (int) getRequestStr('user_id') ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Добавление записи на стену
	 */
	public function EventAdd() {
		$this->Viewer_SetResponseAjax('json');

		if ( ! $this->oUserCurrent ) {
			return $this->EventErrorDebug();
		}

		if ( ! $this->CheckUserProfile() ) {
			return $this->EventErrorDebug();
		}

		// Создаем запись
		$oWall = Engine::GetEntity('Wall');

		$oWall->_setValidateScenario('add');
		$oWall->setWallUserId($this->oUserProfile->getId());
		$oWall->setUserId($this->oUserCurrent->getId());
		$oWall->setText(getRequestStr('text'));
		$oWall->setPid(getRequestStr('pid'));

		$this->Hook_Run('wall_add_validate_before', array( 'oWall' => $oWall ));

		if ($oWall->_Validate()) {
			// Экранируем текст и добавляем запись в БД
			$oWall->setText($this->Text_Parser($oWall->getText()));
			$this->Hook_Run('wall_add_before', array('oWall'=>$oWall));

			if ( $this->Wall_AddWall($oWall) ) {
				$this->Hook_Run('wall_add_after', array('oWall'=>$oWall));

				// Отправляем уведомления
				if ($oWall->getWallUserId()!=$oWall->getUserId()) {
					$this->Notify_SendWallNew($oWall,$this->oUserCurrent);
				}

				if ($oWallParent=$oWall->GetPidWall() and $oWallParent->getUserId()!=$oWall->getUserId()) {
					$this->Notify_SendWallReply($oWallParent,$oWall,$this->oUserCurrent);
				}

				// Добавляем событие в ленту
				$this->Stream_Write($oWall->getUserId(), 'add_wall', $oWall->getId());
			} else {
				$this->Message_AddError($this->Lang_Get('common.error.add'),$this->Lang_Get('error'));
			}
		} else {
			$this->Message_AddError($oWall->_getValidateError(),$this->Lang_Get('error'));
		}
	}

	/**
	 * Удаление записи со стены
	 */
	public function EventRemove() {
		$this->Viewer_SetResponseAjax('json');

		if ( ! $this->oUserCurrent ) {
			return $this->EventErrorDebug();
		}

		if ( ! $this->CheckUserProfile() ) {
			return $this->EventErrorDebug();
		}

		// Получаем запись
		if ( ! ( $oWall = $this->Wall_GetWallById( getRequestStr('id') ) ) ) {
			return $this->EventErrorDebug();
		}

		// Если разрешено удаление - удаляем
		if ( $oWall->isAllowDelete() ) {
			$this->Wall_DeleteWall($oWall);
			return;
		}

		return $this->EventErrorDebug();
	}

	/**
	 * Ajax подгрузка сообщений стены
	 */
	public function EventLoad() {
		$this->Viewer_SetResponseAjax('json');

		if ( ! $this->CheckUserProfile() ) {
			return $this->EventErrorDebug();
		}

		// Формируем фильтр для запроса к БД
		$aFilter = array(
			'wall_user_id' => $this->oUserProfile->getId(),
			'pid'          => null
		);

		if ( is_numeric(getRequest('last_id')) ) {
			$aFilter['id_less'] = getRequest('last_id');
		} else if ( is_numeric(getRequest('first_id')) ) {
			$aFilter['id_more'] = getRequest('first_id');
		} else {
			return $this->EventErrorDebug();
		}

		// Получаем сообщения и формируем ответ
		$aWall = $this->Wall_GetWall($aFilter, array('id' => 'desc'), 1, Config::Get('module.wall.per_page'));

		$this->Viewer_Assign('posts', $aWall['collection'], true);
		$this->Viewer_Assign('oUserCurrent', $this->oUserCurrent); // хак, т.к. к этому моменту текущий юзер не загружен в шаблон

		$this->Viewer_AssignAjax('html', $this->Viewer_Fetch('components/wall/wall.posts.tpl'));
		$this->Viewer_AssignAjax('count_loaded', count($aWall['collection']));

		if (count($aWall['collection'])) {
			$this->Viewer_AssignAjax('last_id', end($aWall['collection'])->getId());
		}
	}

	/**
	 * Подгрузка комментариев
	 */
	public function EventLoadComments() {
		$this->Viewer_SetResponseAjax('json');

		if ( ! $this->CheckUserProfile() ) {
			return $this->EventErrorDebug();
		}

		if ( ! ($oWall = $this->Wall_GetWallById(getRequestStr('target_id'))) or $oWall->getPid() ) {
			return $this->EventErrorDebug();
		}

		// Формируем фильтр для запроса к БД
		$aFilter = array(
			'wall_user_id' => $this->oUserProfile->getId(),
			'pid'          => $oWall->getId()
		);

		if ( is_numeric(getRequest('last_id')) ) {
			$aFilter['id_less'] = getRequest('last_id');
		} else if ( is_numeric(getRequest('first_id')) ) {
			$aFilter['id_more'] = getRequest('first_id');
		} else {
			return $this->EventErrorDebug();
		}

		// Получаем сообщения и формируем ответ
		// Необходимо вернуть все ответы, но ставим "разумное" ограничение
		$aWall = $this->Wall_GetWall($aFilter, array('id' => 'asc'), 1, 300);

		// Передаем переменные
		$this->Viewer_Assign('comments', $aWall['collection'], true);

		$this->Viewer_AssignAjax('html', $this->Viewer_Fetch('components/wall/wall.comments.tpl'));
		$this->Viewer_AssignAjax('count_loaded', count($aWall['collection']));

		if ( count($aWall['collection']) ) {
			$this->Viewer_AssignAjax('last_id', end($aWall['collection'])->getId());
		}
	}
}