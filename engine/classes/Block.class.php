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
 * Абстрактный класс блока
 * Это те блоки которые обрабатывают шаблоны Smarty перед выводом(например блок "Облако тегов")
 *
 */
abstract class Block extends Object {	
	protected $oEngine=null;	
	protected $aParams=array();
	
	public function __construct($aParams) {	
		$this->aParams=$aParams;	
		$this->oEngine=Engine::getInstance();		
	}
	
	protected function GetParam($sName,$def=null) {
		if (isset($this->aParams[$sName])) {
			return $this->aParams[$sName];
		} else {
			return $def;
		}
	}
	
	public function __call($sName,$aArgs) {
		return $this->oEngine->_CallModule($sName,$aArgs);
	}	
	
	abstract public function Exec();
}
?>