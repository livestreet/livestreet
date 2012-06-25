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
 * Объект сущности топика
 *
 * @package modules.topic
 * @since 1.0
 */
class ModuleTopic_EntityTopic extends Entity {
	/**
	 * Массив объектов(не всегда) для дополнительных типов топиков(линки, опросы, подкасты и т.п.)
	 *
	 * @var array
	 */
	protected $aExtra=null;

	/**
	 * Определяем правила валидации
	 */
	public function Init() {
		parent::Init();
		$this->aValidateRules[]=array('topic_title','string','max'=>200,'min'=>2,'allowEmpty'=>false,'label'=>$this->Lang_Get('topic_create_title'),'on'=>array('topic','link','photoset'));
		$this->aValidateRules[]=array('topic_title','string','max'=>200,'min'=>2,'allowEmpty'=>false,'label'=>$this->Lang_Get('topic_question_create_title'),'on'=>array('question'));
		$this->aValidateRules[]=array('topic_text_source','string','max'=>Config::Get('module.topic.max_length'),'min'=>2,'allowEmpty'=>false,'label'=>$this->Lang_Get('topic_create_text'),'on'=>array('topic','photoset'));
		$this->aValidateRules[]=array('topic_text_source','string','max'=>500,'min'=>10,'allowEmpty'=>false,'label'=>$this->Lang_Get('topic_create_text'),'on'=>array('link'));
		$this->aValidateRules[]=array('topic_text_source','string','max'=>500,'allowEmpty'=>true,'label'=>$this->Lang_Get('topic_create_text'),'on'=>array('question'));
		$this->aValidateRules[]=array('topic_tags','tags','count'=>15,'label'=>$this->Lang_Get('topic_create_tags'),'allowEmpty'=>Config::Get('module.topic.allow_empty_tags'),'on'=>array('topic','link','question','photoset'));
		$this->aValidateRules[]=array('blog_id','blog_id','on'=>array('topic','link','question','photoset'));
		$this->aValidateRules[]=array('topic_text_source','topic_unique','on'=>array('topic','link','question','photoset'));
		$this->aValidateRules[]=array('topic_type','topic_type','on'=>array('topic','link','question','photoset'));
		$this->aValidateRules[]=array('link_url','url','allowEmpty'=>false,'label'=>$this->Lang_Get('topic_link_create_url'),'on'=>array('link'));
	}
	/**
	 * Проверка типа топика
	 *
	 * @param string $sValue	Проверяемое значение
	 * @param array $aParams	Параметры
	 * @return bool|string
	 */
	public function ValidateTopicType($sValue,$aParams) {
		if ($this->Topic_IsAllowTopicType($sValue)) {
			return true;
		}
		return $this->Lang_Get('topic_create_type_error');
	}
	/**
	 * Проверка топика на уникальность
	 *
	 * @param string $sValue	Проверяемое значение
	 * @param array $aParams	Параметры
	 * @return bool|string
	 */
	public function ValidateTopicUnique($sValue,$aParams) {
		$this->setTextHash(md5($this->getType().$sValue.$this->getTitle()));
		if ($oTopicEquivalent=$this->Topic_GetTopicUnique($this->getUserId(),$this->getTextHash())) {
			if ($iId=$this->getId() and $oTopicEquivalent->getId()==$iId) {
				return true;
			}
			return $this->Lang_Get('topic_create_text_error_unique');
		}
		return true;
	}
	/**
	 * Валидация ID блога
	 *
	 * @param string $sValue	Проверяемое значение
	 * @param array $aParams	Параметры
	 * @return bool|string
	 */
	public function ValidateBlogId($sValue,$aParams) {
		if ($sValue==0) {
			return true; // персональный блог
		}
		if ($this->Blog_GetBlogById((string)$sValue)) {
			return true;
		}
		return $this->Lang_Get('topic_create_blog_error_unknown');
	}

