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
 * Модуль для работы с комментариями
 *
 * @package modules.comment
 * @since 1.0
 */
class ModuleComment extends Module {
	/**
	 * Объект маппера
	 *
	 * @var ModuleComment_MapperComment
	 */
	protected $oMapper;
	/**
	 * Объект текущего пользователя
	 *
	 * @var ModuleUser_EntityUser|null
	 */
	protected $oUserCurrent=null;

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		$this->oMapper=Engine::GetMapper(__CLASS__);
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}
	/**
	 * Получить коммент по айдишнику
	 *
	 * @param int $sId	ID комментария
	 * @return ModuleComment_EntityComment|null
	 */
	public function GetCommentById($sId) {
		$aComments=$this->GetCommentsAdditionalData($sId);
		if (isset($aComments[$sId])) {
			return $aComments[$sId];
		}
		return null;
	}
	/**
	 * Получает уникальный коммент, это помогает спастись от дублей комментов
	 *
	 * @param int $sTargetId	ID владельца комментария
	 * @param string $sTargetType	Тип владельца комментария
	 * @param int $sUserId	ID пользователя
	 * @param int $sCommentPid	ID родительского комментария
	 * @param string $sHash	Хеш строка текста комментария
	 * @return ModuleComment_EntityComment|null
	 */
	public function GetCommentUnique($sTargetId,$sTargetType,$sUserId,$sCommentPid,$sHash) {
		$sId=$this->oMapper->GetCommentUnique($sTargetId,$sTargetType,$sUserId,$sCommentPid,$sHash);
		return $this->GetCommentById($sId);
	}
	/**
	 * Получить все комменты
	 *
	 * @param string $sTargetType	Тип владельца комментария
	 * @param int $iPage	Номер страницы
	 * @param int $iPerPage	Количество элементов на страницу
	 * @param array $aExcludeTarget	Список ID владельцев, которые необходимо исключить из выдачи
	 * @param array $aExcludeParentTarget	Список ID родителей владельцев, которые необходимо исключить из выдачи, например, исключить комментарии топиков к определенным блогам(закрытым)
	 * @return array('collection'=>array,'count'=>int)
	 */
	public function GetCommentsAll($sTargetType,$iPage,$iPerPage,$aExcludeTarget=array(),$aExcludeParentTarget=array()) {
		$s=serialize($aExcludeTarget).serialize($aExcludeParentTarget);
		if (false === ($data = $this->Cache_Get("comment_all_{$sTargetType}_{$iPage}_{$iPerPage}_{$s}"))) {
			$data = array('collection'=>$this->oMapper->GetCommentsAll($sTargetType,$iCount,$iPage,$iPerPage,$aExcludeTarget,$aExcludeParentTarget),'count'=>$iCount);
			$this->Cache_Set($data, "comment_all_{$sTargetType}_{$iPage}_{$iPerPage}_{$s}", array("comment_new_{$sTargetType}","comment_update_status_{$sTargetType}"), 60*60*24*1);
		}
		$data['collection']=$this->GetCommentsAdditionalData($data['collection'],array('target','favourite','user'=>array()));
		return $data;
	}
	/**
	 * Получает дополнительные данные(объекты) для комментов по их ID
	 *
	 * @param array $aCommentId	Список ID комментов
	 * @param array|null $aAllowData	Список типов дополнительных данных, которые нужно получить для комментариев
	 * @return array
	 */
	public function GetCommentsAdditionalData($aCommentId,$aAllowData=null) {
		if (is_null($aAllowData)) {
			$aAllowData=array('vote','target','favourite','user'=>array());
		}
		func_array_simpleflip($aAllowData);
		if (!is_array($aCommentId)) {
			$aCommentId=array($aCommentId);
		}
		/**
		 * Получаем комменты
		 */
		$aComments=$this->GetCommentsByArrayId($aCommentId);
		/**
		 * Формируем ID дополнительных данных, которые нужно получить
		 */
		$aUserId=array();
		$aTargetId=array('topic'=>array(),'talk'=>array());
		foreach ($aComments as $oComment) {
			if (isset($aAllowData['user'])) {
				$aUserId[]=$oComment->getUserId();
			}
			if (isset($aAllowData['target'])) {
				$aTargetId[$oComment->getTargetType()][]=$oComment->getTargetId();
			}
		}
		/**
		 * Получаем дополнительные данные
		 */
		$aUsers=isset($aAllowData['user']) && is_array($aAllowData['user']) ? $this->User_GetUsersAdditionalData($aUserId,$aAllowData['user']) : $this->User_GetUsersAdditionalData($aUserId);
		/**
		 * В зависимости от типа target_type достаем данные
		 */
		$aTargets=array();
		//$aTargets['topic']=isset($aAllowData['target']) && is_array($aAllowData['target']) ? $this->Topic_GetTopicsAdditionalData($aTargetId['topic'],$aAllowData['target']) : $this->Topic_GetTopicsAdditionalData($aTargetId['topic']);
		$aTargets['topic']=$this->Topic_GetTopicsAdditionalData($aTargetId['topic'],array('blog'=>array('owner'=>array())));
		$aVote=array();
		if (isset($aAllowData['vote']) and $this->oUserCurrent) {
			$aVote=$this->Vote_GetVoteByArray($aCommentId,'comment',$this->oUserCurrent->getId());
		}
		if (isset($aAllowData['favourite']) and $this->oUserCurrent) {
			$aFavouriteComments=$this->Favourite_GetFavouritesByArray($aCommentId,'comment',$this->oUserCurrent->getId());
		}
		/**
		 * Добавляем данные к результату
		 */
		foreach ($aComments as $oComment) {
			if (isset($aUsers[$oComment->getUserId()])) {
				$oComment->setUser($aUsers[$oComment->getUserId()]);
			} else {
				$oComment->setUser(null); // или $oComment->setUser(new ModuleUser_EntityUser());
			}
			if (isset($aTargets[$oComment->getTargetType()][$oComment->getTargetId()])) {
				$oComment->setTarget($aTargets[$oComment->getTargetType()][$oComment->getTargetId()]);
			} else {
				$oComment->setTarget(null);
			}
			if (isset($aVote[$oComment->getId()])) {
				$oComment->setVote($aVote[$oComment->getId()]);
			} else {
				$oComment->setVote(null);
			}
			if (isset($aFavouriteComments[$oComment->getId()])) {
				$oComment->setIsFavourite(true);
			} else {
				$oComment->setIsFavourite(false);
			}
		}
		return $aComments;
	}
	/**
	 * Список комментов по ID
	 *
	 * @param array $aCommentId	Список ID комментариев
	 * @return array
	 */
	public function GetCommentsByArrayId($aCommentId) {
		if (!$aCommentId) {
			return array();
		}
		if (Config::Get('sys.cache.solid')) {
			return $this->GetCommentsByArrayIdSolid($aCommentId);
		}
		if (!is_array($aCommentId)) {
			$aCommentId=array($aCommentId);
		}
		$aCommentId=array_unique($aCommentId);
		$aComments=array();
		$aCommentIdNotNeedQuery=array();
		/**
		 * Делаем мульти-запрос к кешу
		 */
		$aCacheKeys=func_build_cache_keys($aCommentId,'comment_');
		if (false !== ($data = $this->Cache_Get($aCacheKeys))) {
			/**
			 * Проверяем что досталось из кеша
			 */
			foreach ($aCacheKeys as $sValue => $sKey ) {
				if (array_key_exists($sKey,$data)) {
					if ($data[$sKey]) {
						$aComments[$data[$sKey]->getId()]=$data[$sKey];
					} else {
						$aCommentIdNotNeedQuery[]=$sValue;
					}
				}
			}
		}
		/**
		 * Смотрим каких комментов не было в кеше и делаем запрос в БД
		 */
		$aCommentIdNeedQuery=array_diff($aCommentId,array_keys($aComments));
		$aCommentIdNeedQuery=array_diff($aCommentIdNeedQuery,$aCommentIdNotNeedQuery);
		$aCommentIdNeedStore=$aCommentIdNeedQuery;
		if ($data = $this->oMapper->GetCommentsByArrayId($aCommentIdNeedQuery)) {
			foreach ($data as $oComment) {
				/**
				 * Добавляем к результату и сохраняем в кеш
				 */
				$aComments[$oComment->getId()]=$oComment;
				$this->Cache_Set($oComment, "comment_{$oComment->getId()}", array(), 60*60*24*4);
				$aCommentIdNeedStore=array_diff($aCommentIdNeedStore,array($oComment->getId()));
			}
		}
		/**
		 * Сохраняем в кеш запросы не вернувшие результата
		 */
		foreach ($aCommentIdNeedStore as $sId) {
			$this->Cache_Set(null, "comment_{$sId}", array(), 60*60*24*4);
		}
		/**
		 * Сортируем результат согласно входящему массиву
		 */
		$aComments=func_array_sort_by_keys($aComments,$aCommentId);
		return $aComments;
	}
	/**
	 * Получает список комментариев по ID используя единый кеш
	 *
	 * @param array $aCommentId Список ID комментариев
	 * @return array
	 */
	public function GetCommentsByArrayIdSolid($aCommentId) {
		if (!is_array($aCommentId)) {
			$aCommentId=array($aCommentId);
		}
		$aCommentId=array_unique($aCommentId);
		$aComments=array();
		$s=join(',',$aCommentId);
		if (false === ($data = $this->Cache_Get("comment_id_{$s}"))) {
			$data = $this->oMapper->GetCommentsByArrayId($aCommentId);
			foreach ($data as $oComment) {
				$aComments[$oComment->getId()]=$oComment;
			}
			$this->Cache_Set($aComments, "comment_id_{$s}", array("comment_update"), 60*60*24*1);
			return $aComments;
		}
		return $data;
	}
	/**
	 * Получить все комменты сгрупированные по типу(для вывода прямого эфира)
	 *
	 * @param string $sTargetType	Тип владельца комментария
	 * @param int $iLimit	Количество элементов
	 * @return array
	 */
	public function GetCommentsOnline($sTargetType,$iLimit) {
		/**
		 * Исключаем из выборки идентификаторы закрытых блогов (target_parent_id)
		 */
		$aCloseBlogs = ($this->oUserCurrent)
			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
			: $this->Blog_GetInaccessibleBlogsByUser();

		$s=serialize($aCloseBlogs);

		if (false === ($data = $this->Cache_Get("comment_online_{$sTargetType}_{$s}_{$iLimit}"))) {
			$data = $this->oMapper->GetCommentsOnline($sTargetType,$aCloseBlogs,$iLimit);
			$this->Cache_Set($data, "comment_online_{$sTargetType}_{$s}_{$iLimit}", array("comment_online_update_{$sTargetType}"), 60*60*24*1);
		}
		$data=$this->GetCommentsAdditionalData($data);
		return $data;
	}
	/**
	 * Получить комменты по юзеру
	 *
	 * @param  int $sId	ID пользователя
	 * @param  string $sTargetType	Тип владельца комментария
	 * @param  int    $iPage	Номер страницы
	 * @param  int    $iPerPage	Количество элементов на страницу
	 * @return array
	 */
	public function GetCommentsByUserId($sId,$sTargetType,$iPage,$iPerPage) {
		/**
		 * Исключаем из выборки идентификаторы закрытых блогов
		 */
		$aCloseBlogs = ($this->oUserCurrent && $sId==$this->oUserCurrent->getId())
			? array()
			: $this->Blog_GetInaccessibleBlogsByUser();
		$s=serialize($aCloseBlogs);

		if (false === ($data = $this->Cache_Get("comment_user_{$sId}_{$sTargetType}_{$iPage}_{$iPerPage}_{$s}"))) {
			$data = array('collection'=>$this->oMapper->GetCommentsByUserId($sId,$sTargetType,$iCount,$iPage,$iPerPage,array(),$aCloseBlogs),'count'=>$iCount);
			$this->Cache_Set($data, "comment_user_{$sId}_{$sTargetType}_{$iPage}_{$iPerPage}_{$s}", array("comment_new_user_{$sId}_{$sTargetType}","comment_update_status_{$sTargetType}"), 60*60*24*2);
		}
		$data['collection']=$this->GetCommentsAdditionalData($data['collection']);
		return $data;
	}
	/**
	 * Получает количество комментариев одного пользователя
	 *
	 * @param  id $sId ID пользователя
	 * @param  string $sTargetType	Тип владельца комментария
	 * @return int
	 */
	public function GetCountCommentsByUserId($sId,$sTargetType) {
		/**
		 * Исключаем из выборки идентификаторы закрытых блогов
		 */
		$aCloseBlogs = ($this->oUserCurrent && $sId==$this->oUserCurrent->getId())
			? array()
			: $this->Blog_GetInaccessibleBlogsByUser();
		$s=serialize($aCloseBlogs);

		if (false === ($data = $this->Cache_Get("comment_count_user_{$sId}_{$sTargetType}_{$s}"))) {
			$data = $this->oMapper->GetCountCommentsByUserId($sId,$sTargetType,array(),$aCloseBlogs);
			$this->Cache_Set($data, "comment_count_user_{$sId}_{$sTargetType}", array("comment_new_user_{$sId}_{$sTargetType}","comment_update_status_{$sTargetType}"), 60*60*24*2);
		}
		return $data;
	}
	/**
	 * Получить комменты по рейтингу и дате
	 *
	 * @param  string $sDate	Дата за которую выводить рейтинг, т.к. кеширование происходит по дате, то дату лучше передавать с точностью до часа (минуты и секунды как 00:00)
	 * @param  string $sTargetType	Тип владельца комментария
	 * @param  int    $iLimit	Количество элементов
	 * @return array
	 */
	public function GetCommentsRatingByDate($sDate,$sTargetType,$iLimit=20) {
		/**
		 * Выбираем топики, комметарии к которым являются недоступными для пользователя
		 */
		$aCloseBlogs = ($this->oUserCurrent)
			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
			: $this->Blog_GetInaccessibleBlogsByUser();
		$s=serialize($aCloseBlogs);
		/**
		 * Т.к. время передаётся с точностью 1 час то можно по нему замутить кеширование
		 */
		if (false === ($data = $this->Cache_Get("comment_rating_{$sDate}_{$sTargetType}_{$iLimit}_{$s}"))) {
			$data = $this->oMapper->GetCommentsRatingByDate($sDate,$sTargetType,$iLimit,array(),$aCloseBlogs);
			$this->Cache_Set($data, "comment_rating_{$sDate}_{$sTargetType}_{$iLimit}_{$s}", array("comment_new_{$sTargetType}","comment_update_status_{$sTargetType}","comment_update_rating_{$sTargetType}"), 60*60*24*2);
		}
		$data=$this->GetCommentsAdditionalData($data);
		return $data;
	}
	/**
	 * Получить комменты по владельцу
	 *
	 * @param  int $sId	ID владельца коммента
	 * @param  string $sTargetType	Тип владельца комментария
	 * @param  int $iPage	Номер страницы
	 * @param  int $iPerPage	Количество элементов на страницу
	 * @return array('comments'=>array,'iMaxIdComment'=>int)
	 */
	public function GetCommentsByTargetId($sId,$sTargetType,$iPage=1,$iPerPage=0) {
		if (Config::Get('module.comment.use_nested')) {
			return $this->GetCommentsTreeByTargetId($sId,$sTargetType,$iPage,$iPerPage);
		}

		if (false === ($aCommentsRec = $this->Cache_Get("comment_target_{$sId}_{$sTargetType}"))) {
			$aCommentsRow=$this->oMapper->GetCommentsByTargetId($sId,$sTargetType);
			if (count($aCommentsRow)) {
				$aCommentsRec=$this->BuildCommentsRecursive($aCommentsRow);
			}
			$this->Cache_Set($aCommentsRec, "comment_target_{$sId}_{$sTargetType}", array("comment_new_{$sTargetType}_{$sId}"), 60*60*24*2);
		}
		if (!isset($aCommentsRec['comments'])) {
			return array('comments'=>array(),'iMaxIdComment'=>0);
		}
		$aComments=$aCommentsRec;
		$aComments['comments']=$this->GetCommentsAdditionalData(array_keys($aCommentsRec['comments']));
		foreach ($aComments['comments'] as $oComment) {
			$oComment->setLevel($aCommentsRec['comments'][$oComment->getId()]);
		}
		return $aComments;

	}
	/**
	 * Получает комменты используя nested set
	 *
	 * @param int $sId	ID владельца коммента
	 * @param string $sTargetType	Тип владельца комментария
	 * @param  int $iPage	Номер страницы
	 * @param  int $iPerPage	Количество элементов на страницу
	 * @return array('comments'=>array,'iMaxIdComment'=>int,'count'=>int)
	 */
	public function GetCommentsTreeByTargetId($sId,$sTargetType,$iPage=1,$iPerPage=0) {
		if (!Config::Get('module.comment.nested_page_reverse') and $iPerPage and $iCountPage=ceil($this->GetCountCommentsRootByTargetId($sId,$sTargetType)/$iPerPage)) {
			$iPage=$iCountPage-$iPage+1;
		}
		$iPage=$iPage<1 ? 1 : $iPage;
		if (false === ($aReturn = $this->Cache_Get("comment_tree_target_{$sId}_{$sTargetType}_{$iPage}_{$iPerPage}"))) {

			/**
			 * Нужно или нет использовать постраничное разбиение комментариев
			 */
			if ($iPerPage) {
				$aComments=$this->oMapper->GetCommentsTreePageByTargetId($sId,$sTargetType,$iCount,$iPage,$iPerPage);
			} else {
				$aComments=$this->oMapper->GetCommentsTreeByTargetId($sId,$sTargetType);
				$iCount=count($aComments);
			}
			$iMaxIdComment=count($aComments) ? max($aComments) : 0;
			$aReturn=array('comments'=>$aComments,'iMaxIdComment'=>$iMaxIdComment,'count'=>$iCount);
			$this->Cache_Set($aReturn, "comment_tree_target_{$sId}_{$sTargetType}_{$iPage}_{$iPerPage}", array("comment_new_{$sTargetType}_{$sId}"), 60*60*24*2);
		}
		$aReturn['comments']=$this->GetCommentsAdditionalData($aReturn['comments']);
		return $aReturn;
	}
	/**
	 * Возвращает количество дочерних комментариев у корневого коммента
	 *
	 * @param int $sId	ID владельца коммента
	 * @param string $sTargetType	Тип владельца комментария
	 * @return int
	 */
	public function GetCountCommentsRootByTargetId($sId,$sTargetType) {
		return $this->oMapper->GetCountCommentsRootByTargetId($sId,$sTargetType);
	}
	/**
	 * Возвращает номер страницы, на которой расположен комментарий
	 *
	 * @param int $sId	ID владельца коммента
	 * @param string $sTargetType	Тип владельца комментария
	 * @param ModuleComment_EntityComment $oComment	Объект комментария
	 * @return bool|int
	 */
	public function GetPageCommentByTargetId($sId,$sTargetType,$oComment) {
		if (!Config::Get('module.comment.nested_per_page')) {
			return 1;
		}
		if (is_numeric($oComment)) {
			if (!($oComment=$this->GetCommentById($oComment))) {
				return false;
			}
			if ($oComment->getTargetId()!=$sId or $oComment->getTargetType()!=$sTargetType) {
				return false;
			}
		}
		/**
		 * Получаем корневого родителя
		 */
		if ($oComment->getPid()) {
			if (!($oCommentRoot=$this->oMapper->GetCommentRootByTargetIdAndChildren($sId,$sTargetType,$oComment->getLeft()))) {
				return false;
			}
		} else {
			$oCommentRoot=$oComment;
		}
		$iCount=ceil($this->oMapper->GetCountCommentsAfterByTargetId($sId,$sTargetType,$oCommentRoot->getLeft())/Config::Get('module.comment.nested_per_page'));

		if (!Config::Get('module.comment.nested_page_reverse') and $iCountPage=ceil($this->GetCountCommentsRootByTargetId($sId,$sTargetType)/Config::Get('module.comment.nested_per_page'))) {
			$iCount=$iCountPage-$iCount+1;
		}
		return $iCount ? $iCount : 1;
	}
	/**
	 * Добавляет коммент
	 *
	 * @param  ModuleComment_EntityComment $oComment	Объект комментария
	 * @return bool|ModuleComment_EntityComment
	 */
	public function AddComment(ModuleComment_EntityComment $oComment) {
		if (Config::Get('module.comment.use_nested')) {
			$sId=$this->oMapper->AddCommentTree($oComment);
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update"));
		} else {
			$sId=$this->oMapper->AddComment($oComment);
		}
		if ($sId) {
			if ($oComment->getTargetType()=='topic') {
				$this->Topic_increaseTopicCountComment($oComment->getTargetId());
			}
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_new_{$oComment->getTargetType()}","comment_new_user_{$oComment->getUserId()}_{$oComment->getTargetType()}","comment_new_{$oComment->getTargetType()}_{$oComment->getTargetId()}"));
			$oComment->setId($sId);
			return $oComment;
		}
		return false;
	}
	/**
	 * Обновляет коммент
	 *
	 * @param  ModuleComment_EntityComment $oComment	Объект комментария
	 * @return bool
	 */
	public function UpdateComment(ModuleComment_EntityComment $oComment) {
		if ($this->oMapper->UpdateComment($oComment)) {
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update","comment_update_{$oComment->getTargetType()}_{$oComment->getTargetId()}"));
			$this->Cache_Delete("comment_{$oComment->getId()}");
			return true;
		}
		return false;
	}
	/**
	 * Обновляет рейтинг у коммента
	 *
	 * @param  ModuleComment_EntityComment $oComment	Объект комментария
	 * @return bool
	 */
	public function UpdateCommentRating(ModuleComment_EntityComment $oComment) {
		if ($this->oMapper->UpdateComment($oComment)) {
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update_rating_{$oComment->getTargetType()}"));
			$this->Cache_Delete("comment_{$oComment->getId()}");
			return true;
		}
		return false;
	}
	/**
	 * Обновляет статус у коммента - delete или publish
	 *
	 * @param  ModuleComment_EntityComment $oComment	Объект комментария
	 * @return bool
	 */
	public function UpdateCommentStatus(ModuleComment_EntityComment $oComment) {
		if ($this->oMapper->UpdateComment($oComment)) {
			/**
			 * Если комментарий удаляется, удаляем его из прямого эфира
			 */
			if($oComment->getDelete()) $this->DeleteCommentOnlineByArrayId($oComment->getId(),$oComment->getTargetType());
			/**
			 * Обновляем избранное
			 */
			$this->Favourite_SetFavouriteTargetPublish($oComment->getId(),'comment',!$oComment->getDelete());
			/**
			 * Чистим зависимые кеши
			 */
			if(Config::Get('sys.cache.solid')){
				$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update"));
			}
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update_status_{$oComment->getTargetType()}"));
			$this->Cache_Delete("comment_{$oComment->getId()}");
			return true;
		}
		return false;
	}
	/**
	 * Устанавливает publish у коммента
	 *
	 * @param  int $sTargetId	ID владельца коммента
	 * @param  string $sTargetType	Тип владельца комментария
	 * @param  int    $iPublish	Статус отображать комментарии или нет
	 * @return bool
	 */
	public function SetCommentsPublish($sTargetId,$sTargetType,$iPublish) {
		if(!$aComments = $this->GetCommentsByTargetId($sTargetId,$sTargetType)) {
			return false;
		}
		if(!isset($aComments['comments']) or count($aComments)==0) {
			return;
		}

		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update_status_{$sTargetType}"));
		/**
		 * Если статус публикации успешно изменен, то меняем статус в отметке "избранное".
		 * Если комментарии снимаются с публикации, удаляем их из прямого эфира.
		 */
		if($this->oMapper->SetCommentsPublish($sTargetId,$sTargetType,$iPublish)){
			$this->Favourite_SetFavouriteTargetPublish(array_keys($aComments['comments']),'comment',$iPublish);
			if($iPublish!=1) $this->DeleteCommentOnlineByTargetId($sTargetId,$sTargetType);
			return true;
		}
		return false;
	}
	/**
	 * Удаляет коммент из прямого эфира
	 *
	 * @param  int $sTargetId	ID владельца коммента
	 * @param  string $sTargetType	Тип владельца комментария
	 * @return bool
	 */
	public function DeleteCommentOnlineByTargetId($sTargetId,$sTargetType) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_online_update_{$sTargetType}"));
		return $this->oMapper->DeleteCommentOnlineByTargetId($sTargetId,$sTargetType);
	}
	/**
	 * Добавляет новый коммент в прямой эфир
	 *
	 * @param ModuleComment_EntityCommentOnline $oCommentOnline	Объект онлайн комментария
	 * @return bool|int
	 */
	public function AddCommentOnline(ModuleComment_EntityCommentOnline $oCommentOnline) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_online_update_{$oCommentOnline->getTargetType()}"));
		return $this->oMapper->AddCommentOnline($oCommentOnline);
	}
	/**
	 * Получить новые комменты для владельца
	 *
	 * @param int $sId	ID владельца коммента
	 * @param string $sTargetType	Тип владельца комментария
	 * @param int $sIdCommentLast ID последнего прочитанного комментария
	 * @return array('comments'=>array,'iMaxIdComment'=>int)
	 */
	public function GetCommentsNewByTargetId($sId,$sTargetType,$sIdCommentLast) {
		if (false === ($aComments = $this->Cache_Get("comment_target_{$sId}_{$sTargetType}_{$sIdCommentLast}"))) {
			$aComments=$this->oMapper->GetCommentsNewByTargetId($sId,$sTargetType,$sIdCommentLast);
			$this->Cache_Set($aComments, "comment_target_{$sId}_{$sTargetType}_{$sIdCommentLast}", array("comment_new_{$sTargetType}_{$sId}"), 60*60*24*1);
		}
		if (count($aComments)==0) {
			return array('comments'=>array(),'iMaxIdComment'=>0);
		}

		$iMaxIdComment=max($aComments);
		$aCmts=$this->GetCommentsAdditionalData($aComments);
		$oViewerLocal=$this->Viewer_GetLocalViewer();
		$oViewerLocal->Assign('oUserCurrent',$this->User_GetUserCurrent());
		$oViewerLocal->Assign('bOneComment',true);
		if($sTargetType!='topic') {
			$oViewerLocal->Assign('bNoCommentFavourites',true);
		}
		$aCmt=array();
		foreach ($aCmts as $oComment) {
			$oViewerLocal->Assign('oComment',$oComment);
			$sText=$oViewerLocal->Fetch($this->GetTemplateCommentByTarget($sId,$sTargetType));
			$aCmt[]=array(
				'html' => $sText,
				'obj'  => $oComment,
			);
		}
		return array('comments'=>$aCmt,'iMaxIdComment'=>$iMaxIdComment);
	}
	/**
	 * Возвращает шаблон комментария для рендеринга
	 * Плагин может переопределить данный метод и вернуть свой шаблон в зависимости от типа
	 *
	 * @param int $iTargetId	ID объекта комментирования
	 * @param string $sTargetType	Типа объекта комментирования
	 * @return string
	 */
	public function GetTemplateCommentByTarget($iTargetId,$sTargetType) {
		return "comment.tpl";
	}
	/**
	 * Строит дерево комментариев
	 *
	 * @param array $aComments	Список комментариев
	 * @param bool $bBegin	Флаг начала построения дерева, для инициализации параметров внутри метода
	 * @return array('comments'=>array,'iMaxIdComment'=>int)
	 */
	protected function BuildCommentsRecursive($aComments,$bBegin=true) {
		static $aResultCommnets;
		static $iLevel;
		static $iMaxIdComment;
		if ($bBegin) {
			$aResultCommnets=array();
			$iLevel=0;
			$iMaxIdComment=0;
		}
		foreach ($aComments as $aComment) {
			$aTemp=$aComment;
			if ($aComment['comment_id']>$iMaxIdComment) {
				$iMaxIdComment=$aComment['comment_id'];
			}
			$aTemp['level']=$iLevel;
			unset($aTemp['childNodes']);
			$aResultCommnets[$aTemp['comment_id']]=$aTemp['level'];
			if (isset($aComment['childNodes']) and count($aComment['childNodes'])>0) {
				$iLevel++;
				$this->BuildCommentsRecursive($aComment['childNodes'],false);
			}
		}
		$iLevel--;
		return array('comments'=>$aResultCommnets,'iMaxIdComment'=>$iMaxIdComment);
	}
	/**
	 * Получает привязку комментария к ибранному(добавлен ли коммент в избранное у юзера)
	 *
	 * @param  int $sCommentId	ID комментария
	 * @param  int $sUserId	ID пользователя
	 * @return ModuleFavourite_EntityFavourite|null
	 */
	public function GetFavouriteComment($sCommentId,$sUserId) {
		return $this->Favourite_GetFavourite($sCommentId,'comment',$sUserId);
	}
	/**
	 * Получить список избранного по списку айдишников
	 *
	 * @param array $aCommentId	Список ID комментов
	 * @param int $sUserId	ID пользователя
	 * @return array
	 */
	public function GetFavouriteCommentsByArray($aCommentId,$sUserId) {
		return $this->Favourite_GetFavouritesByArray($aCommentId,'comment',$sUserId);
	}
	/**
	 * Получить список избранного по списку айдишников, но используя единый кеш
	 *
	 * @param array  $aCommentId	Список ID комментов
	 * @param int    $sUserId	ID пользователя
	 * @return array
	 */
	public function GetFavouriteCommentsByArraySolid($aCommentId,$sUserId) {
		return $this->Favourite_GetFavouritesByArraySolid($aCommentId,'comment',$sUserId);
	}
	/**
	 * Получает список комментариев из избранного пользователя
	 *
	 * @param  int $sUserId	ID пользователя
	 * @param  int    $iCurrPage	Номер страницы
	 * @param  int    $iPerPage	Количество элементов на страницу
	 * @return array
	 */
	public function GetCommentsFavouriteByUserId($sUserId,$iCurrPage,$iPerPage) {
		$aCloseTopics = array();
		/**
		 * Получаем список идентификаторов избранных комментов
		 */
		$data = ($this->oUserCurrent && $sUserId==$this->oUserCurrent->getId())
			? $this->Favourite_GetFavouritesByUserId($sUserId,'comment',$iCurrPage,$iPerPage,$aCloseTopics)
			: $this->Favourite_GetFavouriteOpenCommentsByUserId($sUserId,$iCurrPage,$iPerPage);
		/**
		 * Получаем комменты по переданому массиву айдишников
		 */
		$data['collection']=$this->GetCommentsAdditionalData($data['collection']);
		return $data;
	}
	/**
	 * Возвращает число комментариев в избранном
	 *
	 * @param  int $sUserId	ID пользователя
	 * @return int
	 */
	public function GetCountCommentsFavouriteByUserId($sUserId) {
		return ($this->oUserCurrent && $sUserId==$this->oUserCurrent->getId())
			? $this->Favourite_GetCountFavouritesByUserId($sUserId,'comment')
			: $this->Favourite_GetCountFavouriteOpenCommentsByUserId($sUserId);
	}
	/**
	 * Добавляет комментарий в избранное
	 *
	 * @param  ModuleFavourite_EntityFavourite $oFavourite	Объект избранного
	 * @return bool|ModuleFavourite_EntityFavourite
	 */
	public function AddFavouriteComment(ModuleFavourite_EntityFavourite $oFavourite) {
		if( ($oFavourite->getTargetType()=='comment')
			&& ($oComment=$this->Comment_GetCommentById($oFavourite->getTargetId()))
			&& in_array($oComment->getTargetType(),Config::get('module.comment.favourite_target_allow'))) {
			return $this->Favourite_AddFavourite($oFavourite);
		}
		return false;
	}
	/**
	 * Удаляет комментарий из избранного
	 *
	 * @param  ModuleFavourite_EntityFavourite $oFavourite	Объект избранного
	 * @return bool
	 */
	public function DeleteFavouriteComment(ModuleFavourite_EntityFavourite $oFavourite) {
		if( ($oFavourite->getTargetType()=='comment')
			&& ($oComment=$this->Comment_GetCommentById($oFavourite->getTargetId()))
			&& in_array($oComment->getTargetType(),Config::get('module.comment.favourite_target_allow'))) {
			return $this->Favourite_DeleteFavourite($oFavourite);
		}
		return false;
	}
	/**
	 * Удаляет комментарии из избранного по списку
	 *
	 * @param  array $aCommentId	Список ID комментариев
	 * @return bool
	 */
	public function DeleteFavouriteCommentsByArrayId($aCommentId) {
		return $this->Favourite_DeleteFavouriteByTargetId($aCommentId, 'comment');
	}
	/**
	 * Удаляет комментарии из базы данных
	 *
	 * @param   array|int $aTargetId	Список ID владельцев
	 * @param   string $sTargetType	Тип владельцев
	 * @return  bool
	 */
	public function DeleteCommentByTargetId($aTargetId,$sTargetType) {
		if(!is_array($aTargetId)) $aTargetId = array($aTargetId);
		/**
		 * Получаем список идентификаторов удаляемых комментариев
		 */
		$aCommentsId = array();
		foreach ($aTargetId as $sTargetId) {
			$aComments=$this->GetCommentsByTargetId($sTargetId,$sTargetType);
			$aCommentsId = array_merge($aCommentsId, array_keys($aComments['comments']));
		}
		/**
		 * Если ни одного комментария не найдено, выходим
		 */
		if(!count($aCommentsId)) return true;
		/**
		 * Чистим зависимые кеши
		 */
		if(Config::Get('sys.cache.solid')) {
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array("comment_update","comment_target_{$sTargetId}_{$sTargetType}"));
		} else {
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array("comment_target_{$sTargetId}_{$sTargetType}"));
			/**
			 * Удаляем кеш для каждого комментария
			 */
			foreach($aCommentsId as $iCommentId) $this->Cache_Delete("comment_{$iCommentId}");
		}
		if($this->oMapper->DeleteCommentByTargetId($aTargetId,$sTargetType)){
			/**
			 * Удаляем комментарии из избранного
			 */
			$this->DeleteFavouriteCommentsByArrayId($aCommentsId);
			/**
			 * Удаляем комментарии к топику из прямого эфира
			 */
			$this->DeleteCommentOnlineByArrayId($aCommentsId,$sTargetType);
			/**
			 * Удаляем голосование за комментарии
			 */
			$this->Vote_DeleteVoteByTarget($aCommentsId,'comment');
			return true;
		}
		return false;
	}
	/**
	 * Удаляет коммент из прямого эфира по массиву переданных идентификаторов
	 *
	 * @param  array|int $aCommentId
	 * @param  string      $sTargetType	Тип владельцев
	 * @return bool
	 */
	public function DeleteCommentOnlineByArrayId($aCommentId,$sTargetType) {
		if(!is_array($aCommentId)) $aCommentId = array($aCommentId);
		/**
		 * Чистим кеш
		 */
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_online_update_{$sTargetType}"));
		return $this->oMapper->DeleteCommentOnlineByArrayId($aCommentId,$sTargetType);
	}
	/**
	 * Меняем target parent по массиву идентификаторов
	 *
	 * @param  int $sParentId	Новый ID родителя владельца
	 * @param  string $sTargetType	Тип владельца
	 * @param  array|int $aTargetId	Список ID владельцев
	 * @return bool
	 */
	public function UpdateTargetParentByTargetId($sParentId, $sTargetType, $aTargetId) {
		if(!is_array($aTargetId)) $aTargetId = array($aTargetId);
		// чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_new_{$sTargetType}"));

		return $this->oMapper->UpdateTargetParentByTargetId($sParentId, $sTargetType, $aTargetId);
	}
	/**
	 * Меняем target parent по массиву идентификаторов в таблице комментариев online
	 *
	 * @param  int $sParentId	Новый ID родителя владельца
	 * @param  string $sTargetType	Тип владельца
	 * @param  array|int $aTargetId	Список ID владельцев
	 * @return bool
	 */
	public function UpdateTargetParentByTargetIdOnline($sParentId, $sTargetType, $aTargetId) {
		if(!is_array($aTargetId)) $aTargetId = array($aTargetId);
		// чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_online_update_{$sTargetType}"));

		return $this->oMapper->UpdateTargetParentByTargetIdOnline($sParentId, $sTargetType, $aTargetId);
	}
	/**
	 * Меняет target parent на новый
	 *
	 * @param int $sParentId	Прежний ID родителя владельца
	 * @param string $sTargetType	Тип владельца
	 * @param int $sParentIdNew	Новый ID родителя владельца
	 * @return bool
	 */
	public function MoveTargetParent($sParentId, $sTargetType, $sParentIdNew) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_new_{$sTargetType}"));
		return $this->oMapper->MoveTargetParent($sParentId, $sTargetType, $sParentIdNew);
	}
	/**
	 * Меняет target parent на новый в прямом эфире
	 *
	 * @param int $sParentId	Прежний ID родителя владельца
	 * @param string $sTargetType	Тип владельца
	 * @param int $sParentIdNew	Новый ID родителя владельца
	 * @return bool
	 */
	public function MoveTargetParentOnline($sParentId, $sTargetType, $sParentIdNew) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_online_update_{$sTargetType}"));
		return $this->oMapper->MoveTargetParentOnline($sParentId, $sTargetType, $sParentIdNew);
	}
	/**
	 * Перестраивает дерево комментариев
	 * Восстанавливает значения left, right и level
	 *
	 * @param int $aTargetId	Список ID владельцев
	 * @param string $sTargetType	Тип владельца
	 */
	public function RestoreTree($aTargetId=null,$sTargetType=null) {
		// обработать конкретную сущность
		if (!is_null($aTargetId) and !is_null($sTargetType)) {
			$this->oMapper->RestoreTree(null,0,-1,$aTargetId,$sTargetType);
			return ;
		}
		$aType=array();
		// обработать все сущности конкретного типа
		if (!is_null($sTargetType)) {
			$aType[]=$sTargetType;
		} else {
			// обработать все сущности всех типов
			$aType=$this->oMapper->GetCommentTypes();
		}
		foreach ($aType as $sTargetType) {
			// для каждого типа получаем порциями ID сущностей
			$iPage=1;
			$iPerPage=50;
			while ($aResult=$this->oMapper->GetTargetIdByType($sTargetType,$iPage,$iPerPage)) {
				foreach ($aResult as $Row) {
					$this->oMapper->RestoreTree(null,0,-1,$Row['target_id'],$sTargetType);
				}
				$iPage++;
			}
		}
	}
	/**
	 * Пересчитывает счетчик избранных комментариев
	 *
	 * @return bool
	 */
	public function RecalculateFavourite() {
		return $this->oMapper->RecalculateFavourite();
	}
}
?>