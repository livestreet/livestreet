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

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
require_once('mapper/Blog.mapper.class.php');

/**
 * Модуль для работы с блогами
 *
 */
class LsBlog extends Module {	
	protected $oMapperBlog;	
	protected $oUserCurrent=null;
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {				
		$this->oMapperBlog=new Mapper_Blog($this->Database_GetConnect());
		$this->oMapperBlog->SetUserCurrent($this->User_GetUserCurrent());
		$this->oUserCurrent=$this->User_GetUserCurrent();		
	}
	/**
	 * Получить персональный блог юзера
	 *
	 * @param Entity_User $oUser
	 * @return unknown
	 */
	public function GetPersonalBlogByUserId($sUserId) {
		return $this->oMapperBlog->GetPersonalBlogByUserId($sUserId);
	}
	/**
	 * Получить блог по айдишнику(номеру)
	 *
	 * @param unknown_type $sBlogId
	 * @return unknown
	 */
	public function GetBlogById($sBlogId) {
		return $this->oMapperBlog->GetBlogById($sBlogId);
	}
	/**
	 * Получить блог по УРЛу
	 *
	 * @param unknown_type $sBlogUrl
	 * @return unknown
	 */
	public function GetBlogByUrl($sBlogUrl) {
		$s2=-1;		
		if ($this->oUserCurrent) {
			$s2=$this->oUserCurrent->getId();
		}
		if (false === ($data = $this->Cache_Get("blog_url_{$sBlogUrl}_{$s2}"))) {						
			if ($data = $this->oMapperBlog->GetBlogByUrl($sBlogUrl)) {				
				$this->Cache_Set($data, "blog_url_{$sBlogUrl}_{$s2}", array("blog_update_{$data->getId()}",'blog_new'), 60*5);				
			} else {
				$this->Cache_Set($data, "blog_url_{$sBlogUrl}_{$s2}", array('blog_update','blog_new'), 60*5);
			}
		}
		return $data;		
	}
	/**
	 * Получить блог по названию
	 *
	 * @param unknown_type $sTitle
	 * @return unknown
	 */
	public function GetBlogByTitle($sTitle) {		
		if (false === ($data = $this->Cache_Get("blog_url_{$sTitle}"))) {						
			if ($data = $this->oMapperBlog->GetBlogByTitle($sTitle)) {				
				$this->Cache_Set($data, "blog_url_{$sTitle}", array("blog_update_{$data->getId()}",'blog_new'), 60*5);				
			} else {
				$this->Cache_Set($data, "blog_url_{$sTitle}", array('blog_update','blog_new'), 60*5);
			}
		}
		return $data;		
	}
	/**
	 * Создаёт персональный блог
	 *
	 * @param Entity_User $oUser
	 * @return unknown
	 */
	public function CreatePersonalBlog(UserEntity_User $oUser) {
		$oBlog=new BlogEntity_Blog();
		$oBlog->setOwnerId($oUser->getId());
		$oBlog->setTitle('Блог им. '.$oUser->getLogin());
		$oBlog->setType('personal');
		$oBlog->setDescription('Это ваш персональный блог.');
		$oBlog->setDateAdd(date("Y-m-d H:i:s")); 
		$oBlog->setLimitRatingTopic(-1000);
		$oBlog->setUrl(null);	
		$oBlog->setAvatar(0);
		$oBlog->setAvatarType(null);	
		return $this->AddBlog($oBlog);		
	}
	/**
	 * Добавляет блог
	 *
	 * @param BlogEntity_Blog $oBlog
	 * @return unknown
	 */
	public function AddBlog(BlogEntity_Blog $oBlog) {		
		if ($sId=$this->oMapperBlog->AddBlog($oBlog)) {
			$oBlog->setId($sId);
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('blog_new',"blog_new_user_{$oBlog->getOwnerId()}"));						
			return $oBlog;
		}
		return false;
	}
	/**
	 * Обновляет блог
	 *
	 * @param BlogEntity_Blog $oBlog
	 * @return unknown
	 */
	public function UpdateBlog(BlogEntity_Blog $oBlog) {
		$oBlog->setDateEdit(date("Y-m-d H:i:s"));
		$res=$this->oMapperBlog->UpdateBlog($oBlog);		
		if ($res) {			
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('blog_update',"blog_update_{$oBlog->getId()}"));
			return true;
		}
		return false;
	}
	/**
	 * Добавляет голосование за блог
	 *
	 * @param BlogEntity_BlogVote $oBlogVote
	 * @return unknown
	 */
	public function AddBlogVote(BlogEntity_BlogVote $oBlogVote) {
		if ($this->oMapperBlog->AddBlogVote($oBlogVote)) {			
			return true;
		}
		return false;
	}
	/**
	 * Добавляет отношение юзера к блогу, по сути присоединяет к блогу
	 *
	 * @param BlogEntity_BlogUser $oBlogUser
	 * @return unknown
	 */
	public function AddRelationBlogUser(BlogEntity_BlogUser $oBlogUser) {
		if ($this->oMapperBlog->AddRelationBlogUser($oBlogUser)) {		
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("blog_relation_change_{$oBlogUser->getUserId()}"));	
			return true;
		}
		return false;
	}
	/**
	 * Удалет отношение юзера к блогу, по сути отключает от блога
	 *
	 * @param BlogEntity_BlogUser $oBlogUser
	 * @return unknown
	 */
	public function DeleteRelationBlogUser(BlogEntity_BlogUser $oBlogUser) {
		if ($this->oMapperBlog->DeleteRelationBlogUser($oBlogUser)) {		
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("blog_relation_change_{$oBlogUser->getUserId()}"));		
			return true;
		}
		return false;
	}
	/**
	 * Получает список блогов по хозяину
	 *
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetBlogsByOwnerId($sUserId) {
		return $this->oMapperBlog->GetBlogsByOwnerId($sUserId);
	}
	/**
	 * Получает список всех НЕ персональных блогов
	 *
	 * @return unknown
	 */
	public function GetBlogs() {
		return $this->oMapperBlog->GetBlogs();
	}
	/**
	 * Получает список отновшений блога к юзеру(пользователей блога)
	 *
	 * @param unknown_type $sBlogId
	 * @return unknown
	 */
	public function GetRelationBlogUsersByBlogId($sBlogId) {
		$aFilter=array(
			'blog_id'=> $sBlogId,
			'is_moderator' => 0,
			'is_administrator' => 0,
		);
		return $this->oMapperBlog->GetRelationBlogUsers($aFilter);
	}
	/**
	 * Получает список пользователей блога
	 *
	 * @param unknown_type $sBlogId
	 * @return unknown
	 */
	public function GetBlogUsersByBlogId($sBlogId) {
		$aFilter=array(
			'blog_id'=> $sBlogId,
			'is_moderator' => 0,
			'is_administrator' => 0,
		);
		return $this->oMapperBlog->GetBlogUsers($aFilter);
	}
	/**
	 * Получает отношение юзера к блогу(состоит в блоге или нет)
	 *
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetRelationBlogUsersByUserId($sUserId) {
		$aFilter=array(
			'user_id'=> $sUserId			
		);
		return $this->oMapperBlog->GetRelationBlogUsers($aFilter);
	}
	/**
	 * Состоит ли юзер в конкретном блоге
	 *
	 * @param unknown_type $sBlogId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetRelationBlogUserByBlogIdAndUserId($sBlogId,$sUserId) {
		$aFilter=array(
			'blog_id'=> $sBlogId,
			'user_id' => $sUserId			
		);
		if ($aBlogUser=$this->oMapperBlog->GetRelationBlogUsers($aFilter)) {
			return $aBlogUser[0];
		}
		return false;
	}
	/**
	 * Список модеро вблога
	 *
	 * @param unknown_type $sBlogId
	 * @return unknown
	 */
	public function GetBlogModeratorsByBlogId($sBlogId) {
		$aFilter=array(
			'blog_id'=> $sBlogId,
			'is_moderator' => 1,
			'is_administrator' => 0,
		);
		return $this->oMapperBlog->GetRelationBlogUsers($aFilter);
	}
	/**
	 * Список админов блога
	 *
	 * @param unknown_type $sBlogId
	 * @return unknown
	 */
	public function GetBlogAdministratorsByBlogId($sBlogId) {
		$aFilter=array(
			'blog_id'=> $sBlogId,
			'is_moderator' => 0,
			'is_administrator' => 1,
		);
		return $this->oMapperBlog->GetRelationBlogUsers($aFilter);
	}
	/**
	 * Список тех кто состоит в блоге
	 *
	 * @param unknown_type $sBlogId
	 * @return unknown
	 */
	public function GetRelationBlog($sBlogId) {
		$aFilter=array(
			'blog_id'=> $sBlogId,			
		);
		return $this->oMapperBlog->GetRelationBlogUsers($aFilter);
	}
	
	public function UpdateRelationBlogUser(BlogEntity_BlogUser $oBlogUser) {
		return $this->oMapperBlog->UpdateRelationBlogUser($oBlogUser);
	}
	/**
	 * Получает список блогов по рейтингу
	 *
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetBlogsRating($iCurrPage,$iPerPage) { 
		$s1=-1;		
		if ($this->oUserCurrent) {
			$s1=$this->oUserCurrent->getId();
		}
		if (false === ($data = $this->Cache_Get("blog_rating_{$iCurrPage}_{$iPerPage}_$s1"))) {				
			$data = array('collection'=>$this->oMapperBlog->GetBlogsRating($iCount,$iCurrPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "blog_rating_{$iCurrPage}_{$iPerPage}_$s1", array("blog_update","blog_new"), 60*15);
		}
		return $data;		
	}
	/**
	 * Список подключенных блогов по рейтингу
	 *
	 * @param unknown_type $sUserId
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetBlogsRatingJoin($sUserId,$iLimit) { 		
		if (false === ($data = $this->Cache_Get("blog_rating_join_{$sUserId}_{$iLimit}"))) {				
			$data = $this->oMapperBlog->GetBlogsRatingJoin($sUserId,$iLimit);			
			$this->Cache_Set($data, "blog_rating_join_{$sUserId}_{$iLimit}", array('blog_update',"blog_relation_change_{$sUserId}"), 60*60*24);
		}
		return $data;		
	}
	/**
	 * Список сових блогов по рейтингу
	 *
	 * @param unknown_type $sUserId
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetBlogsRatingSelf($sUserId,$iLimit) { 		
		if (false === ($data = $this->Cache_Get("blog_rating_self_{$sUserId}_{$iLimit}"))) {				
			$data = $this->oMapperBlog->GetBlogsRatingSelf($sUserId,$iLimit);			
			$this->Cache_Set($data, "blog_rating_self_{$sUserId}_{$iLimit}", array('blog_update',"blog_new_user_{$sUserId}"), 60*60*24);
		}
		return $data;		
	}
	/**
	 * Получает голосование за блог(проверяет голосовал ли юзер за этот блог)
	 *
	 * @param unknown_type $sBlogId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetBlogVote($sBlogId,$sUserId) {
		return $this->oMapperBlog->GetBlogVote($sBlogId,$sUserId);
	}	
}
?>