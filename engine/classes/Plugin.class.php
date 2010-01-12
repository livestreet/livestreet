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
 * Абстракция плагина, от которой наследуются все плагины
 *
 */
abstract class Plugin extends Object {
	
	protected $aDelegates=array();
	
	public function __construct() {

	}

	/**
	 * Функция инициализации плагина
	 *
	 */
	public function Init() {

	}
	
	/**
	 * Передает информацию о делегатах на Plugin-модуль
	 * Вызывается Engine перед инициализацией плагина
	 */
	final function Delegate() {
		if(is_array($this->aDelegates) and count($this->aDelegates)) {
			foreach ($this->aDelegates as $sObjectName=>$aParams) {
				if(is_array($aParams) and count($aParams)) {
					foreach ($aParams as $sFrom=>$sTo) {
						$this->Plugin_Delegate($sObjectName,$sFrom,$sTo);
					}
				}
			}
		}
	}
	
	/**
	 * Возвращает массив делегатов
	 *
	 * @return array
	 */
	final function GetDelegates() {
		return $this->aDelegates;
	}
	
	/**
	 * Функция активации плагина
	 *
	 */
	public function Activate() {
		return true;
	}
	/**
	 * Функция деактивации плагина
	 *
	 */
	public function Deactivate() {
		return true;
	}
	/**
	 * Транслирует на базу данных запросы из указанного файла
	 * 
	 * @param  string $sFilePath
	 * @return array
	 */
	protected function ExportSQL($sFilePath) {
		$sFileQuery = @file_get_contents($sFilePath);
		/**
		 * Замена префикса таблиц
		 */
		$sFileQuery = str_replace('prefix_', Config::Get('db.table.prefix'), $sFileQuery);

		/**
		 * Массивы запросов и пустой контейнер для сбора ошибок
		 */
		$aErrors = array();
		$aQuery=explode(';',$sFileQuery);
		/**
		 * Выполняем запросы по очереди
		 */
		foreach($aQuery as $sQuery){
			$sQuery = trim($sQuery);
			/**
			 * Заменяем движек, если таковой указан в запросе
			 */
			if(Config::Get('db.tables.engine')!='InnoDB') $sQuery=str_ireplace('ENGINE=InnoDB', "ENGINE=".Config::Get('db.tables.engine'),$sQuery);
			
			if($sQuery!='') {
				$bResult=$this->Database_GetConnect()->query($sQuery);
				if(!$bResult) $aErrors[] = mysql_error();
			}
		}
				
		/**
		 * Возвращаем результат выполнения, взависимости от количества ошибок 
		 */
		if(count($aErrors)==0) {
			return array('result'=>true,'errors'=>null);
		}
		return array('result'=>false,'errors'=>$aErrors);
	}
	
	public function __call($sName,$aArgs) {
		return Engine::getInstance()->_CallModule($sName,$aArgs);
	}
}
?>