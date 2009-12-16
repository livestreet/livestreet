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

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
require_once('mapper/Profiler.mapper.class.php');

/**
 * Модуль статических страниц
 *
 */
class LsProfiler extends Module {		
	/**
	 * Меппер для сохранения логов в базу данных и формирования выборок по данным из базы
	 *
	 * @var Mapper_Profiler
	 */
	protected $oMapper;

	/**
	 * Хендлер открытого файла лога
	 *
	 * @var resource
	 */
	protected $hLog;
	
	/**
	 * @var string
	 */
	protected $sDataDelimiter = "\t";
	
	/**
	 * Инициализация модуля
	 */
	public function Init() {
		$this->oMapper=new Mapper_Profiler($this->Database_GetConnect());
		$this->hLog = @fopen(Config::Get('path.root.server').'/logs/'.Config::Get('sys.logs.profiler_file'),'r+');
	}
	
	/**
	 * Добавить новую запись в базу данных
	 *
	 * @param  ProfilerEntity_Entry $oEntry
	 * @return bool
	 */
	public function AddEntry(ProfilerEntity_Entry $oEntry) {
		return $this->oMapper->AddEntry($oEntry);
	}
	
	/**
	 * Читает из лог-файла записи
	 *
	 * @param  string $sPath
	 * @return ProfilerEntity_Entry
	 */
	public function ReadEntry() {
		/**
		 * Если хендлер не определен, или лог закончен, вовращаем null
		 */
		if(!$this->hLog or feof($this->hLog)) return null;
		/**
		 * Читаем следующую строку и формируем объект Entry
		 */
		$sLine=fgets($this->hLog);
		if(!$sLine) return null;
		
		$aTime = array();
		list(
			  $aTime['request_date'],$aTime['request_id'],$aTime['time_full'],
			  $aTime['time_start'],$aTime['time_stop'],$aTime['time_id'],
			  $aTime['time_pid'],$aTime['time_name'],$aTime['time_comment']
			)=explode($this->sDataDelimiter,$sLine,9);

		return Engine::GetEntity('Profiler_Entry',$aTime);
	}
	
	/**
	 * Выгружает записи из лога в базу данных
	 *
	 * @param  string $sDateStart
	 * @param  string $sPath
	 * @return bool|int
	 */
	public function UploadLog($sDateStart,$sPath=null) {
		if($sPath) $this->hLog = @fopen($sPath,'r+');
		if(!$this->hLog) return null;
		
		rewind($this->hLog);
		
		$iCount=0;
		while($oEntry=$this->ReadEntry()) {
			if(strtotime($oEntry->getDate())>strtotime($sDateStart)){ 
				$this->AddEntry($oEntry);
				$iCount++; 
			}
			unset($oEntry);
		}
		return $iCount;
	}
	
	/**
	 * Получает дату последней записи профайлера в базе данных
	 *
	 * @return string
	 */
	public function GetDatabaseStat() {
		return $this->oMapper->GetDatabaseStat();
	}
	
	/**
	 * Очищает файл лога
	 *
	 * @return bool
	 */
	public function EraseLog() {
		
	}
	
	/**
	 * Получает записи профайлера из базы данных, группированных по уровню "Report"
	 * TODO: Реализовать кеширование данных
	 * 
	 * @param  array $aFilter
	 * @param  int   $iPage
	 * @param  int   $iPerPage
	 * @return array
	 */
	public function GetReportsByFilter($aFilter,$iPage,$iPerPage) {
		$data=array(
			'collection'=>$this->oMapper->GetReportsByFilter($aFilter,$iCount,$iPage,$iPerPage),
			'count'=>$iCount
		);

		return $data;	
	}
	
	/**
	 * Удаление отчетов из базы данных
	 * TODO: Добавить обработку кеша данных
	 * 
	 * @param  array|int $aIds
	 * @return bool
	 */
	public function DeleteEntryByRequestId($aIds) {
		if(!is_array($aIds)) $aIds = array($aIds);
		return $this->oMapper->DeleteEntryByRequestId($aIds);
	}
}
?>