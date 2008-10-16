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
			blog_url
			)
			VALUES(?d,  ?,	?,	?,	?,	?, ?)
		";			
		if ($iId=$this->oDb->query($sql,$oBlog->getOwnerId(),$oBlog->getTitle(),$oBlog->getDescription(),$oBlog->getType(),$oBlog->getDateAdd(),$oBlog->getLimitRatingTopic(),$oBlog->getUrl())) {
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
				blog_url= ?
			WHERE
				blog_id = ?d
		";			
		if ($this->oDb->query($sql,$oBlog->getTitle(),$oBlog->getDescription(),$oBlog->getType(),$oBlog->getDateEdit(),$oBlog->getRating(),$oBlog->getCountVote(),$oBlog->getCountUser(),$oBlog->getLimitRatingTopic(),$oBlog->getUrl(),$oBlog->getId())) {
			return true;
		}		
		return false;
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
		
	
	public function GetRelationBlogUsers($aFilter) {
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
					bu.*,
					u.user_login as user_login,
					u.user_mail as user_mail,
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					u.user_settings_notice_new_topic,
					u.user_settings_notice_new_comment,
					u.user_settings_notice_new_talk,
					u.user_settings_notice_reply_comment,
					b.blog_title as blog_title,	
					b.blog_url as blog_url				
				FROM 
					".DB_TABLE_BLOG_USER." as bu,
					".DB_TABLE_USER." as u,
					".DB_TABLE_BLOG." as b 
				WHERE 
					".$sWhere." 
					AND
					bu.blog_id=b.blog_id
					AND
					bu.user_id=u.user_id
					
				ORDER by u.user_login asc;	
					";		
		$aBlogUsers=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aUser) {
				$aBlogUsers[]=new BlogEntity_BlogUser($aUser);
			}
		}
		return $aBlogUsers;
	}
	
	
	public function GetPersonalBlogByUserId($sUserId) {
		$sql = "SELECT * FROM ".DB_TABLE_BLOG." WHERE user_owner_id = ?d and blog_type='personal'";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId)) {
			return new BlogEntity_Blog($aRow);
		}
		return null;
	}
	
	public function GetBlogById($sId) {
		$sql = "SELECT * FROM ".DB_TABLE_BLOG." WHERE blog_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sId)) {
			return new BlogEntity_Blog($aRow);
		}
		return null;
	}
	
	public function GetBlogByTitle($sTitle) {
		$sql = "SELECT * FROM ".DB_TABLE_BLOG." WHERE blog_title = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sTitle)) {
			return new BlogEntity_Blog($aRow);
		}
		return null;
	}
	
	public function GetBlogVote($sBlogId,$sUserId) {
		$sql = "SELECT * FROM ".DB_TABLE_BLOG_VOTE." WHERE blog_id = ?d and user_voter_id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$sBlogId,$sUserId)) {
			return new BlogEntity_BlogVote($aRow);
		}
		return null;
	}
	
	
	
	public function GetBlogByUrl($sUrl) {
		$iCurrentUserId=-1;
		if (is_object($this->oUserCurrent)) {
			$iCurrentUserId=$this->oUserCurrent->getId();
		}
		$sql = "SELECT 
			b.*,
			u.user_login as user_login,
			u.user_profile_avatar as user_profile_avatar,
			u.user_profile_avatar_type as user_profile_avatar_type,
			IF(bv.blog_id IS NULL,0,1) as user_is_vote,
			bv.vote_delta as user_vote_delta	 
			FROM 
				".DB_TABLE_BLOG." as b
				
				LEFT JOIN (
						SELECT
							blog_id,
							vote_delta												
						FROM ".DB_TABLE_BLOG_VOTE." 
						WHERE user_voter_id = ?d
					) AS bv ON bv.blog_id = b.blog_id,
				
				".DB_TABLE_USER." as u
			WHERE 
				b.blog_url = ? 
				AND
				b.user_owner_id = u.user_id
				";
		if ($aRow=$this->oDb->selectRow($sql,$iCurrentUserId,$sUrl)) {
			return new BlogEntity_Blog($aRow);
		}
		return null;
	}
	
	public function GetBlogsByOwnerId($sUserId) {
		$sql = "SELECT 
			b.*			 
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
				$aBlogs[]=new BlogEntity_Blog($aBlog);
			}
		}
		return $aBlogs;
	}
	
	public function GetBlogs() {
		$sql = "SELECT 
			b.*			 
			FROM 
				".DB_TABLE_BLOG." as b				
			WHERE 				
				b.blog_type<>'personal'				
				";	
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=new BlogEntity_Blog($aBlog);
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
	

	
	public function GetBlogsRating($iLimit) {
		$sql = "SELECT 
					b.*,					
					u.user_profile_avatar as user_profile_avatar,
					u.user_profile_avatar_type as user_profile_avatar_type,
					u.user_login as user_login													
				FROM 
					".DB_TABLE_BLOG." as b,					
					".DB_TABLE_USER." as u					 
				WHERE 						
					b.blog_rating >= 0
					AND					
					b.blog_type<>'personal'
					AND		
					b.user_owner_id=u.user_id								
				ORDER by b.blog_rating desc
				LIMIT 0, ?d 
				;	
					";		
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=new BlogEntity_Blog($aRow);
			}
		}
		return $aReturn;
	}
	
	
}
?>