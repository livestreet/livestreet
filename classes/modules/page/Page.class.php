<?
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
require_once('mapper/Page.mapper.class.php');

/**
 * Модуль статических страниц
 *
 */
class Page extends Module {		
	protected $oMapper;
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->oMapper=new Mapper_Page($this->Database_GetConnect());
	}
	/**
	 * Добавляет страницу
	 *
	 * @param PageEntity_Page $oPage
	 * @return unknown
	 */
	public function AddPage(PageEntity_Page $oPage) {
		return $this->oMapper->AddPage($oPage);
	}
	/**
	 * Обновляет страницу
	 *
	 * @param PageEntity_Page $oPage
	 * @return unknown
	 */
	public function UpdatePage(PageEntity_Page $oPage) {
		return $this->oMapper->UpdatePage($oPage);
	}	
	/**
	 * Получает страницу по полному УРЛу
	 *
	 * @param unknown_type $sUrlFull
	 */
	public function GetPageByUrlFull($sUrlFull,$iActive=1) {
		return $this->oMapper->GetPageByUrlFull($sUrlFull,$iActive);
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
	public function GetPages() {
		$aPages=array();
		$aPagesRow=$this->oMapper->GetPages();	
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
			$aResultCommnets=array();
			$iLevel=0;
		}		
		foreach ($aPages as $aPage) {
			$aTemp=$aPage;
			$aTemp['level']=$iLevel;
			unset($aTemp['childNodes']);
			$aResultPages[]=new PageEntity_Page($aTemp);			
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
		return $this->oMapper->deletePageById($sId);
	}
}
?>