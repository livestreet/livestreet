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

class PluginPage_Mapper_Page extends Mapper {
	
	public function AddPage(PluginPage_PageEntity_Page $oPage) {
		$sql = "INSERT INTO ".Config::Get('plugin.page.table.page')." 
			(page_pid,
			page_url,
			page_url_full,
			page_title,
			page_text,
			page_date_add,
			page_seo_keywords,
			page_seo_description,
			page_active			
			)
			VALUES(?, ?,	?,	?,  ?,  ?,  ?,  ?,  ?d)
		";			
		if ($iId=$this->oDb->query($sql,$oPage->getPid(),$oPage->getUrl(),$oPage->getUrlFull(),$oPage->getTitle(),$oPage->getText(),$oPage->getDateAdd(),$oPage->getSeoKeywords(),$oPage->getSeoDescription(),$oPage->getActive())) 
		{
			return $iId;
		}		
		return false;
	}
	
	public function UpdatePage(PluginPage_PageEntity_Page $oPage) {
		$sql = "UPDATE ".Config::Get('plugin.page.table.page')." 
			SET page_pid = ? ,
			page_url = ? ,
			page_url_full = ? ,
			page_title = ? ,
			page_text = ? ,
			page_date_edit = ? ,
			page_seo_keywords = ? ,
			page_seo_description = ? ,
			page_active	 = ? 		
			WHERE page_id = ?d
		";			
		if ($this->oDb->query($sql,$oPage->getPid(),$oPage->getUrl(),$oPage->getUrlFull(),$oPage->getTitle(),$oPage->getText(),$oPage->getDateEdit(),$oPage->getSeoKeywords(),$oPage->getSeoDescription(),$oPage->getActive(),$oPage->getId())) 
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
	
	public function GetPages() {
		$sql = "SELECT 
					*,					
					page_id as ARRAY_KEY,
					page_pid as PARENT_KEY
				FROM 
					".Config::Get('plugin.page.table.page')." 				
				ORDER by page_title asc;	
					";
		if ($aRows=$this->oDb->select($sql)) {
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
}
?>