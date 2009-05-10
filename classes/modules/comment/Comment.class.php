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
require_once('mapper/TopicComment.mapper.class.php');

/**
 * Модуль для работы с комментариями
 *
 */
class LsComment extends Module {		
	protected $oMapperTopicComment;	
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {			
		$this->oMapperTopicComment=new Mapper_TopicComment($this->Database_GetConnect());		
	}
	/**
	 * Получить коммент по айдишнику
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function GetCommentById($sId) {
		return $this->oMapperTopicComment->GetCommentById($sId);
	}	
	/**
	 * Получает уникальный коммент, это помогает спастись от дублей комментов
	 *
	 * @param unknown_type $sTopicId
	 * @param unknown_type $sUserId
	 * @param unknown_type $sCommentPid
	 * @param unknown_type $sHash
	 */
	public function GetCommentUnique($sTopicId,$sUserId,$sCommentPid,$sHash) {
		return $this->oMapperTopicComment->GetCommentUnique($sTopicId,$sUserId,$sCommentPid,$sHash);
	}
	/**
	 * Получить все комменты
	 *
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetCommentsAll($iCount,$iPage,$iPerPage) {		
		if (false === ($data = $this->Cache_Get("comment_all_{$iPage}_{$iPerPage}"))) {			
			$data = array('collection'=>$this->oMapperTopicComment->GetCommentsAll($iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "comment_all_{$iPage}_{$iPerPage}", array('comment_new','topic_update'), 60*5);
		}
		return $data;		 	
	}	
	/**
	 * Получисть список комментов по списку айдишников
	 *
	 * @param unknown_type $aArrayId
	 * @return unknown
	 */
	public function GetCommentsByArrayId($aArrayId) {
		$sIds=serialize($aArrayId);
		if (false === ($data = $this->Cache_Get("comment_list_{$sIds}"))) {			
			$data = $this->oMapperTopicComment->GetCommentsByArrayId($aArrayId);
			$this->Cache_Set($data, "comment_list_{$sIds}", array("comment_update",'topic_update'), 60*5);
		}
		return $data;
	}
	/**
	 * Получть все комменты сгрупированные по топику(для вывода прямого эфира)
	 *
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetCommentsAllGroup($iLimit) {
		if (false === ($data = $this->Cache_Get('comment_block_'.$iLimit))) {			
			$data = $this->oMapperTopicComment->GetCommentsAllGroup($iLimit);
			$this->Cache_Set($data, 'comment_block_'.$iLimit, array('comment_new','topic_update'), 60*5);
		}
		return $data;		
	}
	/**
	 * Получить комменты по юзеру
	 *
	 * @param unknown_type $sId
	 * @param unknown_type $iCount
	 * @param unknown_type $iPage
	 * @param unknown_type $iPerPage
	 * @return unknown
	 */
	public function GetCommentsByUserId($sId,$iCount,$iPage,$iPerPage) {	
		if (false === ($data = $this->Cache_Get("comment_user_{$sId}_{$iPage}_{$iPerPage}"))) {			
			$data = array('collection'=>$this->oMapperTopicComment->GetCommentsByUserId($sId,$iCount,$iPage,$iPerPage),'count'=>$iCount);
			$this->Cache_Set($data, "comment_user_{$sId}_{$iPage}_{$iPerPage}", array("comment_new_user_{$sId}",'topic_update'), 60*5);
		}
		return $data;				
	}
	
