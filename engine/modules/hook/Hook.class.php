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
class ModuleHook extends Module {		
		
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
		$sName=strtolower($sName);
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
	
	public function AddDelegateModule($sName,$sCallBack,$iPriority=1) {
		return $this->Add($sName,'module',$sCallBack,$iPriority,array('delegate'=>true));
	}
	
	public function AddDelegateFunction($sName,$sCallBack,$iPriority=1) {
		return $this->Add($sName,'function',$sCallBack,$iPriority,array('delegate'=>true));
	}
	
	public function AddDelegateHook($sName,$sCallBack,$iPriority=1,$aParams=array()) {
		$aParams['delegate']=true;
		return $this->Add($sName,'hook',$sCallBack,$iPriority,$aParams);
	}
	
	public function Run($sName,&$aVars=array()) {
		$result=array();
		$sName=strtolower($sName);
		$bTemplateHook=strpos($sName,'template_')===0 ? true : false;
		if (isset($this->aHooks[$sName])) {
			$aHookNum=array();
			$aHookNumDelegate=array();
			/**
			 * Все хуки делим на обычные(exec) и делигирующие(delegate)
			 */
			for ($i=0;$i<count($this->aHooks[$sName]);$i++) {				
				if (isset($this->aHooks[$sName][$i]['params']['delegate']) and $this->aHooks[$sName][$i]['params']['delegate']) {
					$aHookNumDelegate[$i]=$this->aHooks[$sName][$i]['priority'];
				} else {
					$aHookNum[$i]=$this->aHooks[$sName][$i]['priority'];
				}				
			}			
			arsort($aHookNum,SORT_NUMERIC);
			arsort($aHookNumDelegate,SORT_NUMERIC);
			/**
			 * Сначала запускаем на выполнение простые
			 */
			foreach ($aHookNum as $iKey => $iPr) {
				$aHook=$this->aHooks[$sName][$iKey];
				if ($bTemplateHook) {
					/**
					 * Если это шаблонных хук то сохраняем результат 
					 */
					$result['template_result'][]=$this->RunType($aHook,$aVars);
				} else {
					$this->RunType($aHook,$aVars);
				}
			}
			/**
			 * Теперь запускаем делигирующие
			 * Делегирующий хук должен вернуть результат в формате:
			 * 
			 */
			foreach ($aHookNumDelegate as $iKey => $iPr) {
				$aHook=$this->aHooks[$sName][$iKey];				
				$result=array(
					'delegate_result'=>$this->RunType($aHook,$aVars)
				);
				/**
				 * На данный момент только один хук может быть делегирующим
				 */
				break;
			}
		}
		return $result;
	}
	
	protected function RunType($aHook,&$aVars) {
		$result=null;
		switch ($aHook['type']) {
			case 'module':
				$result=call_user_func_array(array($this,$aHook['callback']),array(&$aVars));
				break;
			case 'function':
				$result=call_user_func_array($aHook['callback'],array(&$aVars));
				break;
			case 'hook':
				if (isset($aHook['params']['sClassName']) and class_exists($aHook['params']['sClassName'])) {
					$oHook=new $aHook['params']['sClassName'];
					$result=call_user_func_array(array($oHook,$aHook['callback']),array(&$aVars));
				}
				break;
			default:
				break;
		}
		return $result;
	}
}
?>