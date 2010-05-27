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

class ModuleComment_EntityComment extends Entity 
{    
    public function getId() {
        return $this->_aData['comment_id'];
    } 
    public function getPid() {
        return $this->_aData['comment_pid'];
    } 
    public function getTargetId() {
        return $this->_aData['target_id'];
    }
    public function getTargetType() {
        return $this->_aData['target_type'];
    }
    public function getTargetParentId() {
        return (array_key_exists('target_parent_id',$this->_aData)) ? $this->_aData['target_parent_id'] : 0;
    }
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getText() {
        return $this->_aData['comment_text'];
    }
    public function getDate() {
        return $this->_aData['comment_date'];
    }
    public function getUserIp() {
        return $this->_aData['comment_user_ip'];
    }    
    public function getRating() {        
        return number_format(round($this->_aData['comment_rating'],2), 0, '.', '');
    }
    public function getCountVote() {
        return $this->_aData['comment_count_vote'];
    }
    public function getDelete() {
        return $this->_aData['comment_delete'];
    }
    public function getPublish() {
        return $this->_aData['comment_publish'];
    }
    public function getTextHash() {
        return $this->_aData['comment_text_hash'];
    }
    
        
    public function getLevel() {
        return $this->_aData['level'];
    }   
    public function isBad() {    	
        if ($this->getRating()<=Config::Get('module.comment.bad')) {
        	return true;
        } 
        return false;
    }
    public function getUser() {
        return $this->_aData['user'];
    }
    public function getTarget() {
        return $this->_aData['target'];
    }
    public function getVote() {
        return $this->_aData['vote'];
    }
     public function getIsFavourite() {
        return $this->_aData['comment_is_favourite'];
    }   
    
    
    
	public function setId($data) {
        $this->_aData['comment_id']=$data;
    }
    public function setPid($data) {
        $this->_aData['comment_pid']=$data;
    }
    public function setTargetId($data) {
        $this->_aData['target_id']=$data;
    }
    public function setTargetType($data) {
        $this->_aData['target_type']=$data;
    }
    public function setTargetParentId($data) {
    	$this->_aData['target_parent_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setText($data) {
        $this->_aData['comment_text']=$data;
    }
    public function setDate($data) {
        $this->_aData['comment_date']=$data;
    }
    public function setUserIp($data) {
        $this->_aData['comment_user_ip']=$data;
    }    
    public function setRating($data) {
        $this->_aData['comment_rating']=$data;
    }
    public function setCountVote($data) {
        $this->_aData['comment_count_vote']=$data;
    }
    public function setDelete($data) {
        $this->_aData['comment_delete']=$data;
    }
    public function setPublish($data) {
        $this->_aData['comment_publish']=$data;
    }
	public function setTextHash($data) {
        $this->_aData['comment_text_hash']=$data;
    }
    
    
    public function setLevel($data) {
        $this->_aData['level']=$data;
    }
    public function setUser($data) {
        $this->_aData['user']=$data;
    }
    public function setTarget($data) {
        $this->_aData['target']=$data;
    }
    public function setVote($data) {
        $this->_aData['vote']=$data;
    }  
    public function setIsFavourite($data) {
        $this->_aData['comment_is_favourite']=$data;
    }      
}
?>