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
 * Сущность записи на стене
 *
 * @package modules.wall
 * @since 1.0
 */
class ModuleWall_EntityWall extends Entity {
	/**
	 * Определяем правила валидации
	 *
	 * @var array
	 */
	protected $aValidateRules=array(
		array('pid','pid','on'=>array('','add')),
		array('user_id','time_limit','on'=>array('add')),
	);

	/**
	 * Инициализация
	 */
	public function Init() {
		parent::Init();
		$this->aValidateRules[]=array('text','string','max'=>Config::Get('module.wall.text_max'),'min'=>Config::Get('module.wall.text_min'),'allowEmpty'=>false,'on'=>array('','add'));
	}
	/**
	 * Проверка на ограничение по времени
	 *
	 * @param string $sValue	Проверяемое значение
	 * @param array $aParams	Параметры
	 * @return bool|string
	 */
	public function ValidateTimeLimit($sValue,$aParams) {
		if ($oUser=$this->User_GetUserById($this->getUserId())) {
			if ($this->ACL_CanAddWallTime($oUser,$this)) {
				return true;
			}
		}
		return $this->Lang_Get('wall_add_time_limit');
	}
	/**
	 * Валидация родительского сообщения
	 *
	 * @param string $sValue	Проверяемое значение
	 * @param array $aParams	Параметры
	 * @return bool|string
	 */
	public function ValidatePid($sValue,$aParams) {
		if (!$sValue) {
			$this->setPid(null);
			return true;
		} elseif ($oParentWall=$this->GetPidWall()) {
			/**
			 * Если отвечаем на сообщение нужной стены и оно корневое, то все ОК
			 */
			if ($oParentWall->getWallUserId()==$this->getWallUserId() and !$oParentWall->getPid()) {
				return true;
			}
		}
		return $this->Lang_Get('wall_add_pid_error');
	}
	/**
	 * Возвращает родительскую запись
	 *
	 * @return ModuleWall_EntityWall|null
	 */
	public function GetPidWall() {
		if ($this->getPid()) {
			return $this->Wall_GetWallById($this->getPid());
		}
		return null;
	}
	/**
	 * Проверка на возможность удаления сообщения
	 *
	 * @return bool
	 */
	public function isAllowDelete() {
		if ($oUserCurrent=$this->User_GetUserCurrent()) {
			if ($oUserCurrent->getId()==$this->getWallUserId() or $oUserCurrent->isAdministrator()) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Возвращает пользователя, которому принадлежит стена
	 *
	 * @return ModuleUser_EntityUser|null
	 */
	public function getWallUser() {
		if (!$this->_getDataOne('wall_user')) {
			$this->_aData['wall_user']=$this->User_GetUserById($this->getWallUserId());
		}
		return $this->_getDataOne('wall_user');
	}
	/**
	 * Возвращает URL стены
	 *
	 * @return string
	 */
	public function getUrlWall() {
		return $this->getWallUser()->getUserWebPath().'wall/';
	}
}
?>