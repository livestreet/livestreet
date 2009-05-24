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

class Mapper_Topic extends Mapper {	
	protected $oUserCurrent=null;
	
	public function SetUserCurrent($oUserCurrent)  {
		$this->oUserCurrent=$oUserCurrent;
	}
	
	public function AddTopic(TopicEntity_Topic $oTopic) {
		$sql = "INSERT INTO ".DB_TABLE_TOPIC." 
			(blog_id,
			user_id,
			topic_type,
			topic_title,			
			topic_tags,
			topic_date_add,
			topic_user_ip,
			topic_publish,
			topic_publish_draft,
			topic_publish_index,
			topic_cut_text,
			topic_forbid_comment,			
			topic_text_hash			
			)
			VALUES(?d,  ?d,	?,	?,	?,  ?, ?, ?d, ?d, ?d, ?, ?, ?)
		";			
		if ($iId=$this->oDb->query($sql,$oTopic->getBlogId(),$oTopic->getUserId(),$oTopic->getType(),$oTopic->getTitle(),
			$oTopic->getTags(),$oTopic->getDateAdd(),$oTopic->getUserIp(),$oTopic->getPublish(),$oTopic->getPublishDraft(),$oTopic->getPublishIndex(),$oTopic->getCutText(),$oTopic->getForbidComment(),$oTopic->getTextHash())) 
		{
			$oTopic->setId($iId);
			$this->AddTopicContent($oTopic);
			return $iId;
		}		
		return false;
	}
	
	public function AddTopicContent(TopicEntity_Topic $oTopic) {
		$sql = "INSERT INTO ".DB_TABLE_TOPIC_CONTENT." 
			(topic_id,			
			topic_text,
			topic_text_short,
			topic_text_source,
			topic_extra			
			)
			VALUES(?d,  ?,	?,	?, ? )
		";			
		if ($iId=$this->oDb->query($sql,$oTopic->getId(),$oTopic->getText(),
			$oTopic->getTextShort(),$oTopic->getTextSource(),$oTopic->getExtra())) 
		{
			return $iId;
		}		
		return false;
	}
	
	public function AddTopicTag(TopicEntity_TopicTag $oTopicTag) {
		$sql = "INSERT INTO ".DB_TABLE_TOPIC_TAG." 
			(topic_id,
			user_id,
			blog_id,
			topic_tag_text		
			)
			VALUES(?d,  ?d,  ?d,	?)
		";			
		if ($iId=$this->oDb->query($sql,$oTopicTag->getTopicId(),$oTopicTag->getUserId(),$oTopicTag->getBlogId(),$oTopicTag->getText())) 
		{
			return $iId;
		}		
		return false;
	}
	
	public function AddTopicVote(TopicEntity_TopicVote $oTopicVote) {
		$sql = "INSERT INTO ".DB_TABLE_TOPIC_VOTE." 
			(topic_id,
			user_voter_id,
			vote_delta		
			)
			VALUES(?d,  ?d,	?f)
		";			
		if ($this->oDb->query($sql,$oTopicVote->getTopicId(),$oTopicVote->getVoterId(),$oTopicVote->getDelta())===0) 
		{
			return true;
		}		
		return false;
	}
	
	public function DeleteTopicTagsByTopicId($sTopicId) {
		$sql = "DELETE FROM ".DB_TABLE_TOPIC_TAG." 
			WHERE
				topic_id = ?d				
		";			
		if ($this->oDb->query($sql,$sTopicId)) {
			return true;
		}		
		return false;
	}
	