	/**
	 * Возвращает ID топика
	 *
	 * @return int|null
	 */
	public function getId() {
		return $this->_getDataOne('topic_id');
	}
	/**
	 * Возвращает ID блога
	 *
	 * @return int|null
	 */
	public function getBlogId() {
		return $this->_getDataOne('blog_id');
	}
	/**
	 * Возвращает ID пользователя
	 *
	 * @return int|null
	 */
	public function getUserId() {
		return $this->_getDataOne('user_id');
	}
	/**
	 * Возвращает тип топика
	 *
	 * @return string|null
	 */
	public function getType() {
		return $this->_getDataOne('topic_type');
	}
	/**
	 * Возвращает заголовок топика
	 *
	 * @return string|null
	 */
	public function getTitle() {
		return $this->_getDataOne('topic_title');
	}
	/**
	 * Возвращает текст топика
	 *
	 * @return string|null
	 */
	public function getText() {
		return $this->_getDataOne('topic_text');
	}
	/**
	 * Возвращает короткий текст топика (до ката)
	 *
	 * @return string|null
	 */
	public function getTextShort() {
		return $this->_getDataOne('topic_text_short');
	}
	/**
	 * Возвращает исходный текст топика, без примененя парсера тегов
	 *
	 * @return string|null
	 */
	public function getTextSource() {
		return $this->_getDataOne('topic_text_source');
	}
	/**
	 * Возвращает сериализованные строку дополнительный данных топика
	 *
	 * @return string
	 */
	public function getExtra() {
		return $this->_getDataOne('topic_extra') ? $this->_getDataOne('topic_extra') : serialize('');
	}
	/**
	 * Возвращает строку со списком тегов через запятую
	 *
	 * @return string|null
	 */
	public function getTags() {
		return $this->_getDataOne('topic_tags');
	}
	/**
	 * Возвращает дату создания топика
	 *
	 * @return string|null
	 */
	public function getDateAdd() {
		return $this->_getDataOne('topic_date_add');
	}
	/**
	 * Возвращает дату редактирования топика
	 *
	 * @return string|null
	 */
	public function getDateEdit() {
		return $this->_getDataOne('topic_date_edit');
	}
	/**
	 * Возвращает IP пользователя
	 *
	 * @return string|null
	 */
	public function getUserIp() {
		return $this->_getDataOne('topic_user_ip');
	}
	/**
	 * Возвращает статус опубликованности топика
	 *
	 * @return int|null
	 */
	public function getPublish() {
		return $this->_getDataOne('topic_publish');
	}
	/**
	 * Возвращает статус опубликованности черновика
	 *
	 * @return int|null
	 */
	public function getPublishDraft() {
		return $this->_getDataOne('topic_publish_draft');
	}
	/**
	 * Возвращает статус публикации топика на главной странице
	 *
	 * @return int|null
	 */
	public function getPublishIndex() {
		return $this->_getDataOne('topic_publish_index');
	}
	/**
	 * Возвращает рейтинг топика
	 *
	 * @return string
	 */
	public function getRating() {
		return number_format(round($this->_getDataOne('topic_rating'),2), 0, '.', '');
	}
	/**
	 * Возвращает число проголосовавших за топик
	 *
	 * @return int|null
	 */
	public function getCountVote() {
		return $this->_getDataOne('topic_count_vote');
	}
	/**
	 * Возвращает число проголосовавших за топик положительно
	 *
	 * @return int|null
	 */
	public function getCountVoteUp() {
		return $this->_getDataOne('topic_count_vote_up');
	}
	/**
	 * Возвращает число проголосовавших за топик отрицательно
	 *
	 * @return int|null
	 */
	public function getCountVoteDown() {
		return $this->_getDataOne('topic_count_vote_down');
	}
	/**
	 * Возвращает число воздержавшихся при голосовании за топик
	 *
	 * @return int|null
	 */
	public function getCountVoteAbstain() {
		return $this->_getDataOne('topic_count_vote_abstain');
	}
	/**
	 * Возвращает число прочтений топика
	 *
	 * @return int|null
	 */
	public function getCountRead() {
		return $this->_getDataOne('topic_count_read');
	}
	/**
	 * Возвращает количество комментариев к топику
	 *
	 * @return int|null
	 */
	public function getCountComment() {
		return $this->_getDataOne('topic_count_comment');
	}
	/**
	 * Возвращает текст ката
	 *
	 * @return string|null
	 */
	public function getCutText() {
		return $this->_getDataOne('topic_cut_text');
	}
	/**
	 * Возвращает статус запрета комментировать топик
	 *
	 * @return int|null
	 */
	public function getForbidComment() {
		return $this->_getDataOne('topic_forbid_comment');
	}
	/**
	 * Возвращает хеш топика для проверки топика на уникальность
	 *
	 * @return string|null
	 */
	public function getTextHash() {
		return $this->_getDataOne('topic_text_hash');
	}

