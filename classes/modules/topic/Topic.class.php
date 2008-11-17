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
require_once('mapper/Topic.mapper.class.php');

/**
 * Модуль для работы с топиками
 *
 */
class Topic extends Module {		
	protected $oMapperTopic;
	protected $oUserCurrent=null;
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->oMapperTopic=new Mapper_Topic($this->Database_GetConnect());
		$this->oMapperTopic->SetUserCurrent($this->User_GetUserCurrent());
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}
	/**
	 * Добавляет топик
	 *
	 * @param TopicEntity_Topic $oTopic
	 * @return unknown
	 */
	public function AddTopic(TopicEntity_Topic $oTopic) {
		if ($sId=$this->oMapperTopic->AddTopic($oTopic)) {
			$oTopic->setId($sId);
			if ($oTopic->getPublish()) {
				$aTags=explode(',',$oTopic->getTags());
				foreach ($aTags as $sTag) {
					$oTag=new TopicEntity_TopicTag();
					$oTag->setTopicId($oTopic->getId());
					$oTag->setUserId($oTopic->getUserId());
					$oTag->setBlogId($oTopic->getBlogId());
					$oTag->setText($sTag);
					$this->oMapperTopic->AddTopicTag($oTag);
				}
			}
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('topic_new',"topic_new_user_{$oTopic->getUserId()}","topic_new_blog_{$oTopic->getBlogId()}"));						
			return $oTopic;
		}
		return false;
	}
	/**
	 * Добавляет голосование за топик
	 *
	 * @param TopicEntity_TopicVote $oTopicVote
	 * @return unknown
	 */
	public function AddTopicVote(TopicEntity_TopicVote $oTopicVote) {
		if ($this->oMapperTopic->AddTopicVote($oTopicVote)) {			
			return true;
		}
		return false;
	}
	/**
	 * Удаляет теги у топика
	 *
	 * @param unknown_type $sTopicId
	 * @return unknown
	 */
	public function DeleteTopicTagsByTopicId($sTopicId) {
		return $this->oMapperTopic->DeleteTopicTagsByTopicId($sTopicId);
	}	
	/**
	 * Обновляет топик
	 *
	 * @param TopicEntity_Topic $oTopic
	 * @return unknown
	 */
	public function UpdateTopic(TopicEntity_Topic $oTopic) {
		$oTopic->setDateEdit(date("Y-m-d H:i:s"));
		if ($this->oMapperTopic->UpdateTopic($oTopic)) {	
			$aTags=explode(',',$oTopic->getTags());
			$this->DeleteTopicTagsByTopicId($oTopic->getId());
			if ($oTopic->getPublish()) {
				foreach ($aTags as $sTag) {
					$oTag=new TopicEntity_TopicTag();
					$oTag->setTopicId($oTopic->getId());
					$oTag->setUserId($oTopic->getUserId());
					$oTag->setBlogId($oTopic->getBlogId());
					$oTag->setText($sTag);
					$this->oMapperTopic->AddTopicTag($oTag);
				}
			}
			/**
			 * Обновляем избранное
			 */
			$this->oMapperTopic->SetFavouriteTopicPublish($oTopic->getId(),$oTopic->getPublish());
			/**
			 * Удаляем комментарий топика из прямого эфира
			 */
			if ($oTopic->getPublish()==0) {
				$this->Comment_deleteTopicCommentOnline($oTopic->getId());
			}
			//чистим зависимые кеши			
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('topic_update',"topic_update_{$oTopic->getId()}","topic_update_user_{$oTopic->getUserId()}","topic_update_blog_{$oTopic->getBlogId()}"));			
			return true;
		}
		return false;
	}	
		
	/**
	 * Получить топик по айдишнику учитывая его доступность(publish)
	 * если publish=-1 то publish не учитывается при выборке
	 *
	 * @param unknown_type $sId
	 * @param unknown_type $oUser
	 * @param unknown_type $iPublish
	 * @return unknown
	 */
	public function GetTopicById($sId,$oUser=null,$iPublish=1) {
		$s='';
		if (is_object($oUser)) {
			$s=$oUser->getId();		
		}
		$s2=-1;		
		if ($this->oUserCurrent) {
			$s2=$this->oUserCurrent->getId();
		}
		if (false === ($data = $this->Cache_Get("topic_{$sId}_{$s}_{$s2}_{$iPublish}"))) {			
			if ($data = $this->oMapperTopic->GetTopicById($sId,$oUser,$iPublish)) {				
				$this->Cache_Set($data, "topic_{$sId}_{$s}_{$s2}_{$iPublish}", array("topic_update_{$data->getId()}","blog_update_{$data->getBlogId()}"), 60*5);
			}			
		}		
		return $data;		
	}	
	/**
	 * Получить список топиков по списку айдишников
	 *
	 * @param unknown_type $aArrayId
	 * @param unknown_type $oUser
	 * @param unknown_type $iPublish
	 */
	public function GetTopicsByArrayId($aArrayId,$oUser=null,$iPublish=1) {
		$s='';
		if (is_object($oUser)) {
			$s=$oUser->getId();		
		}
		$s2=-1;		
		if ($this->oUserCurrent) {
			$s2=$this->oUserCurrent->getId();
		}
		$sIds=serialize($aArrayId);
		if (false === ($data = $this->Cache_Get("topic_list_{$sIds}_{$s}_{$s2}_{$iPublish}"))) {			
			if ($data = $this->oMapperTopic->GetTopicsByArrayId($aArrayId,$oUser,$iPublish)) {				
				$this->Cache_Set($data, "topic_list_{$sIds}_{$s}_{$s2}_{$iPublish}", array("topic_update"), 60*5);
			}			
		}		
		return $data;
	}
	/**
	 * Получает список топиков из избранного
	 *
	 * @param unknown_type $sUserId
	 * @param unknown_type $iCount
	 * @param unknown_type $iCurrPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsFavouriteByUserId($sUserId,$iCount,$iCurrPage,$iPerPage) {
		$s2=-1;		
		if ($this->oUserCurrent) {
			$s2=$this->oUserCurrent->getId();
		}
		if (false === ($data = $this->Cache_Get("topic_favourite_user_{$sUserId}_{$s2}_{$iCurrPage}_{$iPerPage}"))) {			
			$data = array('collection'=>$this->oMapperTopic->GetTopicsFavouriteByUserId($sUserId,$iCount,$iCurrPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "topic_favourite_user_{$sUserId}_{$s2}_{$iCurrPage}_{$iPerPage}", array('topic_update',"favourite_change_user_{$sUserId}"), 60*5);
		}
		return $data;		
	}
	/**
	 * Возвращает число топиков в избранном
	 *
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetCountTopicsFavouriteByUserId($sUserId) {
		if (false === ($data = $this->Cache_Get("topic_count_favourite_user_{$sUserId}"))) {			
			$data = $this->oMapperTopic->GetCountTopicsFavouriteByUserId($sUserId);
			$this->Cache_Set($data, "topic_count_favourite_user_{$sUserId}", array('topic_update',"favourite_change_user_{$sUserId}"), 60*5);
		}
		return $data;		
	}
	
	protected function GetTopicsByFilter($aFilter,$iPage,$iPerPage) {
		$s=serialize($aFilter);		
		$s2=-1;		
		if ($this->oUserCurrent) {
			$s2=$this->oUserCurrent->getId();
		}			
		if (false === ($data = $this->Cache_Get("topic_filter_{$s}_{$s2}_{$iPage}_{$iPerPage}"))) {			
			$data = array('collection'=>$this->oMapperTopic->GetTopics($aFilter,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "topic_filter_{$s}_{$s2}_{$iPage}_{$iPerPage}", array('comment_new','topic_update','topic_new'), 60*5);
		}
		return $data;		
	}
	
	protected function GetCountTopicsByFilter($aFilter) {
		$s=serialize($aFilter);					
		if (false === ($data = $this->Cache_Get("topic_count_{$s}"))) {			
			$data = $this->oMapperTopic->GetCountTopics($aFilter);
			$this->Cache_Set($data, "topic_count_{$s}", array('topic_update','topic_new'), 60*5);
		}
		return 	$data;
	}
	/**
	 * Получает список хороших топиков для вывода на главную страницу(из всех блогов, как коллективных так и персональных)
	 *
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsGood($iPage,$iPerPage) {
		$aFilter=array(
			'blog_type' => array(
				'personal',
				'open',
			),
			'topic_publish' => 1,
			'topic_rating'  => array(
				'value' => BLOG_INDEX_LIMIT_GOOD,
				'type'  => 'top',
				'publish_index'  => 1,
			),
		);			
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}
	/**
	 * Получает список ВСЕХ новых топиков
	 *
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsNew($iPage,$iPerPage) {
		$sDate=date("Y-m-d H:00:00",time()-BLOG_TOPIC_NEW_TIME);
		$aFilter=array(
			'blog_type' => array(
				'personal',
				'open',
			),
			'topic_publish' => 1,
			'topic_new' => $sDate,
		);		
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}
	/**
	 * Получает список топиков хороших из персональных блогов
	 *
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsPersonalGood($iCount,$iPage,$iPerPage) {
		$aFilter=array(
			'blog_type' => array(
				'personal',
			),
			'topic_publish' => 1,
			'topic_rating'  => array(
				'value' => BLOG_PERSONAL_LIMIT_GOOD,
				'type'  => 'top',
			),
		);			
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}	
	/**
	 * Получает список топиков плохих из персональных блогов
	 *
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsPersonalBad($iCount,$iPage,$iPerPage) {
		$aFilter=array(
			'blog_type' => array(
				'personal',
			),
			'topic_publish' => 1,
			'topic_rating'  => array(
				'value' => BLOG_PERSONAL_LIMIT_GOOD,
				'type'  => 'down',
			),
		);		
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}	
	/**
	 * Получает список топиков новых из персональных блогов
	 *
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsPersonalNew($iCount,$iPage,$iPerPage) {
		$sDate=date("Y-m-d H:00:00",time()-BLOG_TOPIC_NEW_TIME);
		$aFilter=array(
			'blog_type' => array(
				'personal',
			),
			'topic_publish' => 1,
			'topic_new' => $sDate,
		);		
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}
	/**
	 * Получает число новых топиков в персональных блогах
	 *
	 * @return unknown
	 */
	public function GetCountTopicsPersonalNew() {
		$sDate=date("Y-m-d H:00:00",time()-BLOG_TOPIC_NEW_TIME);
		$aFilter=array(
			'blog_type' => array(
				'personal',
			),
			'topic_publish' => 1,
			'topic_new' => $sDate,
		);				
		return $this->GetCountTopicsByFilter($aFilter);
	}
	/**
	 * Получает список топиков по юзеру
	 *
	 * @param unknown_type $sUserId
	 * @param unknown_type $iPublish
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsPersonalByUser($sUserId,$iPublish,$iCount,$iPage,$iPerPage) {
		$aFilter=array(			
			'topic_publish' => $iPublish,
			'user_id' => $sUserId,			
		);
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}
	/**
	 * Возвращает количество топиков которые создал юзер
	 *
	 * @param unknown_type $sUserId
	 * @param unknown_type $iPublish
	 * @return unknown
	 */
	public function GetCountTopicsPersonalByUser($sUserId,$iPublish) {
		$aFilter=array(			
			'topic_publish' => $iPublish,
			'user_id' => $sUserId,			
		);
		$s=serialize($aFilter);					
		if (false === ($data = $this->Cache_Get("topic_count_user_{$s}"))) {			
			$data = $this->oMapperTopic->GetCountTopics($aFilter);
			$this->Cache_Set($data, "topic_count_user_{$s}", array("topic_update_user_{$sUserId}","topic_new_user_{$sUserId}"), 60*5);
		}
		return 	$data;		
	}
	/**
	 * Получает список топиков хороших из коллективных блогов
	 *
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsCollectiveGood($iCount,$iPage,$iPerPage) {
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'topic_rating'  => array(
				'value' => BLOG_COLLECTIVE_LIMIT_GOOD,
				'type'  => 'top',
			),
		);
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);		
	}
	/**
	 * Получает список топиков плохих из коллективных блогов
	 *
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsCollectiveBad($iCount,$iPage,$iPerPage) {
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'topic_rating'  => array(
				'value' => BLOG_COLLECTIVE_LIMIT_GOOD,
				'type'  => 'down',
			),
		);
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);		
	}	
	/**
	 * Получает список топиков новых из коллективных блогов
	 *
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsCollectiveNew($iCount,$iPage,$iPerPage) {
		$sDate=date("Y-m-d H:00:00",time()-BLOG_TOPIC_NEW_TIME);
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'topic_new' => $sDate,
		);
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);		
	}
	/**
	 * Получает число новых топиков в коллективных блогах
	 *
	 * @return unknown
	 */
	public function GetCountTopicsCollectiveNew() {
		$sDate=date("Y-m-d H:00:00",time()-BLOG_TOPIC_NEW_TIME);
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'topic_new' => $sDate,
		);
		return $this->GetCountTopicsByFilter($aFilter);		
	}
	/**
	 * Получает топики по рейтингу и дате
	 *
	 * @param unknown_type $sDate
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetTopicsRatingByDate($sDate,$iLimit=20) {	
		$s2=-1;		
		if ($this->oUserCurrent) {
			$s2=$this->oUserCurrent->getId();
		}
		if (false === ($data = $this->Cache_Get("topic_rating_{$sDate}_{$s2}_{$iLimit}"))) {			
			$data = $this->oMapperTopic->GetTopicsRatingByDate($sDate,$iLimit);
			$this->Cache_Set($data, "topic_rating_{$sDate}_{$s2}_{$iLimit}", array('topic_update'), 60*5);
		}
		return $data;		
	}	
	/**
	 * Получает список топиков хороший из блога
	 *
	 * @param unknown_type $oBlog
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsByBlogGood($oBlog,$iCount,$iPage,$iPerPage) {
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'blog_id' => $oBlog->getId(),
			'topic_rating'  => array(
				'value' => BLOG_COLLECTIVE_LIMIT_GOOD,
				'type'  => 'top',
			),
		);
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);		
	}	
	/**
	 * Получает список топиков плохих из блога
	 *
	 * @param unknown_type $oBlog
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsByBlogBad($oBlog,$iCount,$iPage,$iPerPage) {
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'blog_id' => $oBlog->getId(),
			'topic_rating'  => array(
				'value' => BLOG_COLLECTIVE_LIMIT_GOOD,
				'type'  => 'down',
			),
		);
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);		
	}	
	/**
	 * Получает список топиков новых из блога
	 *
	 * @param unknown_type $oBlog
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsByBlogNew($oBlog,$iCount,$iPage,$iPerPage) {
		$sDate=date("Y-m-d H:00:00",time()-BLOG_TOPIC_NEW_TIME);
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'blog_id' => $oBlog->getId(),
			'topic_new' => $sDate,
			
		);
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);		
	}
	/**
	 * Получает число новых топиков из блога
	 *
	 * @param unknown_type $oBlog
	 * @return unknown
	 */
	public function GetCountTopicsByBlogNew($oBlog) {
		$sDate=date("Y-m-d H:00:00",time()-BLOG_TOPIC_NEW_TIME);
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'blog_id' => $oBlog->getId(),
			'topic_new' => $sDate,
			
		);
		return $this->GetCountTopicsByFilter($aFilter);		
	}
	/**
	 * Получает список топиков по тегу
	 *
	 * @param unknown_type $sTag
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsByTag($sTag,$iCount,$iPage,$iPerPage) {		
		if (false === ($data = $this->Cache_Get("topic_tag_{$sTag}_{$iPage}_{$iPerPage}"))) {			
			$data = array('collection'=>$this->oMapperTopic->GetTopicsByTag($sTag,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "topic_tag_{$sTag}_{$iPage}_{$iPerPage}", array('topic_update','topic_new'), 60*15);
		}
		return $data;		
	}
	/**
	 * Получает список тегов топиков
	 *
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetTopicTags($iLimit) {
		if (false === ($data = $this->Cache_Get("tag_{$iLimit}"))) {			
			$data = $this->oMapperTopic->GetTopicTags($iLimit);
			$this->Cache_Set($data, "tag_{$iLimit}", array('topic_update','topic_new'), 60*15);
		}
		return $data;		
	}
	/**
	 * Получает список тегов по юзеру
	 *
	 * @param unknown_type $sUserId
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetTopicTagsByUserId($sUserId,$iLimit) {
		if (false === ($data = $this->Cache_Get("tag_user_{$sUserId}_{$iLimit}"))) {			
			$data = $this->oMapperTopic->GetTopicTagsByUserId($sUserId,$iLimit);
			$this->Cache_Set($data, "tag_user_{$sUserId}_{$iLimit}", array("topic_update_user_{$sUserId}","topic_new_user_{$sUserId}"), 60*15);
		}
		return $data;		
	}
	/**
	 * Получает голосование за топик(голосовал юзер за топик или нет)
	 *
	 * @param unknown_type $sTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTopicVote($sTopicId,$sUserId) {
		return $this->oMapperTopic->GetTopicVote($sTopicId,$sUserId);
	}
	/**
	 * Увеличивает у топика число комментов
	 *
	 * @param unknown_type $sTopicId
	 * @return unknown
	 */
	public function increaseTopicCountComment($sTopicId) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('topic_update',"topic_update_{$sTopicId}"));						
		return $this->oMapperTopic->increaseTopicCountComment($sTopicId);
	}
	/**
	 * Получает привязку топика к ибранному(добавлен ли топик в избранное у юзера)
	 *
	 * @param unknown_type $sTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetFavouriteTopic($sTopicId,$sUserId) {
		return $this->oMapperTopic->GetFavouriteTopic($sTopicId,$sUserId);
	}
	/**
	 * Добавляет топик в избранное
	 *
	 * @param TopicEntity_FavouriteTopic $oFavouriteTopic
	 * @return unknown
	 */
	public function AddFavouriteTopic(TopicEntity_FavouriteTopic $oFavouriteTopic) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("favourite_change_user_{$oFavouriteTopic->getUserId()}"));						
		return $this->oMapperTopic->AddFavouriteTopic($oFavouriteTopic);
	}
	/**
	 * Удаляет топик из избранного
	 *
	 * @param TopicEntity_FavouriteTopic $oFavouriteTopic
	 * @return unknown
	 */
	public function DeleteFavouriteTopic(TopicEntity_FavouriteTopic $oFavouriteTopic) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("favourite_change_user_{$oFavouriteTopic->getUserId()}"));
		return $this->oMapperTopic->DeleteFavouriteTopic($oFavouriteTopic);
	}
	/**
	 * Получает список тегов по первым буквам тега
	 *
	 * @param unknown_type $sTag
	 * @param unknown_type $iLimit
	 */
	public function GetTopicTagsByLike($sTag,$iLimit) {
		if (false === ($data = $this->Cache_Get("tag_like_{$sTag}_{$iLimit}"))) {			
			$data = $this->oMapperTopic->GetTopicTagsByLike($sTag,$iLimit);
			$this->Cache_Set($data, "tag_like_{$sTag}_{$iLimit}", array("topic_update","topic_new"), 60*15);
		}
		return $data;		
	}
	/**
	 * Обновляем дату прочтения топика, если читаем его первый раз то добавляем
	 *
	 * @param unknown_type $sTopicId
	 * @param unknown_type $sUserId
	 */
	public function SetDateRead($sTopicId,$sUserId,$iCountComment) {
		$res=$this->oMapperTopic->SetDateRead($sTopicId,$sUserId,$iCountComment);
		if ($res==1 or $res==2) {			
			return true;
		}
		return false;
	}	
	/**
	 * Получаем дату прочтения топика юзером
	 *
	 * @param unknown_type $sTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetDateRead($sTopicId,$sUserId) {
		return $this->oMapperTopic->GetDateRead($sTopicId,$sUserId);
	}
	/**
	 * Проверяет голосовал ли юзер за топик-вопрос
	 *
	 * @param unknown_type $sTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTopicQuestionVote($sTopicId,$sUserId) {
		return $this->oMapperTopic->GetTopicQuestionVote($sTopicId,$sUserId);
	}
	/**
	 * Добавляет факт голосования за топик-вопрос
	 *
	 * @param TopicEntity_TopicQuestionVote $oTopicQuestionVote
	 */
	public function AddTopicQuestionVote(TopicEntity_TopicQuestionVote $oTopicQuestionVote) {
		return $this->oMapperTopic->AddTopicQuestionVote($oTopicQuestionVote);
	}
}
?>