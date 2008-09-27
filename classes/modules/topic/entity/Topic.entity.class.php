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

class TopicEntity_Topic extends Entity 
{    
    public function getId() {
        return $this->_aData['topic_id'];
    }  
    public function getBlogId() {
        return $this->_aData['blog_id'];
    }
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getType() {
        return $this->_aData['topic_type'];
    }
    public function getTitle() {
        return $this->_aData['topic_title'];
    }
    public function getText() {
        return $this->_aData['topic_text'];
    }    
    public function getTextShort() {
        return $this->_aData['topic_text_short'];
    }
    public function getTextSource() {
        return $this->_aData['topic_text_source'];
    }
    public function getExtra() {
        return $this->_aData['topic_extra'];
    } 
    public function getTags() {
        return $this->_aData['topic_tags'];
    }
    public function getDateAdd() {
        return $this->_aData['topic_date_add'];
    }
    public function getDateEdit() {
        return $this->_aData['topic_date_edit'];
    }
    public function getUserIp() {
        return $this->_aData['topic_user_ip'];
    }
    public function getPublish() {
        return $this->_aData['topic_publish'];
    }
    public function getRating() {            
        return number_format(round($this->_aData['topic_rating'],2), 0, '.', '');
    }
    public function getCountVote() {
        return $this->_aData['topic_count_vote'];
    }
    public function getCountRead() {
        return $this->_aData['topic_count_read'];
    }
    public function getCountComment() {
        return $this->_aData['topic_count_comment'];
    }
    
    public function getTagsLink() {
    	$aTags=explode(',',$this->getTags());
    	foreach ($aTags as $key => $value) {
    		$aTags[$key]='<a href="'.DIR_WEB_ROOT.'/tag/'.htmlspecialchars($value).'/" class="smalltags">'.htmlspecialchars($value).'</a>';
    	}
        return trim(join(', ',$aTags));
    }
    public function getUserLogin() {
        return $this->_aData['user_login'];
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
    
    public function getBlogUrlFull() {
    	if ($this->getBlogType()=='personal') {
    		return DIR_WEB_ROOT.'/my/'.$this->getUserLogin().'/';
    	} else {
    		return DIR_WEB_ROOT.'/blog/'.$this->getBlogUrl().'/';
    	}
    }
    public function getUrl() {
    	if ($this->getBlogType()=='personal') {
    		return DIR_WEB_ROOT.'/blog/'.$this->getId().'.html';
    	} else {
    		return DIR_WEB_ROOT.'/blog/'.$this->getBlogUrl().'/'.$this->getId().'.html';
    	}
    }
    public function getUserIsVote() {
        return $this->_aData['user_is_vote'];
    }
    public function getUserVoteDelta() {
        return $this->_aData['user_vote_delta'];
    }
    

    
    
	public function setId($data) {
        $this->_aData['topic_id']=$data;
    }
    public function setBlogId($data) {
        $this->_aData['blog_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setType($data) {
        $this->_aData['topic_type']=$data;
    }
    public function setTitle($data) {
        $this->_aData['topic_title']=$data;
    }
    public function setText($data) {
        $this->_aData['topic_text']=$data;
    }    
    public function setExtra($data) {
        $this->_aData['topic_extra']=$data;
    }
    public function setTextShort($data) {
        $this->_aData['topic_text_short']=$data;
    }
    public function setTextSource($data) {
        $this->_aData['topic_text_source']=$data;
    }
    public function setTags($data) {
        $this->_aData['topic_tags']=$data;
    }
    public function setDateAdd($data) {
        $this->_aData['topic_date_add']=$data;
    }
    public function setDateEdit($data) {
        $this->_aData['topic_date_edit']=$data;
    }
    public function setUserIp($data) {
        $this->_aData['topic_user_ip']=$data;
    }
    public function setPublish($data) {
        $this->_aData['topic_publish']=$data;
    }
    public function setRating($data) {
        $this->_aData['topic_rating']=$data;
    }
    public function setCountVote($data) {
        $this->_aData['topic_count_vote']=$data;
    }
    public function setCountRead($data) {
        $this->_aData['topic_count_read']=$data;
    }
    public function setCountComment($data) {
        $this->_aData['topic_count_comment']=$data;
    }
}
?>