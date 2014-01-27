<?php
/**
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Модуль управления правами на основе ролей и разрешений
 */
class ModuleRbac extends ModuleORM {

	const ROLE_CODE_GUEST='guest';

	const PERMISSION_STATE_ACTIVE=1;
	const PERMISSION_STATE_INACTIVE=0;

	const ROLE_STATE_ACTIVE=1;
	const ROLE_STATE_INACTIVE=0;

	protected $aUserRoleCache=array();
	protected $aRoleCache=array();
	protected $aRulePermissionCache=array();
	protected $aPermissionCache=array();

	protected $sMessageLast=null;

	protected $oMapper=null;

	public function Init() {
		parent::Init();
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	/**
	 * Проверяет разрешение для текущего авторизованного пользователя
	 *
	 * @param string $sPermissionCode
	 * @param array $aParams
	 *
	 * @return bool
	 */
	public function IsAllow($sPermissionCode,$aParams=array()) {
		return $this->IsAllowUser($this->User_GetUserCurrent(),$sPermissionCode,$aParams);
	}

	public function IsAllowUser($oUser,$sPermissionCode,$aParams=array()) {
		if (!$sPermissionCode) {
			return false;
		}
		/**
		 * Загружаем все роли и пермишены
		 */
		$this->LoadRoleAndPermissions();
		$sUserId=self::ROLE_CODE_GUEST;
		if ($oUser) {
			$sUserId=$oUser->getId();
		}
		/**
		 * Смотрим роли в кеше
		 */
		if (!isset($this->aUserRoleCache[$sUserId])) {
			if ($sUserId==self::ROLE_CODE_GUEST) {
				$aRoles=$this->GetRoleByCodeAndState(self::ROLE_CODE_GUEST,self::ROLE_STATE_ACTIVE);
				$aRoles=$aRoles ? array($aRoles) : array();
			} else {
				$aRoles=$oUser->getRolesActive();
			}
			$this->aUserRoleCache[$sUserId]=$aRoles;
		} else {
			$aRoles=$this->aUserRoleCache[$sUserId];
		}
		/**
		 * Получаем пермишены для ролей
		 */
		$sPermissionCode=func_underscore($sPermissionCode);
		foreach($aRoles as $oRole) {
			if ($this->CheckPermissionByRole($oRole,$sPermissionCode)) {
				/**
				 * У роли есть необходимый пермишен, теперь проверим на возможную кастомную обработку с параметрами
				 */
				$sMethod='CheckCustom'.func_camelize($sPermissionCode);
				if (method_exists($this,$sMethod)) {
					if (call_user_func(array($this,$sMethod),$oUser,$aParams)) {
						return true;
					}
				} else {
					return true;
				}
			}
		}
		if (isset($this->aPermissionCache[$sPermissionCode])) {
			$aPerm=$this->aPermissionCache[$sPermissionCode];
			if ($aPerm['msg_error']) {
				$sMsg=$aPerm['msg_error'];
			} else {
				$sMsg='У вас нет прав на "'.($aPerm['title'] ? $aPerm['title'] : $aPerm['code']).'"';
			}
		} else {
			$sMsg='У вас нет прав на "'.$sPermissionCode.'"';
		}
		$this->sMessageLast=$sMsg;
		return false;
	}

	protected function LoadRoleAndPermissions() {
		/**
		 * Роли
		 */
		$this->LoadRoles();
		/**
		 * Пермишены
		 */
		$this->LoadPermissions();
	}

	protected function LoadPermissions() {
		if ($this->aRulePermissionCache) {
			return;
		}
		$aResult=$this->oMapper->GetRoleWithPermissions();
		foreach($aResult as $aRow) {
			$this->aRulePermissionCache[$aRow['role_id']][]=$aRow['code'];
			$this->aPermissionCache[$aRow['code']]=$aRow;
		}
	}

	protected function LoadRoles() {
		if ($this->aRoleCache) {
			return;
		}
		$aRoles=$this->GetRoleItemsByState(self::ROLE_STATE_ACTIVE);
		foreach($aRoles as $oRole) {
			$this->aRoleCache[$oRole->getId()]=$oRole;
		}
	}

	protected function CheckPermissionByRole($oRole,$sPermissionCode) {
		/**
		 * Проверяем наличие пермишена в текущей роли
		 */
		if (isset($this->aRulePermissionCache[$oRole->getId()])) {
			if (in_array($sPermissionCode,$this->aRulePermissionCache[$oRole->getId()])) {
				return true;
			}
		}
		/**
		 * Смотрим родительскую роль
		 */
		if ($oRole->getPid() and isset($this->aRoleCache[$oRole->getPid()])) {
			return $this->CheckPermissionByRole($this->aRoleCache[$oRole->getPid()],$sPermissionCode);
		}
		return false;
	}

	public function GetMsgLast() {
		return $this->sMessageLast;
	}
}