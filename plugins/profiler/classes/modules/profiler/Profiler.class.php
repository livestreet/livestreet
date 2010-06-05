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
 * Модуль статических страниц
 *
 */
class PluginProfiler_ModuleProfiler extends Module {		
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
		$this->oMapper=Engine::GetMapper(__CLASS__);
		$this->hLog = @fopen(Config::Get('path.root.server').'/logs/'.Config::Get('sys.logs.profiler_file'),'r+');
	}
	
	/**
	 * Добавить новую запись в базу данных
	 *
	 * @param  PluginProfiler_ModuleProfiler_EntityEntry $oEntry
	 * @return bool
	 */
	public function AddEntry(PluginProfiler_ModuleProfiler_EntityEntry $oEntry) {
		return $this->oMapper->AddEntry($oEntry);
	}
	
	/**
	 * Читает из лог-файла записи
	 *
	 * @param  string $sPath
	 * @return PluginProfiler_ModuleProfiler_EntityEntry
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

		return Engine::GetEntity('PluginProfiler_Profiler_Entry',$aTime);
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
	 * Получает профайл-отчет по идентификатору
	 * TODO: доработать система вывода записей в виде дерева
	 *
	 * @param  int $sId
	 * @return ProfileEntity_Report
	 */
	public function GetReportById($sId,$sPid=null) {
		$aReportRows=$this->oMapper->GetReportById($sId,$sPid);
		if(count($aReportRows)) {
			/**
			 * Если запрошена часть записей, отдельно получаем статистику общей выборки
			 */
			$aStat = !is_null($sPid)
			? $this->GetReportStatById($sId)
			: array(
					'count'     => 0,
					'query'     => 0,
					'modules'   => array(),
					'time_full' => 0
				);
			
			$oReport = Engine::GetEntity('PluginProfiler_Profiler_Report');
			$aEntries = $this->BuildEntriesRecursive($aReportRows);
			foreach ($aEntries as $oEntry) {
				$oReport->addEntry($oEntry);
				if(is_null($sPid)) {
					/**
					 * Заполняем статистику
					 */
					$aStat['count']++;
					$aStat['time_full']=max($aStat['time_full'],$oEntry->getTimeFull());
					if($oEntry->getName()=='query') $aStat['query']++;					
				}
			}
			
			$oReport->setStat($aStat);
			return $oReport;
		}
		return null;
	}
	
	/**
	 * Получает статистику данного отчета 
	 * (количество замеров, общее время, количество запросов к БД, используемые модули)
	 *
	 * @param  string $sId
	 * @return array
	 */
	public function GetReportStatById($sId) {
		$aStat = array(
			'count'     => 0,
			'query'     => 0,
			'modules'   => array(),
			'time_full' => 0
		);
		
		$aReportRows=$this->oMapper->GetReportStatById($sId);
		foreach ($aReportRows as $aEntry) {
			$aStat['count']++;
			$aStat['time_full']=max($aStat['time_full'],$aEntry['time_full']);
			/**
			 * Является ли запросом
			 */
			if($aEntry['time_name']=='query') $aStat['query']++;
		}
		return $aStat;
	}
	
	protected function BuildEntriesRecursive($aEntries,$bBegin=true) {
		static $aResultEntries;
		static $iLevel;
		if ($bBegin) {
			$aResultEntries=array();
			$iLevel=0;
		}
		foreach ($aEntries as $aEntry) {
			$aTemp=$aEntry;
			$aTemp['level']=$iLevel;
			unset($aTemp['childNodes']);
			$aResultEntries[]=Engine::GetEntity('PluginProfiler_Profiler_Entry',$aTemp);
			if (isset($aEntry['childNodes']) and count($aEntry['childNodes'])>0) {
				$iLevel++;
				$this->BuildEntriesRecursive($aEntry['childNodes'],false);
			}
		}
		$iLevel--;
		return $aResultEntries;
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