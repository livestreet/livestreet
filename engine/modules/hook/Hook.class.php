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
 * Модуль поддержки хуков(hooks)
 *
 */
class LsHook extends Module {		
		
	/**
	 * Содержит список хуков
	 *
	 * @var array( 'name' => array(
	 * 		array(
	 * 			'type' => 'module' | 'hook' | 'function',
	 * 			'callback' => 'callback_name',
	 * 			'priority'	=> 1,
	 * 			'params' => array()
	 * 		),
	 * 	),
	 * )
	 */
	protected $aHooks=array();
	
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {	
		
	}
	
	public function Add($sName,$sType,$sCallBack,$iPriority=1,$aParams=array()) {
		$sType=strtolower($sType);
		if (!in_array($sType,array('module','hook','function'))) {
			return false;
		}
		$this->aHooks[$sName][]=array('type'=>$sType,'callback'=>$sCallBack,'params'=>$aParams,'priority'=>(int)$iPriority);
	}
	
	public function AddExecModule($sName,$sCallBack,$iPriority=1) {
		return $this->Add($sName,'module',$sCallBack,$iPriority);
	}
	
	public function AddExecFunction($sName,$sCallBack,$iPriority=1) {
		return $this->Add($sName,'function',$sCallBack,$iPriority);
	}
	
	public function AddExecHook($sName,$sCallBack,$iPriority=1,$aParams=array()) {
		return $this->Add($sName,'hook',$sCallBack,$iPriority,$aParams);
	}
	
	public function Run($sName,$aVars=array()) {
		if (isset($this->aHooks[$sName])) {
			$aHookNum=array();
			for ($i=0;$i<count($this->aHooks[$sName]);$i++) {
				$aHookNum[$i]=$this->aHooks[$sName][$i]['priority'];
			}			
			arsort($aHookNum,SORT_NUMERIC);
			foreach ($aHookNum as $iKey => $iPr) {
				$aHook=$this->aHooks[$sName][$iKey];
				switch ($aHook['type']) {
					case 'module':
						call_user_func_array(array($this,$aHook['callback']),array($aVars));
						break;
					case 'function':
						call_user_func_array($aHook['callback'],array($aVars));
						break;
					case 'hook':
						if (isset($aHook['params']['sClassName']) and class_exists($aHook['params']['sClassName'])) {
							$oHook=new $aHook['params']['sClassName'];							
							call_user_func_array(array($oHook,$aHook['callback']),array($aVars));
						}											
						break;
					default:
						break;
				}
				
			}
		}
	}	
}
?>