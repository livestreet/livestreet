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
 *
 */
class ModuleSecurity extends Module {
	/**
	 * Инициализируем модуль
	 *
	 */
	public function Init() {
		
	}


	public function ValidateSendForm() {
		if (!($this->ValidateSessionKey() && 1)) {
			die("Hacking attemp!");
		}
	}
	
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
	
	public function Shutdown() {
		$this->SetSessionKey();
	}	
}
?>