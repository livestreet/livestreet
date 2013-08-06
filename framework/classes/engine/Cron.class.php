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
 * Абстрактный класс для работы с крон-процессами.
 * Например, его использует отложенная рассылка почтовых уведомлений для пользователей.
 * Обработчик крона не запускается автоматически(!!), его необходимо добавлять в системный крон (nix*: crontab -e)
 *
 * @package engine
 * @since 1.0
 */
class Cron extends LsObject {
	/**
	 * Объект ядра
	 *
	 * @var Engine
	 */
	protected $oEngine=null;
	/**
	 * Дескриптор блокирующего файла
	 * Если этот файл существует, то крон не запустится повторно.
	 *
	 * @var string
	 */
	protected $oLockFile=null;
	/**
	 * Имя процесса, под которым будут помечены все сообщения в логах
	 *
	 * @var string
	 */
	protected $sProcessName;

	/**
	 * @param string|null $sLockFile Полный путь до лок файла, например <pre>Config::Get('sys.cache.dir').'notify.lock'</pre>
	 */
	public function __construct($sLockFile=null) {
		$this->sProcessName=get_class($this);
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
	 * @param  string $sMsg	Сообщение для записи в лог
	 */
	public function Log($sMsg) {
		if (Config::Get('sys.logs.cron')) {
			$sMsg=$this->sProcessName.': '.$sMsg;
			$this->oEngine->Logger_Notice($sMsg);
		}
	}
	/**
	 * Проверяет уникальность создаваемого процесса
	 *
	 * @return bool
	 */
	public function isLock() {
		return ($this->oLockFile && !flock($this->oLockFile, LOCK_EX|LOCK_NB));
	}
	/**
	 * Снимает блокировку на повторный процесс
	 *
	 * @return bool
	 */
	public function unsetLock() {
		return ($this->oLockFile && @flock($this->oLockFile, LOCK_UN));
	}
	/**
	 * Основной метод крон-процесса.
	 * Реализует логику работы крон процесса с последующей передачей управления на пользовательскую функцию
	 *
	 * @return string
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
	 * Завершение крон-процесса
	 */
	public function Shutdown() {
		$this->unsetLock();	
		$this->Log('Cron process ended');		
	}
	/**
	 * Вызывается при уничтожении объекта
	 */
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
	/**
	 * Ставим хук на вызов неизвестного метода и считаем что хотели вызвать метод какого либо модуля
	 * @see Engine::_CallModule
	 *
	 * @param string $sName Имя метода
	 * @param array $aArgs Аргументы
	 * @return mixed
	 */
	public function __call($sName,$aArgs) {
		return $this->oEngine->_CallModule($sName,$aArgs);
	}
}
?>