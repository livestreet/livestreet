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

class Mapper_TopicComment extends Mapper {	
		public function GetCommentsRatingByDate($sDate,$iLimit) {
		$sql = "SELECT 
					c.*,
					t.topic_title as topic_title,
					t.topic_count_comment as topic_count_comment,
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					u_owner.user_login	as blog_owner_login					
				FROM 
					".DB_TABLE_TOPIC_COMMENT." as c,
					".DB_TABLE_TOPIC." as t,
					".DB_TABLE_USER." as u,
					".DB_TABLE_BLOG." as b,
					".DB_TABLE_USER." as u_owner 
				WHERE 				
					c.comment_date >= ? 
					AND
					c.comment_rating >= 0
					AND
					c.topic_id=t.topic_id
					AND			
					c.user_id=u.user_id
					AND
					t.blog_id=b.blog_id	
					AND
					b.user_owner_id=u_owner.user_id			
				ORDER by c.comment_rating desc, c.comment_date desc
				LIMIT 0, ?d 
				;	
					";	
		/**
		 * оптимизирован
		 */
		$sql = "SELECT 
					c_full.*,
					t.topic_title as topic_title,
					t.topic_count_comment as topic_count_comment,
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					u_owner.user_login	as blog_owner_login
				FROM (					
					SELECT 
						c.comment_id										
					FROM 
						".DB_TABLE_TOPIC_COMMENT." as c	force INDEX(rating_date_id)				
					WHERE 	
						c.comment_rating >= 0	
						AND		
						c.comment_date >= ? 											
					ORDER by c.comment_rating desc, c.comment_date desc
					LIMIT 0, ?d 
					) as c_fast
					JOIN ".DB_TABLE_TOPIC_COMMENT." AS c_full ON c_fast.comment_id=c_full.comment_id
					JOIN ".DB_TABLE_USER." AS u ON c_full.user_id=u.user_id
					JOIN ".DB_TABLE_TOPIC." AS t ON c_full.topic_id=t.topic_id					
					JOIN ".DB_TABLE_BLOG." AS b ON t.blog_id=b.blog_id
					JOIN ".DB_TABLE_USER." AS u_owner ON b.user_owner_id=u_owner.user_id
				;	
					";
			
		$aComments=array();
		if ($aRows=$this->oDb->select($sql,$sDate,$iLimit)) {
			foreach ($aRows as $aTopicComment) {
				$aComments[]=new CommentEntity_TopicComment($aTopicComment);
			}
		}
		return $aComments;
	}
	
