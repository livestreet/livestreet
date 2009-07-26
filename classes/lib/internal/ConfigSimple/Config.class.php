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
 * Управление простым конфигом в виде массива
 *
 */
class ConfigSimple {
	
	static protected $aInstance=array();
	protected $aConfig=array();
	
	protected function __construct() {
		
	}
	
	/**
	 * Ограничиваем объект только одним экземпляром
	 *
	 * @return ConfigSimple
	 */
	static public function getInstance($sName='general') {
		if (isset(self::$aInstance[$sName])) {
			return self::$aInstance[$sName];
		} else {
			self::$aInstance[$sName]= new self();
			return self::$aInstance[$sName];
		}
	}
	
	public function Load($sFile,$bRewrite=true) {
		if (!file_exists($sFile)) {
			return false;
		}
		$aConfig=include($sFile);
		$this->SetConfig($aConfig,$bRewrite);		
		return true;
	}
	
	public function GetConfig() {
		return $this->aConfig;
	}
	
	public function SetConfig($aConfig=array(),$bRewrite=true) {
		if (is_array($aConfig)) {
			if ($bRewrite) {
				$this->aConfig=$aConfig;
			} else {
				$this->aConfig=$this->ArrayEmerge($this->aConfig,$aConfig);
			}
			return true;
		}
		$this->aConfig=array();
		return false;
	}
	
	public function Get($sKey) {
		$aKeys=explode('.',$sKey);
		$cfg=$this->aConfig;
		foreach ($aKeys as $sK) {						
			if (isset($cfg[$sK])) {
				$cfg=$cfg[$sK];
			} else {
				return null;
			}			
		}
		return $cfg;
	}
	
	public function Set($sKey,$value) {
		$aKeys=explode('.',$sKey);
		$sEval='$this->aConfig';
		foreach ($aKeys as $sK) {
			$sEval.="['$sK']";
		}
		$sEval.='=$value;';
		eval($sEval);		
	}
	
	protected function ArrayEmerge($aArr1,$aArr2) {
		return func_array_merge_assoc($aArr1,$aArr2);
	}
}
?>