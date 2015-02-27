<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Сущность опроса
 *
 * @package application.modules.poll
 * @since 2.0
 */
class ModulePoll_EntityPoll extends EntityORM
{

    protected $aValidateRules = array(
        array('title', 'string', 'allowEmpty' => false, 'min' => 3, 'max' => 250, 'on' => array('create', 'update')),
        array(
            'count_answer_max',
            'number',
            'allowEmpty'  => true,
            'integerOnly' => true,
            'min'         => 0,
            'on'          => array('create', 'update')
        ),
        array('type', 'check_type', 'on' => array('create', 'update')),
        array('answers_raw', 'check_answers_raw', 'on' => array('create', 'update')),
        array('target_raw', 'check_target_raw', 'on' => array('create')),
        array('title', 'check_title', 'on' => array('create', 'update')),
        array('is_guest_allow', 'check_is_guest_allow', 'on' => array('create', 'update')),
        array('is_guest_check_ip', 'check_is_guest_check_ip', 'on' => array('create', 'update')),
    );

    protected $aRelations = array(
        'answers'      => array(self::RELATION_TYPE_HAS_MANY, 'ModulePoll_EntityAnswer', 'poll_id'),
        'vote_current' => array(self::RELATION_TYPE_HAS_ONE, 'ModulePoll_EntityVote', 'poll_id'),
    );

    protected function beforeSave()
    {
        if ($bResult = parent::beforeSave()) {
            if ($this->_isNew()) {
                $this->setDateCreate(date("Y-m-d H:i:s"));
            }
        }
        return $bResult;
    }

    protected function afterSave()
    {
        parent::afterSave();
        /**
         * Сохраняем варианты
         */
        if ($aAnswers = $this->getAnswersObject()) {
            foreach ($aAnswers as $oAnswer) {
                $oAnswer->setPollId($this->getId());
                $oAnswer->Save();
            }
        }
        /**
         * Удаляем варианты
         */
        if ($aAnswers = $this->getAnswersObjectForRemove()) {
            foreach ($aAnswers as $oAnswer) {
                $oAnswer->Delete();
            }
        }
    }

    protected function afterDelete()
    {
        parent::afterDelete();
        /**
         * Удаляем варианты ответов
         */
        $aAnswerItems = $this->Poll_GetAnswerItemsByPollId($this->getId());
        foreach ($aAnswerItems as $oAnswer) {
            $oAnswer->Delete();
        }
        /**
         * Удаляем голосования
         */
        $aVoteItems = $this->Poll_GetVoteItemsByPollId($this->getId());
        foreach ($aVoteItems as $oVote) {
            $oVote->Delete();
        }
    }

    public function ValidateCheckTitle()
    {
        $this->setTitle(htmlspecialchars($this->getTitle()));
        return true;
    }

    public function ValidateCheckIsGuestAllow()
    {
        $this->setIsGuestAllow($this->getIsGuestAllow() ? 1 : 0);
        return true;
    }

    public function ValidateCheckIsGuestCheckIp()
    {
        $this->setIsGuestCheckIp($this->getIsGuestCheckIp() ? 1 : 0);
        return true;
    }

    public function ValidateCheckType()
    {
        if (!$this->_isNew() and $this->getCountVote()) {
            /**
             * Запрещаем смену типа
             */
            $this->setCountAnswerMax($this->_getOriginalDataOne('count_answer_max'));
            return true;
        }
        $iCount = $this->getCountAnswerMax();
        if ($this->getType() == 'one') {
            $this->setCountAnswerMax(1);
            return true;
        } else {
            if ($iCount < 2) {
                return 'Максимальное количество вариантов ответа должно быть больше одного';
            }
        }
        return true;
    }