	public function GetCommentById($sId) {
		$sql = "SELECT * FROM ".DB_TABLE_TOPIC_COMMENT." WHERE comment_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sId)) {
			return new CommentEntity_TopicComment($aRow);
		}
		return null;
	}
	
	public function GetCommentsAll(&$iCount,$iCurrPage,$iPerPage) {
		$sql = "SELECT 					
					c.*,
					t.topic_title as topic_title,
					t.topic_count_comment as topic_count_comment,
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					u_owner.user_login	as blog_owner_login					
				FROM 
					".DB_TABLE_TOPIC_COMMENT." as c,
					".DB_TABLE_TOPIC." as t,
					".DB_TABLE_USER." as u,
					".DB_TABLE_BLOG." as b,
					".DB_TABLE_USER." as u_owner 
				WHERE 								
					c.topic_id=t.topic_id
					AND
					t.topic_publish = 1
					AND			
					c.user_id=u.user_id
					AND
					t.blog_id=b.blog_id
					AND
					b.user_owner_id=u_owner.user_id
				ORDER by c.comment_date desc
				LIMIT ?d, ?d
				;	
					";	
		/**
		 * оптимизирован
		 */
		$sql = "SELECT
					c_fast.*,
					c_full.*,
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					u_owner.user_login	as blog_owner_login
				FROM (
					SELECT 					
						c.comment_id,
						t.topic_title as topic_title,
						t.topic_count_comment as topic_count_comment,
						t.blog_id									
					FROM 
						".DB_TABLE_TOPIC_COMMENT." as c,
						".DB_TABLE_TOPIC." as t					 
					WHERE 								
						c.topic_id=t.topic_id
						AND
						t.topic_publish = 1					
					ORDER by c.comment_id desc
					LIMIT ?d, ?d
					) AS c_fast
					JOIN ".DB_TABLE_TOPIC_COMMENT." AS c_full ON c_fast.comment_id=c_full.comment_id
					JOIN ".DB_TABLE_USER." AS u ON c_full.user_id=u.user_id
					JOIN ".DB_TABLE_BLOG." AS b ON c_fast.blog_id=b.blog_id
					JOIN ".DB_TABLE_USER." AS u_owner ON b.user_owner_id=u_owner.user_id
										
					";
			
		$aComments=array();
		if ($aRows=$this->oDb->select($sql,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aTopicComment) {
				$aComments[]=new CommentEntity_TopicComment($aTopicComment);
			}
			$iCount=$this->GetCountCommentsAll();
		}
		return $aComments;
	}
	
	public function GetCountCommentsAll() {
		$sql = "SELECT 					
						count(c.comment_id) as count															
					FROM 
						".DB_TABLE_TOPIC_COMMENT." as c,
						".DB_TABLE_TOPIC." as t					 
					WHERE 								
						c.topic_id=t.topic_id
						AND
						t.topic_publish = 1					
					";		
		if ($aRow=$this->oDb->selectRow($sql)) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function GetCommentsAllGroup($iLimit) {
		/**
		 * это ацкий запрос
		 * для его оптимизации нужно создавать отдельную таблицу с прямым эфиром
		 */
		$sql = "SELECT 					
					c.*,
					t.topic_title as topic_title,
					t.topic_count_comment as topic_count_comment,
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					u_owner.user_login	as blog_owner_login				
				FROM 
					".DB_TABLE_TOPIC_COMMENT." as c,
					".DB_TABLE_TOPIC." as t,
					".DB_TABLE_USER." as u,					
					".DB_TABLE_BLOG." as b,
					".DB_TABLE_USER." as u_owner 
				WHERE 	
					c.comment_id=(SELECT comment_id FROM ".DB_TABLE_TOPIC_COMMENT." WHERE topic_id=t.topic_id AND t.topic_publish=1 ORDER BY comment_date DESC LIMIT 0,1)
					AND				
					c.topic_id=t.topic_id
					AND
					t.topic_publish = 1
					AND			
					c.user_id=u.user_id					
					AND
					t.blog_id=b.blog_id
					AND
					b.user_owner_id=u_owner.user_id
				/*GROUP BY c.topic_id
				*/
				ORDER by c.comment_date desc limit 0, ?d ;	
					";		
		$aComments=array();
		if ($aRows=$this->oDb->select($sql,$iLimit)) {
			foreach ($aRows as $aTopicComment) {
				$aComments[]=new CommentEntity_TopicComment($aTopicComment);
			}
		}
		return $aComments;
	}
	
	public function GetCommentsByTopicId($sId,$oUserCurrent) {
		$iCurrentUserId=-1;
		if (is_object($oUserCurrent)) {
			$iCurrentUserId=$oUserCurrent->getId();
		}
		$sql = "SELECT 
					c.*,
					u.user_login as user_login,
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					c.comment_id as ARRAY_KEY,
					c.comment_pid as PARENT_KEY,
					IF(cv.comment_id IS NULL,0,1) as user_is_vote,
					cv.vote_delta as user_vote_delta
				FROM 
					".DB_TABLE_TOPIC_COMMENT." as c
					
					LEFT JOIN (
						SELECT
							comment_id,
							vote_delta												
						FROM ".DB_TABLE_TOPIC_COMMENT_VOTE." 
						WHERE user_voter_id = ?d
					) AS cv ON cv.comment_id = c.comment_id,
					
					".DB_TABLE_USER." as u 					
				WHERE 
					c.topic_id = ?d 
					AND
					c.user_id=u.user_id
				ORDER by c.comment_id asc;	
					";
		if ($aRows=$this->oDb->select($sql,$iCurrentUserId,$sId)) {
			return $aRows;
		}
		return null;
	}
	
	public function GetCommentsByUserId($sId,&$iCount,$iCurrPage,$iPerPage) {
		$sql = "SELECT 
					c.*,
					t.topic_title as topic_title,
					t.topic_count_comment as topic_count_comment,
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					u.user_login as user_login,
					b.blog_title as blog_title,
					b.blog_type as blog_type,
					b.blog_url as blog_url,
					u_owner.user_login	as blog_owner_login						
				FROM 
					".DB_TABLE_TOPIC_COMMENT." as c,
					".DB_TABLE_TOPIC." as t,
					".DB_TABLE_USER." as u,
					".DB_TABLE_BLOG." as b,
					".DB_TABLE_USER." as u_owner 
				WHERE 
					c.user_id = ?d 
					AND
					c.topic_id=t.topic_id
					AND
					t.topic_publish = 1
					AND			
					c.user_id=u.user_id
					AND
					t.blog_id=b.blog_id
					AND
					b.user_owner_id=u_owner.user_id
				ORDER by c.comment_id desc
				LIMIT ?d, ?d
				;	
					";		
		$aComments=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,$sId,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aTopicComment) {
				$aComments[]=new CommentEntity_TopicComment($aTopicComment);
			}
		}
		return $aComments;
	}
	
	public function GetCountCommentsByUserId($sId) {
		$sql = "SELECT 
					count(c.comment_id) as count					
				FROM 
					".DB_TABLE_TOPIC_COMMENT." as c,
					".DB_TABLE_TOPIC." as t
				WHERE 
					c.user_id = ?d 
					AND
					c.topic_id=t.topic_id
					AND
					t.topic_publish = 1	;	
					";		
		if ($aRow=$this->oDb->selectRow($sql,$sId)) {
			return $aRow['count'];
		}
		return false;
	}
	
	public function AddComment(CommentEntity_TopicComment $oComment) {
		$sql = "INSERT INTO ".DB_TABLE_TOPIC_COMMENT." 
			(comment_pid,
			topic_id,
			user_id,
			comment_text,
			comment_date,
			comment_user_ip		
			)
			VALUES(?,  ?d,	?d,	?,	?,	?)
		";			
		if ($iId=$this->oDb->query($sql,$oComment->getPid(),$oComment->getTopicId(),$oComment->getUserId(),$oComment->getText(),$oComment->getDate(),$oComment->getUserIp())) 
		{
			return $iId;
		}		
		return false;
	}
	
	public function AddTopicCommentVote(CommentEntity_TopicCommentVote $oTopicCommentVote) {
		$sql = "INSERT INTO ".DB_TABLE_TOPIC_COMMENT_VOTE." 
			(comment_id,
			user_voter_id,
			vote_delta		
			)
			VALUES(?d,  ?d,	?f)
		";			
		if ($this->oDb->query($sql,$oTopicCommentVote->getCommentId(),$oTopicCommentVote->getVoterId(),$oTopicCommentVote->getDelta())===0) 
		{
			return true;
		}		
		return false;
	}
	
	public function GetTopicCommentVote($sCommentId,$sUserId) {
		$sql = "SELECT * FROM ".DB_TABLE_TOPIC_COMMENT_VOTE." WHERE comment_id = ?d and user_voter_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sCommentId,$sUserId)) {
			return new CommentEntity_TopicCommentVote($aRow);
		}
		return null;
	}
	
	public function UpdateTopicComment(CommentEntity_TopicComment $oTopicComment) {		
		$sql = "UPDATE ".DB_TABLE_TOPIC_COMMENT." 
			SET 
				comment_text= ?,
				comment_rating= ?f,
				comment_count_vote= ?d
			WHERE
				comment_id = ?d
		";			
		if ($this->oDb->query($sql,$oTopicComment->getText(),$oTopicComment->getRating(),$oTopicComment->getCountVote(),$oTopicComment->getId())) {
			return true;
		}		
		return false;
	}
}
?>