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

class PluginPage_ModulePage_MapperPage extends Mapper {

	public function AddPage(PluginPage_ModulePage_EntityPage $oPage) {
		$sql = "INSERT INTO ".Config::Get('plugin.page.table.page')." 
			(page_pid,
			page_url,
			page_url_full,
			page_title,
			page_text,
			page_date_add,
			page_seo_keywords,
			page_seo_description,
			page_active,			
			page_main,			
			page_sort,			
			page_auto_br
			)
			VALUES(?, ?,	?,	?,  ?,  ?,  ?,  ?,  ?d,  ?d,  ?d,  ?d)
		";
		if ($iId=$this->oDb->query($sql,$oPage->getPid(),$oPage->getUrl(),$oPage->getUrlFull(),$oPage->getTitle(),$oPage->getText(),$oPage->getDateAdd(),$oPage->getSeoKeywords(),$oPage->getSeoDescription(),$oPage->getActive(),$oPage->getMain(),$oPage->getSort(),$oPage->getAutoBr()))
		{
			return $iId;
		}
		return false;
	}

	public function UpdatePage(PluginPage_ModulePage_EntityPage $oPage) {
		$sql = "UPDATE ".Config::Get('plugin.page.table.page')." 
			SET page_pid = ? ,
			page_url = ? ,
			page_url_full = ? ,
			page_title = ? ,
			page_text = ? ,
			page_date_edit = ? ,
			page_seo_keywords = ? ,
			page_seo_description = ? ,
			page_active	 = ?, 		
			page_main	 = ?,		
			page_sort	 = ?, 		
			page_auto_br	 = ?
			WHERE page_id = ?d
		";
		if ($this->oDb->query($sql,$oPage->getPid(),$oPage->getUrl(),$oPage->getUrlFull(),$oPage->getTitle(),$oPage->getText(),$oPage->getDateEdit(),$oPage->getSeoKeywords(),$oPage->getSeoDescription(),$oPage->getActive(),$oPage->getMain(),$oPage->getSort(),$oPage->getAutoBr(),$oPage->getId()))
		{
			return true;
		}
		return false;
	}

	public function SetPagesPidToNull() {
		$sql = "UPDATE ".Config::Get('plugin.page.table.page')." 
			SET 
				page_pid = null,
				page_url_full = page_url 			 				
		";
		if ($this->oDb->query($sql))
		{
			return true;
		}
		return false;
	}

	public function GetPageByUrlFull($sUrlFull,$iActive) {
		$sql = "SELECT * FROM ".Config::Get('plugin.page.table.page')." WHERE page_url_full = ? and page_active = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sUrlFull,$iActive)) {
			return Engine::GetEntity('PluginPage_Page',$aRow);
		}
		return null;
	}

	public function GetPageById($sId) {
		$sql = "SELECT * FROM ".Config::Get('plugin.page.table.page')." WHERE page_id = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sId)) {
			return Engine::GetEntity('PluginPage_Page',$aRow);
		}
		return null;
	}

	public function deletePageById($sId) {
		$sql = "DELETE FROM ".Config::Get('plugin.page.table.page')." WHERE page_id = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sId)) {
			return true;
		}
		return false;
	}

	public function GetPages($aFilter) {
		$sPidNULL='';
		if (array_key_exists('pid',$aFilter) and is_null($aFilter['pid'])) {
			$sPidNULL='and page_pid IS NULL';
		}
		$sql = "SELECT 
					*,					
					page_id as ARRAY_KEY,
					page_pid as PARENT_KEY
				FROM 
					".Config::Get('plugin.page.table.page')." 
				WHERE 
					1=1
					{ and page_active = ?d }					
					{ and page_main = ?d }	
					{ and page_pid = ? } {$sPidNULL}				
				ORDER by page_sort desc;	
					";
		if ($aRows=$this->oDb->select($sql,
									  isset($aFilter['active']) ? $aFilter['active']:DBSIMPLE_SKIP,
									  isset($aFilter['main']) ? $aFilter['main']:DBSIMPLE_SKIP,
									  (array_key_exists('pid',$aFilter) and !is_null($aFilter['pid'])) ? $aFilter['pid'] : DBSIMPLE_SKIP
		)) {
			return $aRows;
		}
		return null;
	}

	public function GetCountPage() {
		$sql = "SELECT count(*) as count FROM ".Config::Get('plugin.page.table.page')." ";
		if ($aRow=$this->oDb->selectRow($sql)) {
			return $aRow['count'];
		}
		return null;
	}

	public function GetPagesByPid($sPid) {
		$sql = "SELECT 
					*				
				FROM 
					".Config::Get('plugin.page.table.page')." 				
				WHERE 
					page_pid = ? ";
		$aResult=array();
		if ($aRows=$this->oDb->select($sql,$sPid)) {
			foreach ($aRows as $aRow) {
				$aResult[]=Engine::GetEntity('PluginPage_Page',$aRow);
			}
		}
		return $aResult;
	}

	public function GetNextPageBySort($iSort,$sPid,$sWay) {
		if ($sWay=='up') {
			$sWay='>';
			$sOrder='asc';
		} else {
			$sWay='<';
			$sOrder='desc';
		}
		$sPidNULL='';
		if (is_null($sPid)) {
			$sPidNULL='page_pid IS NULL and';
		}
		$sql = "SELECT * FROM ".Config::Get('plugin.page.table.page')." WHERE { page_pid = ? and } {$sPidNULL} page_sort {$sWay} ? order by page_sort {$sOrder} limit 0,1";
		if ($aRow=$this->oDb->selectRow($sql,is_null($sPid) ? DBSIMPLE_SKIP : $sPid, $iSort)) {
			return Engine::GetEntity('PluginPage_Page',$aRow);
		}
		return null;
	}

	public function GetMaxSortByPid($sPid) {
		$sPidNULL='';
		if (is_null($sPid)) {
			$sPidNULL='and page_pid IS NULL';
		}
		$sql = "SELECT max(page_sort) as max_sort FROM ".Config::Get('plugin.page.table.page')." WHERE 1=1 { and page_pid = ? } {$sPidNULL} ";
		if ($aRow=$this->oDb->selectRow($sql,is_null($sPid) ? DBSIMPLE_SKIP : $sPid)) {
			return $aRow['max_sort'];
		}
		return 0;
	}


	/**
	 * List of active pages
	 *
	 * @param integer $iCount
	 * @param integer $iCurrPage
	 * @param integer $iPerPage
	 * @return array
	 */
	public function getListOfActivePages(&$iCount, $iCurrPage, $iPerPage) {
		$sql = 'SELECT
                    `page`.*
                FROM
                    `' . Config::Get('plugin.page.table.page') . '` AS `page`
                WHERE
                    `page`.`page_active` = 1
                ORDER BY
                    `page`.`page_id` ASC
                LIMIT
                    ?d, ?d
                ';
		$aPages = array();
		if ($aRows = $this->oDb->selectPage($iCount, $sql, ($iCurrPage - 1) * $iPerPage, $iPerPage)) {
			foreach ($aRows as $aPage) {
				$aPages[] = Engine::GetEntity('PluginPage_Page', $aPage);
			}
		}
		return $aPages;
	}

	/**
	 * Count of active pages
	 *
	 * @return integer
	 */
	public function getCountOfActivePages() {
		$sql = 'SELECT
                    COUNT(`page`.`page_id`)
                FROM
                    `' . Config::Get('plugin.page.table.page') . '` AS `page`
                WHERE
                    `page`.`page_active` = 1
                ';

		return $this->oDb->selectCell($sql);
	}

}
?>