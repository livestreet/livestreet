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
 * Модуль безопасности
 * Необходимо использовать перед обработкой отправленной формы:
 * <pre>
 * if (getRequest('submit_add')) {
 * 	$this->Security_ValidateSendForm();
 * 	// далее код обработки формы
 *  ......
 * }
 * </pre>
 *
 * @package engine.modules
 * @since 1.0
 */
class ModuleSecurity extends Module {
	/**
	 * Инициализируем модуль
	 *
	 */
	public function Init() {

	}
	/**
	 * Производит валидацию отправки формы/запроса от пользователя, позволяет избежать атаки CSRF
	 */
	public function ValidateSendForm() {
		if (!($this->ValidateSessionKey())) {
			die("Hacking attemp!");
		}
	}
	/**
	 * Проверка на соотвествие реферала
	 *
	 * @return bool
	 */
	public function ValidateReferal() {
		if (isset($_SERVER['HTTP_REFERER'])) {
			$aUrl=parse_url($_SERVER['HTTP_REFERER']);
			if (strcasecmp($aUrl['host'],$_SERVER['HTTP_HOST'])==0) {
				return true;
			} elseif (preg_match("/\.".quotemeta($_SERVER['HTTP_HOST'])."$/i",$aUrl['host'])) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Проверяет наличие security-ключа в сессии
	 *
	 * @param null|string $sCode	Код для проверки, если нет то берется из реквеста
	 * @return bool
	 */
	public function ValidateSessionKey($sCode=null) {
		if(!$sCode) $sCode=getRequest('security_ls_key');
		return ($sCode==$this->GenerateSessionKey());
	}
	/**
	 * Устанавливает security-ключ в сессию
	 *
	 * @return string
	 */
	public function SetSessionKey() {
		$sCode = $this->GenerateSessionKey();
		$this->Viewer_Assign('LIVESTREET_SECURITY_KEY',$sCode);

		return $sCode;
	}
	/**
	 * Генерирует текущий security-ключ
	 *
	 * @return string
	 */
	protected function GenerateSessionKey() {
		return md5($this->Session_GetId().Config::Get('module.security.hash'));
	}
	/**
	 * Завершение модуля
	 */
	public function Shutdown() {
		$this->SetSessionKey();
	}
}
?>