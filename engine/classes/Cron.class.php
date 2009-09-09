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

require_once("Engine.class.php");

/**
 * Абстрактный слой работы с крон-процессами
 */
class Cron extends Object {
	/**
	 * @var Engine
	 */
	protected $oEngine=null;
	/**
	 * Объект для логирования действий и вывода
	 *
	 * @va object
	 */
	protected $oLog=null;
	/**
	 * Дескриптор блокирующего файла
	 *
	 * @var string
	 */
	protected $oLockFile=null;
	
	public function __construct($sLockFile=null) {
		$this->oEngine=Engine::getInstance();

		if(!empty($sLockFile)) {
			$this->oLockFile=fopen($sLockFile,'a');
			/**
			 * Если процесс заблокирован, выкидываем исключение
			 */
			if($this->isLock()) {			
				throw new Exception('Try to exec already run process');
			}
			$this->setLock();
		}
	}
	
	/**
	 * Проверяет уникальность создаваемого процесса
	 */
	public function isLock() {
		return ($this->oLockFile && !flock($this->sLockFile, LOCK_EX|LOCK_NB));
	}
	/**
	 * Снимает блокировку на повторный процесс
	 */
	public function unsetLock() {
		return ($this->oLockFile && @flock($this->oLockFile, LOCK_UN));
	}
	
	/**
	 * Основная функция слоя. Реализует логику работы
	 * крон процесса с последующей передачей управления
	 * на пользотвальскую функцию
	 *
	 * @param ( string|array ) $sFunction
	 * @param array $aArgs
	 */
	public function Exec($sFunction, $aArgs) {
		/**
		 * Если выполнение процесса заблокирован, завершаемся
		 */
		if($this->isLock()) {
			return;
		}
		
		if(!function_exists($sFunction)||!is_callable($sFunction)) {
			throw new Exception('Undefined function given');
		}
		/**
		 * Здесь мы реализуем дополнительную логику:
		 * логирование вызова, обработка ошибок,
		 * буферизация вывода.
		 */
		ob_start();
		call_user_func_array($sFunction,$aArgs);
		/**
		 * Получаем весь вывод функции.
		 */
		$sContent=ob_get_contents();
		ob_end_clean();
	}
	
	/**
	 * Здесь будет реализована логика завершения работы срон-процесса
	 */
	public function Shutdown() {
		$this->unsetLock();	
	}
	
	public function __destruct() {
		$this->Shutdown();
		return;	
	}
}
?>