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
 * Класс обработки ajax запросов
 *
 */
class ActionAjax extends Action {


	public function Init() {
		$this->Viewer_SetResponseAjax('json');
		//$this->Security_ValidateSendForm();

		$this->oUserCurrent=$this->User_GetUserCurrent();
	}

	protected function RegisterEvent() {
		$this->AddEventPreg('/^vote$/i','/^comment$/','EventVoteComment');
		$this->AddEventPreg('/^vote$/i','/^topic$/','EventVoteTopic');
		$this->AddEventPreg('/^vote$/i','/^blog$/','EventVoteBlog');
		$this->AddEventPreg('/^vote$/i','/^user$/','EventVoteUser');
		$this->AddEventPreg('/^vote$/i','/^question$/','EventVoteQuestion');

		$this->AddEventPreg('/^favourite$/i','/^save-tags/','EventFavouriteSaveTags');
		$this->AddEventPreg('/^favourite$/i','/^topic$/','EventFavouriteTopic');
		$this->AddEventPreg('/^favourite$/i','/^comment$/','EventFavouriteComment');
		$this->AddEventPreg('/^favourite$/i','/^talk$/','EventFavouriteTalk');

		$this->AddEventPreg('/^stream$/i','/^comment$/','EventStreamComment');
		$this->AddEventPreg('/^stream$/i','/^topic$/','EventStreamTopic');

		$this->AddEventPreg('/^blogs$/i','/^top$/','EventBlogsTop');
		$this->AddEventPreg('/^blogs$/i','/^self$/','EventBlogsSelf');
		$this->AddEventPreg('/^blogs$/i','/^join$/','EventBlogsJoin');

		$this->AddEventPreg('/^preview$/i','/^text$/','EventPreviewText');
		$this->AddEventPreg('/^preview$/i','/^topic/','EventPreviewTopic');

		$this->AddEventPreg('/^upload$/i','/^image$/','EventUploadImage');

		$this->AddEventPreg('/^autocompleter$/i','/^tag$/','EventAutocompleterTag');
		$this->AddEventPreg('/^autocompleter$/i','/^user$/','EventAutocompleterUser');

		$this->AddEventPreg('/^comment$/i','/^delete$/','EventCommentDelete');

		$this->AddEventPreg('/^geo/i','/^get/','/^regions$/','EventGeoGetRegions');
		$this->AddEventPreg('/^geo/i','/^get/','/^cities/','EventGeoGetCities');

		$this->AddEventPreg('/^infobox/i','/^info/','/^blog/','EventInfoboxInfoBlog');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Вывод информации о блоге
	 */
	protected function EventInfoboxInfoBlog() {
		if (!($oBlog=$this->Blog_GetBlogById(getRequest('iBlogId'))) or $oBlog->getType()=='personal') {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}

		$oViewer=$this->Viewer_GetLocalViewer();

		$oViewer->Assign('oBlog',$oBlog);
		if ($oBlog->getType()!='close' or $oBlog->getUserIsJoin()) {
			/**
			 * Получаем последний топик
			 */
			$aResult = $this->Topic_GetTopicsByFilter(array('blog_id'=>$oBlog->getId(),'topic_publish'=>1),1,1);
			$oViewer->Assign('oTopicLast',reset($aResult['collection']));
		}
		$oViewer->Assign('oUserCurrent',$this->oUserCurrent);

		$this->Viewer_AssignAjax('sText',$oViewer->Fetch("infobox.info.blog.tpl"));
	}

	/**
	 * Получение списка регионов по стране
	 */
	protected function EventGeoGetRegions() {
		$iCountryId=getRequest('country');
		$iLimit=200;
		if (is_numeric(getRequest('limit')) and getRequest('limit')>0) {
			$iLimit=getRequest('limit');
		}

		if (!($oCountry=$this->Geo_GetGeoObject('country',$iCountryId))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}

		$aResult=$this->Geo_GetRegions(array('country_id'=>$oCountry->getId()),array('sort'=>'asc'),1,$iLimit);
		$aRegions=array();
		foreach($aResult['collection'] as $oObject) {
			$aRegions[]=array(
				'id' => $oObject->getId(),
				'name' => $oObject->getName(),
			);
		}

		$this->Viewer_AssignAjax('aRegions',$aRegions);
	}

	/**
	 * Получение списка городов по региону
	 */
	protected function EventGeoGetCities() {
		$iRegionId=getRequest('region');
		$iLimit=500;
		if (is_numeric(getRequest('limit')) and getRequest('limit')>0) {
			$iLimit=getRequest('limit');
		}

		if (!($oRegion=$this->Geo_GetGeoObject('region',$iRegionId))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return;
		}

		$aResult=$this->Geo_GetCities(array('region_id'=>$oRegion->getId()),array('sort'=>'asc'),1,$iLimit);
		$aCities=array();
		foreach($aResult['collection'] as $oObject) {
			$aCities[]=array(
				'id' => $oObject->getId(),
				'name' => $oObject->getName(),
			);
		}

		$this->Viewer_AssignAjax('aCities',$aCities);
	}

	/**
	 * Голосование за комментарий
	 *
	 */
	protected function EventVoteComment() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if (!($oComment=$this->Comment_GetCommentById(getRequest('idComment',null,'post')))) {
			$this->Message_AddErrorSingle($this->Lang_Get('comment_vote_error_noexists'),$this->Lang_Get('error'));
			return;
		}

		if ($oComment->getUserId()==$this->oUserCurrent->getId()) {
			$this->Message_AddErrorSingle($this->Lang_Get('comment_vote_error_self'),$this->Lang_Get('attention'));
			return;
		}

		if ($oTopicCommentVote=$this->Vote_GetVote($oComment->getId(),'comment',$this->oUserCurrent->getId())) {
			$this->Message_AddErrorSingle($this->Lang_Get('comment_vote_error_already'),$this->Lang_Get('attention'));
			return;
		}

		if (strtotime($oComment->getDate())<=time()-Config::Get('acl.vote.comment.limit_time')) {
			$this->Message_AddErrorSingle($this->Lang_Get('comment_vote_error_time'),$this->Lang_Get('attention'));
			return;
		}

		if (!$this->ACL_CanVoteComment($this->oUserCurrent,$oComment)) {
			$this->Message_AddErrorSingle($this->Lang_Get('comment_vote_error_acl'),$this->Lang_Get('attention'));
			return;
		}

		$iValue=getRequest('value',null,'post');
		if (!in_array($iValue,array('1','-1'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('comment_vote_error_value'),$this->Lang_Get('attention'));
			return;
		}

		$oTopicCommentVote=Engine::GetEntity('Vote');
		$oTopicCommentVote->setTargetId($oComment->getId());
		$oTopicCommentVote->setTargetType('comment');
		$oTopicCommentVote->setVoterId($this->oUserCurrent->getId());
		$oTopicCommentVote->setDirection($iValue);
		$oTopicCommentVote->setDate(date("Y-m-d H:i:s"));
		$iVal=(float)$this->Rating_VoteComment($this->oUserCurrent,$oComment,$iValue);
		$oTopicCommentVote->setValue($iVal);

		$oComment->setCountVote($oComment->getCountVote()+1);
		if ($this->Vote_AddVote($oTopicCommentVote) and $this->Comment_UpdateComment($oComment)) {
			$this->Message_AddNoticeSingle($this->Lang_Get('comment_vote_ok'),$this->Lang_Get('attention'));
			$this->Viewer_AssignAjax('iRating',$oComment->getRating());
            /**
             * Добавляем событие в ленту
             */
            $this->Stream_write($oTopicCommentVote->getVoterId(), 'vote_comment', $oComment->getId());
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('comment_vote_error'),$this->Lang_Get('error'));
			return;
		}
	}


