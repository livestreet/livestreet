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
 * Ведение профайлинга
 *
 * @package engine.lib
 * @since 1.0
 */
class ProfilerSimple {
	/**
	 * Инстанция профайлера
	 *
	 * @var ProfilerSimple
	 */
	static protected $oInstance=null;
	/**
	 * Массив данных
	 *
	 * @var array
	 */
	protected $aTimes;
	/**
	 * Уникальный номер
	 *
	 * @var string
	 */
	protected $sRequestId;
	/**
	 * Счетчик
	 *
	 * @var int
	 */
	protected $iTimeId;
	/**
	 * Текущий родитель
	 *
	 * @var int|null
	 */
	protected $iTimePidCurrent=null;
	/**
	 * Статус активности профайлера
	 *
	 * @var bool
	 */
	protected $bEnable;
	/**
	 * Путь до файла лога профайлера
	 *
	 * @var string|null
	 */
	protected $sFileName=null;

	/**
	 * Инициализация
	 *
	 * @param string $sFileName	Путь до файла лога профайлера
	 * @param bool $bEnable	Статус активности
	 */
	protected function __construct($sFileName,$bEnable) {
		$this->bEnable=$bEnable;
		$this->sFileName=$sFileName;
		$this->sRequestId=func_generator(32);
		$this->iTimeId=0;
	}
	/**
	 * Ограничиваем объект только одним экземпляром
	 *
	 * @static
	 * @param null $sFileName	Путь до файла лога профайлера
	 * @param bool $bEnable	Статус активности
	 * @return ProfilerSimple
	 */
	static public function getInstance($sFileName=null,$bEnable=true) {
		if (isset(self::$oInstance)) {
			return self::$oInstance;
		} else {
			self::$oInstance= new self($sFileName,$bEnable);
			return self::$oInstance;
		}
	}
	/**
	 * Запуск подсчета времени выполнения операции
	 *
	 * @param string $sName	Название операции
	 * @param string $sComment	Описание
	 * @return bool|int
	 */
	public function Start($sName,$sComment='') {
		if (!$this->bEnable) {
			return false;
		}
		$this->iTimeId++;
		$this->aTimes[$this->sRequestId.$this->iTimeId]=array(
			'request_id' => $this->sRequestId,
			'time_id' => $this->iTimeId,
			'time_pid' => $this->iTimePidCurrent,
			'time_name' => $sName,
			'time_comment' => $sComment,
			'time_start' => microtime(),
		);
		$this->iTimePidCurrent=$this->iTimeId;
		return $this->iTimeId;
	}
	/**
	 * Завершение подсчета времени выполнения операции
	 *
	 * @param int $iTimeId	Номер операции
	 * @return bool
	 */
	public function Stop($iTimeId) {
		if (!$this->bEnable or !$iTimeId or !isset($this->aTimes[$this->sRequestId.$iTimeId])) {
			return false;
		}
		$this->aTimes[$this->sRequestId.$iTimeId]['time_stop']=microtime();
		$this->aTimes[$this->sRequestId.$iTimeId]['time_full']=$this->GetTimeFull($iTimeId);
		$this->iTimePidCurrent=$this->aTimes[$this->sRequestId.$iTimeId]['time_pid'];
	}
	/**
	 * Сохранение лога в файл
	 *
	 * @return bool
	 */
	public function Save() {
		if (!$this->bEnable or !$this->sFileName) {
			return false;
		}
		if ($fp=fopen($this->sFileName,"a")) {
			foreach ($this->aTimes as $aTime) {
				/**
				 * Проверяем есть ли "открытые" счетчики
				 */
				if (!isset($aTime['time_full'])) {
					$this->Stop($aTime['time_id']);
					$aTime=$this->aTimes[$aTime['request_id'].$aTime['time_id']];
				}

				if(!isset($aTime['time_pid'])) $aTime['time_pid']=0;
				if(isset($aTime['time_comment']) and $aTime['time_comment']!='') {
					$aTime['time_comment'] = preg_replace('/\s{1,}/',' ',$aTime['time_comment']);
				}
				$s=date('Y-m-d H:i:s')."\t{$aTime['request_id']}\t{$aTime['time_full']}\t{$aTime['time_start']}\t{$aTime['time_stop']}\t{$aTime['time_id']}\t{$aTime['time_pid']}\t{$aTime['time_name']}\t{$aTime['time_comment']}\r\n";
				fwrite($fp,$s);
			}
			fclose($fp);
		}
	}
	/**
	 * Сохраняем лог при завершении работы
	 */
	public function __destruct() {
		$this->Save();
	}
	/**
	 * Вычисляет полное время замера
	 *
	 * @param  int   $iTimeId	Номер операции
	 * @return float
	 */
	protected function GetTimeFull($iTimeId) {
		list($iStartSeconds,$iStartGeneral)=explode(' ',$this->aTimes[$this->sRequestId.$iTimeId]['time_start'],2);
		list($iStopSeconds,$iStopGeneral)=explode(' ',$this->aTimes[$this->sRequestId.$iTimeId]['time_stop'],2);

		return ($iStopSeconds-$iStartSeconds)+($iStopGeneral-$iStartGeneral);
	}
}
?>