<?
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
 * Абстракция модуля, от которой наследуются все модули
 *
 */
abstract class Hook extends Object {
		
	public function __construct() {		
		
	}
	
	protected function AddHook($sName,$sCallBack,$sClassNameHook,$iPriority=1) {
		$this->Hook_AddExecHook($sName,$sCallBack,$iPriority,array('sClassName'=>$sClassNameHook));
	}
		
	abstract public function RegisterHook();
	
	public function __call($sName,$aArgs) {
		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
}
?>