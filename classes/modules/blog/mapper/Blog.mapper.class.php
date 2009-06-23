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

class Mapper_Blog extends Mapper {	
	protected $oUserCurrent=null;
	
	public function SetUserCurrent($oUserCurrent)  {
		$this->oUserCurrent=$oUserCurrent;
	}
	
	public function AddBlog(BlogEntity_Blog $oBlog) {
		$sql = "INSERT INTO ".DB_TABLE_BLOG." 
			(user_owner_id,
			blog_title,
			blog_description,
			blog_type,			
			blog_date_add,
			blog_limit_rating_topic,
			blog_url,
			blog_avatar,
			blog_avatar_type
			)
			VALUES(?d,  ?,	?,	?,	?,	?, ?, ?, ?)
		";			
		if ($iId=$this->oDb->query($sql,$oBlog->getOwnerId(),$oBlog->getTitle(),$oBlog->getDescription(),$oBlog->getType(),$oBlog->getDateAdd(),$oBlog->getLimitRatingTopic(),$oBlog->getUrl(),$oBlog->getAvatar(),$oBlog->getAvatarType())) {
			return $iId;
		}		
		return false;
	}
	
	public function UpdateBlog(BlogEntity_Blog $oBlog) {		
		$sql = "UPDATE ".DB_TABLE_BLOG." 
			SET 
				blog_title= ?,
				blog_description= ?,
				blog_type= ?,
				blog_date_edit= ?,
				blog_rating= ?f,
				blog_count_vote = ?d,
				blog_count_user= ?d,
				blog_limit_rating_topic= ?f ,
				blog_url= ?,
				blog_avatar= ?,
				blog_avatar_type= ?
			WHERE
				blog_id = ?d
		";			
		if ($this->oDb->query($sql,$oBlog->getTitle(),$oBlog->getDescription(),$oBlog->getType(),$oBlog->getDateEdit(),$oBlog->getRating(),$oBlog->getCountVote(),$oBlog->getCountUser(),$oBlog->getLimitRatingTopic(),$oBlog->getUrl(),$oBlog->getAvatar(),$oBlog->getAvatarType(),$oBlog->getId())) {
			return true;
		}		
		return false;
	}
	