	public function DeleteTopic($sTopicId) {
		$sql = "DELETE FROM ".DB_TABLE_TOPIC." 
			WHERE
				topic_id = ?d				
		";			
		if ($this->oDb->query($sql,$sTopicId)) {
			return true;
		}		
		return false;
	}
	
		
	public function GetTopicUnique($sUserId,$sHash) {
		$sql = "SELECT topic_id FROM ".DB_TABLE_TOPIC." 
			WHERE 				
				user_id = ?d				
				AND
				topic_text_hash =?
				";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId,$sHash)) {
			return $aRow['topic_id'];
		}
		return null;
	}
	
	public function GetTopicsByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					t.*							 
				FROM 
					".DB_TABLE_TOPIC." as t					
				WHERE 
					t.topic_id IN(?a) 
					AND 					
					t.publish = 1				
				ORDER BY FIELD(t.topic_id,?a) ";
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=new TopicEntity_Topic($aTopic);
			}
		}		
		return $aTopics;
	}
	
	
	public function GetTopics($aFilter,&$iCount,$iCurrPage,$iPerPage) {
		$sWhere=$this->buildFilter($aFilter);
		
		$sql = "SELECT 
						t.topic_id							
					FROM 
						".DB_TABLE_TOPIC." as t,	
						".DB_TABLE_BLOG." as b				
					WHERE 
						1=1					
						".$sWhere."					
						AND
						t.blog_id=b.blog_id											
					ORDER by t.topic_id desc
					LIMIT ?d, ?d";		
		$aTopics=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,($iCurrPage-1)*$iPerPage, $iPerPage)) {			
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}				
		return $aTopics;
	}
	
	public function GetCountTopics($aFilter) {		
		$sWhere=$this->buildFilter($aFilter);
		$sql = "SELECT 
					count(t.topic_id) as count									
				FROM 
					".DB_TABLE_TOPIC." as t,					
					".DB_TABLE_BLOG." as b 
				WHERE 
					1=1
					
					".$sWhere."								
					
					AND
					t.blog_id=b.blog_id	;";		
		if ($aRow=$this->oDb->selectRow($sql)) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function GetTopicsByTag($sTag,&$iCount,$iCurrPage,$iPerPage) {		
		$sql = "				
							SELECT 		
								topic_id										
							FROM 
								".DB_TABLE_TOPIC_TAG."								
							WHERE 
								topic_tag_text = ? 	
                            ORDER BY topic_id DESC	
                            LIMIT ?d, ?d ";
		
		$aTopics=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,$sTag,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}			
		}
		return $aTopics;
	}
	
	
	public function GetTopicsRatingByDate($sDate,$iLimit) {
		$sql = "SELECT 
						t.topic_id										
					FROM 
						".DB_TABLE_TOPIC." as t
					WHERE 					
						t.topic_publish = 1
						AND
						t.topic_date_add >= ? 								
						AND
						t.topic_rating >= 0 																	
					ORDER by t.topic_rating desc, t.topic_id desc
					LIMIT 0, ?d ";		
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql,$sDate,$iLimit)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		return $aTopics;
	}
	
	public function GetTopicTags($iLimit) {
		$sql = "SELECT 
			tt.topic_tag_text,
			count(tt.topic_tag_text)	as count		 
			FROM 
				".DB_TABLE_TOPIC_TAG." as tt 			
			GROUP BY 
				tt.topic_tag_text
			ORDER BY 
				count desc		
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		$aReturnSort=array();
		if ($aRows=$this->oDb->select($sql,$iLimit)) {
			foreach ($aRows as $aRow) {				
				$aReturn[mb_strtolower($aRow['topic_tag_text'],'UTF-8')]=$aRow;
			}
			ksort($aReturn);
			foreach ($aReturn as $aRow) {
				$aReturnSort[]=new TopicEntity_TopicTag($aRow);				
			}
		}
		return $aReturnSort;
	}
	
		
	public function GetTopicVote($sTopicId,$sUserId) {
		$sql = "SELECT * FROM ".DB_TABLE_TOPIC_VOTE." WHERE topic_id = ?d and user_voter_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sTopicId,$sUserId)) {
			return new TopicEntity_TopicVote($aRow);
		}
		return null;
	}
	
	public function increaseTopicCountComment($sTopicId) {
		$sql = "UPDATE ".DB_TABLE_TOPIC." 
			SET 
				topic_count_comment=topic_count_comment+1
			WHERE
				topic_id = ?
		";			
		if ($this->oDb->query($sql,$sTopicId)) {
			return true;
		}		
		return false;
	}
	
	public function UpdateTopic(TopicEntity_Topic $oTopic) {		
		$sql = "UPDATE ".DB_TABLE_TOPIC." 
			SET 
				blog_id= ?d,
				topic_title= ?,				
				topic_tags= ?,
				topic_date_add = ?,
				topic_date_edit = ?,
				topic_user_ip= ?,
				topic_publish= ?d ,
				topic_publish_draft= ?d ,
				topic_publish_index= ?d,
				topic_rating= ?f,
				topic_count_vote= ?d,
				topic_count_read= ?d,
				topic_count_comment= ?d, 
				topic_cut_text = ? ,
				topic_forbid_comment = ? ,
				topic_text_hash = ? 
			WHERE
				topic_id = ?d
		";			
		if ($this->oDb->query($sql,$oTopic->getBlogId(),$oTopic->getTitle(),$oTopic->getTags(),$oTopic->getDateAdd(),$oTopic->getDateEdit(),$oTopic->getUserIp(),$oTopic->getPublish(),$oTopic->getPublishDraft(),$oTopic->getPublishIndex(),$oTopic->getRating(),$oTopic->getCountVote(),$oTopic->getCountRead(),$oTopic->getCountComment(),$oTopic->getCutText(),$oTopic->getForbidComment(),$oTopic->getTextHash(),$oTopic->getId())) {
			$this->UpdateTopicContent($oTopic);
			return true;
		}		
		return false;
	}
	
	public function UpdateTopicContent(TopicEntity_Topic $oTopic) {		
		$sql = "UPDATE ".DB_TABLE_TOPIC_CONTENT." 
			SET 				
				topic_text= ?,
				topic_text_short= ?,
				topic_text_source= ?,
				topic_extra= ?
			WHERE
				topic_id = ?d
		";			
		if ($this->oDb->query($sql,$oTopic->getText(),$oTopic->getTextShort(),$oTopic->getTextSource(),$oTopic->getExtra(),$oTopic->getId())) {
			return true;
		}		
		return false;
	}
	
	protected function buildFilter($aFilter) {
		$sWhere='';
		
		if (isset($aFilter['topic_publish'])) {
			$sWhere.=" AND t.topic_publish =  ".(int)$aFilter['topic_publish'];
		}	
		if (isset($aFilter['topic_rating']) and is_array($aFilter['topic_rating'])) {
			$sPublishIndex='';
			if (isset($aFilter['topic_rating']['publish_index']) and $aFilter['topic_rating']['publish_index']==1) {
				$sPublishIndex=" or topic_publish_index=1 ";
			}
			if ($aFilter['topic_rating']['type']=='top') {
				$sWhere.=" AND ( t.topic_rating >= ".(float)$aFilter['topic_rating']['value']." {$sPublishIndex} ) ";
			} else {
				$sWhere.=" AND ( t.topic_rating < ".(float)$aFilter['topic_rating']['value']."  ) ";
			}			
		}
		if (isset($aFilter['topic_new'])) {
			$sWhere.=" AND t.topic_date_add >=  '".$aFilter['topic_new']."'";
		}
		if (isset($aFilter['user_id'])) {
			$sWhere.=" AND t.user_id =  ".(int)$aFilter['user_id'];
		}
		if (isset($aFilter['blog_id'])) {
			$sWhere.=" AND t.blog_id =  ".(int)$aFilter['blog_id'];
		}
		if (isset($aFilter['blog_type']) and is_array($aFilter['blog_type'])) {
			$sWhere.=" AND b.blog_type in ('".join("','",$aFilter['blog_type'])."') ";
		}
		return $sWhere;
	}
	
	
	public function AddFavouriteTopic(TopicEntity_FavouriteTopic $oFavouriteTopic) {
		$sql = "INSERT INTO ".DB_TABLE_FAVOURITE_TOPIC." 
			(user_id,
			topic_id,
			topic_publish		
			)
			VALUES(?d,  ?d, ?d)
		";			
		if ($this->oDb->query($sql,$oFavouriteTopic->getUserId(),$oFavouriteTopic->getTopicId(),$oFavouriteTopic->getTopicPublish())===0) 
		{
			return true;
		}		
		return false;
	}
	
	public function DeleteFavouriteTopic(TopicEntity_FavouriteTopic $oFavouriteTopic) {
		$sql = "DELETE FROM ".DB_TABLE_FAVOURITE_TOPIC." 
			WHERE
				user_id = ?d
				AND
				topic_id = ?d				
		";			
		if ($this->oDb->query($sql,$oFavouriteTopic->getUserId(),$oFavouriteTopic->getTopicId())) 
		{
			return true;
		}		
		return false;
	}
	
	public function SetFavouriteTopicPublish($sTopicId,$iPublish) {
		$sql = "UPDATE ".DB_TABLE_FAVOURITE_TOPIC." 
			SET 
				topic_publish = ?d
			WHERE				
				topic_id = ?d				
		";			
		return $this->oDb->query($sql,$iPublish,$sTopicId); 		
	}
	
	public function GetFavouriteTopic($sTopicId,$sUserId) {
		$sql = "SELECT * FROM ".DB_TABLE_FAVOURITE_TOPIC." WHERE topic_id = ?d and user_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sTopicId,$sUserId)) {
			return new TopicEntity_FavouriteTopic($aRow);
		}
		return null;
	}
	
	public function GetTopicsFavouriteByUserId($sUserId,&$iCount,$iCurrPage,$iPerPage) {	
		$sql = "			
							SELECT 		
								topic_id										
							FROM 
								".DB_TABLE_FAVOURITE_TOPIC."								
							WHERE 
								user_id = ?
								and
								topic_publish = 1 	
                            ORDER BY topic_id DESC	
                            LIMIT ?d, ?d ";
		
		$aTopics=array();		
		if ($aRows=$this->oDb->selectPage($iCount,$sql,$sUserId,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}			
		}		
		return $aTopics;
	}
	
	public function GetCountTopicsFavouriteByUserId($sUserId) {
		$sql = "SELECT 		
					count(topic_id) as count									
				FROM 
					".DB_TABLE_FAVOURITE_TOPIC."								
				WHERE 
					user_id = ?
					and
					topic_publish = 1;	
					";				
		if ($aRow=$this->oDb->selectRow($sql,$sUserId)) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function GetTopicTagsByLike($sTag,$iLimit) {
		$sTag=mb_strtolower($sTag,"UTF-8");		
		$sql = "SELECT 
				topic_tag_text					 
			FROM 
				".DB_TABLE_TOPIC_TAG."	
			WHERE
				topic_tag_text LIKE ?			
			GROUP BY 
				topic_tag_text					
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sTag.'%',$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=new TopicEntity_TopicTag($aRow);
			}
		}
		return $aReturn;
	}
	
	public function UpdateTopicRead(TopicEntity_TopicRead $oTopicRead) {		
		$sql = "UPDATE ".DB_TABLE_TOPIC_READ." 
			SET 
				comment_count_last = ? ,
				comment_id_last = ? ,
				date_read = ? 
			WHERE
				topic_id = ? 
				AND				
				user_id = ? 
		";			
		return $this->oDb->query($sql,$oTopicRead->getCommentCountLast(),$oTopicRead->getCommentIdLast(),$oTopicRead->getDateRead(),$oTopicRead->getTopicId(),$oTopicRead->getUserId());
	}	

	public function AddTopicRead(TopicEntity_TopicRead $oTopicRead) {		
		$sql = "INSERT INTO ".DB_TABLE_TOPIC_READ." 
			SET 
				comment_count_last = ? ,
				comment_id_last = ? ,
				date_read = ? ,
				topic_id = ? ,							
				user_id = ? 
		";			
		return $this->oDb->query($sql,$oTopicRead->getCommentCountLast(),$oTopicRead->getCommentIdLast(),$oTopicRead->getDateRead(),$oTopicRead->getTopicId(),$oTopicRead->getUserId());
	}
				
	public function GetTopicRead($sTopicId,$sUserId) {			
		$sql = "SELECT 
					*									
				FROM 
					".DB_TABLE_TOPIC_READ."					 
				WHERE 					
					topic_id = ?d					
					AND			
					user_id = ?d					
				;	
					";		
		if ($aRow=$this->oDb->selectRow($sql,$sTopicId,$sUserId)) {
			return new TopicEntity_TopicRead($aRow);
		}
		return false;
	}
	
	public function AddTopicQuestionVote(TopicEntity_TopicQuestionVote $oTopicQuestionVote) {
		$sql = "INSERT INTO ".DB_TABLE_TOPIC_QUESTION_VOTE." 
			(topic_id,
			user_voter_id,
			answer		
			)
			VALUES(?d,  ?d,	?f)
		";			
		if ($this->oDb->query($sql,$oTopicQuestionVote->getTopicId(),$oTopicQuestionVote->getVoterId(),$oTopicQuestionVote->getAnswer())===0) 
		{
			return true;
		}		
		return false;
	}
	
	public function GetTopicQuestionVote($sTopicId,$sUserId) {
		$sql = "SELECT * FROM ".DB_TABLE_TOPIC_QUESTION_VOTE." WHERE topic_id = ?d and user_voter_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sTopicId,$sUserId)) {
			return new TopicEntity_TopicQuestionVote($aRow);
		}
		return null;
	}
}
?>