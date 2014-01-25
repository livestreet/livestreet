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
 * Сущность жалобы о пользователе
 *
 * @package modules.user
 * @since 1.0
 */
class ModuleUser_EntityComplaint extends Entity {
	/**
	 * Определяем правила валидации
	 *
	 * @var array
	 */
	protected $aValidateRules=array(
		array('target_user_id','target'),
		array('type','type'),
	);

	/**
	 * Инициализация
	 */
	public function Init() {
		parent::Init();
		$this->aValidateRules[]=array('text','string','max'=>Config::Get('module.user.complaint_text_max'),'min'=>1,'allowEmpty'=>!Config::Get('module.user.complaint_text_required'),'label'=>$this->Lang_Get('user_complaint_text_title'));
		if (Config::Get('module.user.complaint_captcha')){
			$this->aValidateRules[] = array('captcha','captcha','name'=>'complaint_user');
		}
	}
	/**
	 * Валидация пользователя
	 *
	 * @param string $sValue	Значение
	 * @param array $aParams	Параметры
	 * @return bool
	 */
	public function ValidateTarget($sValue,$aParams) {
		if ($oUserTarget=$this->User_GetUserById($sValue) and $this->getUserId()!=$oUserTarget->getId()) {
			return true;
		}
		return $this->Lang_Get('user_complaint_target_error');
	}
	/**
	 * Валидация типа жалобы
	 *
	 * @param string $sValue	Значение
	 * @param array $aParams	Параметры
	 * @return bool
	 */
	public function ValidateType($sValue,$aParams) {
		$aTypes=(array)Config::Get('module.user.complaint_type');
		if (in_array($sValue,$aTypes)) {
			return true;
		}
		return $this->Lang_Get('user_complaint_type_error');
	}

}