    public function ValidateCheckAnswersRaw()
    {
        if (!$this->_isNew() and !$this->isAllowUpdate()) {
            return true;
        }

        $aAnswersRaw = $this->getAnswersRaw();
        if (!is_array($aAnswersRaw)) {
            return 'Необходимо заполнить варианты ответов';
        }
        if (count($aAnswersRaw) < 2) {
            return 'Необходимо заполнить больше одного варианта ответов';
        }
        /**
         * Здесь может быть два варианта - создание опроса или редактирование, при редактирование могут передаваться ID ответов
         */
        if (!$this->_isNew()) {
            $aAnswersOld = $this->Poll_GetAnswerItemsByFilter(array(
                'poll_id' => $this->getId(),
                '#index-from-primary'
            ));
        } else {
            $aAnswersOld = array();
        }
        $aAnswers = array();
        foreach ($aAnswersRaw as $aAnswer) {
            if ($this->_isNew() or !(isset($aAnswer['id']) and isset($aAnswersOld[$aAnswer['id']]) and $oAnswer = $aAnswersOld[$aAnswer['id']])) {
                $oAnswer = Engine::GetEntity('ModulePoll_EntityAnswer');
            }
            if ($oAnswer->getId()) {
                /**
                 * Фильтруем список старых ответов для будущего удаления оставшихся
                 */
                unset($aAnswersOld[$oAnswer->getId()]);
            }
            $oAnswer->setTitle(isset($aAnswer['title']) ? $aAnswer['title'] : '');
            if (!$oAnswer->_Validate()) {
                return $oAnswer->_getValidateError();
            }
            $aAnswers[] = $oAnswer;
        }
        $this->setAnswersObject($aAnswers);

        foreach ($aAnswersOld as $oAnswer) {
            if ($oAnswer->getCountVote()) {
                return 'Нельзя удалить вариант ответа, за который уже голосовали';
            }
        }

        $this->setAnswersObjectForRemove($aAnswersOld);
        return true;
    }

    public function ValidateCheckTargetRaw()
    {
        $aTarget = $this->getTargetRaw();

        $sTargetType = isset($aTarget['type']) ? $aTarget['type'] : '';
        $sTargetId = isset($aTarget['id']) ? $aTarget['id'] : '';
        $sTargetTmp = isset($aTarget['tmp']) ? $aTarget['tmp'] : '';

        if ($sTargetId) {
            $sTargetTmp = null;
            if (!$this->Poll_CheckTarget($sTargetType, $sTargetId)) {
                return 'Неверный тип объекта';
            }
        } else {
            $sTargetId = null;
            if (!$sTargetTmp or !$this->Poll_IsAllowTargetType($sTargetType)) {
                return 'Неверный тип объекта';
            }
            if ($this->Poll_GetPollByFilter(array('target_tmp' => $sTargetTmp, 'target_type <>' => $sTargetType))) {
                return 'Временный идентификатор уже занят';
            }
        }

        $this->setTargetType($sTargetType);
        $this->setTargetId($sTargetId);
        $this->setTargetTmp($sTargetTmp);
        return true;
    }

    /**
     * Проверяет доступность опроса для изменения
     * Важно понимать, что здесь нет проверки на права доступа
     *
     * @return bool
     */
    public function isAllowUpdate()
    {
        $iTime = $this->getDateCreate();
        if ((time() - strtotime($iTime)) > Config::Get('module.poll.time_limit_update')) {
            return false;
        }
        return true;
    }

    /**
     * Проверяет возможность удаления опроса, не пользователем, а в принципе
     * Важно понимать, что здесь нет проверки на права доступа
     *
     * @return bool
     */
    public function isAllowRemove()
    {
        if ($this->getCountVote() || $this->getCountAbstain()) {
            return false;
        }
        return true;
    }

    /**
     * Проверяет возможность голосования в опросе, не пользователем, а в принципе
     * Важно понимать, что здесь нет проверки на права доступа
     *
     * @return bool
     */
    public function isAllowVote()
    {
        $sDateEnd = $this->getDateEnd();
        if ($sDateEnd and (time() - strtotime($sDateEnd)) > 0) {
            return false;
        }
        return true;
    }

    public function getAnswerPercent($oAnswer)
    {
        $iCountAll = $this->getCountVote();
        if ($iCountAll == 0) {
            return 0;
        } else {
            return number_format(round($oAnswer->getCountVote() * 100 / $iCountAll, 1), 1, '.', '');
        }
    }

    public function getCountVoteAnswerMax()
    {
        $iMax = 0;
        $aAnswers = $this->getAnswers();
        foreach ($aAnswers as $oAnswer) {
            if ($oAnswer->getCountVote() > $iMax) {
                $iMax = $oAnswer->getCountVote();
            }
        }
        return $iMax;
    }

    public function getVoteCurrent()
    {
        if (array_key_exists('vote_current', $this->aRelationsData)) {
            return $this->aRelationsData['vote_current'];
        }
        return $this->Poll_GetVoteByUser($this, $this->User_GetUserCurrent());
    }
}