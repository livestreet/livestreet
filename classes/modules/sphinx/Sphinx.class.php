<?php
require_once(Config::Get('path.root.engine').'/lib/external/Sphinx/sphinxapi.php');

/**
 * Модуль для работы с машиной полнотекстового поиска Sphinx
 *
 */
class ModuleSphinx extends Module {
	private $oSphinx = null;
	
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->InitSphinx();
	}
	
	protected function InitSphinx() {
		/**
		 * Получаем объект Сфинкса(из Сфинкс АПИ)
		 */
		$this->oSphinx = new SphinxClient();
		$this->oSphinx->SetServer(Config::Get('module.search.sphinx.host'), intval(Config::Get('module.search.sphinx.port')));
		/**
		 * Устанавливаем тип сортировки
		 */
		$this->oSphinx->SetSortMode(SPH_SORT_EXTENDED, "@weight DESC, @id DESc");
	}
	
	public function Shutdown() {
		
	}
	/**
	 * Возвращает число найденых элементов в зависимоти от их типа
	 *
	 * @param unknown_type $sTerms
	 * @param unknown_type $sObjType
	 * @param unknown_type $aExtraFilters
	 * @return unknown
	 */
	public function GetNumResultsByType($sTerms, $sObjType = 'topics', $aExtraFilters){
		$aResults = $this->FindContent($sTerms, $sObjType, 1, 1, $aExtraFilters);
		return $aResults['total_found'];
	}
	/**
	 * Непосредственно сам поиск
	 *
	 * @param unknown_type $sTerms
	 * @param unknown_type $sObjType
	 * @param unknown_type $iOffset
	 * @param unknown_type $iLimit
	 * @param unknown_type $aExtraFilters
	 * @return unknown
	 */
	public function FindContent($sTerms, $sObjType, $iOffset, $iLimit, $aExtraFilters){
		/**
		 * используем кеширование при поиске
		 */
		$cacheKey = Config::Get('module.search.entity_prefix')."searchResult_{$sObjType}_{$sTerms}_{$iOffset}_{$iLimit}";		
		if (false === ($data = $this->Cache_Get($cacheKey))) {
			/**
			 * Параметры поиска
			 */
			$this->oSphinx->SetMatchMode(SPH_MATCH_ALL);
			$this->oSphinx->SetLimits($iOffset, $iLimit);			
			/**
			 * Устанавливаем атрибуты поиска
			 */
			$this->oSphinx->ResetFilters();
			if(!is_null($aExtraFilters)){
				foreach($aExtraFilters AS $sAttribName => $sAttribValue){
					$this->oSphinx->SetFilter(
						$sAttribName, 
						(is_array($sAttribValue)) ? $sAttribValue : array($sAttribValue)
					);
				}
			}
			/**
			 * Ищем
			 */
			if(!is_array($data = $this->oSphinx->Query($sTerms, Config::Get('module.search.entity_prefix').$sObjType.'Index'))) {
				return FALSE; // Скорее всего недоступен демон searchd
			}				
			/**
			 * Если результатов нет, то и в кеш писать не стоит...
			 * хотя тут момент спорный
			 */
			if ($data['total'] > 0) {
				$this->Cache_Set($data, $cacheKey, array(), 60*15);
			}
		}
		return $data;
	}
	/**
	 * Получить ошибку при последнем обращении к поиску
	 *
	 * @return unknown
	 */
	public function GetLastError(){
		return $this->oSphinx->GetLastError();
	}
	/**
	 * Получаем сниппеты(превью найденых элементов)
	 *
	 * @param unknown_type $sText
	 * @param unknown_type $sIndex
	 * @param unknown_type $sTerms
	 * @param unknown_type $before_match
	 * @param unknown_type $after_match
	 * @return unknown
	 */
	public function GetSnippet($sText, $sIndex, $sTerms, $before_match, $after_match){
		$aReturn = $this->oSphinx->BuildExcerpts(array($sText), Config::Get('module.search.entity_prefix').$sIndex.'Index', $sTerms, array(
				'before_match' => $before_match, 
				'after_match' => $after_match, 
			)
		);
		return $aReturn[0];
	}
}
?>