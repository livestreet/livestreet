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

class ModuleWall_EntityWall extends Entity {

	/**
	 * Определяем правила валидации
	 */
	protected $aValidateRules=array(
		array('pid','pid'),
	);

	public function Init() {
		parent::Init();
		$this->aValidateRules[]=array('text','string','max'=>Config::Get('module.wall.text_max'),'min'=>Config::Get('module.wall.text_min'),'allowEmpty'=>false);
	}
	/**
	 * Валидация родительского сообщения
	 *
	 * @param $sValue
	 * @param $aParams
	 *
	 * @return bool
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
	 * @return ModuleWall_EntityWall
	 */
	public function GetPidWall() {
		if ($this->getPid()) {
			return $this->Wall_GetWallById($this->getPid());
		}
		return null;
	}

	public function getWallUser() {
		if (!isset($this->_aData['wall_user'])) {
			$this->_aData['wall_user']=$this->User_GetUserById($this->getWallUserId());
		}
		return $this->_aData['wall_user'];
	}
}
?>