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

class ModuleTopic_MapperTopic extends Mapper {	
		
	public function AddTopic(ModuleTopic_EntityTopic $oTopic) {
		$sql = "INSERT INTO ".Config::Get('db.table.topic')." 
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
	
	public function AddTopicContent(ModuleTopic_EntityTopic $oTopic) {
		$sql = "INSERT INTO ".Config::Get('db.table.topic_content')." 
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
	
	public function AddTopicTag(ModuleTopic_EntityTopicTag $oTopicTag) {
		$sql = "INSERT INTO ".Config::Get('db.table.topic_tag')." 
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
	
	public function DeleteTopicContentByTopicId($iTopicId) {
		$sql = "DELETE FROM ".Config::Get('db.table.topic_content')." WHERE topic_id = ?d ";
		if ($this->oDb->query($sql,$iTopicId)) {
			return true;
		}		
		return false;
	}
	
	public function DeleteTopicTagsByTopicId($sTopicId) {
		$sql = "DELETE FROM ".Config::Get('db.table.topic_tag')." 
			WHERE
				topic_id = ?d				
		";			
		if ($this->oDb->query($sql,$sTopicId)) {
			return true;
		}		
		return false;
	}
	
	public function DeleteTopic($sTopicId) {
		$sql = "DELETE FROM ".Config::Get('db.table.topic')." 
			WHERE
				topic_id = ?d				
		";			
		if ($this->oDb->query($sql,$sTopicId)) {
			return true;
		}		
		return false;
	}
	
		
	public function GetTopicUnique($sUserId,$sHash) {
		$sql = "SELECT topic_id FROM ".Config::Get('db.table.topic')." 
			WHERE 				
				topic_text_hash =? 						
				AND
				user_id = ?d
			LIMIT 0,1
				";
		if ($aRow=$this->oDb->selectRow($sql,$sHash,$sUserId)) {
			return $aRow['topic_id'];
		}
		return null;
	}
	
	public function GetTopicsByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					t.*,
					tc.*							 
				FROM 
					".Config::Get('db.table.topic')." as t	
					JOIN  ".Config::Get('db.table.topic_content')." AS tc ON t.topic_id=tc.topic_id				
				WHERE 
					t.topic_id IN(?a) 									
				ORDER BY FIELD(t.topic_id,?a) ";
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=Engine::GetEntity('Topic',$aTopic);
			}
		}		
		return $aTopics;
	}
	
	
	public function GetTopics($aFilter,&$iCount,$iCurrPage,$iPerPage) {
		$sWhere=$this->buildFilter($aFilter);
		
		if(!isset($aFilter['order'])) {
			$aFilter['order'] = 't.topic_date_add desc';
		}
		if (!is_array($aFilter['order'])) {
			$aFilter['order'] = array($aFilter['order']);
		}
		
		$sql = "SELECT 
						t.topic_id							
					FROM 
						".Config::Get('db.table.topic')." as t,	
						".Config::Get('db.table.blog')." as b			
					WHERE 
						1=1					
						".$sWhere."
						AND
						t.blog_id=b.blog_id										
					ORDER BY ".
						implode(', ', $aFilter['order'])
				."
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
					".Config::Get('db.table.topic')." as t,					
					".Config::Get('db.table.blog')." as b
				WHERE 
					1=1
					
					".$sWhere."								
					
					AND
					t.blog_id=b.blog_id;";		
		if ($aRow=$this->oDb->selectRow($sql)) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function GetAllTopics($aFilter) {
		$sWhere=$this->buildFilter($aFilter);
		
		if(!isset($aFilter['order'])) {
			$aFilter['order'] = 't.topic_id desc';
		}
		if (!is_array($aFilter['order'])) {
			$aFilter['order'] = array($aFilter['order']);
		}
		
		$sql = "SELECT 
						t.topic_id							
					FROM 
						".Config::Get('db.table.topic')." as t,	
						".Config::Get('db.table.blog')." as b			
					WHERE 
						1=1					
						".$sWhere."
						AND
						t.blog_id=b.blog_id										
					ORDER by ".implode(', ', $aFilter['order'])." ";		
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql)) {			
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}		

