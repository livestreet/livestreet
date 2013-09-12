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
class PluginPage_ModulePage extends Module {
	protected $oMapper;
	protected $aRebuildIds=array();

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		$this->oMapper=Engine::GetMapper(__CLASS__);
	}
	/**
	 * Добавляет страницу
	 *
	 * @param PageEntity_Page $oPage
	 * @return unknown
	 */
	public function AddPage(PluginPage_ModulePage_EntityPage $oPage) {
		if ($sId=$this->oMapper->AddPage($oPage)) {
			$oPage->setId($sId);
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('page_change',"page_change_{$oPage->getId()}","page_change_urlfull_{$oPage->getUrlFull()}"));
			return true;
		}
		return false;
	}
	/**
	 * Обновляет страницу
	 *
	 * @param PageEntity_Page $oPage
	 * @return unknown
	 */
	public function UpdatePage(PluginPage_ModulePage_EntityPage $oPage) {
		if ($this->oMapper->UpdatePage($oPage)) {
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('page_change',"page_change_{$oPage->getId()}","page_change_urlfull_{$oPage->getUrlFull()}"));
			return true;
		}
		return false;
	}
	/**
	 * Получает страницу по полному УРЛу
	 *
	 * @param unknown_type $sUrlFull
	 */
	public function GetPageByUrlFull($sUrlFull,$iActive=1) {
		if (false === ($data = $this->Cache_Get("page_{$sUrlFull}_{$iActive}"))) {
			$data = $this->oMapper->GetPageByUrlFull($sUrlFull,$iActive);
			if ($data) {
				$this->Cache_Set($data, "page_{$sUrlFull}_{$iActive}", array("page_change_{$data->getId()}"), 60*60*24*5);
			} else {
				$this->Cache_Set($data, "page_{$sUrlFull}_{$iActive}", array("page_change_urlfull_{$sUrlFull}"), 60*60*24*5);
			}
		}
		return $data;
	}
	/**
	 * Получает страницу по её айдишнику
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function GetPageById($sId) {
		return $this->oMapper->GetPageById($sId);
	}
	/**
	 * Получает список всех страниц ввиде дерева
	 *
	 * @return unknown
	 */
	public function GetPages($aFilter=array()) {
		$aPages=array();
		$aPagesRow=$this->oMapper->GetPages($aFilter);
		if (count($aPagesRow)) {
			$aPages=$this->BuildPagesRecursive($aPagesRow);
		}
		return $aPages;
	}
	/**
	 * Строит дерево страниц
	 *
	 * @param unknown_type $aPages
	 * @param unknown_type $bBegin
	 * @return unknown
	 */
	protected function BuildPagesRecursive($aPages,$bBegin=true) {
		static $aResultPages;
		static $iLevel;
		if ($bBegin) {
			$aResultPages=array();
			$iLevel=0;
		}
		foreach ($aPages as $aPage) {
			$aTemp=$aPage;
			$aTemp['level']=$iLevel;
			unset($aTemp['childNodes']);
			$aResultPages[]=Engine::GetEntity('PluginPage_Page',$aTemp);
			if (isset($aPage['childNodes']) and count($aPage['childNodes'])>0) {
				$iLevel++;
				$this->BuildPagesRecursive($aPage['childNodes'],false);
			}
		}
		$iLevel--;

		return $aResultPages;
	}
	/**
	 * Рекурсивно обновляет полный URL у всех дочерних страниц(веток)
	 *
	 * @param unknown_type $oPageStart
	 */
	public function RebuildUrlFull($oPageStart) {
		$aPages=$this->GetPagesByPid($oPageStart->getId());
		foreach ($aPages as $oPage) {
			if ($oPage->getId()==$oPageStart->getId()) {
				continue;
			}
			if (in_array($oPage->getId(),$this->aRebuildIds)) {
				continue;
			}
			$this->aRebuildIds[]=$oPage->getId();
			$oPage->setUrlFull($oPageStart->getUrlFull().'/'.$oPage->getUrl());
			$this->UpdatePage($oPage);
			$this->RebuildUrlFull($oPage);
		}
	}
	/**
	 * Получает список дочерних страниц первого уровня
	 *
	 * @param unknown_type $sPid
	 * @return unknown
	 */
	public function GetPagesByPid($sPid) {
		return $this->oMapper->GetPagesByPid($sPid);
	}
	/**
	 * Удаляет страницу по её айдишнику
	 * Если тип таблиц БД InnoDB, то удалятся и все дочернии страницы
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function deletePageById($sId) {
		if ($this->oMapper->deletePageById($sId)) {
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('page_change',"page_change_{$sId}"));
			return true;
		}
		return false;
	}
	/**
	 * Получает число статических страниц
	 *
	 * @return unknown
	 */
	public function GetCountPage() {
		return $this->oMapper->GetCountPage();
	}
	/**
	 * Устанавливает ВСЕМ страницам PID = NULL
	 * Это бывает нужно, когда особо "умный" админ зациклит страницы сами на себя..
	 *
	 * @return unknown
	 */
	public function SetPagesPidToNull() {
		return $this->oMapper->SetPagesPidToNull();
	}
	/**
	 * Получает слудующую по сортировке страницу
	 *
	 * @param unknown_type $iSort
	 * @param unknown_type $sWay
	 * @return unknown
	 */
	public function GetNextPageBySort($iSort,$sPid,$sWay='up') {
		return $this->oMapper->GetNextPageBySort($iSort,$sPid,$sWay);
	}
	/**
	 * Получает значение максимальной сртировки
	 *
	 * @return unknown
	 */
	public function GetMaxSortByPid($sPid) {
		return $this->oMapper->GetMaxSortByPid($sPid);
	}

	/**
	 * Get count of pages
	 *
	 * @return integer
	 */
	public function getCountOfActivePages() {
		return (int)$this->oMapper->getCountOfActivePages();
	}

	/**
	 * Get list of active pages
	 *
	 * @param integer $iCount
	 * @param integer $iCurrPage
	 * @param integer $iPerPage
	 * @return array
	 */
	public function getListOfActivePages(&$iCount, $iCurrPage, $iPerPage) {
		return $this->oMapper->getListOfActivePages($iCount, $iCurrPage, Config::Get('plugin.sitemap.objects_per_page'));
	}

}
?>