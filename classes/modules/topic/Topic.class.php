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
require_once('mapper/Topic.mapper.class.php');

/**
 * Модуль для работы с топиками
 *
 */
class LsTopic extends Module {		
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
	 * Получает дополнительные данные(объекты) для топиков по их ID
	 *
	 */
	public function GetTopicsAdditionalData($aTopicId,$aAllowData=array('user','blog','vote','favourite','comment_new')) {
		func_array_simpleflip($aAllowData);
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		/**
		 * Получаем "голые" топики
		 */
		$aTopics=$this->GetTopicsByArrayId($aTopicId);
		/**
		 * Формируем ID дополнительных данных, которые нужно получить
		 */
		$aUserId=array();
		$aBlogId=array();		
		foreach ($aTopics as $oTopic) {
			if (isset($aAllowData['user'])) {
				$aUserId[]=$oTopic->getUserId();
			}
			if (isset($aAllowData['blog'])) {
				$aBlogId[]=$oTopic->getBlogId();
			}			
		}
		/**
		 * Получаем дополнительные данные
		 */
		$aTopicsVote=array();
		$aFavouriteTopics=array();
		$aTopicsQuestionVote=array();
		$aTopicsRead=array();
		$aUsers=isset($aAllowData['user']) && is_array($aAllowData['user']) ? $this->User_GetUsersAdditionalData($aUserId,$aAllowData['user']) : $this->User_GetUsersAdditionalData($aUserId);
		$aBlogs=isset($aAllowData['blog']) && is_array($aAllowData['blog']) ? $this->Blog_GetBlogsAdditionalData($aBlogId,$aAllowData['blog']) : $this->Blog_GetBlogsAdditionalData($aBlogId);		
		if (isset($aAllowData['vote']) and $this->oUserCurrent) {
			$aTopicsVote=$this->GetTopicsVoteByArray($aTopicId,$this->oUserCurrent->getId());			
			$aTopicsQuestionVote=$this->GetTopicsQuestionVoteByArray($aTopicId,$this->oUserCurrent->getId());
		}	
		if (isset($aAllowData['favourite']) and $this->oUserCurrent) {
			$aFavouriteTopics=$this->GetFavouriteTopicsByArray($aTopicId,$this->oUserCurrent->getId());	
		}
		if (isset($aAllowData['comment_new']) and $this->oUserCurrent) {
			$aTopicsRead=$this->GetTopicsReadByArray($aTopicId,$this->oUserCurrent->getId());	
		}
		/**
		 * Добавляем данные к результату - списку топиков
		 */
		foreach ($aTopics as $oTopic) {
			if (isset($aUsers[$oTopic->getUserId()])) {
				$oTopic->setUser($aUsers[$oTopic->getUserId()]);
			} else {
				$oTopic->setUser(null); // или $oTopic->setUser(new UserEntity_User());
			}
			if (isset($aBlogs[$oTopic->getBlogId()])) {
				$oTopic->setBlog($aBlogs[$oTopic->getBlogId()]);
			} else {
				$oTopic->setBlog(null); // или $oTopic->setBlog(new BlogEntity_Blog());
			}
			if (isset($aTopicsVote[$oTopic->getId()])) {
				$oTopic->setUserIsVote(true);
				$oTopic->setUserVoteDelta($aTopicsVote[$oTopic->getId()]->getDelta());
			} else {
				$oTopic->setUserIsVote(false);
			}
			if (isset($aFavouriteTopics[$oTopic->getId()])) {
				$oTopic->setIsFavourite(true);
			} else {
				$oTopic->setIsFavourite(false);
			}			
			if (isset($aTopicsQuestionVote[$oTopic->getId()])) {
				$oTopic->setUserQuestionIsVote(true);
			} else {
				$oTopic->setUserQuestionIsVote(false);
			}
			if (isset($aTopicsRead[$oTopic->getId()]))	{		
				$oTopic->setCountCommentNew($oTopic->getCountComment()-$aTopicsRead[$oTopic->getId()]->getCommentCountLast());
				$oTopic->setDateRead($aTopicsRead[$oTopic->getId()]->getDateRead());
			} else {
				$oTopic->setCountCommentNew(0);
				$oTopic->setDateRead(date("Y-m-d H:i:s"));
			}						
		}
		return $aTopics;
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
	 * Удаляет топик.
	 * Если тип таблиц в БД InnoDB, то удалятся всё связи по топику(комменты,голосования,избранное)
	 *
	 * @param unknown_type $sTopicId
	 * @return unknown
	 */
	public function DeleteTopic($sTopicId) {		
		$aRes=$this->GetTopicsByArrayId($sTopicId);
		$oTopic=$aRes[$sTopicId];
		//чистим зависимые кеши			
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('topic_update',"topic_update_{$oTopic->getId()}","topic_update_user_{$oTopic->getUserId()}","topic_update_blog_{$oTopic->getBlogId()}"));						
		return $this->oMapperTopic->DeleteTopic($sTopicId);
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
	 * Получить топик по айдишнику
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function GetTopicById($sId) {		
		$aTopics=$this->GetTopicsAdditionalData($sId);
		if (isset($aTopics[$sId])) {
			return $aTopics[$sId];
		}
		return null;
	}	
	/**
	 * Получить список топиков по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetTopicsByArrayId($aTopicId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aTopics=array();
		$aTopicIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTopicId,'topic_');
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aTopics[$data[$sKey]->getId()]=$data[$sKey];
					} else {
						$aTopicIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим каких топиков не было в кеше и делаем запрос в БД
		 */		
		$aTopicIdNeedQuery=array_diff($aTopicId,array_keys($aTopics));		
		$aTopicIdNeedQuery=array_diff($aTopicIdNeedQuery,$aTopicIdNotNeedQuery);		
		$aTopicIdNeedStore=$aTopicIdNeedQuery;
		if ($data = $this->oMapperTopic->GetTopicsByArrayId($aTopicIdNeedQuery)) {
			foreach ($data as $oTopic) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aTopics[$oTopic->getId()]=$oTopic;
				$this->Cache_Set($oTopic, "topic_{$oTopic->getId()}", array("topic_update_{$oTopic->getId()}"), 60*60*24*4);
				$aTopicIdNeedStore=array_diff($aTopicIdNeedStore,array($oTopic->getId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTopicIdNeedStore as $sId) {
			$this->Cache_Set(null, "topic_{$sId}", array("topic_update_{$sId}"), 60*60*24*4);
		}		
		return $aTopics;		
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
	public function GetTopicsFavouriteByUserId($sUserId,$iCurrPage,$iPerPage) {		
		if (false === ($data = $this->Cache_Get("topic_favourite_user_{$sUserId}_{$iCurrPage}_{$iPerPage}"))) {			
			$data = array('collection'=>$this->oMapperTopic->GetTopicsFavouriteByUserId($sUserId,$iCount,$iCurrPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "topic_favourite_user_{$sUserId}_{$iCurrPage}_{$iPerPage}", array('topic_update',"favourite_change_user_{$sUserId}"), 60*60*24*1);
		}
		$data['collection']=$this->GetTopicsAdditionalData($data['collection']);		
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
			$this->Cache_Set($data, "topic_count_favourite_user_{$sUserId}", array('topic_update',"favourite_change_user_{$sUserId}"), 60*60*24*1);
		}
		return $data;		
	}
	
	protected function GetTopicsByFilter($aFilter,$iPage,$iPerPage) {
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_filter_{$s}_{$iPage}_{$iPerPage}"))) {			
			$data = array('collection'=>$this->oMapperTopic->GetTopics($aFilter,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "topic_filter_{$s}_{$iPage}_{$iPerPage}", array('topic_update','topic_new'), 60*60*24*3);
		}
		$data['collection']=$this->GetTopicsAdditionalData($data['collection']);
		return $data;		
	}
	
	protected function GetCountTopicsByFilter($aFilter) {
		$s=serialize($aFilter);					
		if (false === ($data = $this->Cache_Get("topic_count_{$s}"))) {			
			$data = $this->oMapperTopic->GetCountTopics($aFilter);
			$this->Cache_Set($data, "topic_count_{$s}", array('topic_update','topic_new'), 60*60*24*1);
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
	 * Получает заданое число последних топиков
	 *
	 * @param unknown_type $iCount
	 * @return unknown
	 */
	public function GetTopicsLast($iCount) {		
		$aFilter=array(
			'blog_type' => array(
				'personal',
				'open',
			),
			'topic_publish' => 1,			
		);			
		$aReturn=$this->GetTopicsByFilter($aFilter,1,$iCount);
		if (isset($aReturn['collection'])) {
			return $aReturn['collection'];
		}
		return false;
	}
	/**
	 * Получает список топиков хороших из персональных блогов
	 *
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsPersonalGood($iPage,$iPerPage) {
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
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsPersonalBad($iPage,$iPerPage) {
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
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsPersonalNew($iPage,$iPerPage) {
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
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsPersonalByUser($sUserId,$iPublish,$iPage,$iPerPage) {
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
			$this->Cache_Set($data, "topic_count_user_{$s}", array("topic_update_user_{$sUserId}","topic_new_user_{$sUserId}"), 60*60*24);
		}
		return 	$data;		
	}
	/**
	 * список топиков из коллективных блогов
	 *
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @param unknown_type $sShowType
	 * @return unknown
	 */
	public function GetTopicsCollective($iPage,$iPerPage,$sShowType='good') {
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,			
		);
		switch ($sShowType) {
			case 'good':
				$aFilter['topic_rating']=array(
					'value' => BLOG_COLLECTIVE_LIMIT_GOOD,
					'type'  => 'top',
				);			
				break;	
			case 'bad':
				$aFilter['topic_rating']=array(
					'value' => BLOG_COLLECTIVE_LIMIT_GOOD,
					'type'  => 'down',
				);			
				break;	
			case 'new':
				$aFilter['topic_new']=date("Y-m-d H:00:00",time()-BLOG_TOPIC_NEW_TIME);							
				break;
			default:
				break;
		}
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
		if (false === ($data = $this->Cache_Get("topic_rating_{$sDate}_{$iLimit}"))) {
			$data = $this->oMapperTopic->GetTopicsRatingByDate($sDate,$iLimit);
			$this->Cache_Set($data, "topic_rating_{$sDate}_{$iLimit}", array('topic_update'), 60*60*24*2);
		}
		$data=$this->GetTopicsAdditionalData($data);
		return $data;		
	}	
	/**
	 * Список топиков из блога
	 *
	 * @param unknown_type $oBlog
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @param unknown_type $sShowType
	 * @return unknown
	 */
	public function GetTopicsByBlog($oBlog,$iPage,$iPerPage,$sShowType='good') {
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'blog_id' => $oBlog->getId(),
		);
		switch ($sShowType) {
			case 'good':
				$aFilter['topic_rating']=array(
					'value' => BLOG_COLLECTIVE_LIMIT_GOOD,
					'type'  => 'top',
				);			
				break;	
			case 'bad':
				$aFilter['topic_rating']=array(
					'value' => BLOG_COLLECTIVE_LIMIT_GOOD,
					'type'  => 'down',
				);			
				break;	
			case 'new':
				$aFilter['topic_new']=date("Y-m-d H:00:00",time()-BLOG_TOPIC_NEW_TIME);							
				break;
			default:
				break;
		}
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
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetTopicsByTag($sTag,$iPage,$iPerPage) {		
		if (false === ($data = $this->Cache_Get("topic_tag_{$sTag}_{$iPage}_{$iPerPage}"))) {			
			$data = array('collection'=>$this->oMapperTopic->GetTopicsByTag($sTag,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "topic_tag_{$sTag}_{$iPage}_{$iPerPage}", array('topic_update','topic_new'), 60*15);
		}
		$data['collection']=$this->GetTopicsAdditionalData($data['collection']);
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
			$this->Cache_Set($data, "tag_{$iLimit}", array('topic_update','topic_new'), 60*60*24*3);
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
		$data=$this->GetTopicsVoteByArray($sTopicId,$sUserId);
		if (isset($data[$sTopicId])) {
			return $data[$sTopicId];
		}
		return null;
	}
	
	/**
	 * Получить список голосований за топик по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetTopicsVoteByArray($aTopicId,$sUserId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aTopicsVote=array();
		$aTopicIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTopicId,'topic_vote_','_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aTopicsVote[$data[$sKey]->getTopicId()]=$data[$sKey];
					} else {
						$aTopicIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим каких топиков не было в кеше и делаем запрос в БД
		 */		
		$aTopicIdNeedQuery=array_diff($aTopicId,array_keys($aTopicsVote));		
		$aTopicIdNeedQuery=array_diff($aTopicIdNeedQuery,$aTopicIdNotNeedQuery);		
		$aTopicIdNeedStore=$aTopicIdNeedQuery;
		if ($data = $this->oMapperTopic->GetTopicsVoteByArray($aTopicIdNeedQuery,$sUserId)) {
			foreach ($data as $oTopicVote) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aTopicsVote[$oTopicVote->getTopicId()]=$oTopicVote;
				$this->Cache_Set($oTopicVote, "topic_vote_{$oTopicVote->getTopicId()}_{$oTopicVote->getVoterId()}", array(), 60*60*24*4);
				$aTopicIdNeedStore=array_diff($aTopicIdNeedStore,array($oTopicVote->getTopicId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTopicIdNeedStore as $sId) {
			$this->Cache_Set(null, "topic_vote_{$sId}_{$sUserId}", array(), 60*60*24*4);
		}		
		return $aTopicsVote;		
	}
	/**
	 * Увеличивает у топика число комментов
	 *
	 * @param unknown_type $sTopicId
	 * @return unknown
	 */
	public function increaseTopicCountComment($sTopicId) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("topic_update_{$sTopicId}"));						
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
		$data=$this->GetFavouriteTopicsByArray($sTopicId,$sUserId);
		if (isset($data[$sTopicId])) {
			return $data[$sTopicId];
		}
		return null;
	}
	/**
	 * Получить список избранного по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetFavouriteTopicsByArray($aTopicId,$sUserId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aFavouriteTopics=array();
		$aTopicIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTopicId,'favourite_topic_','_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aFavouriteTopics[$data[$sKey]->getTopicId()]=$data[$sKey];
					} else {
						$aTopicIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим каких топиков не было в кеше и делаем запрос в БД
		 */		
		$aTopicIdNeedQuery=array_diff($aTopicId,array_keys($aFavouriteTopics));		
		$aTopicIdNeedQuery=array_diff($aTopicIdNeedQuery,$aTopicIdNotNeedQuery);		
		$aTopicIdNeedStore=$aTopicIdNeedQuery;
		if ($data = $this->oMapperTopic->GetFavouriteTopicsByArray($aTopicIdNeedQuery,$sUserId)) {
			foreach ($data as $oFavouriteTopic) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aFavouriteTopics[$oFavouriteTopic->getTopicId()]=$oFavouriteTopic;
				$this->Cache_Set($oFavouriteTopic, "favourite_topic_{$oFavouriteTopic->getTopicId()}_{$oFavouriteTopic->getUserId()}", array(), 60*60*24*4);
				$aTopicIdNeedStore=array_diff($aTopicIdNeedStore,array($oFavouriteTopic->getTopicId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTopicIdNeedStore as $sId) {
			$this->Cache_Set(null, "favourite_topic_{$sId}_{$sUserId}", array(), 60*60*24*4);
		}		
		return $aFavouriteTopics;		
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
	 * Обновляем/устанавливаем дату прочтения топика, если читаем его первый раз то добавляем
	 *
	 * @param TopicEntity_TopicRead $oTopicRead	 
	 */
	public function SetTopicRead(TopicEntity_TopicRead $oTopicRead) {		
		if ($this->GetTopicRead($oTopicRead->getTopicId(),$oTopicRead->getUserId())) {
			$this->oMapperTopic->UpdateTopicRead($oTopicRead);
		} else {
			$this->oMapperTopic->AddTopicRead($oTopicRead);
		}
		return true;		
	}	
	/**
	 * Получаем дату прочтения топика юзером
	 *
	 * @param unknown_type $sTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTopicRead($sTopicId,$sUserId) {
		return $this->oMapperTopic->GetTopicRead($sTopicId,$sUserId);
	}	
	/**
	 * Получить список просмотром/чтения топиков по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetTopicsReadByArray($aTopicId,$sUserId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aTopicsRead=array();
		$aTopicIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTopicId,'topic_read_','_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aTopicsRead[$data[$sKey]->getTopicId()]=$data[$sKey];
					} else {
						$aTopicIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим каких топиков не было в кеше и делаем запрос в БД
		 */		
		$aTopicIdNeedQuery=array_diff($aTopicId,array_keys($aTopicsRead));		
		$aTopicIdNeedQuery=array_diff($aTopicIdNeedQuery,$aTopicIdNotNeedQuery);		
		$aTopicIdNeedStore=$aTopicIdNeedQuery;
		if ($data = $this->oMapperTopic->GetTopicsReadByArray($aTopicIdNeedQuery,$sUserId)) {
			foreach ($data as $oTopicRead) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aTopicsRead[$oTopicRead->getTopicId()]=$oTopicRead;
				$this->Cache_Set($oTopicRead, "topic_read_{$oTopicRead->getTopicId()}_{$oTopicRead->getUserId()}", array(), 60*60*24*4);
				$aTopicIdNeedStore=array_diff($aTopicIdNeedStore,array($oTopicRead->getTopicId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTopicIdNeedStore as $sId) {
			$this->Cache_Set(null, "topic_read_{$sId}_{$sUserId}", array(), 60*60*24*4);
		}		
		return $aTopicsRead;		
	}
	/**
	 * Проверяет голосовал ли юзер за топик-вопрос
	 *
	 * @param unknown_type $sTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTopicQuestionVote($sTopicId,$sUserId) {
		$data=$this->GetTopicsQuestionVoteByArray($sTopicId,$sUserId);
		if (isset($data[$sTopicId])) {
			return $data[$sTopicId];
		}
		return null;
	}
	/**
	 * Получить список голосований в топике-опросе по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetTopicsQuestionVoteByArray($aTopicId,$sUserId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);
		$aTopicsQuestionVote=array();
		$aTopicIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aTopicId,'topic_question_vote_','_'.$sUserId);
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {			
			/**
			 * проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {	
					if ($data[$sKey]) {
						$aTopicsQuestionVote[$data[$sKey]->getTopicId()]=$data[$sKey];
					} else {
						$aTopicIdNotNeedQuery[]=$sValue;
					}
				} 
			}
		}
		/**
		 * Смотрим каких топиков не было в кеше и делаем запрос в БД
		 */		
		$aTopicIdNeedQuery=array_diff($aTopicId,array_keys($aTopicsQuestionVote));		
		$aTopicIdNeedQuery=array_diff($aTopicIdNeedQuery,$aTopicIdNotNeedQuery);		
		$aTopicIdNeedStore=$aTopicIdNeedQuery;
		if ($data = $this->oMapperTopic->GetTopicsQuestionVoteByArray($aTopicIdNeedQuery,$sUserId)) {
			foreach ($data as $oTopicVote) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aTopicsQuestionVote[$oTopicVote->getTopicId()]=$oTopicVote;
				$this->Cache_Set($oTopicVote, "topic_question_vote_{$oTopicVote->getTopicId()}_{$oTopicVote->getVoterId()}", array(), 60*60*24*4);
				$aTopicIdNeedStore=array_diff($aTopicIdNeedStore,array($oTopicVote->getTopicId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTopicIdNeedStore as $sId) {
			$this->Cache_Set(null, "topic_question_vote_{$sId}_{$sUserId}", array(), 60*60*24*4);
		}		
		return $aTopicsQuestionVote;		
	}
	/**
	 * Добавляет факт голосования за топик-вопрос
	 *
	 * @param TopicEntity_TopicQuestionVote $oTopicQuestionVote
	 */
	public function AddTopicQuestionVote(TopicEntity_TopicQuestionVote $oTopicQuestionVote) {
		return $this->oMapperTopic->AddTopicQuestionVote($oTopicQuestionVote);
	}
	/**
	 * Получает топик по уникальному хешу(текст топика)
	 *
	 * @param unknown_type $sUserId
	 * @param unknown_type $sHash
	 * @return unknown
	 */
	public function GetTopicUnique($sUserId,$sHash) {
		return $this->GetTopicsAdditionalData($this->oMapperTopic->GetTopicUnique($sUserId,$sHash));
	}
	
	
}
?>