	/**
	 * Возвращает массив тегов
	 *
	 * @return array
	 */
	public function getTagsArray() {
		if ($this->getTags()) {
			return explode(',',$this->getTags());
		}
		return array();
	}
	/**
	 * Возвращает количество новых комментариев в топике для текущего пользователя
	 *
	 * @return int|null
	 */
	public function getCountCommentNew() {
		return $this->_getDataOne('count_comment_new');
	}
	/**
	 * Возвращает дату прочтения топика для текущего пользователя
	 *
	 * @return string|null
	 */
	public function getDateRead() {
		return $this->_getDataOne('date_read');
	}
	/**
	 * Возвращает объект пользователя, автора топик
	 *
	 * @return ModuleUser_EntityUser|null
	 */
	public function getUser() {
		if (!$this->_getDataOne('user')) {
			$this->_aData['user']=$this->User_GetUserById($this->getUserId());
		}
		return $this->_getDataOne('user');
	}
	/**
	 * Возвращает объект блого, в котором находится топик
	 *
	 * @return ModuleBlog_EntityBlog|null
	 */
	public function getBlog() {
		return $this->_getDataOne('blog');
	}
	/**
	 * Возвращает полный URL до топика
	 *
	 * @return string
	 */
	public function getUrl() {
		if ($this->getBlog()->getType()=='personal') {
			return Router::GetPath('blog').$this->getId().'.html';
		} else {
			return Router::GetPath('blog').$this->getBlog()->getUrl().'/'.$this->getId().'.html';
		}
	}
	/**
	 * Возвращает объект голосования за топик текущим пользователем
	 *
	 * @return ModuleVote_EntityVote|null
	 */
	public function getVote() {
		return $this->_getDataOne('vote');
	}
	/**
	 * Возвращает статус голосовал ли пользователь в топике-опросе
	 *
	 * @return bool|null
	 */
	public function getUserQuestionIsVote() {
		return $this->_getDataOne('user_question_is_vote');
	}
	/**
	 * Проверяет находится ли данный топик в избранном у текущего пользователя
	 *
	 * @return bool
	 */
	public function getIsFavourite() {
		if ($this->getFavourite()) {
			return true;
		}
		return false;
	}
	/**
	 * Возвращает количество добавивших топик в избранное
	 *
	 * @return int|null
	 */
	public function getCountFavourite() {
		return $this->_getDataOne('topic_count_favourite');
	}
	/**
	 * Возвращает объект подписки на новые комментарии к топику
	 *
	 * @return ModuleSubscribe_EntitySubscribe|null
	 */
	public function getSubscribeNewComment() {
		if (!($oUserCurrent=$this->User_GetUserCurrent())) {
			return null;
		}
		return $this->Subscribe_GetSubscribeByTargetAndMail('topic_new_comment',$this->getId(),$oUserCurrent->getMail());
	}

	/***************************************************************************************************************************************************
	 * методы расширения типов топика
	 ***************************************************************************************************************************************************
	 */

	/**
	 * Извлекает сериализованные данные топика
	 */
	protected function extractExtra() {
		if (is_null($this->aExtra)) {
			$this->aExtra=@unserialize($this->getExtra());
		}
	}
	/**
	 * Устанавливает значение нужного параметра
	 *
	 * @param string $sName	Название параметра/данных
	 * @param mixed $data	Данные
	 */
	protected function setExtraValue($sName,$data) {
		$this->extractExtra();
		$this->aExtra[$sName]=$data;
		$this->setExtra($this->aExtra);
	}
	/**
	 * Извлекает значение параметра
	 *
	 * @param string $sName	Название параметра
	 * @return null|mixed
	 */
	protected function getExtraValue($sName) {
		$this->extractExtra();
		if (isset($this->aExtra[$sName])) {
			return $this->aExtra[$sName];
		}
		return null;
	}