	public function GetBlogsByArrayId($aArrayId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					b.*							 
				FROM 
					".DB_TABLE_BLOG." as b					
				WHERE 
					b.blog_id IN(?a) 								
				ORDER BY FIELD(b.blog_id,?a) ";
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=new BlogEntity_Blog($aBlog);
			}
		}		
		return $aBlogs;
	}	
	
	public function AddRelationBlogUser(BlogEntity_BlogUser $oBlogUser) {
		$sql = "INSERT INTO ".DB_TABLE_BLOG_USER." 
			(blog_id,
			user_id
			)
			VALUES(?d,  ?d)
		";			
		if ($this->oDb->query($sql,$oBlogUser->getBlogId(),$oBlogUser->getUserId())===0) {
			return true;
		}		
		return false;
	}
	
	public function DeleteRelationBlogUser(BlogEntity_BlogUser $oBlogUser) {
		$sql = "DELETE FROM ".DB_TABLE_BLOG_USER." 
			WHERE
				blog_id = ?d
				AND
				user_id = ?d
		";			
		if ($this->oDb->query($sql,$oBlogUser->getBlogId(),$oBlogUser->getUserId())) {
			return true;
		}		
		return false;
	}
		
	public function UpdateRelationBlogUser(BlogEntity_BlogUser $oBlogUser) {		
		$sql = "UPDATE ".DB_TABLE_BLOG_USER." 
			SET 
				is_moderator= ?,
				is_administrator= ?				
			WHERE
				blog_id = ?d 
				AND
				user_id = ?d
		";			
		if ($this->oDb->query($sql,$oBlogUser->getIsModerator(),$oBlogUser->getIsAdministrator(),$oBlogUser->getBlogId(),$oBlogUser->getUserId())) {
			return true;
		}		
		return false;
	}
	
	public function GetBlogUsers($aFilter) {
		$sWhere=' 1=1 ';
		if (isset($aFilter['blog_id'])) {
			$sWhere.=" AND bu.blog_id =  ".(int)$aFilter['blog_id'];
		}
		if (isset($aFilter['is_moderator'])) {
			$sWhere.=" AND bu.is_moderator =  ".(int)$aFilter['is_moderator'];
		}
		if (isset($aFilter['is_administrator'])) {
			$sWhere.=" AND bu.is_administrator =  ".(int)$aFilter['is_administrator'];
		}
		if (isset($aFilter['user_id'])) {
			$sWhere.=" AND bu.user_id =  ".(int)$aFilter['user_id'];
		}
		$sql = "SELECT 
					bu.*				
				FROM 
					".DB_TABLE_BLOG_USER." as bu
				WHERE 
					".$sWhere." 					
				;	
					";		
		$aBlogUsers=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aUser) {
				$aBlogUsers[]=new BlogEntity_BlogUser($aUser);
			}
		}
		return $aBlogUsers;
	}
	
	public function GetBlogUsersByArrayBlog($aArrayId,$sUserId) {	
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
			
		$sql = "SELECT 
					bu.*				
				FROM 
					".DB_TABLE_BLOG_USER." as bu
				WHERE 
					bu.user_id = ?d
					AND
					bu.blog_id IN(?a) ";		
		$aBlogUsers=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$aArrayId)) {
			foreach ($aRows as $aUser) {
				$aBlogUsers[]=new BlogEntity_BlogUser($aUser);
			}
		}
		return $aBlogUsers;
	}
	
		
	public function GetPersonalBlogByUserId($sUserId) {
		$sql = "SELECT blog_id FROM ".DB_TABLE_BLOG." WHERE user_owner_id = ?d and blog_type='personal'";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId)) {
			return $aRow['blog_id'];
		}
		return null;
	}
	
		
	public function GetBlogByTitle($sTitle) {
		$sql = "SELECT blog_id FROM ".DB_TABLE_BLOG." WHERE blog_title = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sTitle)) {
			return $aRow['blog_id'];
		}
		return null;
	}
	

	
	public function GetBlogsVoteByArray($aArrayId,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}
				
		$sql = "SELECT 
					v.*							 
				FROM 
					".DB_TABLE_BLOG_VOTE." as v 
				WHERE 
					v.user_voter_id = ?d
					AND
					v.blog_id IN(?a) 									
				";
		$aVotes=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$aArrayId)) {
			foreach ($aRows as $aRow) {
				$aVotes[]=new BlogEntity_BlogVote($aRow);
			}
		}		
		return $aVotes;
	}
	
	public function GetBlogByUrl($sUrl) {		
		$sql = "SELECT 
				b.blog_id 
			FROM 
				".DB_TABLE_BLOG." as b
			WHERE 
				b.blog_url = ? 		
				";
		if ($aRow=$this->oDb->selectRow($sql,$sUrl)) {
			return $aRow['blog_id'];
		}
		return null;
	}
	
	public function GetBlogsByOwnerId($sUserId) {
		$sql = "SELECT 
			b.blog_id			 
			FROM 
				".DB_TABLE_BLOG." as b				
			WHERE 
				b.user_owner_id = ? 
				AND
				b.blog_type<>'personal'				
				";	
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql,$sUserId)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=$aBlog['blog_id'];
			}
		}
		return $aBlogs;
	}
	
	public function GetBlogs() {
		$sql = "SELECT 
			b.blog_id			 
			FROM 
				".DB_TABLE_BLOG." as b				
			WHERE 				
				b.blog_type<>'personal'				
				";	
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=$aBlog['blog_id'];
			}
		}
		return $aBlogs;
	}
	
	public function AddBlogVote(BlogEntity_BlogVote $oBlogVote) {
		$sql = "INSERT INTO ".DB_TABLE_BLOG_VOTE." 
			(blog_id,
			user_voter_id,
			vote_delta		
			)
			VALUES(?d,  ?d,	?f)
		";			
		if ($this->oDb->query($sql,$oBlogVote->getBlogId(),$oBlogVote->getVoterId(),$oBlogVote->getDelta())===0) 
		{
			return true;
		}		
		return false;
	}
	

	
	public function GetBlogsRating(&$iCount,$iCurrPage,$iPerPage) {		
		$sql = "SELECT 
					b.blog_id													
				FROM 
					".DB_TABLE_BLOG." as b 									 
				WHERE 									
					b.blog_type<>'personal' 												
				ORDER by b.blog_rating desc
				LIMIT ?d, ?d 	";		
		$aReturn=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['blog_id'];
			}
		}
		return $aReturn;
	}
	
	public function GetBlogsRatingJoin($sUserId,$iLimit) {		
		$sql = "SELECT 
					b.*													
				FROM 
					".DB_TABLE_BLOG_USER." as bu,
					".DB_TABLE_BLOG." as b	
				WHERE 	
					bu.user_id = ?d
					AND
					bu.blog_id = b.blog_id
					AND				
					b.blog_type<>'personal'													
				ORDER by b.blog_rating desc
				LIMIT 0, ?d 
				;	
					";		
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=new BlogEntity_Blog($aRow);
			}
		}
		return $aReturn;
	}
	
	public function GetBlogsRatingSelf($sUserId,$iLimit) {		
		$sql = "SELECT 
					b.*													
				FROM 					
					".DB_TABLE_BLOG." as b	
				WHERE 						
					b.user_owner_id = ?d
					AND				
					b.blog_type<>'personal'													
				ORDER by b.blog_rating desc
				LIMIT 0, ?d 
				;	
					";		
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=new BlogEntity_Blog($aRow);
			}
		}
		return $aReturn;
	}
	
}
?>