	public function GetCountCommentsByUserId($sId) {
		if (false === ($data = $this->Cache_Get("comment_count_user_{$sId}"))) {			
			$data = $this->oMapperTopicComment->GetCountCommentsByUserId($sId);
			$this->Cache_Set($data, "comment_count_user_{$sId}", array("comment_new_user_{$sId}",'topic_update'), 60*5);
		}
		return $data;		
	}
	/**
	 * Получить комменты по рейтингу и дате
	 *
	 * @param unknown_type $sDate
	 * @param unknown_type $iLimit
	 * @return unknown
	 */
	public function GetCommentsRatingByDate($sDate,$iLimit=20) {
		//т.к. время передаётся с точностью 1 час то можно по нему замутить кеширование
		if (false === ($data = $this->Cache_Get("comment_rating_{$sDate}_{$iLimit}"))) {			
			$data = $this->oMapperTopicComment->GetCommentsRatingByDate($sDate,$iLimit);
			$this->Cache_Set($data, "comment_rating_{$sDate}_{$iLimit}", array('comment_update','topic_update'), 60*5);
		}
		return $data;		
	}
	/**
	 * Получить комменты для топика
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function GetCommentsByTopicId($sId) {	
		$s=-1;
		$oUserCurrent=$this->User_GetUserCurrent();
		if ($oUserCurrent) {
			$s=$oUserCurrent->getId();
		}		
		if (false === ($aComments = $this->Cache_Get("comment_topic_{$sId}_{$s}"))) {
			$aComments=array();
			$aCommentsRow=$this->oMapperTopicComment->GetCommentsByTopicId($sId,$oUserCurrent);
			if (count($aCommentsRow)) {
				$aComments=$this->BuildCommentsRecursive($aCommentsRow);				
			}
			$this->Cache_Set($aComments, "comment_topic_{$sId}_{$s}", array("comment_update_topic_{$sId}","comment_new_topic_{$sId}"), 60*5);
		}			
		if (!isset($aComments['comments'])) {
			return array('comments'=>array(),'iMaxIdComment'=>0);
		}
		return $aComments;		
	}	
	/**
	 * Получить голосование за коммент(голосовал юзер за этот коммент или нет)
	 *
	 * @param unknown_type $sCommentId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTopicCommentVote($sCommentId,$sUserId) {
		return $this->oMapperTopicComment->GetTopicCommentVote($sCommentId,$sUserId);
	}
	/**
	 * Добавляет коммент
	 *
	 * @param CommentEntity_TopicComment $oComment
	 * @return unknown
	 */
	public function AddComment(CommentEntity_TopicComment $oComment) {
		if ($sId=$this->oMapperTopicComment->AddComment($oComment)) {
			$this->Topic_increaseTopicCountComment($oComment->getTopicId());
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('comment_new',"comment_new_user_{$oComment->getUserId()}","comment_new_topic_{$oComment->getTopicId()}"));
			$oComment->setId($sId);
			return $oComment;
		}
		return false;
	}	
	/**
	 * Добовляет голосование за коммент
	 *
	 * @param CommentEntity_TopicCommentVote $oTopicCommentVote
	 * @return unknown
	 */
	public function AddTopicCommentVote(CommentEntity_TopicCommentVote $oTopicCommentVote) {
		if ($this->oMapperTopicComment->AddTopicCommentVote($oTopicCommentVote)) {			
			return true;
		}
		return false;
	}	
	/**
	 * Обновляет коммент
	 *
	 * @param CommentEntity_TopicComment $oTopicComment
	 * @return unknown
	 */
	public function UpdateTopicComment(CommentEntity_TopicComment $oTopicComment) {		
		if ($this->oMapperTopicComment->UpdateTopicComment($oTopicComment)) {		
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('comment_update',"comment_update_{$oTopicComment->getId()}","comment_update_topic_{$oTopicComment->getTopicId()}"));				
			return true;
		}
		return false;
	}
	/**
	 * Удаляет коммент из прямого эфира
	 *
	 * @param unknown_type $sTopicId
	 * @return unknown
	 */
	public function DeleteTopicCommentOnline($sTopicId) {
		return $this->oMapperTopicComment->DeleteTopicCommentOnline($sTopicId);
	}
	/**
	 * Добавляет новый коммент в прямой эфир
	 *
	 * @param CommentEntity_TopicCommentOnline $oTopicCommentOnline
	 */
	public function AddTopicCommentOnline(CommentEntity_TopicCommentOnline $oTopicCommentOnline) {
		return $this->oMapperTopicComment->AddTopicCommentOnline($oTopicCommentOnline);
	}
	
	
	
	/**
	 * Получить комменты для топика
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function GetCommentsNewByTopicId($sId,$sIdCommentLast) {	
		$s=-1;
		$oUserCurrent=$this->User_GetUserCurrent();
		if ($oUserCurrent) {
			$s=$oUserCurrent->getId();
		}		
		if (false === ($aComments = $this->Cache_Get("comment_topic_{$sId}_{$s}_{$sIdCommentLast}"))) {
			$aComments=array();
			$aCommentsRow=$this->oMapperTopicComment->GetCommentsNewByTopicId($sId,$oUserCurrent,$sIdCommentLast);
			if (count($aCommentsRow)) {
				$aComments=$this->BuildCommentsRecursive($aCommentsRow);				
			}
			$this->Cache_Set($aComments, "comment_topic_{$sId}_{$s}_{$sIdCommentLast}", array("comment_update_topic_{$sId}","comment_new_topic_{$sId}"), 60*5);
		}			
		if (!isset($aComments['comments'])) {
			return array('comments'=>array(),'iMaxIdComment'=>0);
		}
		$aCmts=$aComments['comments'];
		
		if (!class_exists('LsViewer')) {
			require_once(DIR_SERVER_ROOT."/classes/modules/sys_viewer/Viewer.class.php");
		}
		$oViewerLocal=new LsViewer(Engine::getInstance());
		$oViewerLocal->Init();
		$oViewerLocal->VarAssign();
		$oViewerLocal->Assign('aLang',$this->Lang_GetLangMsg());
		
		$aCmt=array();
		foreach ($aCmts as $oComment) {			
			$oViewerLocal->Assign('oComment',$oComment);
			$oViewerLocal->Assign('oUserCurrent',$this->User_GetUserCurrent());			
			$sText=$oViewerLocal->Fetch("comment.tpl");
			$aCmt[]=array(
				'html' => $sText,
				'obj'  => $oComment,
			);
			//var_dump($sText);
		}
			
		return array('comments'=>$aCmt,'iMaxIdComment'=>$aComments['iMaxIdComment']);		
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
			$aResultCommnets[]=new CommentEntity_TopicComment($aTemp);			
			if (isset($aComment['childNodes']) and count($aComment['childNodes'])>0) {
				$iLevel++;
				$this->BuildCommentsRecursive($aComment['childNodes'],false);
			}
		}
		$iLevel--;		
		return array('comments'=>$aResultCommnets,'iMaxIdComment'=>$iMaxIdComment);
	}

}
?>