	/**
	 * Голосование за топик
	 *
	 */
	protected function EventVoteTopic() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if (!($oTopic=$this->Topic_GetTopicById(getRequest('idTopic',null,'post')))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		if ($oTopic->getUserId()==$this->oUserCurrent->getId()) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_vote_error_self'),$this->Lang_Get('attention'));
			return;
		}

		if ($oTopicVote=$this->Vote_GetVote($oTopic->getId(),'topic',$this->oUserCurrent->getId())) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_vote_error_already'),$this->Lang_Get('attention'));
			return;
		}

		if (strtotime($oTopic->getDateAdd())<=time()-Config::Get('acl.vote.topic.limit_time')) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_vote_error_time'),$this->Lang_Get('attention'));
			return;
		}

		$iValue=getRequest('value',null,'post');
		if (!in_array($iValue,array('1','-1','0'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('attention'));
			return;
		}

		if (!$this->ACL_CanVoteTopic($this->oUserCurrent,$oTopic) and $iValue) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_vote_error_acl'),$this->Lang_Get('attention'));
			return;
		}

		$oTopicVote=Engine::GetEntity('Vote');
		$oTopicVote->setTargetId($oTopic->getId());
		$oTopicVote->setTargetType('topic');
		$oTopicVote->setVoterId($this->oUserCurrent->getId());
		$oTopicVote->setDirection($iValue);
		$oTopicVote->setDate(date("Y-m-d H:i:s"));
		$iVal=0;
		if ($iValue!=0) {
			$iVal=(float)$this->Rating_VoteTopic($this->oUserCurrent,$oTopic,$iValue);
		}
		$oTopicVote->setValue($iVal);
		$oTopic->setCountVote($oTopic->getCountVote()+1);
		if ($iValue==1) {
			$oTopic->setCountVoteUp($oTopic->getCountVoteUp()+1);
		} elseif ($iValue==-1) {
			$oTopic->setCountVoteDown($oTopic->getCountVoteDown()+1);
		} elseif ($iValue==0) {
			$oTopic->setCountVoteAbstain($oTopic->getCountVoteAbstain()+1);
		}
		if ($this->Vote_AddVote($oTopicVote) and $this->Topic_UpdateTopic($oTopic)) {
			if ($iValue) {
				$this->Message_AddNoticeSingle($this->Lang_Get('topic_vote_ok'),$this->Lang_Get('attention'));
			} else {
				$this->Message_AddNoticeSingle($this->Lang_Get('topic_vote_ok_abstain'),$this->Lang_Get('attention'));
			}
			$this->Viewer_AssignAjax('iRating',$oTopic->getRating());
             /**
             * Добавляем событие в ленту
             */
            $this->Stream_write($oTopicVote->getVoterId(), 'vote_topic', $oTopic->getId());
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
	}



	/**
	 * Голосование за блог
	 *
	 */
	protected function EventVoteBlog() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if (!($oBlog=$this->Blog_GetBlogById(getRequest('idBlog',null,'post')))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		if ($oBlog->getOwnerId()==$this->oUserCurrent->getId()) {
			$this->Message_AddErrorSingle($this->Lang_Get('blog_vote_error_self'),$this->Lang_Get('attention'));
			return;
		}

		if ($oBlogVote=$this->Vote_GetVote($oBlog->getId(),'blog',$this->oUserCurrent->getId())) {
			$this->Message_AddErrorSingle($this->Lang_Get('blog_vote_error_already'),$this->Lang_Get('attention'));
			return;
		}

		switch($this->ACL_CanVoteBlog($this->oUserCurrent,$oBlog)) {
			case ModuleACL::CAN_VOTE_BLOG_TRUE:
				$iValue=getRequest('value',null,'post');
				if (in_array($iValue,array('1','-1'))) {
					$oBlogVote=Engine::GetEntity('Vote');
					$oBlogVote->setTargetId($oBlog->getId());
					$oBlogVote->setTargetType('blog');
					$oBlogVote->setVoterId($this->oUserCurrent->getId());
					$oBlogVote->setDirection($iValue);
					$oBlogVote->setDate(date("Y-m-d H:i:s"));
					$iVal=(float)$this->Rating_VoteBlog($this->oUserCurrent,$oBlog,$iValue);
					$oBlogVote->setValue($iVal);
					$oBlog->setCountVote($oBlog->getCountVote()+1);
					if ($this->Vote_AddVote($oBlogVote) and $this->Blog_UpdateBlog($oBlog)) {
						$this->Viewer_AssignAjax('iCountVote',$oBlog->getCountVote());
						$this->Viewer_AssignAjax('iRating',$oBlog->getRating());
						$this->Message_AddNoticeSingle($this->Lang_Get('blog_vote_ok'),$this->Lang_Get('attention'));
                         /**
                         * Добавляем событие в ленту
                         */
                        $this->Stream_write($oBlogVote->getVoterId(), 'vote_blog', $oBlog->getId());
					} else {
						$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('attention'));
						return;
					}
				} else {
					$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('attention'));
					return;
				}
				break;
			case ModuleACL::CAN_VOTE_BLOG_ERROR_CLOSE:
				$this->Message_AddErrorSingle($this->Lang_Get('blog_vote_error_close'),$this->Lang_Get('attention'));
				return;
				break;

			default:
			case ModuleACL::CAN_VOTE_BLOG_FALSE:
				$this->Message_AddErrorSingle($this->Lang_Get('blog_vote_error_acl'),$this->Lang_Get('attention'));
				return;
				break;
		}
	}



	/**
	 * Голосование за блог
	 *
	 */
	protected function EventVoteUser() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if (!($oUser=$this->User_GetUserById(getRequest('idUser',null,'post')))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		if ($oUser->getId()==$this->oUserCurrent->getId()) {
			$this->Message_AddErrorSingle($this->Lang_Get('user_vote_error_self'),$this->Lang_Get('attention'));
			return;
		}

		if ($oUserVote=$this->Vote_GetVote($oUser->getId(),'user',$this->oUserCurrent->getId())) {
			$this->Message_AddErrorSingle($this->Lang_Get('user_vote_error_already'),$this->Lang_Get('attention'));
			return;
		}

		if (!$this->ACL_CanVoteUser($this->oUserCurrent,$oUser)) {
			$this->Message_AddErrorSingle($this->Lang_Get('user_vote_error_acl'),$this->Lang_Get('attention'));
			return;
		}

		$iValue=getRequest('value',null,'post');
		if (!in_array($iValue,array('1','-1'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('attention'));
			return;
		}


		$oUserVote=Engine::GetEntity('Vote');
		$oUserVote->setTargetId($oUser->getId());
		$oUserVote->setTargetType('user');
		$oUserVote->setVoterId($this->oUserCurrent->getId());
		$oUserVote->setDirection($iValue);
		$oUserVote->setDate(date("Y-m-d H:i:s"));
		$iVal=(float)$this->Rating_VoteUser($this->oUserCurrent,$oUser,$iValue);
		$oUserVote->setValue($iVal);
		//$oUser->setRating($oUser->getRating()+$iValue);
		$oUser->setCountVote($oUser->getCountVote()+1);
		if ($this->Vote_AddVote($oUserVote) and $this->User_Update($oUser)) {
			$this->Message_AddNoticeSingle($this->Lang_Get('user_vote_ok'),$this->Lang_Get('attention'));
			$this->Viewer_AssignAjax('iRating',$oUser->getRating());
			$this->Viewer_AssignAjax('iSkill',$oUser->getSkill());
			$this->Viewer_AssignAjax('iCountVote',$oUser->getCountVote());
             /**
             * Добавляем событие в ленту
             */
            $this->Stream_write($oUserVote->getVoterId(), 'vote_user', $oUser->getId());
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
	}


	/**
	 * Голосование за вариант ответа в опросе
	 *
	 */
	protected function EventVoteQuestion() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		$idAnswer=getRequest('idAnswer',null,'post');
		$idTopic=getRequest('idTopic',null,'post');

		if (!($oTopic=$this->Topic_GetTopicById($idTopic))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		if ($oTopic->getType()!='question') {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		if ($oTopicQuestionVote=$this->Topic_GetTopicQuestionVote($oTopic->getId(),$this->oUserCurrent->getId())) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_question_vote_already'),$this->Lang_Get('error'));
			return;
		}

		$aAnswer=$oTopic->getQuestionAnswers();
		if (!isset($aAnswer[$idAnswer]) and $idAnswer!=-1) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		if ($idAnswer==-1) {
			$oTopic->setQuestionCountVoteAbstain($oTopic->getQuestionCountVoteAbstain()+1);
		} else {
			$oTopic->increaseQuestionAnswerVote($idAnswer);
		}
		$oTopic->setQuestionCountVote($oTopic->getQuestionCountVote()+1);

		$oTopicQuestionVote=Engine::GetEntity('Topic_TopicQuestionVote');
		$oTopicQuestionVote->setTopicId($oTopic->getId());
		$oTopicQuestionVote->setVoterId($this->oUserCurrent->getId());
		$oTopicQuestionVote->setAnswer($idAnswer);

		if ($this->Topic_AddTopicQuestionVote($oTopicQuestionVote) and $this->Topic_updateTopic($oTopic)) {
			$this->Message_AddNoticeSingle($this->Lang_Get('topic_question_vote_ok'),$this->Lang_Get('attention'));
			$oViewer=$this->Viewer_GetLocalViewer();
			$oViewer->Assign('oTopic',$oTopic);
			$this->Viewer_AssignAjax('sText',$oViewer->Fetch("question_result.tpl"));
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
	}

	/**
	 * Сохраняет теги для избранного
	 *
	 * @return mixed
	 */
	protected function EventFavouriteSaveTags() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if ($oFavourite=$this->Favourite_GetFavourite(getRequest('target_id'),getRequest('target_type'),$this->oUserCurrent->getId())) {
			$aTags=explode(',',trim(getRequest('tags'),"\r\n\t\0\x0B ."));
			$aTagsNew=array();
			$aTagsNewLow=array();
			$aTagsReturn=array();
			foreach ($aTags as $sTag) {
				$sTag=trim($sTag);
				if (func_check($sTag,'text',2,50) and !in_array(mb_strtolower($sTag,'UTF-8'),$aTagsNewLow)) {
					$sTagEsc=htmlspecialchars($sTag);
					$aTagsNew[]=$sTagEsc;
					$aTagsReturn[]=array(
						'tag' => $sTagEsc,
						'url' => $this->oUserCurrent->getUserWebPath().'favourites/'.$oFavourite->getTargetType().'s/tag/'.$sTagEsc.'/', // костыль для URL с множественным числом
					);
					$aTagsNewLow[]=mb_strtolower($sTag,'UTF-8');
				}
			}
			if (!count($aTagsNew)) {
				$oFavourite->setTags('');
			} else {
				$oFavourite->setTags(join(',',$aTagsNew));
			}
			$this->Viewer_AssignAjax('aTags',$aTagsReturn);
			$this->Favourite_UpdateFavourite($oFavourite);
			return;
		}
		$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
	}

	/**
	 * Обработка избранного - топик
	 *
	 */
	protected function EventFavouriteTopic() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		$iType=getRequest('type',null,'post');
		if (!in_array($iType,array('1','0'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		if (!($oTopic=$this->Topic_GetTopicById(getRequest('idTopic',null,'post')))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

                  if (!$oTopic->getPublish()) {
			$this->Message_AddErrorSingle($this->Lang_Get('error_favorite_topic_is_draft'),$this->Lang_Get('error'));
			return;
		}

		$oFavouriteTopic=$this->Topic_GetFavouriteTopic($oTopic->getId(),$this->oUserCurrent->getId());
		if (!$oFavouriteTopic and $iType) {
			$oFavouriteTopicNew=Engine::GetEntity('Favourite',
				array(
					'target_id'      => $oTopic->getId(),
					'user_id'        => $this->oUserCurrent->getId(),
					'target_type'    => 'topic',
					'target_publish' => $oTopic->getPublish()
				)
			);
            $oTopic->setCountFavourite($oTopic->getCountFavourite()+1);
			if ($this->Topic_AddFavouriteTopic($oFavouriteTopicNew) and $this->Topic_UpdateTopic($oTopic)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('topic_favourite_add_ok'),$this->Lang_Get('attention'));
				$this->Viewer_AssignAjax('bState',true);
                $this->Viewer_AssignAjax('iCount', $oTopic->getCountFavourite());
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
			}
		}
		if (!$oFavouriteTopic and !$iType) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_favourite_add_no'),$this->Lang_Get('error'));
			return;
		}
		if ($oFavouriteTopic and $iType) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_favourite_add_already'),$this->Lang_Get('error'));
			return;
		}
		if ($oFavouriteTopic and !$iType) {
            $oTopic->setCountFavourite($oTopic->getCountFavourite()-1);
			if ($this->Topic_DeleteFavouriteTopic($oFavouriteTopic) and $this->Topic_UpdateTopic($oTopic)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('topic_favourite_del_ok'),$this->Lang_Get('attention'));
				$this->Viewer_AssignAjax('bState',false);
                $this->Viewer_AssignAjax('iCount', $oTopic->getCountFavourite());
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
			}
		}
	}



	/**
	 * Обработка избранного - комментарий
	 *
	 */
	protected function EventFavouriteComment() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		$iType=getRequest('type',null,'post');
		if (!in_array($iType,array('1','0'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		if (!($oComment=$this->Comment_GetCommentById(getRequest('idComment',null,'post')))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		$oFavouriteComment=$this->Comment_GetFavouriteComment($oComment->getId(),$this->oUserCurrent->getId());
		if (!$oFavouriteComment and $iType) {
			$oFavouriteCommentNew=Engine::GetEntity('Favourite',
				array(
					'target_id'      => $oComment->getId(),
					'target_type'    => 'comment',
					'user_id'        => $this->oUserCurrent->getId(),
					'target_publish' => $oComment->getPublish()
				)
			);
            $oComment->setCountFavourite($oComment->getCountFavourite()+1);
			if ($this->Comment_AddFavouriteComment($oFavouriteCommentNew) and $this->Comment_UpdateComment($oComment)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('comment_favourite_add_ok'),$this->Lang_Get('attention'));
				$this->Viewer_AssignAjax('bState',true);
                $this->Viewer_AssignAjax('iCount', $oComment->getCountFavourite());
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
			}
		}
		if (!$oFavouriteComment and !$iType) {
			$this->Message_AddErrorSingle($this->Lang_Get('comment_favourite_add_no'),$this->Lang_Get('error'));
			return;
		}
		if ($oFavouriteComment and $iType) {
			$this->Message_AddErrorSingle($this->Lang_Get('comment_favourite_add_already'),$this->Lang_Get('error'));
			return;
		}
		if ($oFavouriteComment and !$iType) {
            $oComment->setCountFavourite($oComment->getCountFavourite()-1);
			if ($this->Comment_DeleteFavouriteComment($oFavouriteComment) and $this->Comment_UpdateComment($oComment)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('comment_favourite_del_ok'),$this->Lang_Get('attention'));
				$this->Viewer_AssignAjax('bState',false);
                $this->Viewer_AssignAjax('iCount', $oComment->getCountFavourite());
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
			}
		}
	}


	/**
	 * Обработка избранного - письмо
	 *
	 */
	protected function EventFavouriteTalk() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		$iType=getRequest('type',null,'post');
		if (!in_array($iType,array('1','0'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		if (!($oTalk=$this->Talk_GetTalkById(getRequest('idTalk',null,'post')))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}


		$oFavouriteTalk=$this->Talk_GetFavouriteTalk($oTalk->getId(),$this->oUserCurrent->getId());
		if (!$oFavouriteTalk and $iType) {
			$oFavouriteTalkNew=Engine::GetEntity('Favourite',
				array(
					'target_id'      => $oTalk->getId(),
					'target_type'    => 'talk',
					'user_id'        => $this->oUserCurrent->getId(),
					'target_publish' => '1'
				)
			);
			if ($this->Talk_AddFavouriteTalk($oFavouriteTalkNew)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('talk_favourite_add_ok'),$this->Lang_Get('attention'));
				$this->Viewer_AssignAjax('bState',true);
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
			}
		}
		if (!$oFavouriteTalk and !$iType) {
			$this->Message_AddErrorSingle($this->Lang_Get('talk_favourite_add_no'),$this->Lang_Get('error'));
			return;
		}
		if ($oFavouriteTalk and $iType) {
			$this->Message_AddErrorSingle($this->Lang_Get('talk_favourite_add_already'),$this->Lang_Get('error'));
			return;
		}
		if ($oFavouriteTalk and !$iType) {
			if ($this->Talk_DeleteFavouriteTalk($oFavouriteTalk)) {
				$this->Message_AddNoticeSingle($this->Lang_Get('talk_favourite_del_ok'),$this->Lang_Get('attention'));
				$this->Viewer_AssignAjax('bState',false);
			} else {
				$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
				return;
			}
		}

	}


	/**
	 * Обработка получения последних комментов
	 *
	 */
	protected function EventStreamComment() {
		if ($aComments=$this->Comment_GetCommentsOnline('topic',Config::Get('block.stream.row'))) {
			$oViewer=$this->Viewer_GetLocalViewer();
			$oViewer->Assign('aComments',$aComments);
			$sTextResult=$oViewer->Fetch("blocks/block.stream_comment.tpl");
			$this->Viewer_AssignAjax('sText',$sTextResult);
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('block_stream_comments_no'),$this->Lang_Get('attention'));
			return;
		}
	}


	/**
	 * Обработка получения последних топиков
	 *
	 */
	protected function EventStreamTopic() {
		if ($oTopics=$this->Topic_GetTopicsLast(Config::Get('block.stream.row'))) {
			$oViewer=$this->Viewer_GetLocalViewer();
			$oViewer->Assign('oTopics',$oTopics);
			$sTextResult=$oViewer->Fetch("blocks/block.stream_topic.tpl");
			$this->Viewer_AssignAjax('sText',$sTextResult);
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('block_stream_topics_no'),$this->Lang_Get('attention'));
			return;
		}
	}


	/**
	 * Обработка получения TOP блогов
	 *
	 */
	protected function EventBlogsTop() {
		if ($aResult=$this->Blog_GetBlogsRating(1,Config::Get('block.blogs.row'))) {
			$aBlogs=$aResult['collection'];
			$oViewer=$this->Viewer_GetLocalViewer();
			$oViewer->Assign('aBlogs',$aBlogs);
			$sTextResult=$oViewer->Fetch("blocks/block.blogs_top.tpl");
			$this->Viewer_AssignAjax('sText',$sTextResult);
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
	}

	/**
	 * Обработка получения своих блогов
	 *
	 */
	protected function EventBlogsSelf() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if ($aBlogs=$this->Blog_GetBlogsRatingSelf($this->oUserCurrent->getId(),Config::Get('block.blogs.row'))) {
			$oViewer=$this->Viewer_GetLocalViewer();
			$oViewer->Assign('aBlogs',$aBlogs);
			$sTextResult=$oViewer->Fetch("blocks/block.blogs_top.tpl");
			$this->Viewer_AssignAjax('sText',$sTextResult);
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('block_blogs_self_error'),$this->Lang_Get('attention'));
			return;
		}
	}

	/**
	 * Обработка получения подключенных блогов
	 *
	 */
	protected function EventBlogsJoin() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if ($aBlogs=$this->Blog_GetBlogsRatingJoin($this->oUserCurrent->getId(),Config::Get('block.blogs.row'))) {
			$oViewer=$this->Viewer_GetLocalViewer();
			$oViewer->Assign('aBlogs',$aBlogs);
			$sTextResult=$oViewer->Fetch("blocks/block.blogs_top.tpl");
			$this->Viewer_AssignAjax('sText',$sTextResult);
		} else {
			$this->Message_AddErrorSingle($this->Lang_Get('block_blogs_join_error'),$this->Lang_Get('attention'));
			return;
		}
	}

	/**
	 * Предпросмотр топика
	 *
	 */
	protected function EventPreviewTopic() {
		$this->Viewer_SetResponseAjax('jsonIframe',false);
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		if (!$this->Topic_IsAllowTopicType($sType=getRequest('topic_type'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('topic_create_type_error'),$this->Lang_Get('error'));
			return;
		}

		$oTopic=Engine::GetEntity('ModuleTopic_EntityTopic');
		$oTopic->_setValidateScenario($sType); // зависит от типа топика

		$oTopic->setTitle(strip_tags(getRequest('topic_title')));
		$oTopic->setTextSource(getRequest('topic_text'));
		$oTopic->setTags(getRequest('topic_tags'));
		$oTopic->setDateAdd(date("Y-m-d H:i:s"));
		$oTopic->setUserId($this->oUserCurrent->getId());
		$oTopic->setType($sType);

		$oTopic->_Validate(array('topic_title','topic_text','topic_tags','topic_type'),false);

		if ($oTopic->_hasValidateErrors()) {
			$this->Message_AddErrorSingle($oTopic->_getValidateError());
			return false;
		}
		/**
		 * Формируем текст топика
		 */
		if (in_array($sType,array('link','question'))) {
			$oTopic->setCutText(null);
			$oTopic->setText(htmlspecialchars($oTopic->getTextSource()));
			$oTopic->setTextShort(htmlspecialchars($oTopic->getTextSource()));
		} else {
			list($sTextShort,$sTextNew,$sTextCut) = $this->Text_Cut($oTopic->getTextSource());
			$oTopic->setCutText($sTextCut);
			$oTopic->setText($this->Text_Parser($sTextNew));
			$oTopic->setTextShort($this->Text_Parser($sTextShort));
		}
		/**
		 * Рендерим шаблон для предпросмотра топика
		 */
		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign('oTopic',$oTopic);
		$sTemplate="topic_preview_{$oTopic->getType()}.tpl";
		if (!$this->Viewer_TemplateExists($sTemplate)) {
			$sTemplate='topic_preview_topic.tpl';
		}
		$sTextResult=$oViewer->Fetch($sTemplate);
		$this->Viewer_AssignAjax('sText',$sTextResult);
		return true;
	}
	/**
	 * Предпросмотр текста
	 *
	 */
	protected function EventPreviewText() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		$sText=getRequest('text',null,'post');
		$bSave=getRequest('save',null,'post');

		if ($bSave) {
			$sTextResult=htmlspecialchars($sText);
		} else {
			$sTextResult=$this->Text_Parser($sText);
		}
		$this->Viewer_AssignAjax('sText',$sTextResult);
	}


	/**
	 * Загрузка изображения
	 *
	 */
	protected function EventUploadImage() {
		$this->Viewer_SetResponseAjax('jsonIframe',false);
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}

		$aForm=getRequest('value',null,'post');
		$sFile=null;
		if (is_uploaded_file($_FILES['img_file']['tmp_name'])) {
			if(!$sFile=$this->Topic_UploadTopicImageFile($_FILES['img_file'],$this->oUserCurrent)) {
				$this->Message_AddErrorSingle($this->Lang_Get('uploadimg_file_error'),$this->Lang_Get('error'));
				return;
			}
		} elseif (isPost('img_url') && $_REQUEST['img_url']!='' && $_REQUEST['img_url']!='http://') {
			$sFile=$this->Topic_UploadTopicImageUrl($_REQUEST['img_url'],$this->oUserCurrent);
			switch (true) {
				case is_string($sFile):

					break;

				case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR_READ):
					$this->Message_AddErrorSingle($this->Lang_Get('uploadimg_url_error_read'),$this->Lang_Get('error'));
					return;

				case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR_SIZE):
					$this->Message_AddErrorSingle($this->Lang_Get('uploadimg_url_error_size'),$this->Lang_Get('error'));
					return;

				case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR_TYPE):
					$this->Message_AddErrorSingle($this->Lang_Get('uploadimg_url_error_type'),$this->Lang_Get('error'));
					return;

				default:
				case ($sFile==ModuleImage::UPLOAD_IMAGE_ERROR):
					$this->Message_AddErrorSingle($this->Lang_Get('uploadimg_url_error'),$this->Lang_Get('error'));
					return;
			}
		}
		if ($sFile) {
			$sText=$this->Image_BuildHTML($sFile, $_REQUEST);
			$this->Viewer_AssignAjax('sText',$sText);
		}
	}

	/**
	 * Автоподставновка тегов
	 *
	 */
	protected function EventAutocompleterTag() {
		if (!($sValue=getRequest('value',null,'post'))) {
			return ;
		}

		$aItems=array();
		$aTags=$this->Topic_GetTopicTagsByLike($sValue,10);
		foreach ($aTags as $oTag) {
			$aItems[]=$oTag->getText();
		}
		$this->Viewer_AssignAjax('aItems',$aItems);
	}

	/**
	 * Автоподставновка пользователей
	 *
	 */
	protected function EventAutocompleterUser() {
		if (!($sValue=getRequest('value',null,'post'))) {
			return ;
		}

		$aItems=array();
		$aUsers=$this->User_GetUsersByLoginLike($sValue,10);
		foreach ($aUsers as $oUser) {
			$aItems[]=$oUser->getLogin();
		}
		$this->Viewer_AssignAjax('aItems',$aItems);
	}


	/**
	 * Удаление/восстановление комментария
	 *
	 */
	protected function EventCommentDelete() {
        
        
		if (!$this->ACL_CanDeleteComment($this->oUserCurrent)) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'),$this->Lang_Get('error'));
			return;
		}

		$idComment=getRequest('idComment',null,'post');
		if (!($oComment=$this->Comment_GetCommentById($idComment))) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}

		$oComment->setDelete(($oComment->getDelete()+1)%2);
		$this->Hook_Run('comment_delete_before', array('oComment'=>$oComment));
		if (!$this->Comment_UpdateCommentStatus($oComment)) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'),$this->Lang_Get('error'));
			return;
		}
		$this->Hook_Run('comment_delete_after', array('oComment'=>$oComment));
		if ($bState=(bool)$oComment->getDelete()) {
			$sMsg=$this->Lang_Get('comment_delete_ok');
			$sTextToggle=$this->Lang_Get('comment_repair');
		} else {
			$sMsg=$this->Lang_Get('comment_repair_ok');
			$sTextToggle=$this->Lang_Get('comment_delete');
		}
		/**
		 * Обновление события в ленте активности
		 */
		$this->Stream_write($oComment->getUserId(), 'add_comment', $oComment->getId(), !$oComment->getDelete());
		
		$this->Message_AddNoticeSingle($sMsg,$this->Lang_Get('attention'));
		$this->Viewer_AssignAjax('bState',$bState);
		$this->Viewer_AssignAjax('sTextToggle',$sTextToggle);
	}



}
?>