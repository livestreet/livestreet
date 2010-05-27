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

class ModuleBlog_MapperBlog extends Mapper {	
	protected $oUserCurrent=null;
	
	public function SetUserCurrent($oUserCurrent)  {
		$this->oUserCurrent=$oUserCurrent;
	}
	
	public function AddBlog(ModuleBlog_EntityBlog $oBlog) {
		$sql = "INSERT INTO ".Config::Get('db.table.blog')." 
			(user_owner_id,
			blog_title,
			blog_description,
			blog_type,			
			blog_date_add,
			blog_limit_rating_topic,
			blog_url,
			blog_avatar
			)
			VALUES(?d,  ?,	?,	?,	?,	?, ?, ?)
		";			
		if ($iId=$this->oDb->query($sql,$oBlog->getOwnerId(),$oBlog->getTitle(),$oBlog->getDescription(),$oBlog->getType(),$oBlog->getDateAdd(),$oBlog->getLimitRatingTopic(),$oBlog->getUrl(),$oBlog->getAvatar())) {
			return $iId;
		}		
		return false;
	}
	
	public function UpdateBlog(ModuleBlog_EntityBlog $oBlog) {		
		$sql = "UPDATE ".Config::Get('db.table.blog')." 
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
				blog_avatar= ?
			WHERE
				blog_id = ?d
		";			
		if ($this->oDb->query($sql,$oBlog->getTitle(),$oBlog->getDescription(),$oBlog->getType(),$oBlog->getDateEdit(),$oBlog->getRating(),$oBlog->getCountVote(),$oBlog->getCountUser(),$oBlog->getLimitRatingTopic(),$oBlog->getUrl(),$oBlog->getAvatar(),$oBlog->getId())) {
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
					".Config::Get('db.table.blog')." as b					
				WHERE 
					b.blog_id IN(?a) 								
				ORDER BY FIELD(b.blog_id,?a) ";
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$aArrayId)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=Engine::GetEntity('Blog',$aBlog);
			}
		}
		return $aBlogs;
	}	
	
	public function AddRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {
		$sql = "INSERT INTO ".Config::Get('db.table.blog_user')." 
			(blog_id,
			user_id,
			user_role
			)
			VALUES(?d,  ?d, ?d)
		";			
		if ($this->oDb->query($sql,$oBlogUser->getBlogId(),$oBlogUser->getUserId(),$oBlogUser->getUserRole())===0) {
			return true;
		}		
		return false;
	}
	
	public function DeleteRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {
		$sql = "DELETE FROM ".Config::Get('db.table.blog_user')." 
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
		
	public function UpdateRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {		
		$sql = "UPDATE ".Config::Get('db.table.blog_user')." 
			SET 
				user_role = ?d			
			WHERE
				blog_id = ?d 
				AND
				user_id = ?d
		";			
		if ($this->oDb->query($sql,$oBlogUser->getUserRole(),$oBlogUser->getBlogId(),$oBlogUser->getUserId())) {
			return true;
		}		
		return false;
	}
	
	public function GetBlogUsers($aFilter) {
		$sWhere=' 1=1 ';
		if (isset($aFilter['blog_id'])) {
			$sWhere.=" AND bu.blog_id =  ".(int)$aFilter['blog_id'];
		}
		if (isset($aFilter['user_id'])) {
			$sWhere.=" AND bu.user_id =  ".(int)$aFilter['user_id'];
		}
		if (isset($aFilter['user_role'])) {
			if(!is_array($aFilter['user_role'])) {
				$aFilter['user_role']=array($aFilter['user_role']);
			}
			$sWhere.=" AND bu.user_role IN ('".join("', '",$aFilter['user_role'])."')";		
		} else {
			$sWhere.=" AND bu.user_role>".ModuleBlog::BLOG_USER_ROLE_GUEST;
		}
		
		$sql = "SELECT
					bu.*				
				FROM 
					".Config::Get('db.table.blog_user')." as bu
				WHERE 
					".$sWhere." 					
				;
					";		
		$aBlogUsers=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aUser) {
				$aBlogUsers[]=Engine::GetEntity('Blog_BlogUser',$aUser);
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
					".Config::Get('db.table.blog_user')." as bu
				WHERE 
					bu.blog_id IN(?a) 					
					AND
					bu.user_id = ?d ";		
		$aBlogUsers=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sUserId)) {
			foreach ($aRows as $aUser) {
				$aBlogUsers[]=Engine::GetEntity('Blog_BlogUser',$aUser);
			}
		}
		return $aBlogUsers;
	}
	
		
	public function GetPersonalBlogByUserId($sUserId) {
		$sql = "SELECT blog_id FROM ".Config::Get('db.table.blog')." WHERE user_owner_id = ?d and blog_type='personal'";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId)) {
			return $aRow['blog_id'];
		}
		return null;
	}
	
		
	public function GetBlogByTitle($sTitle) {
		$sql = "SELECT blog_id FROM ".Config::Get('db.table.blog')." WHERE blog_title = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sTitle)) {
			return $aRow['blog_id'];
		}
		return null;
	}
	

	
	
	public function GetBlogByUrl($sUrl) {		
		$sql = "SELECT 
				b.blog_id 
			FROM 
				".Config::Get('db.table.blog')." as b
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
				".Config::Get('db.table.blog')." as b				
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
				".Config::Get('db.table.blog')." as b				
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
		
	public function GetBlogsRating(&$iCount,$iCurrPage,$iPerPage) {		
		$sql = "SELECT 
					b.blog_id													
				FROM 
					".Config::Get('db.table.blog')." as b 									 
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
					".Config::Get('db.table.blog_user')." as bu,
					".Config::Get('db.table.blog')." as b	
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
				$aReturn[]=Engine::GetEntity('Blog',$aRow);
			}
		}
		return $aReturn;
	}
	
	public function GetBlogsRatingSelf($sUserId,$iLimit) {		
		$sql = "SELECT 
					b.*													
				FROM 					
					".Config::Get('db.table.blog')." as b	
				WHERE 						
					b.user_owner_id = ?d
					AND				
					b.blog_type<>'personal'													
				ORDER by b.blog_rating desc
				LIMIT 0, ?d 
			;";		
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('Blog',$aRow);
			}
		}
		return $aReturn;
	}
	
	public function GetCloseBlogs() {
		$sql = "SELECT b.blog_id										
				FROM ".Config::Get('db.table.blog')." as b					
				WHERE b.blog_type='close'
			;";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['blog_id'];
			}
		}
		return $aReturn;
	}
	
	/**
	 * Удаление блога из базы данных
	 *
	 * @param  int  $iBlogId
	 * @return bool	 
	 */
	public function DeleteBlog($iBlogId) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.blog')." 
			WHERE blog_id = ?d				
		";			
		if ($this->oDb->query($sql,$iBlogId)) {
			return true;
		}
		return false;
	}
	
	/**
	 * Удалить пользователей блога по идентификатору блога
	 *
	 * @param  int  $iBlogId
	 * @return bool
	 */
	public function DeleteBlogUsersByBlogId($iBlogId) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.blog_user')." 
			WHERE blog_id = ?d
		";
		if ($this->oDb->query($sql,$iBlogId)) {
			return true;
		}
		return false;
	}
}
?>