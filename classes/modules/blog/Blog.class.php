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
	 * Получает дополнительные данные(объекты) для блогов по их ID
	 *
	 */
	public function GetBlogsAdditionalData($aBlogId,$aAllowData=array('vote','owner','relation_user')) {
		func_array_simpleflip($aAllowData);
		if (!is_array($aBlogId)) {
			$aBlogId=array($aBlogId);
		}
		/**
		 * Получаем блоги
		 */
		$aBlogs=$this->GetBlogsByArrayId($aBlogId);
		/**
		 * Формируем ID дополнительных данных, которые нужно получить
		 */
		$aUserId=array();		
		foreach ($aBlogs as $oBlog) {
			if (isset($aAllowData['owner'])) {
				$aUserId[]=$oBlog->getOwnerId();
			}						
		}
		/**
		 * Получаем дополнительные данные
		 */
		$aBlogUsers=array();
		$aBlogsVote=array();
		$aUsers=isset($aAllowData['owner']) && is_array($aAllowData['owner']) ? $this->User_GetUsersAdditionalData($aUserId,$aAllowData['owner']) : $this->User_GetUsersAdditionalData($aUserId);				
		if (isset($aAllowData['relation_user']) and $this->oUserCurrent) {
			$aBlogUsers=$this->GetRelationBlogUsersByArrayBlog($aBlogId,$this->oUserCurrent->getId());	
		}
		if (isset($aAllowData['vote']) and $this->oUserCurrent) {
			$aBlogsVote=$this->GetBlogsVoteByArray($aBlogId,$this->oUserCurrent->getId());			
		}
		/**
		 * Добавляем данные к результату - списку блогов
		 */
		foreach ($aBlogs as $oBlog) {
			if (isset($aUsers[$oBlog->getOwnerId()])) {
				$oBlog->setOwner($aUsers[$oBlog->getOwnerId()]);
			} else {
				$oBlog->setOwner(null); // или $oBlog->setOwner(new UserEntity_User());
			}
			if (isset($aBlogUsers[$oBlog->getId()])) {
				if ($aBlogUsers[$oBlog->getId()]->getIsAdministrator()) {
					$oBlog->setUserIsAdministrator(true);
				}
				if ($aBlogUsers[$oBlog->getId()]->getIsModerator()) {
					$oBlog->setUserIsModerator(true);
				}
			} else {
				$oBlog->setUserIsAdministrator(false);
				$oBlog->setUserIsModerator(false);
			}	
			if (isset($aBlogsVote[$oBlog->getId()])) {
				$oBlog->setUserIsVote(true);
				$oBlog->setUserVoteDelta($aBlogsVote[$oBlog->getId()]->getDelta());
			} else {
				$oBlog->setUserIsVote(false);
			}			
		}
		
		return $aBlogs;
	}
	/**
	 * Список блогов по ID
	 *
	 * @param array $aUserId
	 */
	public function GetBlogsByArrayId($aBlogId) {
		if (!is_array($aBlogId)) {
			$aBlogId=array($aBlogId);
		}
		$aBlogId=array_unique($aBlogId);
		$aBlogs=array();
		$aBlogIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aBlogId,'blog_');
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aBlogs[$data[$sKey]->getId()]=$data[$sKey];
					} else {
						$aBlogIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим каких блогов не было в кеше и делаем запрос в БД
		 */		
		$aBlogIdNeedQuery=array_diff($aBlogId,array_keys($aBlogs));		
		$aBlogIdNeedQuery=array_diff($aBlogIdNeedQuery,$aBlogIdNotNeedQuery);		
		$aBlogIdNeedStore=$aBlogIdNeedQuery;
		if ($data = $this->oMapperBlog->GetBlogsByArrayId($aBlogIdNeedQuery)) {
			foreach ($data as $oBlog) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aBlogs[$oBlog->getId()]=$oBlog;
				$this->Cache_Set($oBlog, "blog_{$oBlog->getId()}", array("blog_update_{$oBlog->getId()}"), 60*60*24*4);
				$aBlogIdNeedStore=array_diff($aBlogIdNeedStore,array($oBlog->getId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aBlogIdNeedStore as $sId) {
			$this->Cache_Set(null, "blog_{$sId}", array("blog_update_{$sId}"), 60*60*24*4);
		}		
		return $aBlogs;		
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
		$aBlogs=$this->GetBlogsAdditionalData($sId);
		if (isset($aBlogs[$sId])) {
			return $aBlogs[$sId];
		}
		return null;		
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
		$oBlog->setTitle($this->Lang_Get('blogs_personal_title').' '.$oUser->getLogin());
		$oBlog->setType('personal');
		$oBlog->setDescription($this->Lang_Get('blogs_personal_description'));
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
	public function GetRelationBlogUsersByUserId($sUserId,$iRole=null) {
		$aFilter=array(
			'user_id'=> $sUserId			
		);
		if ($iRole===0) {
			$aFilter['is_moderator']=0;
			$aFilter['is_administrator']=0;
		} elseif ($iRole===1) {
			$aFilter['is_moderator']=1;
		} elseif ($iRole===2) {
			$aFilter['is_administrator']=1;
		}
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
		return null;
	}
	/**
	 * Получить список отношений блог-юзер по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetRelationBlogUsersByArrayBlog($aBlogId,$sUserId) {
		if (!is_array($aBlogId)) {
			$aBlogId=array($aBlogId);
		}
		$aBlogId=array_unique($aBlogId);
		$aBlogUsers=array();
		$aBlogIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aBlogId,'blog_relation_user_','_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aBlogUsers[$data[$sKey]->getBlogId()]=$data[$sKey];
					} else {
						$aBlogIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим каких блогов не было в кеше и делаем запрос в БД
		 */		
		$aBlogIdNeedQuery=array_diff($aBlogId,array_keys($aBlogUsers));		
		$aBlogIdNeedQuery=array_diff($aBlogIdNeedQuery,$aBlogIdNotNeedQuery);		
		$aBlogIdNeedStore=$aBlogIdNeedQuery;
		if ($data = $this->oMapperBlog->GetRelationBlogUsersByArrayBlog($aBlogIdNeedQuery,$sUserId)) {
			foreach ($data as $oBlogUser) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aBlogUsers[$oBlogUser->getBlogId()]=$oBlogUser;
				$this->Cache_Set($oBlogUser, "blog_relation_user_{$oBlogUser->getBlogId()}_{$oBlogUser->getUserId()}", array(), 60*60*24*4);
				$aBlogIdNeedStore=array_diff($aBlogIdNeedStore,array($oBlogUser->getBlogId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aBlogIdNeedStore as $sId) {
			$this->Cache_Set(null, "blog_relation_user_{$sId}_{$sUserId}", array(), 60*60*24*4);
		}		
		return $aBlogUsers;		
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
		$data=$this->GetBlogsVoteByArray($sBlogId,$sUserId);
		if (isset($data[$sBlogId])) {
			return $data[$sBlogId];
		}
		return null;		
	}	
	/**
	 * Получить список голосований за топик по списку айдишников
	 *
	 * @param unknown_type $aBlogId
	 */
	public function GetBlogsVoteByArray($aBlogId,$sUserId) {
		if (!is_array($aBlogId)) {
			$aBlogId=array($aBlogId);
		}
		$aBlogId=array_unique($aBlogId);
		$aBlogsVote=array();
		$aBlogIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aBlogId,'blog_vote_','_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aBlogsVote[$data[$sKey]->getBlogId()]=$data[$sKey];
					} else {
						$aBlogIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим каких блогов не было в кеше и делаем запрос в БД
		 */		
		$aBlogIdNeedQuery=array_diff($aBlogId,array_keys($aBlogsVote));		
		$aBlogIdNeedQuery=array_diff($aBlogIdNeedQuery,$aBlogIdNotNeedQuery);		
		$aBlogIdNeedStore=$aBlogIdNeedQuery;
		if ($data = $this->oMapperBlog->GetBlogsVoteByArray($aBlogIdNeedQuery,$sUserId)) {
			foreach ($data as $oVote) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aBlogsVote[$oVote->getBlogId()]=$oVote;
				$this->Cache_Set($oVote, "blog_vote_{$oVote->getBlogId()}_{$oVote->getVoterId()}", array(), 60*60*24*4);
				$aBlogIdNeedStore=array_diff($aBlogIdNeedStore,array($oVote->getBlogId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aBlogIdNeedStore as $sId) {
			$this->Cache_Set(null, "blog_vote_{$sId}_{$sUserId}", array(), 60*60*24*4);
		}		
		return $aBlogsVote;		
	}
}
?>