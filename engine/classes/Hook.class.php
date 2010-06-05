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
 * Абстракция хука, от которой наследуются все хуки
 *
 */
abstract class Hook extends Object {
		
	public function __construct() {		
		
	}
	
	protected function AddHook($sName,$sCallBack,$sClassNameHook=null,$iPriority=1) {
		if (is_null($sClassNameHook)) {
			$sClassNameHook=get_class($this);
		}
		$this->Hook_AddExecHook($sName,$sCallBack,$iPriority,array('sClassName'=>$sClassNameHook));
	}
	
	protected function AddDelegateHook($sName,$sCallBack,$sClassNameHook=null,$iPriority=1) {
		if (is_null($sClassNameHook)) {
			$sClassNameHook=get_class($this);
		}
		$this->Hook_AddDelegateHook($sName,$sCallBack,$iPriority,array('sClassName'=>$sClassNameHook));
	}
		
	abstract public function RegisterHook();
	
	public function __call($sName,$aArgs) {
		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
}
?>