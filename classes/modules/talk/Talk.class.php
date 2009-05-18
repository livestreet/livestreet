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
require_once('mapper/Talk.mapper.class.php');

/**
 * Модуль разговоров(почта)
 *
 */
class LsTalk extends Module {		
	protected $oMapper;	
		
	/**
	 * Инициализация
	 *
	 */
	public function Init() {		
		$this->oMapper=new Mapper_Talk($this->Database_GetConnect());		
	}
	/**
	 * Формирует и отправляет личное сообщение
	 *
	 * @param string $sTitle
	 * @param string $sText
	 * @param int | UserEntity_User $oUserFrom
	 * @param array | int | UserEntity_User $aUserTo
	 * @param bool $bSendNotify
	 */
	public function SendTalk($sTitle,$sText,$oUserFrom,$aUserTo,$bSendNotify=true) {
		$iUserIdFrom=$oUserFrom instanceof UserEntity_User ? $oUserFrom->getId() : (int)$oUserFrom;
		if (!is_array($aUserTo)) {
			$aUserTo=array($aUserTo);
		}
		$aUserIdTo=array($iUserIdFrom);		
		foreach ($aUserTo as $oUserTo) {
			$aUserIdTo[]=$oUserTo instanceof UserEntity_User ? $oUserTo->getId() : (int)$oUserTo;
		}
		$aUserIdTo=array_unique($aUserIdTo);
		
		$oTalk=new TalkEntity_Talk();
		$oTalk->setUserId($iUserIdFrom);
		$oTalk->setTitle($sTitle);
		$oTalk->setText($sText);
		$oTalk->setDate(date("Y-m-d H:i:s"));
		$oTalk->setDateLast(date("Y-m-d H:i:s"));
		$oTalk->setUserIp(func_getIp());
		if ($oTalk=$this->Talk_AddTalk($oTalk)) {
			foreach ($aUserIdTo as $iUserId) {
				$oTalkUser=new TalkEntity_TalkUser();
				$oTalkUser->setTalkId($oTalk->getId());
				$oTalkUser->setUserId($iUserId);
				if ($iUserId==$iUserIdFrom) {
					$oTalkUser->setDateLast(date("Y-m-d H:i:s"));
				} else {
					$oTalkUser->setDateLast(null);
				}
				$this->Talk_AddTalkUser($oTalkUser);

				if ($bSendNotify) {
					if ($iUserId!=$iUserIdFrom) {
						$oUserFrom=$this->User_GetUserById($iUserIdFrom);
						$oUserToMail=$this->User_GetUserById($iUserId);
						$this->Notify_SendTalkNew($oUserToMail,$oUserFrom,$oTalk);
					}
				}
			}
			return $oTalk;
		}
		return false;
	}
	/**
	 * Добавляет новую тему разговора
	 *
	 * @param TopicEntity_Topic $oTalk
	 * @return unknown
	 */
	public function AddTalk(TalkEntity_Talk $oTalk) {
		if ($sId=$this->oMapper->AddTalk($oTalk)) {
			$oTalk->setId($sId);	
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('talk_new',"talk_new_user_{$oTalk->getUserId()}"));		
			return $oTalk;
		}
		return false;
	}
	/**
	 * Обновление разговора
	 *
	 * @param TalkEntity_Talk $oTalk
	 */
	public function UpdateTalk(TalkEntity_Talk $oTalk) {
		return $this->oMapper->UpdateTalk($oTalk);
	}
	/**
	 * Получает тему разговора по айдишнику
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function GetTalkById($sId) {
		return $this->oMapper->GetTalkById($sId);
	}
	/**
	 * Получает тему разговора по айдишнику и юзеру
	 *
	 * @param unknown_type $sTalkId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTalkByIdAndUserId($sTalkId,$sUserId) {
		return $this->oMapper->GetTalkByIdAndUserId($sTalkId,$sUserId);
	}
	/**
	 * Добавляет юзера к разговору(теме)
	 *
	 * @param TalkEntity_TalkUser $oTalkUser
	 * @return unknown
	 */
	public function AddTalkUser(TalkEntity_TalkUser $oTalkUser) {
		return $this->oMapper->AddTalkUser($oTalkUser);
	}
	/**
	 * Удаляет юзера из разговора
	 *
	 * @param TalkEntity_TalkUser $oTalkUser
	 * @return unknown
	 */
	public function DeleteTalkUser(TalkEntity_TalkUser $oTalkUser) {
		return $this->oMapper->DeleteTalkUser($oTalkUser);
	}
	/**
	 * Есть ли юзер в этом разговоре
	 *
	 * @param unknown_type $sTalkId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTalkUser($sTalkId,$sUserId) {
		return $this->oMapper->GetTalkUser($sTalkId,$sUserId);
	}
	/**
	 * Получить все темы разговора где есть юзер
	 *
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetTalksByUserId($sUserId) {
		$aTalks=$this->oMapper->GetTalksByUserId($sUserId);
		foreach ($aTalks as $oTalk) {
			$oTalk->setUsers($this->oMapper->GetTalkUsers($oTalk->getId()));	
		}		
		return $aTalks;
	}
	/**
	 * Устанавливает дату прочтения темы разговора
	 *
	 * @param unknown_type $sTalkId
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function SetTalkUserDateLast($sTalkId,$sUserId) {
		//чистим зависимые кеши
		$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array("talk_read_user_{$sUserId}"));
		return $this->oMapper->SetTalkUserDateLast($sTalkId,$sUserId);
	}
	/**
	 * Добавляет новый коммент к теме разговора
	 *
	 * @param TalkEntity_TalkComment $oComment
	 * @return unknown
	 */
	public function AddComment(TalkEntity_TalkComment $oComment) {
		if ($sId=$this->oMapper->AddComment($oComment)) {
			$oComment->setId($sId);			
			//чистим зависимые кеши
			$this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('talk_comment_new',"talk_comment_new_talk_{$oComment->getTalkId()}"));
			return $oComment;
		}
		return false;
	}
	/**
	 * Получает число новых тем и комментов где есть юзер
	 *
	 * @param unknown_type $sUserId
	 * @return unknown
	 */
	public function GetCountTalkNew($sUserId) {
		if (false === ($data = $this->Cache_Get("talk_count_all_new_user_{$sUserId}"))) {			
			$data = $this->oMapper->GetCountCommentNew($sUserId)+$this->oMapper->GetCountTalkNew($sUserId);
			$this->Cache_Set($data, "talk_count_all_new_user_{$sUserId}", array("talk_new","talk_comment_new","talk_read_user_{$sUserId}"), 60*5);
		}
		return $data;		
	}
	/**
	 * Получает коммент по его айдишнику
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function GetCommentById($sId) {
		return $this->oMapper->GetCommentById($sId);
	}
	/**
	 * Получает список комментов к теме разговора
	 *
	 * @param unknown_type $sId
	 * @return unknown
	 */
	public function GetCommentsByTalkId($sId) {
		$aComments=array();
		$aCommentsRow=$this->oMapper->GetCommentsByTalkId($sId);	
		if (count($aCommentsRow)) {
			$aComments=$this->BuildCommentsRecursive($aCommentsRow);
		}
		return $aComments;
	}
	/**
	 * Построение дерева комментов
	 *
	 * @param unknown_type $aComments
	 * @param unknown_type $bBegin
	 * @return unknown
	 */
	protected function BuildCommentsRecursive($aComments,$bBegin=true) {
		static $aResultCommnets;
		static $iLevel;
		if ($bBegin) {
			$aResultCommnets=array();
			$iLevel=0;
		}		
		foreach ($aComments as $aComment) {
			$aTemp=$aComment;
			$aTemp['level']=$iLevel;
			unset($aTemp['childNodes']);
			$aResultCommnets[]=new TalkEntity_TalkComment($aTemp);			
			if (isset($aComment['childNodes']) and count($aComment['childNodes'])>0) {
				$iLevel++;
				$this->BuildCommentsRecursive($aComment['childNodes'],false);
			}
		}
		$iLevel--;		
		return $aResultCommnets;
	}
	/**
	 * Получает список юзеров в теме разговора
	 *
	 * @param unknown_type $sTalkId
	 * @return unknown
	 */
	public function GetTalkUsers($sTalkId) {
		return $this->oMapper->GetTalkUsers($sTalkId);
	}
}
?>