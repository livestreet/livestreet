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
class LsSecurity extends Module {
	
	/**
	 * Инициализируем модуль
	 *
	 */
	public function Init() {
		
	}


	public function ValidateSendForm() {
		if (!($this->ValidateReferal() && 1)) {
			die("Hacking attemp!");
		}
	}
	
	public function ValidateReferal() {
		if (isset($_SERVER['HTTP_REFERER'])) {
			$aUrl=parse_url($_SERVER['HTTP_REFERER']);				
			if ($aUrl['host']==$_SERVER['HTTP_HOST']) {
				return true;
			} elseif (preg_match("/\.".quotemeta($_SERVER['HTTP_HOST'])."$/i",$aUrl['host'])) {				 
				return true;				
			}		
		}
		return false;
	}
}
?>