		return $aTopics;		
	}
	
	public function GetTopicsByTag($sTag,$aExcludeBlog,&$iCount,$iCurrPage,$iPerPage) {		
		$sql = "				
							SELECT 		
								topic_id										
							FROM 
								".Config::Get('db.table.topic_tag')."								
							WHERE 
								topic_tag_text = ? 	
								{ AND blog_id NOT IN (?a) }
                            ORDER BY topic_id DESC	
                            LIMIT ?d, ?d ";
		
		$aTopics=array();
		if ($aRows=$this->oDb->selectPage(
				$iCount,$sql,$sTag,
				(is_array($aExcludeBlog)&&count($aExcludeBlog)) ? $aExcludeBlog : DBSIMPLE_SKIP,
				($iCurrPage-1)*$iPerPage, $iPerPage
			)
		) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		return $aTopics;
	}
	
	
	public function GetTopicsRatingByDate($sDate,$iLimit,$aExcludeBlog=array()) {
		$sql = "SELECT 
						t.topic_id										
					FROM 
						".Config::Get('db.table.topic')." as t
					WHERE 					
						t.topic_publish = 1
						AND
						t.topic_date_add >= ?
						AND
						t.topic_rating >= 0
						{ AND t.blog_id NOT IN(?a) } 																	
					ORDER by t.topic_rating desc, t.topic_id desc
					LIMIT 0, ?d ";		
		$aTopics=array();
		if ($aRows=$this->oDb->select(
				$sql,$sDate,
				(is_array($aExcludeBlog)&&count($aExcludeBlog)) ? $aExcludeBlog : DBSIMPLE_SKIP,
				$iLimit
			)
		) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=$aTopic['topic_id'];
			}
		}
		return $aTopics;
	}
	
	public function GetTopicTags($iLimit,$aExcludeTopic=array()) {
		$sql = "SELECT 
			tt.topic_tag_text,
			count(tt.topic_tag_text)	as count		 
			FROM 
				".Config::Get('db.table.topic_tag')." as tt
			WHERE 
				1=1
				{AND tt.topic_id NOT IN(?a) }		
			GROUP BY 
				tt.topic_tag_text
			ORDER BY 
				count desc		
			LIMIT 0, ?d
				";	
		$aReturn=array();
		$aReturnSort=array();
		if ($aRows=$this->oDb->select(
				$sql,
				(is_array($aExcludeTopic)&&count($aExcludeTopic)) ? $aExcludeTopic : DBSIMPLE_SKIP,
				$iLimit
			)
		) {
			foreach ($aRows as $aRow) {				
				$aReturn[mb_strtolower($aRow['topic_tag_text'],'UTF-8')]=$aRow;
			}
			ksort($aReturn);
			foreach ($aReturn as $aRow) {
				$aReturnSort[]=Engine::GetEntity('Topic_TopicTag',$aRow);				
			}
		}
		return $aReturnSort;
	}

	public function GetOpenTopicTags($iLimit,$iUserId=null) {
		$sql = "
			SELECT 
				tt.topic_tag_text,
				count(tt.topic_tag_text)	as count		 
			FROM 
				".Config::Get('db.table.topic_tag')." as tt,
				".Config::Get('db.table.blog')." as b
			WHERE
				1 = 1
				{ AND tt.user_id = ?d }
				AND
				tt.blog_id = b.blog_id
				AND
				b.blog_type <> 'close'
			GROUP BY 
				tt.topic_tag_text
			ORDER BY 
				count desc		
			LIMIT 0, ?d
				";	
		$aReturn=array();
		$aReturnSort=array();
		if ($aRows=$this->oDb->select($sql,is_null($iUserId) ? DBSIMPLE_SKIP : $iUserId,$iLimit)) {
			foreach ($aRows as $aRow) {				
				$aReturn[mb_strtolower($aRow['topic_tag_text'],'UTF-8')]=$aRow;
			}
			ksort($aReturn);
			foreach ($aReturn as $aRow) {
				$aReturnSort[]=Engine::GetEntity('Topic_TopicTag',$aRow);				
			}
		}
		return $aReturnSort;
	}

	
	public function increaseTopicCountComment($sTopicId) {
		$sql = "UPDATE ".Config::Get('db.table.topic')." 
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
	
	public function UpdateTopic(ModuleTopic_EntityTopic $oTopic) {		
		$sql = "UPDATE ".Config::Get('db.table.topic')." 
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
				topic_count_vote_up= ?d,
				topic_count_vote_down= ?d,
				topic_count_vote_abstain= ?d,
				topic_count_read= ?d,
				topic_count_comment= ?d, 
				topic_count_favourite= ?d,
				topic_cut_text = ? ,
				topic_forbid_comment = ? ,
				topic_text_hash = ? 
			WHERE
				topic_id = ?d
		";			
		if ($this->oDb->query($sql,$oTopic->getBlogId(),$oTopic->getTitle(),$oTopic->getTags(),$oTopic->getDateAdd(),$oTopic->getDateEdit(),$oTopic->getUserIp(),$oTopic->getPublish(),$oTopic->getPublishDraft(),$oTopic->getPublishIndex(),$oTopic->getRating(),$oTopic->getCountVote(),$oTopic->getCountVoteUp(),$oTopic->getCountVoteDown(),$oTopic->getCountVoteAbstain(),$oTopic->getCountRead(),$oTopic->getCountComment(),$oTopic->getCountFavourite(),$oTopic->getCutText(),$oTopic->getForbidComment(),$oTopic->getTextHash(),$oTopic->getId())) {
			$this->UpdateTopicContent($oTopic);
			return true;
		}		
		return false;
	}
	
	public function UpdateTopicContent(ModuleTopic_EntityTopic $oTopic) {		
		$sql = "UPDATE ".Config::Get('db.table.topic_content')." 
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
		if (isset($aFilter['topic_date_more'])) {
			$sWhere.=" AND t.topic_date_add >  '".mysql_real_escape_string($aFilter['topic_date_more'])."'";
		}
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
			$sWhere.=is_array($aFilter['user_id'])
				? " AND t.user_id IN(".implode(', ',$aFilter['user_id']).")"
				: " AND t.user_id =  ".(int)$aFilter['user_id'];
		}
		if (isset($aFilter['blog_id'])) {
			if(!is_array($aFilter['blog_id'])) {
				$aFilter['blog_id']=array($aFilter['blog_id']);
			}
			$sWhere.=" AND t.blog_id IN ('".join("','",$aFilter['blog_id'])."')";
		}
		if (isset($aFilter['blog_type']) and is_array($aFilter['blog_type'])) {
			$aBlogTypes = array();
			foreach ($aFilter['blog_type'] as $sType=>$aBlogId) {
				/**
				 * Позиция вида 'type'=>array('id1', 'id2')
				 */
				if(!is_array($aBlogId) && is_string($sType)){
					$aBlogId=array($aBlogId);
				}
				/**
				 * Позиция вида 'type'
				 */
				if(is_string($aBlogId) && is_int($sType)) {
					$sType=$aBlogId;
					$aBlogId=array();
				}
				
				$aBlogTypes[] = (count($aBlogId)==0) 
					? "(b.blog_type='".$sType."')"
					: "(b.blog_type='".$sType."' AND t.blog_id IN ('".join("','",$aBlogId)."'))";
			}
			$sWhere.=" AND (".join(" OR ",(array)$aBlogTypes).")";
		}
		if (isset($aFilter['topic_type'])) {
			if(!is_array($aFilter['topic_type'])) {
				$aFilter['topic_type']=array($aFilter['topic_type']);
			}
			$sWhere.=" AND t.topic_type IN ('".join("','",array_map('mysql_real_escape_string',$aFilter['topic_type']))."')";
		}
		return $sWhere;
	}
	
	public function GetTopicTagsByLike($sTag,$iLimit) {
		$sTag=mb_strtolower($sTag,"UTF-8");		
		$sql = "SELECT 
				topic_tag_text					 
			FROM 
				".Config::Get('db.table.topic_tag')."	
			WHERE
				topic_tag_text LIKE ?			
			GROUP BY 
				topic_tag_text					
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sTag.'%',$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('Topic_TopicTag',$aRow);
			}
		}
		return $aReturn;
	}
	
	public function UpdateTopicRead(ModuleTopic_EntityTopicRead $oTopicRead) {		
		$sql = "UPDATE ".Config::Get('db.table.topic_read')." 
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

	public function AddTopicRead(ModuleTopic_EntityTopicRead $oTopicRead) {		
		$sql = "INSERT INTO ".Config::Get('db.table.topic_read')." 
			SET 
				comment_count_last = ? ,
				comment_id_last = ? ,
				date_read = ? ,
				topic_id = ? ,							
				user_id = ? 
		";			
		return $this->oDb->query($sql,$oTopicRead->getCommentCountLast(),$oTopicRead->getCommentIdLast(),$oTopicRead->getDateRead(),$oTopicRead->getTopicId(),$oTopicRead->getUserId());
	}
	/**
	 * Удаляет записи о чтении записей по списку идентификаторов
	 *
	 * @param  array $aTopicId
	 * @return bool
	 */				
	public function DeleteTopicReadByArrayId($aTopicId) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.topic_read')." 
			WHERE
				topic_id IN(?a)				
		";			
		if ($this->oDb->query($sql,$aTopicId)) {
			return true;
		}
		return false;
	}
			
	public function GetTopicsReadByArray($aArrayId,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					t.*							 
				FROM 
					".Config::Get('db.table.topic_read')." as t 
				WHERE 
					t.topic_id IN(?a)
					AND
					t.user_id = ?d 
				";
		$aReads=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aReads[]=Engine::GetEntity('Topic_TopicRead',$aRow);
			}
		}		
		return $aReads;
	}
	
	public function AddTopicQuestionVote(ModuleTopic_EntityTopicQuestionVote $oTopicQuestionVote) {
		$sql = "INSERT INTO ".Config::Get('db.table.topic_question_vote')." 
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
	
		
	public function GetTopicsQuestionVoteByArray($aArrayId,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					v.*							 
				FROM 
					".Config::Get('db.table.topic_question_vote')." as v 
				WHERE 
					v.topic_id IN(?a)
					AND	
					v.user_voter_id = ?d 				 									
				";
		$aVotes=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sUserId)) {
			foreach ($aRows as $aRow) {
				$aVotes[]=Engine::GetEntity('Topic_TopicQuestionVote',$aRow);
			}
		}		
		return $aVotes;
	}
	
	/**
	 * Перемещает топики в другой блог
	 *
	 * @param  array  $aTopics
	 * @param  string $sBlogId
	 * @return bool
	 */	
	public function MoveTopicsByArrayId($aTopics,$sBlogId) {
		if(!is_array($aTopics)) $aTopics = array($aTopics);
		
		$sql = "UPDATE ".Config::Get('db.table.topic')."
			SET 
				blog_id= ?d
			WHERE
				topic_id IN(?a)
		";			
		if ($this->oDb->query($sql,$sBlogId,$aTopics)) {
			return true;
		}		
		return false;
	}
	
	/**
	 * Перемещает топики в другой блог
	 *
	 * @param  string $sBlogId
	 * @param  string $sBlogIdNew
	 * @return bool
	 */	
	public function MoveTopics($sBlogId,$sBlogIdNew) {
		$sql = "UPDATE ".Config::Get('db.table.topic')."
			SET 
				blog_id= ?d
			WHERE
				blog_id = ?d
		";			
		if ($this->oDb->query($sql,$sBlogIdNew,$sBlogId)) {
			return true;
		}		
		return false;
	}
	
	/**
	 * Перемещает теги топиков в другой блог
	 *
	 * @param string $sBlogId
	 * @param string $sBlogIdNew
	 * @return bool
	 */
	public function MoveTopicsTags($sBlogId,$sBlogIdNew) {
		$sql = "UPDATE ".Config::Get('db.table.topic_tag')."
			SET 
				blog_id= ?d
			WHERE
				blog_id = ?d
		";			
		if ($this->oDb->query($sql,$sBlogIdNew,$sBlogId)) {
			return true;
		}		
		return false;
	}
	
	/**
	 * Перемещает теги топиков в другой блог
	 *
	 * @param array $aTopics
	 * @param string $sBlogId
	 * @return bool
	 */
	public function MoveTopicsTagsByArrayId($aTopics,$sBlogId) {
		if(!is_array($aTopics)) $aTopics = array($aTopics);
		
		$sql = "UPDATE ".Config::Get('db.table.topic_tag')."
			SET 
				blog_id= ?d
			WHERE
				topic_id IN(?a)
		";			
		if ($this->oDb->query($sql,$sBlogId,$aTopics)) {
			return true;
		}		
		return false;
	}
    
	
	
	/**
	 * Возвращает список фоток из фото-сета по списку id фоток
	 *
	 * @param unknown_type $aArrayId
	 * @return unknown
	 */
	public function GetTopicPhotosByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT
					*							 
				FROM 
					".Config::Get('db.table.topic_photo')."		
				WHERE 
					id IN(?a) 								
				ORDER BY FIELD(id,?a) ";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aPhoto) {
				$aReturn[]=Engine::GetEntity('Topic_TopicPhoto',$aPhoto);
			}
		}
		return $aReturn;
	}








	public function getPhotosByTopicId($iTopicId, $iFromId, $iCount)
	{
		$sql = 'SELECT * FROM ' . Config::Get('db.table.topic_photo') . ' WHERE topic_id = ?d {AND id > ?d LIMIT 0, ?d}';
		$aPhotos = $this->oDb->select($sql, $iTopicId, ($iFromId !== null) ? $iFromId : DBSIMPLE_SKIP, $iCount);
		$aReturn = array();
		if (is_array($aPhotos) && count($aPhotos)) {
			foreach($aPhotos as $aPhoto) {
				$aReturn[] = Engine::GetEntity('Topic_TopicPhoto', $aPhoto);
			}
		}
		return $aReturn;
	}

	public function getPhotosByTargetTmp($sTargetTmp)
	{
		$sql = 'SELECT * FROM ' . Config::Get('db.table.topic_photo') . ' WHERE target_tmp = ?';
		$aPhotos = $this->oDb->select($sql, $sTargetTmp);
		$aReturn = array();
		if (is_array($aPhotos) && count($aPhotos)) {
			foreach($aPhotos as $aPhoto) {
				$aReturn[] = Engine::GetEntity('Topic_TopicPhoto', $aPhoto);
			}
		}
		return $aReturn;
	}

	public function getTopicPhotoById($iPhotoId)
	{
		$sql = 'SELECT * FROM ' . Config::Get('db.table.topic_photo') . ' WHERE id = ?d';
		$aPhoto = $this->oDb->selectRow($sql, $iPhotoId);
		if ($aPhoto) {
			return Engine::GetEntity('Topic_TopicPhoto', $aPhoto);
		} else {
			return null;
		}
	}

	public function getCountPhotosByTopicId($iTopicId)
	{
		$sql = 'SELECT count(id) FROM ' . Config::Get('db.table.topic_photo') . ' WHERE topic_id = ?d';
		$aPhotosCount = $this->oDb->selectCol($sql, $iTopicId);
		return $aPhotosCount[0];
	}

	public function getCountPhotosByTargetTmp($sTargetTmp)
	{
		$sql = 'SELECT count(id) FROM ' . Config::Get('db.table.topic_photo') . ' WHERE target_tmp = ?';
		$aPhotosCount = $this->oDb->selectCol($sql, $sTargetTmp);
		return $aPhotosCount[0];
	}

	public function addTopicPhoto($oPhoto)
	{
		if (!$oPhoto->getTopicId() && !$oPhoto->getTargetTmp()) return false;
		$sTargetType = ($oPhoto->getTopicId()) ? 'topic_id' : 'target_tmp';
		$iTargetId = ($sTargetType == 'topic_id') ? $oPhoto->getTopicId() : $oPhoto->getTargetTmp();
		$sql = 'INSERT INTO '. Config::Get('db.table.topic_photo') . ' SET
                        path = ?, description = ?, ?# = ?';
		return $this->oDb->query($sql, $oPhoto->getPath(), $oPhoto->getDescription(), $sTargetType, $iTargetId);
	}

	public function updateTopicPhoto($oPhoto)
	{
		if (!$oPhoto->getTopicId() && !$oPhoto->getTargetTmp()) return false;
		if ($oPhoto->getTopicId()) {
			$oPhoto->setTargetTmp = null;
		}
		$sql = 'UPDATE '. Config::Get('db.table.topic_photo') . ' SET
                        path = ?, description = ?, topic_id = ?d, target_tmp=? WHERE id = ?d';
		$this->oDb->query($sql, $oPhoto->getPath(), $oPhoto->getDescription(), $oPhoto->getTopicId(), $oPhoto->getTargetTmp(), $oPhoto->getId());
	}

	public function deleteTopicPhoto($iPhotoId)
	{
		$sql = 'DELETE FROM '. Config::Get('db.table.topic_photo') . ' WHERE
                        id= ?d';
		$this->oDb->query($sql, $iPhotoId);
	}

	public function RecalculateFavourite() {
		$sql = "
                UPDATE ".Config::Get('db.table.topic')." t 
                SET t.topic_count_favourite = (
                    SELECT count(f.user_id)
                    FROM ".Config::Get('db.table.favourite')." f
                    WHERE 
                        f.target_id = t.topic_id
                    AND
                        f.target_publish = 1
                    AND
                        f.target_type = 'topic'
                )
            ";
		if ($this->oDb->query($sql)) {
			return true;
		}
		return false;
	}

	public function RecalculateVote() {
		$sql = "
                UPDATE ".Config::Get('db.table.topic')." t
                SET t.topic_count_vote_up = (
                    SELECT count(*)
                    FROM ".Config::Get('db.table.vote')." v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = 1
                    AND
                        v.target_type = 'topic'
                ), t.topic_count_vote_down = (
                    SELECT count(*)
                    FROM ".Config::Get('db.table.vote')." v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = -1
                    AND
                        v.target_type = 'topic'
                ), t.topic_count_vote_abstain = (
                    SELECT count(*)
                    FROM ".Config::Get('db.table.vote')." v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = 0
                    AND
                        v.target_type = 'topic'
                )
            ";
		if ($this->oDb->query($sql)) {
			return true;
		}
		return false;
	}
}
?>