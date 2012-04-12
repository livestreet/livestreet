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

class ModuleTopic_EntityTopic extends Entity {

	/**
	 * Определяем правила валидации
	 */
	public function Init() {
		parent::Init();
		$this->aValidateRules[]=array('topic_title','string','max'=>200,'min'=>2,'allowEmpty'=>false,'label'=>$this->Lang_Get('topic_create_title'),'on'=>array('topic','link','photoset'));
		$this->aValidateRules[]=array('topic_title','string','max'=>200,'min'=>2,'allowEmpty'=>false,'label'=>$this->Lang_Get('topic_question_create_title'),'on'=>array('question'));
		$this->aValidateRules[]=array('topic_text_source','string','max'=>Config::Get('module.topic.max_length'),'min'=>2,'allowEmpty'=>false,'label'=>$this->Lang_Get('topic_create_text'),'on'=>array('topic','photoset'));
		$this->aValidateRules[]=array('topic_text_source','string','max'=>500,'min'=>10,'allowEmpty'=>false,'label'=>$this->Lang_Get('topic_create_text'),'on'=>array('link'));
		$this->aValidateRules[]=array('topic_text_source','string','max'=>500,'allowEmpty'=>true,'label'=>$this->Lang_Get('topic_create_text'),'on'=>array('question'));
		$this->aValidateRules[]=array('topic_tags','tags','count'=>15,'label'=>$this->Lang_Get('topic_create_tags'),'on'=>array('topic','link','question','photoset'));
		$this->aValidateRules[]=array('blog_id','blog_id','on'=>array('topic','link','question','photoset'));
		$this->aValidateRules[]=array('topic_text_source','topic_unique','on'=>array('topic','link','question','photoset'));
		$this->aValidateRules[]=array('topic_type','topic_type','on'=>array('topic','link','question','photoset'));
		$this->aValidateRules[]=array('link_url','url','allowEmpty'=>false,'label'=>$this->Lang_Get('topic_link_create_url'),'on'=>array('link'));
	}

	/**
	 * массив объектов(не всегда) для дополнительных типов топиков(линки, опросы, подкасты и т.п.)
	 *
	 * @var array
	 */
	protected $aExtra=null;

