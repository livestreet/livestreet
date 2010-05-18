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
	 * Дескриптор блокирующего файла
	 *
	 * @var string
	 */
	protected $oLockFile=null;
	/**
	 * Имя процесса, под которым будут помечены все сообщения в логах
	 *
	 * @var string
	 */
	protected $sProcessName='Cron';
	
	public function __construct($sLockFile=null) {
		$this->oEngine=Engine::getInstance();
		/**
		 * Инициализируем ядро
		 */
		$this->oEngine->Init();

		if(!empty($sLockFile)) {
			$this->oLockFile=fopen($sLockFile,'a');
		}
		
		/**
		 * Инициализируем лог и делает пометку о старте процесса
		 */
		$this->oEngine->Logger_SetFileName(Config::Get('sys.logs.cron_file'));
		$this->Log('Cron process started');
	}

	/**
	 * Делает запись в лог
	 *
	 * @param  string $sMsg
	 * @return
	 */
	public function Log($sMsg) {
		$sMsg=$this->sProcessName.': '.$sMsg;
		$this->oEngine->Logger_Notice($sMsg);		
	}
	
	/**
	 * Проверяет уникальность создаваемого процесса
	 */
	public function isLock() {
		return ($this->oLockFile && !flock($this->oLockFile, LOCK_EX|LOCK_NB));
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
	 * на пользовательскую функцию
	 *
	 * @param ( string|array ) $sFunction
	 * @param array $aArgs
	 */
	public function Exec() {
		/**
		 * Если выполнение процесса заблокирован, завершаемся
		 */
		if($this->isLock()) {
			throw new Exception('Try to exec already run process');
		}
		/**
		 * Здесь мы реализуем дополнительную логику:
		 * логирование вызова, обработка ошибок,
		 * буферизация вывода.
		 */
		ob_start();
		$this->Client();
		/**
		 * Получаем весь вывод функции.
		 */
		$sContent=ob_get_contents();
		ob_end_clean();
		
		return $sContent;
	}
	
	/**
	 * Здесь будет реализована логика завершения работы cron-процесса
	 */
	public function Shutdown() {
		$this->unsetLock();	
		$this->Log('Cron process ended');		
	}
	
	public function __destruct() {
		$this->Shutdown();
	}
	/**
	 * Клиентская функция будет переопределятся в наследниках класса
	 * для обеспечивания выполнения основного функционала.
	 */
	public function Client(){
		throw new Exception('Call undefined client function');
	}
}
?>