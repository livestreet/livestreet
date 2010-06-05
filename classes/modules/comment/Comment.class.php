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
 */
class ModuleComment extends Module {
	protected $oMapper;	
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
	 * @param unknown_type $sId
	 * @return unknown
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
	 * @param unknown_type $sTargetId
	 * @param unknown_type $sTargetType
	 * @param unknown_type $sUserId
	 * @param unknown_type $sCommentPid
	 * @param unknown_type $sHash
	 */
	public function GetCommentUnique($sTargetId,$sTargetType,$sUserId,$sCommentPid,$sHash) {
		$sId=$this->oMapper->GetCommentUnique($sTargetId,$sTargetType,$sUserId,$sCommentPid,$sHash);
		return $this->GetCommentById($sId);
	}
	/**
	 * Получить все комменты
	 *
	 * @param unknown_type $sTargetType
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
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
	 */
	public function GetCommentsAdditionalData($aCommentId,$aAllowData=array('vote','target','favourite','user'=>array())) {
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
	 * @param array $aUserId
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
			 * проверяем что досталось из кеша
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
	 * Получть все комменты сгрупированные по топику(для вывода прямого эфира)
	 *
	 * @param unknown_type $sTargetType
	 * @param unknown_type $iLimit
	 * @return unknown
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
	 * @param  string $sId
	 * @param  string $sTargetType
	 * @param  int    $iPage
	 * @param  int    $iPerPage
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
	 * @param  string $sId
	 * @param  string $sTargetType
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
	 * @param  string $sDate
	 * @param  string $sTargetType
	 * @param  int    $iLimit
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
		
		//т.к. время передаётся с точностью 1 час то можно по нему замутить кеширование
		if (false === ($data = $this->Cache_Get("comment_rating_{$sDate}_{$sTargetType}_{$iLimit}_{$s}"))) {			
			$data = $this->oMapper->GetCommentsRatingByDate($sDate,$sTargetType,$iLimit,array(),$aCloseBlogs);
			$this->Cache_Set($data, "comment_rating_{$sDate}_{$sTargetType}_{$iLimit}_{$s}", array("comment_new_{$sTargetType}","comment_update_status_{$sTargetType}","comment_update_rating_{$sTargetType}"), 60*60*24*2);
		}
		$data=$this->GetCommentsAdditionalData($data);	
		return $data;
	}
	/**
	 * Получить комменты для топика
	 *
	 * @param  string $sId
	 * @param  string $sTargetType
	 * @return object
	 */
	public function GetCommentsByTargetId($sId,$sTargetType) {				
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
	 * Добавляет коммент
	 *
	 * @param  ModuleComment_EntityComment $oComment
	 * @return bool
	 */
	public function AddComment(ModuleComment_EntityComment $oComment) {
		if ($sId=$this->oMapper->AddComment($oComment)) {
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
	 * @param  ModuleComment_EntityComment $oComment
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
	 * @param  ModuleComment_EntityComment $oComment
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
	 * @param  ModuleComment_EntityComment $oComment
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
	 * @param  string $sTargetId
	 * @param  string $sTargetType
	 * @param  int    $iPublish
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
	 * @param unknown_type $sTargetId
	 * @param unknown_type $sTargetType
	 * @return unknown
	 */
	public function DeleteCommentOnlineByTargetId($sTargetId,$sTargetType) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_online_update_{$sTargetType}"));
		return $this->oMapper->DeleteCommentOnlineByTargetId($sTargetId,$sTargetType);
	}
	/**
	 * Добавляет новый коммент в прямой эфир
	 *
	 * @param ModuleComment_EntityCommentOnline $oCommentOnline
	 */
	public function AddCommentOnline(ModuleComment_EntityCommentOnline $oCommentOnline) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_online_update_{$oCommentOnline->getTargetType()}"));
		return $this->oMapper->AddCommentOnline($oCommentOnline);
	}
	
	/**
	 * Получить новые комменты для топика
	 *
	 * @param unknown_type $sId
	 * @param unknown_type $sTargetType
	 * @param unknown_type $sIdCommentLast - last read comment
	 * @return unknown
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
		if (!class_exists('ModuleViewer')) {
			require_once(Config::Get('path.root.engine')."/modules/viewer/Viewer.class.php");
		}
		$oViewerLocal=$this->Viewer_GetLocalViewer();
		$oViewerLocal->Assign('oUserCurrent',$this->User_GetUserCurrent());
		$oViewerLocal->Assign('bOneComment',true);
		if($sTargetType!='topic') {
			$oViewerLocal->Assign('bNoCommentFavourites',true);
		}
		$aCmt=array();
		foreach ($aCmts as $oComment) {			
			$oViewerLocal->Assign('oComment',$oComment);						
			$sText=$oViewerLocal->Fetch("comment.tpl");
			$aCmt[]=array(
				'html' => $sText,
				'obj'  => $oComment,
			);			
		}
			
		return array('comments'=>$aCmt,'iMaxIdComment'=>$iMaxIdComment);		
	}
	
	
	
	/**
	 * Строит дерево комментариев
	 *
	 * @param unknown_type $aComments
	 * @param unknown_type $bBegin
	 * @return unknown
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
	 * @param  string $sCommentId
	 * @param  string $sUserId
	 * @return ModuleFavourite_EntityFavourite|null
	 */
	public function GetFavouriteComment($sCommentId,$sUserId) {
		return $this->Favourite_GetFavourite($sCommentId,'comment',$sUserId);
	}
	
	/**
	 * Получить список избранного по списку айдишников
	 *
	 * @param array $aCommentId
	 */
	public function GetFavouriteCommentsByArray($aCommentId,$sUserId) {
		return $this->Favourite_GetFavouritesByArray($aCommentId,'comment',$sUserId);
	}

	/**
	 * Получить список избранного по списку айдишников, но используя единый кеш
	 *
	 * @param array  $aCommentId
	 * @param int    $sUserId
	 * @return array
	 */
	public function GetFavouriteCommentsByArraySolid($aCommentId,$sUserId) {
		return $this->Favourite_GetFavouritesByArraySolid($aCommentId,'comment',$sUserId);
	}

	/**
	 * Получает список комментариев из избранного пользователя
	 *
	 * @param  string $sUserId
	 * @param  int    $iCount
	 * @param  int    $iCurrPage
	 * @param  int    $iPerPage
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
	 * @param  string $sUserId
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
	 * @param  ModuleFavourite_EntityFavourite $oFavourite
	 * @return bool
	 */
	public function AddFavouriteComment(ModuleFavourite_EntityFavourite $oFavourite) {	
		if( ($oFavourite->getTargetType()=='comment') 
				&& ($oComment=$this->Comment_GetCommentById($oFavourite->getTargetId())) 
					&& $oComment->getTargetType()=='topic') {
						return $this->Favourite_AddFavourite($oFavourite);
					}
		return false;
	}
	/**
	 * Удаляет комментарий из избранного
	 *
	 * @param  ModuleFavourite_EntityFavourite $oFavourite
	 * @return bool
	 */
	public function DeleteFavouriteComment(ModuleFavourite_EntityFavourite $oFavourite) {
		if( ($oFavourite->getTargetType()=='comment') 
				&& ($oComment=$this->Comment_GetCommentById($oFavourite->getTargetId())) 
					&& $oComment->getTargetType()=='topic') {
						return $this->Favourite_DeleteFavourite($oFavourite);
		}
		return false;
	}
	/**
	 * Удаляет комментарии из избранного по списку 
	 *
	 * @param  array $aCommentId
	 * @return bool
	 */	
	public function DeleteFavouriteCommentsByArrayId($aCommentId) {
		return $this->Favourite_DeleteFavouriteByTargetId($aCommentId, 'comment');	
	}
	/**
	 * Удаляет комментарии из базы данных
	 * 
	 * @param   array|int $aTargetId
	 * @param   string $sTargetType
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
	 * @param  (array|int) $aCommentId
	 * @param  string      $sTargetType
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
	 * @param  string $sParentId
	 * @param  string $sTargetType
	 * @param  array|string $aTargetId
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
	 * @param  string $sParentId
	 * @param  string $sTargetType
	 * @param  array|string $aTargetId
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
	 * @param string $sParentId
	 * @param string $sTargetType
	 * @param string $sParentIdNew
	 * @return bool
	 */
	public function MoveTargetParent($sParentId, $sTargetType, $sParentIdNew) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_new_{$sTargetType}"));
		return $this->oMapper->MoveTargetParent($sParentId, $sTargetType, $sParentIdNew);
	}
	
	/**
	 * Меняет target parent на новый в прямом эфире
	 *
	 * @param string $sParentId
	 * @param string $sTargetType
	 * @param string $sParentIdNew
	 * @return bool
	 */
	public function MoveTargetParentOnline($sParentId, $sTargetType, $sParentIdNew) {
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_online_update_{$sTargetType}"));
		return $this->oMapper->MoveTargetParentOnline($sParentId, $sTargetType, $sParentIdNew);
	}
	
}
?>