	/**
	 * Возвращает URL для топика-ссылки
	 *
	 * @param bool $bShort	Укарачивать урл или нет
	 * @return null|string
	 */
	public function getLinkUrl($bShort=false) {
		if ($this->getType()!='link') {
			return null;
		}

		if ($this->getExtraValue('url')) {
			if ($bShort) {
				$sUrl=htmlspecialchars($this->getExtraValue('url'));
				if (preg_match("/^https?:\/\/(.*)$/i",$sUrl,$aMatch)) {
					$sUrl=$aMatch[1];
				}
				$sUrlShort=substr($sUrl,0,30);
				if (strlen($sUrlShort)!=strlen($sUrl)) {
					return $sUrlShort.'...';
				}
				return $sUrl;
			}
			$sUrl=$this->getExtraValue('url');
			if (!preg_match("/^https?:\/\/(.*)$/i",$sUrl,$aMatch)) {
				$sUrl='http://'.$sUrl;
			}
			return $sUrl;
		}
		return null;
	}
	/**
	 * Устанавливает URL для топика-ссылки
	 *
	 * @param string $data
	 */
	public function setLinkUrl($data) {
		if ($this->getType()!='link') {
			return;
		}
		$this->setExtraValue('url',$data);
	}
	/**
	 * Возвращает количество переходов по ссылке в топике-ссылке
	 *
	 * @return int|null
	 */
	public function getLinkCountJump() {
		if ($this->getType()!='link') {
			return null;
		}
		return (int)$this->getExtraValue('count_jump');
	}
	/**
	 * Устанавливает количество переходов по ссылке в топике-ссылке
	 *
	 * @param string $data
	 */
	public function setLinkCountJump($data) {
		if ($this->getType()!='link') {
			return;
		}
		$this->setExtraValue('count_jump',$data);
	}

	/**
	 * Добавляет вариант ответа в топик-опрос
	 *
	 * @param string $data
	 */
	public function addQuestionAnswer($data) {
		if ($this->getType()!='question') {
			return;
		}
		$this->extractExtra();
		$this->aExtra['answers'][]=array('text'=>$data,'count'=>0);
		$this->setExtra($this->aExtra);
	}
	/**
	 * Очищает варианты ответа в топике-опрос
	 */
	public function clearQuestionAnswer() {
		if ($this->getType()!='question') {
			return;
		}
		$this->setExtraValue('answers',array());
	}
	/**
	 * Возвращает варианты ответа в топике-опрос
	 *
	 * @param bool $bSortVote
	 * @return array|null
	 */
	public function getQuestionAnswers($bSortVote=false) {
		if ($this->getType()!='question') {
			return null;
		}

		if ($this->getExtraValue('answers')) {
			$aAnswers=$this->getExtraValue('answers');
			if ($bSortVote) {
				uasort($aAnswers, create_function('$a,$b',"if (\$a['count'] == \$b['count']) { return 0; } return (\$a['count'] < \$b['count']) ? 1 : -1;"));
			}
			return $aAnswers;
		}
		return array();
	}
	/**
	 * Увеличивает количество ответов на данный вариант в топике-опросе
	 *
	 * @param int $sIdAnswer  ID варианта ответа
	 */
	public function increaseQuestionAnswerVote($sIdAnswer) {
		if ($aAnswers=$this->getQuestionAnswers()) {
			if (isset($aAnswers[$sIdAnswer])) {
				$aAnswers[$sIdAnswer]['count']++;
				$this->aExtra['answers']=$aAnswers;
				$this->setExtra($this->aExtra);
			}
		}
	}
	/**
	 * Возвращает максимально количество ответов на вариант в топике-опросе
	 *
	 * @return int
	 */
	public function getQuestionAnswerMax() {
		$aAnswers=$this->getQuestionAnswers();
		$iMax=0;
		foreach ($aAnswers as $aAns) {
			if ($aAns['count']>$iMax) {
				$iMax=$aAns['count'];
			}
		}
		return $iMax;
	}
	/**
	 * Возвращает в процентах количество проголосовавших за конкретный вариант
	 *
	 * @param int $sIdAnswer ID варианта
	 * @return int|string
	 */
	public function getQuestionAnswerPercent($sIdAnswer) {
		if ($aAnswers=$this->getQuestionAnswers()) {
			if (isset($aAnswers[$sIdAnswer])) {
				$iCountAll=$this->getQuestionCountVote()-$this->getQuestionCountVoteAbstain();
				if ($iCountAll==0) {
					return 0;
				} else {
					return number_format(round($aAnswers[$sIdAnswer]['count']*100/$iCountAll,1), 1, '.', '');
				}
			}
		}
	}
	/**
	 * Возвращает общее число принявших участие в опросе в топике-опросе
	 *
	 * @return int|null
	 */
	public function getQuestionCountVote() {
		if ($this->getType()!='question') {
			return null;
		}
		return (int)$this->getExtraValue('count_vote');
	}
	/**
	 * Устанавливает общее число принявших участие в опросе в топике-опросе
	 *
	 * @param int $data
	 */
	public function setQuestionCountVote($data) {
		if ($this->getType()!='question') {
			return;
		}
		$this->setExtraValue('count_vote',$data);
	}
	/**
	 * Возвращает число воздержавшихся от участия в опросе в топике-опросе
	 *
	 * @return int|null
	 */
	public function getQuestionCountVoteAbstain() {
		if ($this->getType()!='question') {
			return null;
		}
		return (int)$this->getExtraValue('count_vote_abstain');
	}
	/**
	 * Устанавливает число воздержавшихся от участия в опросе в топике-опросе
	 *
	 * @param int $data
	 * @return mixed
	 */
	public function setQuestionCountVoteAbstain($data) {
		if ($this->getType()!='question') {
			return;
		}
		$this->setExtraValue('count_vote_abstain',$data);
	}

