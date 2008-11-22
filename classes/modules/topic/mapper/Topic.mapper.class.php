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
			topic_publish_index,
			topic_cut_text,
			topic_forbid_comment			
			)
			VALUES(?d,  ?d,	?,	?,	?,  ?, ?, ?d, ?d, ?, ?)
		";			
		if ($iId=$this->oDb->query($sql,$oTopic->getBlogId(),$oTopic->getUserId(),$oTopic->getType(),$oTopic->getTitle(),
			$oTopic->getTags(),$oTopic->getDateAdd(),$oTopic->getUserIp(),$oTopic->getPublish(),$oTopic->getPublishIndex(),$oTopic->getCutText(),$oTopic->getForbidComment())) 
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
	
	public function GetTopicById($sId,$oUser,$iPublish) {
		$iCurrentUserId=-1;
		if (is_object($this->oUserCurrent)) {
			$iCurrentUserId=$this->oUserCurrent->getId();
		}
		$sWhereUser=' 1=1 ';
		if ($iPublish!=-1) { //можно считать это костылём..
			$sWhereUser=' t.topic_publish = '.(int)$iPublish.' ';
			if ($oUser) {
				$sWhereUser.=' OR t.user_id = '.(int)$oUser->getId();
			}
		}
		$sql = "SELECT 
				t.*,
				u.user_login as user_login,
				b.blog_type as blog_type,	
				b.blog_url as blog_url,
				b.blog_title as blog_title,
				IF(tv.topic_id IS NULL,0,1) as user_is_vote,
				tv.vote_delta as user_vote_delta,
				IF(tqv.topic_id IS NULL,0,1) as user_question_is_vote			 
				FROM 
					".DB_TABLE_TOPIC." as t
					
					LEFT JOIN (
						SELECT
							topic_id,
							vote_delta												
						FROM ".DB_TABLE_TOPIC_VOTE." 
						WHERE user_voter_id = ?d
					) AS tv ON tv.topic_id = t.topic_id
					
					LEFT JOIN (
						SELECT
							topic_id																			
						FROM ".DB_TABLE_TOPIC_QUESTION_VOTE." 
						WHERE user_voter_id = ?d
					) AS tqv ON tqv.topic_id = t.topic_id,
					
					".DB_TABLE_USER." as u,
					".DB_TABLE_BLOG." as b
				WHERE 
					t.topic_id = ?d 
					AND 					
						(							
							".$sWhereUser."
						)
					AND
					t.user_id=u.user_id
					AND
					t.blog_id=b.blog_id
					";
		/**
		 * оптимизированный запрос
		 */
		$sql = "SELECT 
					t_fast.*,
					tc.*,
					u.user_login as user_login,
					b.blog_type as blog_type,	
					b.blog_url as blog_url,
					b.blog_title as blog_title,
					b.user_owner_id as blog_owner_id,
					IF(tv.topic_id IS NULL,0,1) as user_is_vote,
					tv.vote_delta as user_vote_delta,
					IF(tqv.topic_id IS NULL,0,1) as user_question_is_vote,
					bu.is_moderator as user_is_blog_moderator,
					bu.is_administrator as user_is_blog_administrator
				FROM
					(
						SELECT 
							t.*							 
						FROM 
							".DB_TABLE_TOPIC." as t					
						WHERE 
								t.topic_id = ?d 
							AND 					
							(								 
								".$sWhereUser."
							)					
					) AS t_fast
					JOIN ".DB_TABLE_USER." AS u ON t_fast.user_id=u.user_id 
					JOIN ".DB_TABLE_BLOG." AS b ON t_fast.blog_id=b.blog_id	
					LEFT JOIN (
						SELECT
							topic_id,
							vote_delta												
						FROM ".DB_TABLE_TOPIC_VOTE." 
						WHERE user_voter_id = ?d
					) AS tv ON t_fast.topic_id=tv.topic_id
					LEFT JOIN (
						SELECT
							is_moderator,
							is_administrator,
							blog_id												
						FROM ".DB_TABLE_BLOG_USER." 
						WHERE user_id = ?d
					) AS bu ON t_fast.blog_id=bu.blog_id
					LEFT JOIN (
						SELECT
							topic_id																			
						FROM ".DB_TABLE_TOPIC_QUESTION_VOTE." 
						WHERE user_voter_id = ?d
					) AS tqv ON t_fast.topic_id=tqv.topic_id
					JOIN  ".DB_TABLE_TOPIC_CONTENT." AS tc ON t_fast.topic_id=tc.topic_id	
					";
		if ($aRow=$this->oDb->selectRow($sql,$sId,$iCurrentUserId,$iCurrentUserId,$iCurrentUserId)) {
			return new TopicEntity_Topic($aRow);
		}
		return null;
	}
	
	
	public function GetTopicsByArrayId($aArrayId,$oUser,$iPublish) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
		
		$iCurrentUserId=-1;
		if (is_object($this->oUserCurrent)) {
			$iCurrentUserId=$this->oUserCurrent->getId();
		}
		$sWhereUser=' 1=1 ';
		if ($iPublish!=-1) { //можно считать это костылём..
			$sWhereUser=' t.topic_publish = '.(int)$iPublish.' ';
			if ($oUser) {
				$sWhereUser.=' OR t.user_id = '.(int)$oUser->getId();
			}
		}		
		$sql = "SELECT 
					t_fast.*,
					tc.*,
					u.user_login as user_login,
					b.blog_type as blog_type,	
					b.blog_url as blog_url,
					b.blog_title as blog_title,
					b.user_owner_id as blog_owner_id,
					IF(tv.topic_id IS NULL,0,1) as user_is_vote,
					tv.vote_delta as user_vote_delta,
					IF(tqv.topic_id IS NULL,0,1) as user_question_is_vote,
					bu.is_moderator as user_is_blog_moderator,
					bu.is_administrator as user_is_blog_administrator
				FROM
					(
						SELECT 
							t.*							 
						FROM 
							".DB_TABLE_TOPIC." as t					
						WHERE 
								t.topic_id IN(?a) 
							AND 					
							(								 
								".$sWhereUser."
							)					
					) AS t_fast
					JOIN ".DB_TABLE_USER." AS u ON t_fast.user_id=u.user_id 
					JOIN ".DB_TABLE_BLOG." AS b ON t_fast.blog_id=b.blog_id	
					LEFT JOIN (
						SELECT
							topic_id,
							vote_delta												
						FROM ".DB_TABLE_TOPIC_VOTE." 
						WHERE user_voter_id = ?d
					) AS tv ON t_fast.topic_id=tv.topic_id
					LEFT JOIN (
						SELECT
							is_moderator,
							is_administrator,
							blog_id												
						FROM ".DB_TABLE_BLOG_USER." 
						WHERE user_id = ?d
					) AS bu ON t_fast.blog_id=bu.blog_id
					LEFT JOIN (
						SELECT
							topic_id																			
						FROM ".DB_TABLE_TOPIC_QUESTION_VOTE." 
						WHERE user_voter_id = ?d
					) AS tqv ON t_fast.topic_id=tqv.topic_id
					JOIN  ".DB_TABLE_TOPIC_CONTENT." AS tc ON t_fast.topic_id=tc.topic_id	
					ORDER BY FIELD(t_fast.topic_id,?a)
					";
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$iCurrentUserId,$iCurrentUserId,$iCurrentUserId,$aArrayId)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=new TopicEntity_Topic($aTopic);
			}
		}		
		return $aTopics;
	}
	
	
	public function GetTopics($aFilter,&$iCount,$iCurrPage,$iPerPage) {	
		$iCurrentUserId=-1;
		if (is_object($this->oUserCurrent)) {
			$iCurrentUserId=$this->oUserCurrent->getId();
		}	
		$sWhere=$this->buildFilter($aFilter);	
			
		$sql = "SELECT 
					t.*,
					tc.*,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					IF(tv.topic_id IS NULL,0,1) as user_is_vote,
					tv.vote_delta as user_vote_delta					
				FROM 
					".DB_TABLE_TOPIC." as t
					
					LEFT JOIN (
						SELECT
							topic_id,
							vote_delta												
						FROM ".DB_TABLE_TOPIC_VOTE." 
						WHERE user_voter_id = ?d
					) AS tv ON tv.topic_id = t.topic_id
					LEFT JOIN  
						".DB_TABLE_TOPIC_CONTENT." 
					 AS tc ON tc.topic_id = t.topic_id,
					 
					".DB_TABLE_USER." as u,
					".DB_TABLE_BLOG." as b 
				WHERE 
					1=1
					
					".$sWhere."								
					
					AND
					t.blog_id=b.blog_id					
					AND			
					t.user_id=u.user_id					
				ORDER by t.topic_date_add desc
				LIMIT ?d, ?d
				;	
					";
		
		/**
		 * запрос немного оптимизирован, почуствуй разницу :)
		 * на самом деле его еще можно ускорить - во вложеном запросе убрать условие по типу блога и вынести его в JOIN + изменив фильтр(вынести из него тип блога)
		 * и вообще от фильтра нужно избавляться, т.к. эта универсальной сказывается на быстродействии из-за разных комбинаций ключей
		 * 
		 * при таком запросе приходиться отдельно запрашивать общее число записей
		 */
		$sql = "
				SELECT 
					t_fast.*, 
					tc.*,
					u.user_login as user_login,
					IF(tv.topic_id IS NULL,0,1) as user_is_vote,
					tv.vote_delta as user_vote_delta,
					IF(tqv.topic_id IS NULL,0,1) as user_question_is_vote,
					bu.is_moderator as user_is_blog_moderator,
					bu.is_administrator as user_is_blog_administrator,
					IF(tr.comment_count_last IS NULL,t_fast.topic_count_comment,t_fast.topic_count_comment-tr.comment_count_last) as count_comment_new 
				FROM (
					SELECT 
						t.*,	
						b.blog_title as blog_title,
						b.blog_type as blog_type,
						b.blog_url as blog_url,
						b.user_owner_id as blog_owner_id									
					FROM 
						".DB_TABLE_TOPIC." as t,	
						".DB_TABLE_BLOG." as b				
					WHERE 
						1=1
					
						".$sWhere."								
					
						AND
						t.blog_id=b.blog_id											
					ORDER by t.topic_date_add desc
					LIMIT ?d, ?d
				) as t_fast
				JOIN ".DB_TABLE_USER." AS u ON t_fast.user_id=u.user_id
				LEFT JOIN (
						SELECT
							topic_id,
							vote_delta												
						FROM ".DB_TABLE_TOPIC_VOTE." 
						WHERE user_voter_id = ?d
					) AS tv ON t_fast.topic_id=tv.topic_id 
				LEFT JOIN (
						SELECT
							topic_id,
							comment_count_last												
						FROM ".DB_TABLE_TOPIC_READ." 
						WHERE user_id = ?d
					) AS tr ON t_fast.topic_id=tr.topic_id
				LEFT JOIN (
						SELECT
							topic_id																			
						FROM ".DB_TABLE_TOPIC_QUESTION_VOTE." 
						WHERE user_voter_id = ?d
					) AS tqv ON t_fast.topic_id=tqv.topic_id
				LEFT JOIN (
						SELECT
							is_moderator,
							is_administrator,
							blog_id												
						FROM ".DB_TABLE_BLOG_USER." 
						WHERE user_id = ?d
					) AS bu ON t_fast.blog_id=bu.blog_id
				JOIN  ".DB_TABLE_TOPIC_CONTENT." AS tc ON t_fast.topic_id=tc.topic_id
				order by t_fast.topic_date_add desc
				;	
					";
		
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql,($iCurrPage-1)*$iPerPage, $iPerPage, $iCurrentUserId,$iCurrentUserId,$iCurrentUserId,$iCurrentUserId)) {			
			foreach ($aRows as $aTopic) {
				$aTopics[]=new TopicEntity_Topic($aTopic);
			}
		}
		$iCount=$this->GetCountTopics($aFilter);		
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
					t.blog_id=b.blog_id		
										
				;	
					";		
		if ($aRow=$this->oDb->selectRow($sql)) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function GetTopicsByTag($sTag,&$iCount,$iCurrPage,$iPerPage) {	
		$iCurrentUserId=-1;
		if (is_object($this->oUserCurrent)) {
			$iCurrentUserId=$this->oUserCurrent->getId();
		}			
		$sql = "SELECT 		
					t.*,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					IF(tv.topic_id IS NULL,0,1) as user_is_vote,
					tv.vote_delta as user_vote_delta					
				FROM 
					".DB_TABLE_TOPIC_TAG." as tt,
					".DB_TABLE_TOPIC." as t
					
					LEFT JOIN (
						SELECT
							topic_id,
							vote_delta												
						FROM ".DB_TABLE_TOPIC_VOTE." 
						WHERE user_voter_id = ?d
					) AS tv ON tv.topic_id = t.topic_id,
					
					".DB_TABLE_USER." as u,
					".DB_TABLE_BLOG." as b 
				WHERE 
					tt.topic_tag_text = ? 
					AND
					t.topic_id = tt.topic_id					 								
					AND
					t.topic_publish = 1
					AND					
					b.blog_type in ('personal','open')					
					AND
					t.blog_id=b.blog_id					
					AND			
					t.user_id=u.user_id					
				ORDER by t.topic_date_add desc 
				LIMIT ?d, ?d
				;	
					";
		/**
		 * оптимизирован
		 */
		$sql = "	SELECT
						t.*,
                        tc.*,
                        u.user_login as user_login,
                        b.blog_title as blog_title,
						b.blog_type as blog_type,
						b.blog_url as blog_url,
                        IF(tv.topic_id IS NULL,0,1) as user_is_vote,
						tv.vote_delta as user_vote_delta,
						IF(tqv.topic_id IS NULL,0,1) as user_question_is_vote 
					FROM (				
							SELECT 		
								topic_id										
							FROM 
								".DB_TABLE_TOPIC_TAG."								
							WHERE 
							topic_tag_text = ? 	
                            ORDER BY topic_id DESC	
                            LIMIT ?d, ?d				
						 ) as tt
						 JOIN ".DB_TABLE_TOPIC." AS t ON tt.topic_id=t.topic_id
						 JOIN ".DB_TABLE_USER." AS u ON t.user_id=u.user_id
						 JOIN ".DB_TABLE_BLOG." AS b ON t.blog_id=b.blog_id	
						 LEFT JOIN (
								SELECT
									topic_id,
									vote_delta												
								FROM ".DB_TABLE_TOPIC_VOTE." 
								WHERE user_voter_id = ?d
								) AS tv ON tt.topic_id=tv.topic_id
						 LEFT JOIN (
								SELECT
									topic_id																			
								FROM ".DB_TABLE_TOPIC_QUESTION_VOTE." 
								WHERE user_voter_id = ?d
								) AS tqv ON tt.topic_id=tqv.topic_id
                         LEFT JOIN ".DB_TABLE_TOPIC_CONTENT." AS tc ON tt.topic_id=tc.topic_id
                         order by t.topic_id desc
				;	
					";
		
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql,$sTag,($iCurrPage-1)*$iPerPage, $iPerPage,$iCurrentUserId,$iCurrentUserId)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=new TopicEntity_Topic($aTopic);
			}
			$iCount=$this->GetCountTopicsByTag($sTag);
		}
		return $aTopics;
	}
	
	public function GetCountTopicsByTag($sTag) {
		$sql = "SELECT 		
					count(topic_id) as count									
				FROM 
					".DB_TABLE_TOPIC_TAG."								
				WHERE 
					topic_tag_text = ? ;	
					";				
		if ($aRow=$this->oDb->selectRow($sql,$sTag)) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function GetTopicsRatingByDate($sDate,$iLimit) {		
		$iCurrentUserId=-1;
		if (is_object($this->oUserCurrent)) {
			$iCurrentUserId=$this->oUserCurrent->getId();
		}		
		$sql = "SELECT 
					t.*,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					IF(tv.topic_id IS NULL,0,1) as user_is_vote,
					tv.vote_delta as user_vote_delta					
				FROM 
					".DB_TABLE_TOPIC." as t
					
					LEFT JOIN (
						SELECT
							topic_id,
							vote_delta												
						FROM ".DB_TABLE_TOPIC_VOTE." 
						WHERE user_voter_id = ?d
					) AS tv ON tv.topic_id = t.topic_id,
					
					".DB_TABLE_USER." as u,
					".DB_TABLE_BLOG." as b 
				WHERE 
					t.topic_date_add >= ? 								
					AND
					t.topic_publish = 1
					AND					
					b.blog_type in ('personal','open')
					AND
					t.topic_rating >= 0
					AND
					t.blog_id=b.blog_id					
					AND			
					t.user_id=u.user_id					
				ORDER by t.topic_rating desc, t.topic_date_add desc
				LIMIT 0, ?d ;	
					";
		/**
		 * оптимизирован
		 */
		$sql = "SELECT
					t_fast.*,
					tc.*,
					u.user_login as user_login,
					IF(tv.topic_id IS NULL,0,1) as user_is_vote,
					tv.vote_delta as user_vote_delta,
					IF(tqv.topic_id IS NULL,0,1) as user_question_is_vote
				FROM (
					SELECT 
						t.*,
						b.blog_title as blog_title,
						b.blog_type as blog_type,
						b.blog_url as blog_url										
					FROM 
						".DB_TABLE_TOPIC." as t,					
						".DB_TABLE_BLOG." as b 
					WHERE 					
						t.topic_publish = 1
						AND
						t.topic_date_add >= ? 								
						AND
						t.topic_rating >= 0
						AND
						t.blog_id=b.blog_id
						AND					
						b.blog_type in ('personal','open')											
					ORDER by t.topic_rating desc, t.topic_id desc
					LIMIT 0, ?d 	
					) AS t_fast
					JOIN ".DB_TABLE_USER." AS u ON t_fast.user_id=u.user_id
					LEFT JOIN (
								SELECT
									topic_id,
									vote_delta												
								FROM ".DB_TABLE_TOPIC_VOTE." 
								WHERE user_voter_id = ?d
								) AS tv ON t_fast.topic_id=tv.topic_id
					LEFT JOIN (
								SELECT
									topic_id																			
								FROM ".DB_TABLE_TOPIC_QUESTION_VOTE." 
								WHERE user_voter_id = ?d
								) AS tqv ON t_fast.topic_id=tqv.topic_id
					JOIN ".DB_TABLE_TOPIC_CONTENT." AS tc ON t_fast.topic_id=tc.topic_id 
					order by t_fast.topic_rating desc
					";
		
		$aTopics=array();
		if ($aRows=$this->oDb->select($sql,$sDate,$iLimit,$iCurrentUserId,$iCurrentUserId)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=new TopicEntity_Topic($aTopic);
			}
		}
		return $aTopics;
	}
	
	public function GetTopicTags($iLimit) {
		$sql = "SELECT 
			tt.topic_tag_text,
			count(tt.topic_tag_text)	as count		 
			FROM 
				".DB_TABLE_TOPIC_TAG." as tt, 
				".DB_TABLE_TOPIC." as t		
			WHERE
				t.topic_id=tt.topic_id
				AND
				t.topic_publish = 1		
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
				$aReturn[$aRow['topic_tag_text']]=$aRow;
			}			
			ksort($aReturn);			
			foreach ($aReturn as $aRow) {
				$aReturnSort[]=new TopicEntity_TopicTag($aRow);				
			}
		}
		return $aReturnSort;
	}
	
	public function GetTopicTagsByUserId($sUserId,$iLimit) {
		$sql = "SELECT 
			topic_tag_text,
			count(topic_tag_text)	as count		 
			FROM 
				".DB_TABLE_TOPIC_TAG."	
			WHERE
				user_id = ?			
			GROUP BY 
				topic_tag_text
			ORDER BY 
				topic_tag_text ASC		
			LIMIT 0, ?d		
				";	
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=new TopicEntity_TopicTag($aRow);
			}
		}
		return $aReturn;
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
				topic_date_edit = ?,
				topic_user_ip= ?,
				topic_publish= ? ,
				topic_publish_index= ?,
				topic_rating= ?f,
				topic_count_vote= ?d,
				topic_count_read= ?d,
				topic_count_comment= ?d, 
				topic_cut_text = ? ,
				topic_forbid_comment = ? 
			WHERE
				topic_id = ?d
		";			
		if ($this->oDb->query($sql,$oTopic->getBlogId(),$oTopic->getTitle(),$oTopic->getTags(),$oTopic->getDateEdit(),$oTopic->getUserIp(),$oTopic->getPublish(),$oTopic->getPublishIndex(),$oTopic->getRating(),$oTopic->getCountVote(),$oTopic->getCountRead(),$oTopic->getCountComment(),$oTopic->getCutText(),$oTopic->getForbidComment(),$oTopic->getId())) {
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
		$iCurrentUserId=-1;
		if (is_object($this->oUserCurrent)) {
			$iCurrentUserId=$this->oUserCurrent->getId();
		}		
		$sql = "SELECT 
					t.*,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					IF(tv.topic_id IS NULL,0,1) as user_is_vote,
					tv.vote_delta as user_vote_delta					
				FROM 
					".DB_TABLE_FAVOURITE_TOPIC." as ft,
					".DB_TABLE_TOPIC." as t
					
					LEFT JOIN (
						SELECT
							topic_id,
							vote_delta												
						FROM ".DB_TABLE_TOPIC_VOTE." 
						WHERE user_voter_id = ?d
					) AS tv ON tv.topic_id = t.topic_id,
					
					".DB_TABLE_USER." as u,
					".DB_TABLE_BLOG." as b 
				WHERE 
					ft.user_id = ? 								
					AND
					ft.topic_id=t.topic_id
					AND
					t.topic_publish = 1
					AND					
					b.blog_type in ('personal','open')					
					AND
					t.blog_id=b.blog_id					
					AND			
					t.user_id=u.user_id					
				ORDER BY t.topic_date_add desc
				LIMIT ?d, ?d ;	
					";
		
		
		
		
		
		$sql = "	SELECT
						t.*,
                        tc.*,
                        u.user_login as user_login,
                        b.blog_title as blog_title,
						b.blog_type as blog_type,
						b.blog_url as blog_url,
                        IF(tv.topic_id IS NULL,0,1) as user_is_vote,
						tv.vote_delta as user_vote_delta,
						IF(tqv.topic_id IS NULL,0,1) as user_question_is_vote 
					FROM (				
							SELECT 		
								topic_id										
							FROM 
								".DB_TABLE_FAVOURITE_TOPIC."								
							WHERE 
								user_id = ?
								and
								topic_publish = 1 	
                            ORDER BY topic_id DESC	
                            LIMIT ?d, ?d				
						 ) as tt
						 JOIN ".DB_TABLE_TOPIC." AS t ON tt.topic_id=t.topic_id
						 JOIN ".DB_TABLE_USER." AS u ON t.user_id=u.user_id
						 JOIN ".DB_TABLE_BLOG." AS b ON t.blog_id=b.blog_id	
						 LEFT JOIN (
								SELECT
									topic_id,
									vote_delta												
								FROM ".DB_TABLE_TOPIC_VOTE." 
								WHERE user_voter_id = ?d
								) AS tv ON tt.topic_id=tv.topic_id
						 LEFT JOIN (
								SELECT
									topic_id																			
								FROM ".DB_TABLE_TOPIC_QUESTION_VOTE." 
								WHERE user_voter_id = ?d
								) AS tqv ON tt.topic_id=tqv.topic_id
                         LEFT JOIN ".DB_TABLE_TOPIC_CONTENT." AS tc ON tt.topic_id=tc.topic_id
                         order by t.topic_id DESC
				;	
					";
		
		$aTopics=array();		
		if ($aRows=$this->oDb->select($sql,$sUserId,($iCurrPage-1)*$iPerPage, $iPerPage,$iCurrentUserId,$iCurrentUserId)) {
			foreach ($aRows as $aTopic) {
				$aTopics[]=new TopicEntity_Topic($aTopic);
			}
			$iCount=$this->GetCountTopicsFavouriteByUserId($sUserId);
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
	
	public function SetDateRead($sTopicId,$sUserId,$iCountComment) {
		$sDate=date("Y-m-d H:i:s");
		$sql = "REPLACE ".DB_TABLE_TOPIC_READ." 
			SET 
				comment_count_last = ? ,
				date_read = ? ,
				topic_id = ? ,				
				user_id = ? 
		";			
		return $this->oDb->query($sql,$iCountComment,$sDate,$sTopicId,$sUserId);
	}			
				
	public function GetDateRead($sTopicId,$sUserId) {			
		$sql = "SELECT 
					date_read									
				FROM 
					".DB_TABLE_TOPIC_READ."					 
				WHERE 					
					topic_id = ?d					
					AND			
					user_id = ?d					
				;	
					";		
		if ($aRow=$this->oDb->selectRow($sql,$sTopicId,$sUserId)) {
			return $aRow['date_read'];
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