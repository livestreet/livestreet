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

class PluginPage_ModulePage_EntityPage extends Entity 
{    
    public function getId() {
        return $this->_aData['page_id'];
    } 
    public function getPid() {
        return $this->_aData['page_pid'];
    }
    public function getUrl() {
        return $this->_aData['page_url'];
    }
    public function getUrlFull() {
        return $this->_aData['page_url_full'];
    }
    public function getTitle() {
        return $this->_aData['page_title'];
    }
    public function getText() {
        return $this->_aData['page_text'];
    }
    public function getDateAdd() {
        return $this->_aData['page_date_add'];
    }
    public function getDateEdit() {
        return $this->_aData['page_date_edit'];
    }
    public function getSeoKeywords() {
        return $this->_aData['page_seo_keywords'];
    }
    public function getSeoDescription() {
        return $this->_aData['page_seo_description'];
    }
    public function getActive() {
        return $this->_aData['page_active'];
    }
    public function getMain() {
        return $this->_aData['page_main'];
    }
    public function getSort() {
        return $this->_aData['page_sort'];
    }     
    
    public function getLevel() {
        return $this->_aData['level'];
    }
    
      
     
    
	public function setId($data) {
        $this->_aData['page_id']=$data;
    }   
    public function setPid($data) {
        $this->_aData['page_pid']=$data;
    }
    public function setUrl($data) {
        $this->_aData['page_url']=$data;
    }
    public function setUrlFull($data) {
        $this->_aData['page_url_full']=$data;
    }
    public function setTitle($data) {
        $this->_aData['page_title']=$data;
    }
    public function setText($data) {
        $this->_aData['page_text']=$data;
    }
    public function setDateAdd($data) {
        $this->_aData['page_date_add']=$data;
    }
    public function setDateEdit($data) {
        $this->_aData['page_date_edit']=$data;
    }
    public function setSeoKeywords($data) {
        $this->_aData['page_seo_keywords']=$data;
    }
    public function setSeoDescription($data) {
        $this->_aData['page_seo_description']=$data;
    }
    public function setActive($data) {
        $this->_aData['page_active']=$data;
    }
    public function setMain($data) {
        $this->_aData['page_main']=$data;
    }
    public function setSort($data) {
        $this->_aData['page_sort']=$data;
    }
}
?>