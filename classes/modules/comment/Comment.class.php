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
require_once('mapper/Comment.mapper.class.php');

/**
 * Модуль для работы с комментариями
 *
 */
class LsComment extends Module {		
	protected $oMapper;	
	protected $oUserCurrent=null;
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {			
		$this->oMapper=new Mapper_Comment($this->Database_GetConnect());
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
	public function GetCommentsAll($sTargetType,$iPage,$iPerPage) {		
		if (false === ($data = $this->Cache_Get("comment_all_{$sTargetType}_{$iPage}_{$iPerPage}"))) {			
			$data = array('collection'=>$this->oMapper->GetCommentsAll($sTargetType,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "comment_all_{$sTargetType}_{$iPage}_{$iPerPage}", array("comment_new_{$sTargetType}","comment_update_status_{$sTargetType}"), 60*60*24*1);
		}
		$data['collection']=$this->GetCommentsAdditionalData($data['collection']);
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
			if (isset($aAllowData['favourite']) and $this->oUserCurrent) {
				$aFavouriteComments=$this->Favourite_GetFavouritesByArray($aCommentId,'comment',$this->oUserCurrent->getId());	
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
		/**
		 * Добавляем данные к результату
		 */
		foreach ($aComments as $oComment) {
			if (isset($aUsers[$oComment->getUserId()])) {
				$oComment->setUser($aUsers[$oComment->getUserId()]);
			} else {
				$oComment->setUser(null); // или $oComment->setUser(new UserEntity_User());
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
		if (1) {
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
		 * Если получаем комментарии не текущего пользователя, 
		 * то получаем exlude массив идентификаторов топиков, 
		 * которые нужно исключить из выдачи
		 */
		$aCloseTopics = ($this->oUserCurrent)
			? (array)$this->Topic_GetTopicsCloseByUser($this->oUserCurrent->getId())
			: (array)$this->Topic_GetTopicsCloseByUser();
			
		$s=serialize($aCloseTopics);
		
		if (false === ($data = $this->Cache_Get("comment_online_{$sTargetType}_{$s}_{$iLimit}"))) {			
			$data = $this->oMapper->GetCommentsOnline($sTargetType,$aCloseTopics,$iLimit);
			$this->Cache_Set($data, "comment_online_{$sTargetType}_{$s}_{$iLimit}", array("comment_online_update_{$sTargetType}"), 60*60*24*1);
		}
		$data=$this->GetCommentsAdditionalData($data);
		return $data;		
	}
	/**
	 * Получить комменты по юзеру
	 *
	 * @param unknown_type $sId
	 * @param unknown_type $sTargetType
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetCommentsByUserId($sId,$sTargetType,$iPage,$iPerPage) {	
		/**
		 * Если получаем комментарии не текущего пользователя, 
		 * то получаем exlude массив идентификаторов топиков, 
		 * которые нужно исключить из выдачи
		 */
		$aCloseTopics = ($this->oUserCurrent && $sId==$this->oUserCurrent->getId()) 
			? array()			
			: (array)$this->Topic_GetTopicsCloseByUser();
		
		if (false === ($data = $this->Cache_Get("comment_user_{$sId}_{$sTargetType}_{$iPage}_{$iPerPage}"))) {			
			$data = array('collection'=>$this->oMapper->GetCommentsByUserId($sId,$sTargetType,$aCloseTopics,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "comment_user_{$sId}_{$sTargetType}_{$iPage}_{$iPerPage}", array("comment_new_user_{$sId}","comment_update_status_{$sTargetType}"), 60*60*24*2);
		}
		$data['collection']=$this->GetCommentsAdditionalData($data['collection']);
		return $data;				
	}
	
	public function GetCountCommentsByUserId($sId,$sTargetType) {
		/**
		 * Если получаем комментарии не текущего пользователя, 
		 * то получаем exlude массив идентификаторов топиков, 
		 * которые нужно исключить из выдачи
		 */
		$aCloseTopics = ($this->oUserCurrent && $sId==$this->oUserCurrent->getId()) 
			? array()
			: (array)$this->Topic_GetTopicsCloseByUser();
				
		if (false === ($data = $this->Cache_Get("comment_count_user_{$sId}_{$sTargetType}"))) {			
			$data = $this->oMapper->GetCountCommentsByUserId($sId,$sTargetType,$aCloseTopics);
			$this->Cache_Set($data, "comment_count_user_{$sId}_{$sTargetType}", array("comment_new_user_{$sId}","comment_update_status_{$sTargetType}"), 60*60*24*2);
		}
		return $data;		
	}
	/**
	 * Получить комменты по рейтингу и дате
	 *
	 * @param unknown_type $sDate
	 * @param unknown_type $sTargetType
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetCommentsRatingByDate($sDate,$sTargetType,$iLimit=20) {
		/**
		 * Выбираем топики, комметарии к которым являются недоступными для пользователя
		 */
		$aCloseTopics = ($this->oUserCurrent) 
			? $this->Topic_GetTopicsCloseByUser($this->oUserCurrent->getId())
			: $this->Topic_GetTopicsCloseByUser();
		$s=serialize($aCloseTopics);
		
		//т.к. время передаётся с точностью 1 час то можно по нему замутить кеширование
		if (false === ($data = $this->Cache_Get("comment_rating_{$sDate}_{$sTargetType}_{$iLimit}_{$s}"))) {			
			$data = $this->oMapper->GetCommentsRatingByDate($sDate,$sTargetType,$iLimit,$aCloseTopics);
			$this->Cache_Set($data, "comment_rating_{$sDate}_{$sTargetType}_{$iLimit}_{$s}", array("comment_new_{$sTargetType}","comment_update_status_{$sTargetType}","comment_update_rating_{$sTargetType}"), 60*60*24*2);
		}
		$data=$this->GetCommentsAdditionalData($data);	
		return $data;		
	}
	/**
	 * Получить комменты для топика
	 *
	 * @param unknown_type $sId
	 * @param unknown_type $sTargetType
	 * @return unknown
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
	 * @param CommentEntity_Comment $oComment
	 * @return unknown
	 */
	public function AddComment(CommentEntity_Comment $oComment) {
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
	 * @param CommentEntity_Comment $oComment
	 * @return unknown
	 */
	public function UpdateComment(CommentEntity_Comment $oComment) {		
		if ($this->oMapper->UpdateComment($oComment)) {		
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update","comment_update_{$oComment->getId()}","comment_update_{$oComment->getTargetType()}","comment_update_{$oComment->getTargetType()}_{$oComment->getTargetId()}"));				
			$this->Cache_Delete("comment_{$oComment->getId()}");
			return true;
		}
		return false;
	}
	/**
	 * Обновляет рейтинг у коммента
	 *
	 * @param CommentEntity_Comment $oComment
	 * @return unknown
	 */
	public function UpdateCommentRating(CommentEntity_Comment $oComment) {		
		if ($this->oMapper->UpdateComment($oComment)) {		
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update_{$oComment->getId()}","comment_update_rating_{$oComment->getTargetType()}"));
			$this->Cache_Delete("comment_{$oComment->getId()}");			
			return true;
		}
		return false;
	}
	/**
	 * Обновляет статус у коммента - delete или publish
	 *
	 * @param CommentEntity_Comment $oComment
	 * @return unknown
	 */
	public function UpdateCommentStatus(CommentEntity_Comment $oComment) {		
		if ($this->oMapper->UpdateComment($oComment)) {		
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update")); // временно, т.к. нужно использовать только при solid кеше			
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update_{$oComment->getId()}","comment_update_status_{$oComment->getTargetType()}"));
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
		if(!$oComment = $this->GetCommentsByTargetId($sTargetId,$sTargetType)) {
			return false;
		}
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("comment_update_status_{$sTargetType}"));		
		// Если статус публикации успешно изменен, то меняем статус в отметке "избранное"
		return ($this->oMapper->SetCommentsPublish($sTargetId,$sTargetType,$iPublish))
				? $this->Favourite_SetFavouriteTargetPublish($oComment->getId(),'comment',$iPublish)
				: false;
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
	 * @param CommentEntity_CommentOnline $oCommentOnline
	 */
	public function AddCommentOnline(CommentEntity_CommentOnline $oCommentOnline) {
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
		if (!class_exists('LsViewer')) {
			require_once(Config::Get('path.root.engine')."/modules/viewer/Viewer.class.php");
		}
		$oViewerLocal=new LsViewer(Engine::getInstance());
		$oViewerLocal->Init();
		$oViewerLocal->VarAssign();
		$oViewerLocal->Assign('aLang',$this->Lang_GetLangMsg());
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
	 * @return FavouriteEntity_Favourite|null
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
			? $this->Favourite_GetFavouritesByUserId($sUserId,'comment',$aCloseTopics,$iCurrPage,$iPerPage)
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
	 * @param  FavouriteEntity_Favourite $oFavourite
	 * @return bool
	 */
	public function AddFavouriteComment(FavouriteEntity_Favourite $oFavourite) {	
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
	 * @param  FavouriteEntity_Favourite $oFavourite
	 * @return bool
	 */
	public function DeleteFavouriteComment(FavouriteEntity_Favourite $oFavourite) {
		if( ($oFavourite->getTargetType()=='comment') 
				&& ($oComment=$this->Comment_GetCommentById($oFavourite->getTargetId())) 
					&& $oComment->getTargetType()=='topic') {
						return $this->Favourite_DeleteFavourite($oFavourite);
		}
		return false;
	}	
}
?>