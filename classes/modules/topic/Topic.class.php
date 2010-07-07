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

/**
 * Модуль для работы с топиками
 *
 */
class ModuleTopic extends Module {		
	protected $oMapperTopic;
	protected $oUserCurrent=null;
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->oMapperTopic=Engine::GetMapper(__CLASS__);
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}
	/**
	 * Получает дополнительные данные(объекты) для топиков по их ID
	 *
	 */
	public function GetTopicsAdditionalData($aTopicId,$aAllowData=array('user'=>array(),'blog'=>array('owner'=>array(),'relation_user'),'vote','favourite','comment_new')) {
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
		$aTopicIdQuestion=array();		
		foreach ($aTopics as $oTopic) {
			if (isset($aAllowData['user'])) {
				$aUserId[]=$oTopic->getUserId();
			}
			if (isset($aAllowData['blog'])) {
				$aBlogId[]=$oTopic->getBlogId();
			}
			if ($oTopic->getType()=='question')	{		
				$aTopicIdQuestion[]=$oTopic->getId();
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
			$aTopicsVote=$this->Vote_GetVoteByArray($aTopicId,'topic',$this->oUserCurrent->getId());
			$aTopicsQuestionVote=$this->GetTopicsQuestionVoteByArray($aTopicIdQuestion,$this->oUserCurrent->getId());
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
				$oTopic->setUser(null); // или $oTopic->setUser(new ModuleUser_EntityUser());
			}
			if (isset($aBlogs[$oTopic->getBlogId()])) {
				$oTopic->setBlog($aBlogs[$oTopic->getBlogId()]);
			} else {
				$oTopic->setBlog(null); // или $oTopic->setBlog(new ModuleBlog_EntityBlog());
			}
			if (isset($aTopicsVote[$oTopic->getId()])) {
				$oTopic->setVote($aTopicsVote[$oTopic->getId()]);				
			} else {
				$oTopic->setVote(null);
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
	 * @param ModuleTopic_EntityTopic $oTopic
	 * @return unknown
	 */
	public function AddTopic(ModuleTopic_EntityTopic $oTopic) {
		if ($sId=$this->oMapperTopic->AddTopic($oTopic)) {
			$oTopic->setId($sId);
			if ($oTopic->getPublish()) {
				$aTags=explode(',',$oTopic->getTags());
				foreach ($aTags as $sTag) {
					$oTag=Engine::GetEntity('Topic_TopicTag');
					$oTag->setTopicId($oTopic->getId());
					$oTag->setUserId($oTopic->getUserId());
					$oTag->setBlogId($oTopic->getBlogId());
					$oTag->setText($sTag);
					$this->oMapperTopic->AddTopicTag($oTag);
				}
			}
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('topic_new',"topic_update_user_{$oTopic->getUserId()}","topic_new_blog_{$oTopic->getBlogId()}"));						
			return $oTopic;
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
	 * @param unknown_type $oTopicId|$sTopicId
	 * @return unknown
	 */
	public function DeleteTopic($oTopicId) {
		if ($oTopicId instanceof ModuleTopic_EntityTopic) {
			$sTopicId=$oTopicId->getId();
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("topic_update_user_{$oTopicId->getUserId()}"));
		} else {
			$sTopicId=$oTopicId;
		}
		/**
		 * Чистим зависимые кеши
		 */
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('topic_update'));
		$this->Cache_Delete("topic_{$sTopicId}");
		/**
		 * Если топик успешно удален, удаляем связанные данные
		 */
		if($bResult=$this->oMapperTopic->DeleteTopic($sTopicId)){
			return $this->DeleteTopicAdditionalData($sTopicId);
		}

		return false;
	}
	/**
	 * Удаляет свзяанные с топика данные
	 *
	 * @param  int  $iTopicId
	 * @return bool
	 */
	public function DeleteTopicAdditionalData($iTopicId) {
		/**
		 * Чистим зависимые кеши
		 */
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('topic_update'));
		$this->Cache_Delete("topic_{$iTopicId}");
		/**
		 * Удаляем комментарии к топику. 
		 * При удалении комментариев они удаляются из избранного,прямого эфира и голоса за них
		 */
		$this->Comment_DeleteCommentByTargetId($iTopicId,'topic');
		/**
		 * Удаляем топик из избранного
		 */
		$this->DeleteFavouriteTopicByArrayId($iTopicId);
		/**
		 * Удаляем топик из прочитанного
		 */
		$this->DeleteTopicReadByArrayId($iTopicId);
		/**
		 * Удаляем голосование к топику
		 */
		$this->Vote_DeleteVoteByTarget($iTopicId,'topic');
		/**
		 * Удаляем теги
		 */
		$this->DeleteTopicTagsByTopicId($iTopicId);
		
		return true;
	}
	/**
	 * Обновляет топик
	 *
	 * @param ModuleTopic_EntityTopic $oTopic
	 * @return unknown
	 */
	public function UpdateTopic(ModuleTopic_EntityTopic $oTopic) {
		/**
		 * Получаем топик ДО изменения
		 */
		$oTopicOld=$this->GetTopicById($oTopic->getId());
		$oTopic->setDateEdit(date("Y-m-d H:i:s"));
		if ($this->oMapperTopic->UpdateTopic($oTopic)) {	
			/**
			 * Если топик изменил видимость(publish) или локацию (BlogId) или список тегов
			 */
			if (($oTopic->getPublish()!=$oTopicOld->getPublish()) || ($oTopic->getBlogId()!=$oTopicOld->getBlogId()) || ($oTopic->getTags()!=$oTopicOld->getTags())) {
				/**
				 * Обновляем теги
				 */
				$aTags=explode(',',$oTopic->getTags());
				$this->DeleteTopicTagsByTopicId($oTopic->getId());
				
				if ($oTopic->getPublish()) {
					foreach ($aTags as $sTag) {
						$oTag=Engine::GetEntity('Topic_TopicTag');
						$oTag->setTopicId($oTopic->getId());
						$oTag->setUserId($oTopic->getUserId());
						$oTag->setBlogId($oTopic->getBlogId());
						$oTag->setText($sTag);
						$this->oMapperTopic->AddTopicTag($oTag);
					}
				}
			}
			if ($oTopic->getPublish()!=$oTopicOld->getPublish()) {
				/**
			 	* Обновляем избранное
			 	*/
				$this->SetFavouriteTopicPublish($oTopic->getId(),$oTopic->getPublish());
				/**
			 	* Удаляем комментарий топика из прямого эфира
			 	*/
				if ($oTopic->getPublish()==0) {
					$this->Comment_DeleteCommentOnlineByTargetId($oTopic->getId(),'topic');
				}
				/**
				 * Изменяем видимость комментов
				 */
				$this->Comment_SetCommentsPublish($oTopic->getId(),'topic',$oTopic->getPublish());
			}
			//чистим зависимые кеши			
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('topic_update',"topic_update_user_{$oTopic->getUserId()}","topic_update_blog_{$oTopic->getBlogId()}"));
			$this->Cache_Delete("topic_{$oTopic->getId()}");
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
		if (!$aTopicId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetTopicsByArrayIdSolid($aTopicId);
		}
		
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
				$this->Cache_Set($oTopic, "topic_{$oTopic->getId()}", array(), 60*60*24*4);
				$aTopicIdNeedStore=array_diff($aTopicIdNeedStore,array($oTopic->getId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aTopicIdNeedStore as $sId) {
			$this->Cache_Set(null, "topic_{$sId}", array(), 60*60*24*4);
		}	
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aTopics=func_array_sort_by_keys($aTopics,$aTopicId);
		return $aTopics;		
	}
	/**
	 * Получить список топиков по списку айдишников, но используя единый кеш
	 *
	 * @param unknown_type $aTopicId
	 * @return unknown
	 */
	public function GetTopicsByArrayIdSolid($aTopicId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);	
		$aTopics=array();	
		$s=join(',',$aTopicId);
		if (false === ($data = $this->Cache_Get("topic_id_{$s}"))) {			
			$data = $this->oMapperTopic->GetTopicsByArrayId($aTopicId);
			foreach ($data as $oTopic) {
				$aTopics[$oTopic->getId()]=$oTopic;
			}
			$this->Cache_Set($aTopics, "topic_id_{$s}", array("topic_update"), 60*60*24*1);
			return $aTopics;
		}		
		return $data;
	}
	/**
	 * Получает список топиков из избранного
	 *
	 * @param  string $sUserId
	 * @param  int    $iCount
	 * @param  int    $iCurrPage
	 * @param  int    $iPerPage
	 * @return array
	 */
	public function GetTopicsFavouriteByUserId($sUserId,$iCurrPage,$iPerPage) {		
		$aCloseTopics =array();							
		/**
		 * Получаем список идентификаторов избранных записей
		 */
		$data = ($this->oUserCurrent && $sUserId==$this->oUserCurrent->getId())
			? $this->Favourite_GetFavouritesByUserId($sUserId,'topic',$iCurrPage,$iPerPage,$aCloseTopics)
			: $this->Favourite_GetFavouriteOpenTopicsByUserId($sUserId,$iCurrPage,$iPerPage);
		/**
		 * Получаем записи по переданому массиву айдишников
		 */
		$data['collection']=$this->GetTopicsAdditionalData($data['collection']);		
		return $data;
	}
	/**
	 * Возвращает число топиков в избранном
	 *
	 * @param  string $sUserId
	 * @return int
	 */
	public function GetCountTopicsFavouriteByUserId($sUserId) {
		$aCloseTopics = array();					
		return ($this->oUserCurrent && $sUserId==$this->oUserCurrent->getId()) 
			? $this->Favourite_GetCountFavouritesByUserId($sUserId,'topic',$aCloseTopics)
			: $this->Favourite_GetCountFavouriteOpenTopicsByUserId($sUserId);	
	}
	/**
	 * Список топиков по фильтру
	 *
	 * @param  array $aFilter
	 * @param  int   $iPage
	 * @param  int   $iPerPage
	 * @return array
	 */
	public function GetTopicsByFilter($aFilter,$iPage=0,$iPerPage=0,$aAllowData=array('user'=>array(),'blog'=>array('owner'=>array(),'relation_user'),'vote','favourite','comment_new')) {
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_filter_{$s}_{$iPage}_{$iPerPage}"))) {			
			$data = ($iPage*$iPerPage!=0) 
				? array(
						'collection'=>$this->oMapperTopic->GetTopics($aFilter,$iCount,$iPage,$iPerPage),
						'count'=>$iCount
					)
				: array(
						'collection'=>$this->oMapperTopic->GetAllTopics($aFilter),
						'count'=>$this->GetCountTopicsByFilter($aFilter)
					);
			$this->Cache_Set($data, "topic_filter_{$s}_{$iPage}_{$iPerPage}", array('topic_update','topic_new'), 60*60*24*3);
		}
		$data['collection']=$this->GetTopicsAdditionalData($data['collection'],$aAllowData);
		return $data;
	}
	/**
	 * Количество топиков по фильтру
	 *
	 * @param unknown_type $aFilter
	 * @return unknown
	 */
	public function GetCountTopicsByFilter($aFilter) {
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
	 * @param  int    $iPage
	 * @param  int    $iPerPage
	 * @param  bool   $bAddAccessible Указывает на необходимость добавить в выдачу топики, 
	 *                                из блогов доступных пользователю. При указании false,
	 *                                в выдачу будут переданы только топики из общедоступных блогов.	 
	 * @return array
	 */
	public function GetTopicsGood($iPage,$iPerPage,$bAddAccessible=true) {
		$aFilter=array(
			'blog_type' => array(
				'personal',
				'open'
			),
			'topic_publish' => 1,
			'topic_rating'  => array(
				'value' => Config::Get('module.blog.index_good'),
				'type'  => 'top',
				'publish_index'  => 1,
			)
		);	
		/**
		 * Если пользователь авторизирован, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent && $bAddAccessible) {
			$aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
			if(count($aOpenBlogs)) $aFilter['blog_type']['close'] = $aOpenBlogs;			
		}
		
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}
	/**
	 * Получает список ВСЕХ новых топиков
	 *
	 * @param  int    $iPage
	 * @param  int    $iPerPage
	 * @param  bool   $bAddAccessible Указывает на необходимость добавить в выдачу топики, 
	 *                                из блогов доступных пользователю. При указании false,
	 *                                в выдачу будут переданы только топики из общедоступных блогов.
	 * @return array
	 */
	public function GetTopicsNew($iPage,$iPerPage,$bAddAccessible=true) {
		$sDate=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));
		$aFilter=array(
			'blog_type' => array(
				'personal',
				'open',
			),
			'topic_publish' => 1,
			'topic_new' => $sDate,
		);	
		/**
		 * Если пользователь авторизирован, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent && $bAddAccessible) {
			$aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
			if(count($aOpenBlogs)) $aFilter['blog_type']['close'] = $aOpenBlogs;
		}			
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
		/**
		 * Если пользователь авторизирован, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent) {
			$aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
			if(count($aOpenBlogs)) $aFilter['blog_type']['close'] = $aOpenBlogs;
		}	
		$aReturn=$this->GetTopicsByFilter($aFilter,1,$iCount);
		if (isset($aReturn['collection'])) {
			return $aReturn['collection'];
		}
		return false;
	}
	/**
	 * список топиков из персональных блогов
	 *
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @param unknown_type $sShowType
	 * @return unknown
	 */
	public function GetTopicsPersonal($iPage,$iPerPage,$sShowType='good') {
		$aFilter=array(
			'blog_type' => array(
				'personal',
			),
			'topic_publish' => 1,			
		);
		switch ($sShowType) {
			case 'good':
				$aFilter['topic_rating']=array(
					'value' => Config::Get('module.blog.personal_good'),
					'type'  => 'top',
				);			
				break;	
			case 'bad':
				$aFilter['topic_rating']=array(
					'value' => Config::Get('module.blog.personal_good'),
					'type'  => 'down',
				);			
				break;	
			case 'new':
				$aFilter['topic_new']=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));							
				break;
			default:
				break;
		}
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}	
	/**
	 * Получает число новых топиков в персональных блогах
	 *
	 * @return unknown
	 */
	public function GetCountTopicsPersonalNew() {
		$sDate=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));
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
			'blog_type' => array('open','personal'),
		);
		/**
		 * Если пользователь смотрит свой профиль, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent && $this->oUserCurrent->getId()==$sUserId) {
			$aFilter['blog_type'][]='close';
		}		
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
			'blog_type' => array('open','personal'),
		);
		/**
		 * Если пользователь смотрит свой профиль, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent && $this->oUserCurrent->getId()==$sUserId) {
			$aFilter['blog_type'][]='close';
		}		
		$s=serialize($aFilter);
		if (false === ($data = $this->Cache_Get("topic_count_user_{$s}"))) {			
			$data = $this->oMapperTopic->GetCountTopics($aFilter);
			$this->Cache_Set($data, "topic_count_user_{$s}", array("topic_update_user_{$sUserId}"), 60*60*24);
		}
		return 	$data;		
	}
	
	/**
	 * Получает список идентификаторов топиков 
	 * из закрытых блогов по юзеру
	 *
	 * @param  string $sUserId
	 * @return array
	 */
	public function GetTopicsCloseByUser($sUserId=null) {
		if(!is_null($sUserId) && $oUser=$this->User_GetUserById($sUserId)) {
			$aCloseBlogs=$this->Blog_GetInaccessibleBlogsByUser($oUser);
			$aFilter=array(
				'topic_publish' => 1,
				'blog_id' => $aCloseBlogs,
			);
		} else {
			$aFilter=array(
				'topic_publish' => 1,
				'blog_type' => array('close'),
			);
		}
		
		$aTopics=$this->GetTopicsByFilter($aFilter);
		return array_keys($aTopics['collection']);
	}
	
	/**
	 * Получает список топиков из указанного блога
	 *
	 * @param  int   $iBlogId
	 * @param  int   $iPage
	 * @param  int   $iPerPage
	 * @param  array $aAllowData
	 * @param  bool  $bIdsOnly
	 * @return array
	 */
	public function GetTopicsByBlogId($iBlogId,$iPage=0,$iPerPage=0,$aAllowData=array(),$bIdsOnly=true) {
		$aFilter=array('blog_id'=>$iBlogId);
		
		if(!$aTopics = $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage,$aAllowData) ) {
			return false;
		}
		
		return ($bIdsOnly) 
			? array_keys($aTopics['collection'])
			: $aTopics;		
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
					'value' => Config::Get('module.blog.collective_good'),
					'type'  => 'top',
				);			
				break;	
			case 'bad':
				$aFilter['topic_rating']=array(
					'value' => Config::Get('module.blog.collective_good'),
					'type'  => 'down',
				);			
				break;	
			case 'new':
				$aFilter['topic_new']=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));							
				break;
			default:
				break;
		}
		/**
		 * Если пользователь авторизирован, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent) {
			$aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
			if(count($aOpenBlogs)) $aFilter['blog_type']['close'] = $aOpenBlogs;
		}
		return $this->GetTopicsByFilter($aFilter,$iPage,$iPerPage);
	}	
	/**
	 * Получает число новых топиков в коллективных блогах
	 *
	 * @return unknown
	 */
	public function GetCountTopicsCollectiveNew() {
		$sDate=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));
		$aFilter=array(
			'blog_type' => array(
				'open',
			),
			'topic_publish' => 1,
			'topic_new' => $sDate,
		);
		/**
		 * Если пользователь авторизирован, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent) {
			$aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
			if(count($aOpenBlogs)) $aFilter['blog_type']['close'] = $aOpenBlogs;
		}		
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
		/**
		 * Получаем список блогов, топики которых нужно исключить из выдачи
		 */
		$aCloseBlogs = ($this->oUserCurrent)
			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
			: $this->Blog_GetInaccessibleBlogsByUser();	
		
		$s=serialize($aCloseBlogs);
		
		if (false === ($data = $this->Cache_Get("topic_rating_{$sDate}_{$iLimit}_{$s}"))) {
			$data = $this->oMapperTopic->GetTopicsRatingByDate($sDate,$iLimit,$aCloseBlogs);
			$this->Cache_Set($data, "topic_rating_{$sDate}_{$iLimit}_{$s}", array('topic_update'), 60*60*24*2);
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
			'topic_publish' => 1,
			'blog_id' => $oBlog->getId(),			
		);
		switch ($sShowType) {
			case 'good':
				$aFilter['topic_rating']=array(
					'value' => Config::Get('module.blog.collective_good'),
					'type'  => 'top',
				);			
				break;	
			case 'bad':
				$aFilter['topic_rating']=array(
					'value' => Config::Get('module.blog.collective_good'),
					'type'  => 'down',
				);			
				break;	
			case 'new':
				$aFilter['topic_new']=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));							
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
		$sDate=date("Y-m-d H:00:00",time()-Config::Get('module.topic.new_time'));
		$aFilter=array(			
			'topic_publish' => 1,
			'blog_id' => $oBlog->getId(),
			'topic_new' => $sDate,
			
		);	
		return $this->GetCountTopicsByFilter($aFilter);		
	}
	/**
	 * Получает список топиков по тегу
	 *
	 * @param  string $sTag
	 * @param  int    $iPage
	 * @param  int    $iPerPage
	 * @param  bool   $bAddAccessible Указывает на необходимость добавить в выдачу топики, 
	 *                                из блогов доступных пользователю. При указании false,
	 *                                в выдачу будут переданы только топики из общедоступных блогов.
	 * @return array
	 */
	public function GetTopicsByTag($sTag,$iPage,$iPerPage,$bAddAccessible=true) {
		$aCloseBlogs = ($this->oUserCurrent && $bAddAccessible) 
			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
			: $this->Blog_GetInaccessibleBlogsByUser();
		
		$s = serialize($aCloseBlogs);	
		if (false === ($data = $this->Cache_Get("topic_tag_{$sTag}_{$iPage}_{$iPerPage}_{$s}"))) {			
			$data = array('collection'=>$this->oMapperTopic->GetTopicsByTag($sTag,$aCloseBlogs,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "topic_tag_{$sTag}_{$iPage}_{$iPerPage}_{$s}", array('topic_update','topic_new'), 60*60*24*2);
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
	public function GetTopicTags($iLimit,$aExcludeTopic=array()) {
		$s=serialize($aExcludeTopic);
		if (false === ($data = $this->Cache_Get("tag_{$iLimit}_{$s}"))) {			
			$data = $this->oMapperTopic->GetTopicTags($iLimit,$aExcludeTopic);
			$this->Cache_Set($data, "tag_{$iLimit}_{$s}", array('topic_update','topic_new'), 60*60*24*3);
		}
		return $data;
	}
	/**
	 * Получает список тегов из топиков открытых блогов (open,personal)
	 *
	 * @param  int $iLimit
	 * @return array
	 */
	public function GetOpenTopicTags($iLimit) {
		if (false === ($data = $this->Cache_Get("tag_{$iLimit}_open"))) {			
			$data = $this->oMapperTopic->GetOpenTopicTags($iLimit);
			$this->Cache_Set($data, "tag_{$iLimit}_open", array('topic_update','topic_new'), 60*60*24*3);
		}
		return $data;
	}	
	
	/**
	 * Увеличивает у топика число комментов
	 *
	 * @param unknown_type $sTopicId
	 * @return unknown
	 */
	public function increaseTopicCountComment($sTopicId) {		
		$this->Cache_Delete("topic_{$sTopicId}");
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("topic_update"));
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
		return $this->Favourite_GetFavourite($sTopicId,'topic',$sUserId);
	}
	/**
	 * Получить список избранного по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetFavouriteTopicsByArray($aTopicId,$sUserId) {
		return $this->Favourite_GetFavouritesByArray($aTopicId,'topic',$sUserId);
	}
	/**
	 * Получить список избранного по списку айдишников, но используя единый кеш
	 *
	 * @param array $aTopicId
	 * @param int $sUserId
	 * @return array
	 */
	public function GetFavouriteTopicsByArraySolid($aTopicId,$sUserId) {
		return $this->Favourite_GetFavouritesByArraySolid($aTopicId,'topic',$sUserId);
	}
	/**
	 * Добавляет топик в избранное
	 *
	 * @param ModuleFavourite_EntityFavourite $oFavouriteTopic
	 * @return unknown
	 */
	public function AddFavouriteTopic(ModuleFavourite_EntityFavourite $oFavouriteTopic) {		
		return $this->Favourite_AddFavourite($oFavouriteTopic);
	}
	/**
	 * Удаляет топик из избранного
	 *
	 * @param ModuleFavourite_EntityFavourite $oFavouriteTopic
	 * @return unknown
	 */
	public function DeleteFavouriteTopic(ModuleFavourite_EntityFavourite $oFavouriteTopic) {	
		return $this->Favourite_DeleteFavourite($oFavouriteTopic);
	}
	/**
	 * Устанавливает переданный параметр публикации таргета (топика)
	 *
	 * @param  string $sTopicId
	 * @param  int    $iPublish
	 * @return bool
	 */
	public function SetFavouriteTopicPublish($sTopicId,$iPublish) {
		return $this->Favourite_SetFavouriteTargetPublish($sTopicId,'topic',$iPublish);		
	}	
	/**
	 * Удаляет топики из избранного по списку 
	 *
	 * @param  array $aTopicId
	 * @return bool
	 */
	public function DeleteFavouriteTopicByArrayId($aTopicId) {
		return $this->Favourite_DeleteFavouriteByTargetId($aTopicId, 'topic');
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
			$this->Cache_Set($data, "tag_like_{$sTag}_{$iLimit}", array("topic_update","topic_new"), 60*60*24*3);
		}
		return $data;		
	}
	/**
	 * Обновляем/устанавливаем дату прочтения топика, если читаем его первый раз то добавляем
	 *
	 * @param ModuleTopic_EntityTopicRead $oTopicRead	 
	 */
	public function SetTopicRead(ModuleTopic_EntityTopicRead $oTopicRead) {		
		if ($this->GetTopicRead($oTopicRead->getTopicId(),$oTopicRead->getUserId())) {
			$this->Cache_Delete("topic_read_{$oTopicRead->getTopicId()}_{$oTopicRead->getUserId()}");
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("topic_read_user_{$oTopicRead->getUserId()}"));
			$this->oMapperTopic->UpdateTopicRead($oTopicRead);
		} else {
			$this->Cache_Delete("topic_read_{$oTopicRead->getTopicId()}_{$oTopicRead->getUserId()}");
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("topic_read_user_{$oTopicRead->getUserId()}"));
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
		$data=$this->GetTopicsReadByArray($sTopicId,$sUserId);
		if (isset($data[$sTopicId])) {
			return $data[$sTopicId];
		}
		return null;
	}
	/**
	 * Удаляет записи о чтении записей по списку идентификаторов
	 *
	 * @param  array|int $aTopicId
	 * @return bool
	 */
	public function DeleteTopicReadByArrayId($aTopicId) {
		if(!is_array($aTopicId)) $aTopicId = array($aTopicId);
		return $this->oMapperTopic->DeleteTopicReadByArrayId($aTopicId);
	}
	/**
	 * Получить список просмотром/чтения топиков по списку айдишников
	 *
	 * @param unknown_type $aTopicId
	 */
	public function GetTopicsReadByArray($aTopicId,$sUserId) {
		if (!$aTopicId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetTopicsReadByArraySolid($aTopicId,$sUserId);
		}
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
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aTopicsRead=func_array_sort_by_keys($aTopicsRead,$aTopicId);
		return $aTopicsRead;		
	}
	/**
	 * Получить список просмотром/чтения топиков по списку айдишников, но используя единый кеш
	 *
	 * @param unknown_type $aTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTopicsReadByArraySolid($aTopicId,$sUserId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);	
		$aTopicsRead=array();	
		$s=join(',',$aTopicId);
		if (false === ($data = $this->Cache_Get("topic_read_{$sUserId}_id_{$s}"))) {			
			$data = $this->oMapperTopic->GetTopicsReadByArray($aTopicId,$sUserId);
			foreach ($data as $oTopicRead) {
				$aTopicsRead[$oTopicRead->getTopicId()]=$oTopicRead;
			}
			$this->Cache_Set($aTopicsRead, "topic_read_{$sUserId}_id_{$s}", array("topic_read_user_{$sUserId}"), 60*60*24*1);
			return $aTopicsRead;
		}		
		return $data;
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
		if (!$aTopicId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetTopicsQuestionVoteByArraySolid($aTopicId,$sUserId);
		}
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
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aTopicsQuestionVote=func_array_sort_by_keys($aTopicsQuestionVote,$aTopicId);
		return $aTopicsQuestionVote;		
	}
	/**
	 * Получить список голосований в топике-опросе по списку айдишников, но используя единый кеш
	 *
	 * @param unknown_type $aTopicId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTopicsQuestionVoteByArraySolid($aTopicId,$sUserId) {
		if (!is_array($aTopicId)) {
			$aTopicId=array($aTopicId);
		}
		$aTopicId=array_unique($aTopicId);	
		$aTopicsQuestionVote=array();	
		$s=join(',',$aTopicId);
		if (false === ($data = $this->Cache_Get("topic_question_vote_{$sUserId}_id_{$s}"))) {			
			$data = $this->oMapperTopic->GetTopicsQuestionVoteByArray($aTopicId,$sUserId);
			foreach ($data as $oTopicVote) {
				$aTopicsQuestionVote[$oTopicVote->getTopicId()]=$oTopicVote;
			}
			$this->Cache_Set($aTopicsQuestionVote, "topic_question_vote_{$sUserId}_id_{$s}", array("topic_question_vote_user_{$sUserId}"), 60*60*24*1);
			return $aTopicsQuestionVote;
		}		
		return $data;
	}
	/**
	 * Добавляет факт голосования за топик-вопрос
	 *
	 * @param ModuleTopic_EntityTopicQuestionVote $oTopicQuestionVote
	 */
	public function AddTopicQuestionVote(ModuleTopic_EntityTopicQuestionVote $oTopicQuestionVote) {
		$this->Cache_Delete("topic_question_vote_{$oTopicQuestionVote->getTopicId()}_{$oTopicQuestionVote->getVoterId()}");
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("topic_question_vote_user_{$oTopicQuestionVote->getVoterId()}"));
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
		$sId=$this->oMapperTopic->GetTopicUnique($sUserId,$sHash);
		return $this->GetTopicById($sId);
	}
	/**
	 * Рассылает уведомления о новом топике подписчикам блога
	 *
	 * @param unknown_type $oBlog
	 * @param unknown_type $oTopic
	 * @param unknown_type $oUserTopic
	 */
	public function SendNotifyTopicNew($oBlog,$oTopic,$oUserTopic) {
		$aBlogUsers=$this->Blog_GetBlogUsersByBlogId($oBlog->getId());
		foreach ($aBlogUsers as $oBlogUser) {
			if ($oBlogUser->getUserId()==$oUserTopic->getId()) {
				continue;
			}
			$this->Notify_SendTopicNewToSubscribeBlog($oBlogUser->getUser(),$oTopic,$oBlog,$oUserTopic);
		}
		//отправляем создателю блога
		if ($oBlog->getOwnerId()!=$oUserTopic->getId()) {
			$this->Notify_SendTopicNewToSubscribeBlog($oBlog->getOwner(),$oTopic,$oBlog,$oUserTopic);
		}	
	}
	/**
	 * Возвращает список последних топиков пользователя,
	 * опубликованных не более чем $iTimeLimit секунд назад
	 *
	 * @param  string $sUserId
	 * @param  int    $iTimeLimit
	 * @param  int    $iCountLimit
	 * @return array
	 */
	public function GetLastTopicsByUserId($sUserId,$iTimeLimit,$iCountLimit=1,$aAllowData=array()) {
		$aFilter = array(
			'topic_publish' => 1,
			'user_id' => $sUserId,
			'topic_new' => date("Y-m-d H:i:s",time()-$iTimeLimit),
		);
		$aTopics = $this->GetTopicsByFilter($aFilter,1,$iCountLimit,$aAllowData);

		return $aTopics;
	}
	
	/**
	 * Перемещает топики в другой блог
	 *
	 * @param  array  $aTopics
	 * @param  string $sBlogId
	 * @return bool
	 */
	public function MoveTopicsByArrayId($aTopics,$sBlogId) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("topic_update", "topic_new_blog_{$sBlogId}"));
		if ($res=$this->oMapperTopic->MoveTopicsByArrayId($aTopics,$sBlogId)) {
			// перемещаем теги
			$this->oMapperTopic->MoveTopicsTagsByArrayId($aTopics,$sBlogId);
			// меняем target parent у комментов
			$this->Comment_UpdateTargetParentByTargetId($sBlogId, 'topic', $aTopics);
			// меняем target parent у комментов в прямом эфире
			$this->Comment_UpdateTargetParentByTargetIdOnline($sBlogId, 'topic', $aTopics);
			return $res;
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
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("topic_update", "topic_new_blog_{$sBlogId}", "topic_new_blog_{$sBlogIdNew}"));
		if ($res=$this->oMapperTopic->MoveTopics($sBlogId,$sBlogIdNew)) {
			// перемещаем теги
			$this->oMapperTopic->MoveTopicsTags($sBlogId,$sBlogIdNew);
			// меняем target parent у комментов
			$this->Comment_MoveTargetParent($sBlogId, 'topic', $sBlogIdNew);
			// меняем target parent у комментов в прямом эфире
			$this->Comment_MoveTargetParentOnline($sBlogId, 'topic', $sBlogIdNew);
			return $res;
		}
		return false;
	}	
	
	/**
	 * Заргузка изображений при написании топика
	 *
	 * @param  array           $aFile
	 * @param  ModuleUser_EntityUser $oUser
	 * @return string|bool
	 */
	public function UploadTopicImageFile($aFile,$oUser) {
		if(!is_array($aFile) || !isset($aFile['tmp_name'])) {
			return false;
		}
							
		$sFileTmp=Config::Get('sys.cache.dir').func_generator();		
		if (!move_uploaded_file($aFile['tmp_name'],$sFileTmp)) {			
			return false;
		}
		$sDirUpload=$this->Image_GetIdDir($oUser->getId());
		$aParams=$this->Image_BuildParams('topic');
		
		if ($sFileImage=$this->Image_Resize($sFileTmp,$sDirUpload,func_generator(6),Config::Get('view.img_max_width'),Config::Get('view.img_max_height'),Config::Get('view.img_resize_width'),null,true,$aParams)) {
			@unlink($sFileTmp);
			return $this->Image_GetWebPath($sFileImage);
		}
		@unlink($sFileTmp);
		return false;
	}
	/**
	 * Загрузка изображений по переданному URL
	 *
	 * @param  string          $sUrl
	 * @param  ModuleUser_EntityUser $oUser
	 * @return (string|bool)
	 */
	public function UploadTopicImageUrl($sUrl, $oUser) {
		/**
		 * Проверяем, является ли файл изображением
		 */
		if(!@getimagesize($sUrl)) {
			return ModuleImage::UPLOAD_IMAGE_ERROR_TYPE;
		}
		/**
		 * Открываем файловый поток и считываем файл поблочно,
		 * контролируя максимальный размер изображения
		 */
		$oFile=fopen($sUrl,'r');
		if(!$oFile) {
			return ModuleImage::UPLOAD_IMAGE_ERROR_READ;
		}
		
		$iMaxSizeKb=500;
		$iSizeKb=0;
		$sContent='';
		while (!feof($oFile) and $iSizeKb<$iMaxSizeKb) {
			$sContent.=fread($oFile ,1024*1);
			$iSizeKb++;
		}

		/**
		 * Если конец файла не достигнут,
		 * значит файл имеет недопустимый размер
		 */
		if(!feof($oFile)) {
			return ModuleImage::UPLOAD_IMAGE_ERROR_SIZE;
		}
		fclose($oFile);

		/**
		 * Создаем tmp-файл, для временного хранения изображения
		 */
		$sFileTmp=Config::Get('sys.cache.dir').func_generator();
		
		$fp=fopen($sFileTmp,'w');
		fwrite($fp,$sContent);
		fclose($fp);
		
		$sDirSave=$this->Image_GetIdDir($oUser->getId());
		$aParams=$this->Image_BuildParams('topic');
		
		/**
		 * Передаем изображение на обработку
		 */
		if ($sFileImg=$this->Image_Resize($sFileTmp,$sDirSave,func_generator(),Config::Get('view.img_max_width'),Config::Get('view.img_max_height'),Config::Get('view.img_resize_width'),null,true,$aParams)) {
			@unlink($sFileTmp);
			return $this->Image_GetWebPath($sFileImg);
		} 		
		
		@unlink($sFileTmp);
		return ModuleImage::UPLOAD_IMAGE_ERROR;
	}
}
?>