	/**
	 * Проверка типа топика
	 *
	 * @param $sValue
	 * @param $aParams
	 * @return bool | string
	 */
	public function ValidateTopicType($sValue,$aParams) {
		if ($this->Topic_IsAllowTopicType($sValue)) {
			return true;
		}
		return $this->Lang_Get('topic_create_type_error');
	}
	/**
	 * Проверка топика на уникальность
	 *
	 * @param $sValue
	 * @param $aParams
	 * @return bool | string
	 */
	public function ValidateTopicUnique($sValue,$aParams) {
		$this->setTextHash(md5($this->getType().$sValue.$this->getTitle()));
		if ($oTopicEquivalent=$this->Topic_GetTopicUnique($this->getUserId(),$this->getTextHash())) {
			if ($iId=$this->getTopicId() and $oTopicEquivalent->getId()==$iId) { // хак, запрашиваем не getId(), а getTopicId() - вернет null если это новый топик без ID
				return true;
			}
			return $this->Lang_Get('topic_create_text_error_unique');
		}
		return true;
	}
	/**
	 * Валидация ID блога
	 *
	 * @param $sValue
	 * @param $aParams
	 * @return bool | string
	 */
	public function ValidateBlogId($sValue,$aParams) {
		if ($sValue==0) {
			return true; // персональный блог
		}
		if ($this->Blog_GetBlogById((string)$sValue)) {
			return true;
		}
		return $this->Lang_Get('topic_create_blog_error_unknown');
	}

	
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
	public function getCountVoteUp() {
		return $this->_aData['topic_count_vote_up'];
	}
	public function getCountVoteDown() {
		return $this->_aData['topic_count_vote_down'];
	}
	public function getCountVoteAbstain() {
		return $this->_aData['topic_count_vote_abstain'];
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
		if (!isset($this->_aData['user'])) {
			$this->_aData['user']=$this->User_GetUserById($this->getUserId());
		}
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
		if ($this->getFavourite()) {
			return true;
		}
		return false;
    }
    public function getCountFavourite() {
        return $this->_aData['topic_count_favourite'];
    }
	public function getSubscribeNewComment() {
		if (!($oUserCurrent=$this->User_GetUserCurrent())) {
			return null;
		}
		return $this->Subscribe_GetSubscribeByTargetAndMail('topic_new_comment',$this->getId(),$oUserCurrent->getMail());
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
    
    protected function setExtraValue($sName,$data) {
    	$this->extractExtra();
    	$this->aExtra[$sName]=$data;
    	$this->setExtra($this->aExtra);
    }
    
    protected function getExtraValue($sName) {
    	$this->extractExtra();
    	if (isset($this->aExtra[$sName])) {
    		return $this->aExtra[$sName];
    	}
    	return null;
    }
    
    // методы для топика-ссылки
    public function getLinkUrl($bShort=false) {
    	if ($this->getType()!='link') {
    		return null;
    	}
    	
    	if ($this->getExtraValue('url')) {
    		if ($bShort) {
    			$sUrl=htmlspecialchars($this->getExtraValue('url'));
    			if (preg_match("/^https?:\/\/(.*)$/i",$sUrl,$aMatch)) {
    				$sUrl=$aMatch[1];
    			}
    			$sUrlShort=substr($sUrl,0,30);
    			if (strlen($sUrlShort)!=strlen($sUrl)) {
    				return $sUrlShort.'...';
    			}
    			return $sUrl;
    		}
    		$sUrl=$this->getExtraValue('url');
    		if (!preg_match("/^https?:\/\/(.*)$/i",$sUrl,$aMatch)) {
    			$sUrl='http://'.$sUrl;
    		}
    		return $sUrl;
    	}
    	return null;
    }    
    public function setLinkUrl($data) {
        if ($this->getType()!='link') {
    		return;
    	}
    	$this->setExtraValue('url',$data);
    }
    public function getLinkCountJump() {
    	if ($this->getType()!='link') {
    		return null;
    	}
    	return (int)$this->getExtraValue('count_jump');
    }
    public function setLinkCountJump($data) {
        if ($this->getType()!='link') {
    		return;
    	}
    	$this->setExtraValue('count_jump',$data);
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
    	$this->setExtraValue('answers',array());
    }
    public function getQuestionAnswers($bSortVote=false) {
    	if ($this->getType()!='question') {
    		return null;
    	}
    	
    	if ($this->getExtraValue('answers')) {
    		$aAnswers=$this->getExtraValue('answers');
			if ($bSortVote) {
				uasort($aAnswers, create_function('$a,$b',"if (\$a['count'] == \$b['count']) { return 0; } return (\$a['count'] < \$b['count']) ? 1 : -1;"));
			}
			return $aAnswers;
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
    	
    	return (int)$this->getExtraValue('count_vote');
    }
    public function setQuestionCountVote($data) {
        if ($this->getType()!='question') {
    		return;
    	}
    	$this->setExtraValue('count_vote',$data);
    }
    public function getQuestionCountVoteAbstain() {
    	if ($this->getType()!='question') {
    		return null;
    	}
    	
    	return (int)$this->getExtraValue('count_vote_abstain');
    }
    public function setQuestionCountVoteAbstain($data) {
        if ($this->getType()!='question') {
    		return;
    	}
    	$this->setExtraValue('count_vote_abstain',$data);
    }

    // Методы для фото-топика

    public function getPhotosetPhotos($iFromId = null, $iCount = null) {
    	return $this->Topic_getPhotosByTopicId($this->getId(), $iFromId, $iCount);
    }
    public function getPhotosetCount() {
    	return $this->getExtraValue('count_photo');
    }
    public function getPhotosetMainPhotoId() {
    	return $this->getExtraValue('main_photo_id');
    }
    public function setPhotosetMainPhotoId($data) {
    	$this->setExtraValue('main_photo_id',$data);
    }
    public function setPhotosetCount($data) {
    	$this->setExtraValue('count_photo',$data);
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
	public function setCountVoteUp($data) {
		$this->_aData['topic_count_vote_up']=$data;
	}
	public function setCountVoteDown($data) {
		$this->_aData['topic_count_vote_down']=$data;
	}
	public function setCountVoteAbstain($data) {
		$this->_aData['topic_count_vote_abstain']=$data;
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
    public function setCountFavourite($data) {
        $this->_aData['topic_count_favourite']=$data;
    }
}
?>