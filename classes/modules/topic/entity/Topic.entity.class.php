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

class ModuleTopic_EntityTopic extends Entity 
{    
	/**
	 * массив объектов(не всегда) для дополнительных типов топиков(линки, опросы, подкасты и т.п.)
	 *
	 * @var unknown_type
	 */
	protected $aExtra=null;
	
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
    	if (isset($this->_aData['topic_extra'])) {
        	return $this->_aData['topic_extra'];
    	}
    	return serialize('');
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
    public function getPublishDraft() {
        return $this->_aData['topic_publish_draft'];
    }
    public function getPublishIndex() {
        return $this->_aData['topic_publish_index'];
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
    public function getCutText() {
        return $this->_aData['topic_cut_text'];
    }
    public function getForbidComment() {
        return $this->_aData['topic_forbid_comment'];
    }
    public function getTextHash() {
        return $this->_aData['topic_text_hash'];
    }
    
    public function getTagsArray() {
    	return explode(',',$this->getTags());    	
    } 
    public function getCountCommentNew() {
        return $this->_aData['count_comment_new'];
    }  
    public function getDateRead() {
        return $this->_aData['date_read'];
    }  
    public function getUser() {
        return $this->_aData['user'];
    }
    public function getBlog() {
        return $this->_aData['blog'];
    }
    
    
    public function getUrl() {
    	if ($this->getBlog()->getType()=='personal') {
    		return Router::GetPath('blog').$this->getId().'.html';
    	} else {
    		return Router::GetPath('blog').$this->getBlog()->getUrl().'/'.$this->getId().'.html';
    	}
    }
    public function getVote() {
        return $this->_aData['vote'];
    }    
    public function getUserQuestionIsVote() {
        return $this->_aData['user_question_is_vote'];
    }	
    public function getIsFavourite() {
        return $this->_aData['topic_is_favourite'];
    }
    /***************************************************************************************************************************************************
     * методы расширения типов топика
     ***************************************************************************************************************************************************     
     */
    
    protected function extractExtra() {
    	if (is_null($this->aExtra)) {
    		$this->aExtra=unserialize($this->getExtra());
    	}
    }
    // методы для топика-ссылки
    public function getLinkUrl($bShort=false) {
    	if ($this->getType()!='link') {
    		return null;
    	}
    	$this->extractExtra();
    	if (isset($this->aExtra['url'])) {     		    		
    		if ($bShort) {
    			$sUrl=htmlspecialchars($this->aExtra['url']);
    			if (preg_match("/^http:\/\/(.*)$/i",$sUrl,$aMatch)) {
    				$sUrl=$aMatch[1];
    			}
    			$sUrlShort=substr($sUrl,0,30);
    			if (strlen($sUrlShort)!=strlen($sUrl)) {
    				return $sUrlShort.'...';
    			}
    			return $sUrl;
    		}
    		$sUrl=$this->aExtra['url'];
    		if (preg_match("/^http:\/\/(.*)$/i",$sUrl,$aMatch)) {
    			$sUrl=$aMatch[1];
    		}
    		return 'http://'.$sUrl;
    	}
    	return null;
    }    
    public function setLinkUrl($data) {
        if ($this->getType()!='link') {
    		return;
    	}
    	$this->extractExtra();
    	$this->aExtra['url']=$data;
    	$this->setExtra($this->aExtra);
    }
    public function getLinkCountJump() {
    	if ($this->getType()!='link') {
    		return null;
    	}
    	$this->extractExtra();
    	if (isset($this->aExtra['count_jump'])) {
    		return (int)$this->aExtra['count_jump'];
    	}
    	return 0;
    }
    public function setLinkCountJump($data) {
        if ($this->getType()!='link') {
    		return;
    	}
    	$this->extractExtra();
    	$this->aExtra['count_jump']=$data;
    	$this->setExtra($this->aExtra);
    }
    //методы для топика-вопроса
    public function addQuestionAnswer($data) {
    	if ($this->getType()!='question') {
    		return;
    	}
    	$this->extractExtra();
    	$this->aExtra['answers'][]=array('text'=>$data,'count'=>0);
    	$this->setExtra($this->aExtra);
    }
    public function clearQuestionAnswer() {
    	if ($this->getType()!='question') {
    		return;
    	}
    	$this->extractExtra();
    	$this->aExtra['answers']=array();
    	$this->setExtra($this->aExtra);
    }
    public function getQuestionAnswers() {
    	if ($this->getType()!='question') {
    		return null;
    	}
    	$this->extractExtra();
    	if (isset($this->aExtra['answers'])) {
    		return $this->aExtra['answers'];
    	}
    	return array();
    }
    public function increaseQuestionAnswerVote($sIdAnswer) {
    	if ($aAnswers=$this->getQuestionAnswers()) {
    		if (isset($aAnswers[$sIdAnswer])) {
    			$aAnswers[$sIdAnswer]['count']++;
    			$this->aExtra['answers']=$aAnswers;
    			$this->setExtra($this->aExtra);
    		}
    	}
    }
    public function getQuestionAnswerMax() {
    	$aAnswers=$this->getQuestionAnswers();
    	$iMax=0;
    	foreach ($aAnswers as $aAns) {
    		if ($aAns['count']>$iMax) {
    			$iMax=$aAns['count'];
    		}
    	}
    	return $iMax;
    }
    public function getQuestionAnswerPercent($sIdAnswer) {
    	if ($aAnswers=$this->getQuestionAnswers()) {
    		if (isset($aAnswers[$sIdAnswer])) {    			
    			$iCountAll=$this->getQuestionCountVote()-$this->getQuestionCountVoteAbstain();
    			if ($iCountAll==0) {
    				return 0;
    			} else {    				
    				return number_format(round($aAnswers[$sIdAnswer]['count']*100/$iCountAll,1), 1, '.', '');
    			}
    		}
    	}
    }
    public function getQuestionCountVote() {
    	if ($this->getType()!='question') {
    		return null;
    	}
    	$this->extractExtra();
    	if (isset($this->aExtra['count_vote'])) {
    		return (int)$this->aExtra['count_vote'];
    	}
    	return 0;
    }
    public function setQuestionCountVote($data) {
        if ($this->getType()!='question') {
    		return;
    	}
    	$this->extractExtra();
    	$this->aExtra['count_vote']=$data;
    	$this->setExtra($this->aExtra);
    }
    public function getQuestionCountVoteAbstain() {
    	if ($this->getType()!='question') {
    		return null;
    	}
    	$this->extractExtra();
    	if (isset($this->aExtra['count_vote_abstain'])) {
    		return (int)$this->aExtra['count_vote_abstain'];
    	}
    	return 0;
    }
    public function setQuestionCountVoteAbstain($data) {
        if ($this->getType()!='question') {
    		return;
    	}
    	$this->extractExtra();
    	$this->aExtra['count_vote_abstain']=$data;
    	$this->setExtra($this->aExtra);
    }
    
    
    
    //*************************************************************************************************************************************************
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
        $this->_aData['topic_extra']=serialize($data);
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
    public function setPublishDraft($data) {
        $this->_aData['topic_publish_draft']=$data;
    }
    public function setPublishIndex($data) {
        $this->_aData['topic_publish_index']=$data;
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
    public function setCutText($data) {
        $this->_aData['topic_cut_text']=$data;
    }
    public function setForbidComment($data) {
        $this->_aData['topic_forbid_comment']=$data;
    }
    public function setTextHash($data) {
        $this->_aData['topic_text_hash']=$data;
    }
    
    public function setUser($data) {
        $this->_aData['user']=$data;
    }
    public function setBlog($data) {
        $this->_aData['blog']=$data;
    }
    public function setUserQuestionIsVote($data) {
        $this->_aData['user_question_is_vote']=$data;
    }
    public function setVote($data) {
        $this->_aData['vote']=$data;
    }    
    public function setCountCommentNew($data) {
        $this->_aData['count_comment_new']=$data;
    }
    public function setDateRead($data) {
        $this->_aData['date_read']=$data;
    }
    public function setIsFavourite($data) {
        $this->_aData['topic_is_favourite']=$data;
    }
}
?>