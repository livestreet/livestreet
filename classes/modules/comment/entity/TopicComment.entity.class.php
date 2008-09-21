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

class CommentEntity_TopicComment extends Entity 
{    
    public function getId() {
        return $this->_aData['comment_id'];
    } 
    public function getPid() {
        return $this->_aData['comment_pid'];
    } 
    public function getTopicId() {
        return $this->_aData['topic_id'];
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
    
    public function getUserLogin() {
        return $this->_aData['user_login'];
    }
    public function getLevel() {
        return $this->_aData['level'];
    }
	public function getUserProfileAvatar() {
        return $this->_aData['user_profile_avatar'];
    }
    public function getUserProfileAvatarType() {
        return $this->_aData['user_profile_avatar_type'];
    }
    public function getUserProfileAvatarPath($iSize=100) {     	  
    	if ($this->getUserProfileAvatar()) { 	
        	return DIR_WEB_ROOT.DIR_UPLOADS_IMAGES.'/'.$this->getUserId().'/avatar_'.$iSize.'x'.$iSize.'.'.$this->getUserProfileAvatarType();
    	} else {
    		return DIR_STATIC_SKIN.'/img/avatar_'.$iSize.'x'.$iSize.'.jpg';
    	}
    }
	public function getTopicTitle() {
        return $this->_aData['topic_title'];
    }
    public function getTopicCountComment() {
        return $this->_aData['topic_count_comment'];
    }
    public function getBlogType() {
        return $this->_aData['blog_type'];
    }
    public function getBlogUrl() {
        return $this->_aData['blog_url'];
    }
    public function getBlogTitle() {
        return $this->_aData['blog_title'];
    }
    public function getBlogOwnerLogin() {
    	return $this->_aData['blog_owner_login'];
    }
    public function getBlogUrlFull() {
    	if ($this->getBlogType()=='personal') {
    		return DIR_WEB_ROOT.'/my/'.$this->getBlogOwnerLogin().'/';
    	} else {
    		return DIR_WEB_ROOT.'/blog/'.$this->getBlogUrl().'/';
    	}
    }
    public function getTopicUrl() {
    	if ($this->getBlogType()=='personal') {
    		return DIR_WEB_ROOT.'/blog/'.$this->getTopicId().'.html';
    	} else {
    		return DIR_WEB_ROOT.'/blog/'.$this->getBlogUrl().'/'.$this->getTopicId().'.html';
    	}
    }
    public function getUserIsVote() {
        return $this->_aData['user_is_vote'];
    }
    public function getUserVoteDelta() {
        return $this->_aData['user_vote_delta'];
    }    
    public function isBad() {    	
        if ($this->getRating()<=BLOG_COMMENT_BAD) {
        	return true;
        } 
        return false;
    }
    
    
    
    
	public function setId($data) {
        $this->_aData['comment_id']=$data;
    }
    public function setPid($data) {
        $this->_aData['comment_pid']=$data;
    }
    public function setTopicId($data) {
        $this->_aData['topic_id']=$data;
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

}
?>