	/**
	 * Возвращает фотографии из топика-фотосета
	 *
	 * @param int|null $iFromId	ID с которого начинать  выборку
	 * @param int|null $iCount	Количество
	 * @return array
	 */
	public function getPhotosetPhotos($iFromId = null, $iCount = null) {
		return $this->Topic_getPhotosByTopicId($this->getId(), $iFromId, $iCount);
	}
	/**
	 * Возвращает количество фотографий в топике-фотосете
	 *
	 * @return int|null
	 */
	public function getPhotosetCount() {
		return $this->getExtraValue('count_photo');
	}
	/**
	 * Возвращает ID главной фото в топике-фотосете
	 *
	 * @return int|null
	 */
	public function getPhotosetMainPhotoId() {
		return $this->getExtraValue('main_photo_id');
	}
	/**
	 * Устанавливает ID главной фото в топике-фотосете
	 *
	 * @param int $data
	 */
	public function setPhotosetMainPhotoId($data) {
		$this->setExtraValue('main_photo_id',$data);
	}
	/**
	 * Устанавливает количество фотографий в топике-фотосете
	 *
	 * @param int $data
	 */
	public function setPhotosetCount($data) {
		$this->setExtraValue('count_photo',$data);
	}


	//*************************************************************************************************************************************************

	/**
	 * Устанваливает ID топика
	 *
	 * @param int $data
	 */
	public function setId($data) {
		$this->_aData['topic_id']=$data;
	}
	/**
	 * Устанавливает ID блога
	 *
	 * @param int $data
	 */
	public function setBlogId($data) {
		$this->_aData['blog_id']=$data;
	}
	/**
	 * Устанавливает ID пользователя
	 *
	 * @param int $data
	 */
	public function setUserId($data) {
		$this->_aData['user_id']=$data;
	}
	/**
	 * Устанавливает тип топика
	 *
	 * @param string $data
	 */
	public function setType($data) {
		$this->_aData['topic_type']=$data;
	}
	/**
	 * Устанавливает заголовок топика
	 *
	 * @param string $data
	 */
	public function setTitle($data) {
		$this->_aData['topic_title']=$data;
	}
	/**
	 * Устанавливает текст топика
	 *
	 * @param string $data
	 */
	public function setText($data) {
		$this->_aData['topic_text']=$data;
	}
	/**
	 * Устанавливает сериализованную строчку дополнительных данных
	 *
	 * @param string $data
	 */
	public function setExtra($data) {
		$this->_aData['topic_extra']=serialize($data);
	}
	/**
	 * Устанавливает короткий текст топика до ката
	 *
	 * @param string $data
	 */
	public function setTextShort($data) {
		$this->_aData['topic_text_short']=$data;
	}
	/**
	 * Устаналивает исходный текст топика
	 *
	 * @param string $data
	 */
	public function setTextSource($data) {
		$this->_aData['topic_text_source']=$data;
	}
	/**
	 * Устанавливает список тегов в виде строки
	 *
	 * @param string $data
	 */
	public function setTags($data) {
		$this->_aData['topic_tags']=$data;
	}
	/**
	 * Устанавливает дату создания топика
	 *
	 * @param string $data
	 */
	public function setDateAdd($data) {
		$this->_aData['topic_date_add']=$data;
	}
	/**
	 * Устанавливает дату редактирования топика
	 *
	 * @param string $data
	 */
	public function setDateEdit($data) {
		$this->_aData['topic_date_edit']=$data;
	}
	/**
	 * Устанавливает IP пользователя
	 *
	 * @param string $data
	 */
	public function setUserIp($data) {
		$this->_aData['topic_user_ip']=$data;
	}
	/**
	 * Устанавливает флаг публикации топика
	 *
	 * @param string $data
	 */
	public function setPublish($data) {
		$this->_aData['topic_publish']=$data;
	}
	/**
	 * Устанавливает флаг публикации черновика
	 *
	 * @param string $data
	 */
	public function setPublishDraft($data) {
		$this->_aData['topic_publish_draft']=$data;
	}
	/**
	 * Устанавливает флаг публикации на главной странице
	 *
	 * @param string $data
	 */
	public function setPublishIndex($data) {
		$this->_aData['topic_publish_index']=$data;
	}
	/**
	 * Устанавливает рейтинг топика
	 *
	 * @param string $data
	 */
	public function setRating($data) {
		$this->_aData['topic_rating']=$data;
	}
	/**
	 * Устанавливает количество проголосовавших
	 *
	 * @param int $data
	 */
	public function setCountVote($data) {
		$this->_aData['topic_count_vote']=$data;
	}
	/**
	 * Устанавливает количество проголосовавших в плюс
	 *
	 * @param int $data
	 */
	public function setCountVoteUp($data) {
		$this->_aData['topic_count_vote_up']=$data;
	}
	/**
	 * Устанавливает количество проголосовавших в минус
	 *
	 * @param int $data
	 */
	public function setCountVoteDown($data) {
		$this->_aData['topic_count_vote_down']=$data;
	}
	/**
	 * Устанавливает число воздержавшихся
	 *
	 * @param int $data
	 */
	public function setCountVoteAbstain($data) {
		$this->_aData['topic_count_vote_abstain']=$data;
	}
	/**
	 * Устанавливает число прочтения топика
	 *
	 * @param int $data
	 */
	public function setCountRead($data) {
		$this->_aData['topic_count_read']=$data;
	}
	/**
	 * Устанавливает количество комментариев
	 *
	 * @param int $data
	 */
	public function setCountComment($data) {
		$this->_aData['topic_count_comment']=$data;
	}
	/**
	 * Устанавливает текст ката
	 *
	 * @param string $data
	 */
	public function setCutText($data) {
		$this->_aData['topic_cut_text']=$data;
	}
	/**
	 * Устанавливает флаг запрета коментирования топика
	 *
	 * @param int $data
	 */
	public function setForbidComment($data) {
		$this->_aData['topic_forbid_comment']=$data;
	}
	/**
	 * Устанавливает хеш топика
	 *
	 * @param string $data
	 */
	public function setTextHash($data) {
		$this->_aData['topic_text_hash']=$data;
	}
	/**
	 * Устанавливает объект пользователя
	 *
	 * @param ModuleUser_EntityUser $data
	 */
	public function setUser($data) {
		$this->_aData['user']=$data;
	}
	/**
	 * Устанавливает объект блога
	 *
	 * @param ModuleBlog_EntityBlog $data
	 */
	public function setBlog($data) {
		$this->_aData['blog']=$data;
	}
	/**
	 * Устанавливает факт голосования пользователя в топике-опросе
	 *
	 * @param int $data
	 */
	public function setUserQuestionIsVote($data) {
		$this->_aData['user_question_is_vote']=$data;
	}
	/**
	 * Устанавливает объект голосования за топик
	 *
	 * @param ModuleVote_EntityVote $data
	 */
	public function setVote($data) {
		$this->_aData['vote']=$data;
	}
	/**
	 * Устанавливает количество новых комментариев
	 *
	 * @param int $data
	 */
	public function setCountCommentNew($data) {
		$this->_aData['count_comment_new']=$data;
	}
	/**
	 * Устанавливает дату прочтения топика текущим пользователем
	 *
	 * @param string $data
	 */
	public function setDateRead($data) {
		$this->_aData['date_read']=$data;
	}
	/**
	 * Устанавливает количество пользователей, добавивших топик в избранное
	 *
	 * @param int $data
	 */
	public function setCountFavourite($data) {
		$this->_aData['topic_count_favourite']=$data;
	}
}
?>